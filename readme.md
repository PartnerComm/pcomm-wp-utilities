# PartnerComm Wordpress Utilities
Package of utilities to help make wordpress plugin and theme development faster and easier.

##Sections
* Custom Post Types, Terms, and Taxonomies
* Plugin Settings Generator
* Helpers

### Custom Post Types
To create a custom post type, extend the `PComm\WPUtils\Post\DefaultDefinition` and add definitions for slugs, terms, meta boxes, etc.

### Custom Taxonomy
To create a custom taxonomy, extend the `PComm\WPUtils\Taxonomy\DefaultDefinition` and add definitions for slugs, terms, meta boxes, etc.

### Custom Terms
To create a custom taxonomy, extend the `PComm\WPUtils\Taxonomy\DefaultDefinition` and add definitions for slugs, terms, meta boxes, etc.

### Settings Pages
To create a custom settings page for your plugin, create a new settings page class that extends `PComm\WPUtils\Admin\SettingsPage` instantiate it, and register init hooks

```php
$newSettingsPage  = new CustomSettingsPage();
add_action( 'admin_init', [$newSettingsPage, 'initSettingsData'] );
add_action( 'admin_menu', [$newSettingsPage, 'initSettingsPages'] );
```

To build custom settings fields, follow the below pattern
```php
 protected $settings = [
    'default' => [ //Outer Tab or Page Title
      'description' => 'lorem ipsum description text',

      'fields' => [ //Groups of fieldsets on page
          //Single Field
          'field-option-name' => [ //This is unique field ID used as key
            'type' => 'text', //text, check, radio, select
            'label' => 'This is a Text Field',
            'description' => 'lorem ipsum help text optional additional paragraph text'
          ],

          //Single Field
          'select-option-name' => [ //This is unique field ID used as key
            'type' => 'select', //text, check, radio, select
            'label' => 'This is a Select Field',
            'description' => 'lorem ipsum help text optional additional paragraph text',
            'options' => ['option1' => 'Option One', 'option2' => 'Option Two'] //Available Options if this is a select/radio
          ],

          //Single Field
          'check-option-name' => [ //This is unique field ID used as key
            'type' => 'check', //text, check, radio, select
            'label' => 'This is a Check Field',
            'description' => 'lorem ipsum help text optional additional paragraph text',
            'value' => 'iamchecked'
          ],

          //Single Field
          'radion-option-name' => [ //This is unique field ID used as key
            'type' => 'radio', //text, check, radio, select
            'label' => 'This is a Radio Set',
            'description' => 'lorem ipsum help text optional additional paragraph text',
            'options' => ['option1' => 'Option One', 'option2' => 'Option Two']
          ]
      ]
    ]
  ];
```

To access a Setting in your code, you can use the helper function directly
`$setting = \PComm\WPUtils\Helpers\getSetting($pluginSlug, $settingSlug);`
