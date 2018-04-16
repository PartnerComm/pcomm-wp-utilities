<?php

namespace PComm\WPUtils\Admin;

/**
 * Settings Page Generator
 * 
 * Extend this class and enable hooks for initializing settings and data
 * $settings  = new SettingsPageInstance();
 * add_action( 'admin_init', [$settings, 'initSettingsData'] );
 * add_action( 'admin_menu', [$settings, 'initSettingsPages'] );
 */
abstract class SettingsPage {

  /**
   * @var string Unique Slug of the Settings (could be plugin slug)
   */
  protected $slug = '';

  /**
   * @var string Title to display in settings menu
   */
  protected $menuTitle = '';

  /**
   * @var string Title to display on top of settings page
   */
  protected $pageTitle ='';

  /**
   * @var array Settings block to build the page
   * ['default' => [ //Outer Tab or Page Title @TODO, Create Multiple Forms on Page
   *     'description' => 'lorem ipsum description text', //Optional description on the top of the page
   *     'fields' => [ //Groups of fieldsets on pags
   *        //Single Field
   *        'field-option-name' => [ //This is unique field ID used as key ex: users-can-do-something
   *        'type' => 'text', //text, check, radio, select
   *        'label' => 'This is a field label',
   *        'description' => 'help tip displayed under the field',
   *        'options' => ['option-key'=>'Label of Option'] // SELECT/RADIO - key/val pairs of options and labels
   *      ]
   * ]]
   */
  protected $settings = [];

  public function __construct()
  {
    $this->validateSettings();
  }
  
 /**
 * Initialze Settings Pages
 *
 * @return void
 */
  public function initSettingsPages()
  {

    if ( empty ( $GLOBALS['admin_page_hooks']['pc-plugins'] ) ) {
      add_menu_page( 'PartnerComm Plugins', 'PartnerComm Plugins', 'manage_options', 'pc-plugins', function() {
        //die('You are here!');
        echo "<h1>Welcome To PartnerComm's Plugins Bazaar (S2)</h1>";
      });
    }

    add_submenu_page( 'pc-plugins', $this->pageTitle, $this->menuTitle, 'manage_options', $this->slug, function() {
      include(realpath(__DIR__).'/../Views/settings-options.php');
    });
  }

  /**
   * initialize settings data and field registration
   *
   * @return void
   */
  public function initSettingsData()
  {
    
    register_setting( $this->slug, $this->slug );

    foreach($this->settings as $sectionSlug => $sectionData) {
      add_settings_section( $sectionSlug, $sectionData['description'], function() {
      }, $this->slug );

      foreach($sectionData['fields'] as $fieldSlug => $fieldData) {
          add_settings_field( $this->slug . '-' . $fieldSlug, $fieldData['label'], function() use ($fieldSlug, $fieldData) {
            $settings = (array) get_option( $this->slug );
            $field = $this->slug . '-' . $fieldSlug;
            $value = (!empty($settings[$field])) ? esc_attr( $settings[$field] ) : '';
            
            switch($fieldData['type']) {
              case 'text':
                $fieldOutput = "<input type='text' name='{$this->slug}[{$field}]' value='{$value}' />";
              break;
              case 'select':
                $fieldOutput = "<select name='{$this->slug}[{$field}]'>";
                  foreach($fieldData['options'] as $optVal => $label) {
                    $selected = ($value == $optVal) ? "selected='selected'" : '';
                    $fieldOutput .= "<option value='{$optVal}' {$selected}>{$label}</option>";
                  }
                $fieldOutput .= "</select>";
              break;
              case 'check':
                $selected = ($value == $fieldData['value']) ? "checked='checked'" : '';
                $fieldOutput = "<input type='checkbox' name='{$this->slug}[{$field}]' value='{$fieldData['value']}' {$selected}/>";
              break;

              case 'radio':
                  $fieldOutput = '';
                  foreach($fieldData['options'] as $option => $label) {
                    $selected = ($value == $option) ? "checked='checked'" : '';
                    $fieldOutput .= "<label for='{$field}-{$option}'><input id='{$field}-{$option}' type='radio' name='{$this->slug}[{$field}]' value='{$option}' {$selected}/> {$label}</label><br>";    
                  }
              break;

              default:
                $fieldOutput = "<input type='text' name='{$this->slug}[{$field}]' value='{$value}' />";
              break;

            }
            
            if(!empty($fieldData['description'])) {
              $fieldOutput .= "<p class='description'>{$fieldData['description']}</p>";
            }

            echo $fieldOutput;
      
          }, $this->slug, $sectionSlug );
      }
    }
  }

  protected function validateSettings()
  {
    //Check if Fields is an array
    if(empty($this->settings['default']['fields'])) {
      throw new \PComm\WPUtils\Exceptions\SettingsPageException("Fields array are required");
    }
    
    //Check if radio and select have multiple options
    foreach($this->settings['default']['fields'] as $slug => $fieldData) {
      if( \in_array($fieldData['type'], ['select', 'radio']) && 
         ( empty($fieldData['options']) || 
           count($fieldData['options'] < 2)
         )) {
          throw new \PComm\WPUtils\Exceptions\SettingsPageException("Select and Radio must have more than one option");
         }
    }

  }

} 