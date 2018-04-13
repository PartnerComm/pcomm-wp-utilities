<?php
namespace PComm\WPUtils\Admin;

class SettingsPageTest extends \PHPUnit\Framework\TestCase {

  /**
   * @return void
   * @expectedException     \PComm\WPUtils\Exceptions\SettingsPageException
   */
    public function testThrowsExceptionIfSettingsMissingFieldsTree()
    {
      $mockSettingsPage = new class extends \PComm\WPUtils\Admin\SettingsPage {
        protected $settings = [
          'default' => []
        ];
      };
    }

  /**
   * @return void
   * @expectedException     \PComm\WPUtils\Exceptions\SettingsPageException
   */
    public function testThrowsExceptionIfSelectHasEmptyOptions()
    {
      $mockSettingsPage = new class extends \PComm\WPUtils\Admin\SettingsPage {
        protected $settings = [
          'default' => [
            'fields' => [
              'slug' => [
                'type' => 'select',
                'options' => []
              ]
            ]
          ]
        ];
      };
    }

  /**
   * @return void
   * @expectedException     \PComm\WPUtils\Exceptions\SettingsPageException
   */
  public function testThrowsExceptionIfRadioHasOneOption()
  {
    $mockSettingsPage = new class extends \PComm\WPUtils\Admin\SettingsPage {
      protected $settings = [
        'default' => [
          'fields' => [
            'slug' => [
              'type' => 'radio',
              'options' => ['key'=>'Value']
            ]
          ]
        ]
      ];
    };
  }

}