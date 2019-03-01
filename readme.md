# PartnerComm Wordpress Utilities
Package of utilities to help make wordpress plugin and theme development faster and easier.

##Sections
* Custom Post Types, Terms, and Taxonomies
* Plugin Settings Generator
* Helpers
<hr>
### Custom Post Types
To create a custom post type, extend the `PComm\WPUtils\Post\DefaultDefinition` and add definitions for slugs, terms, meta boxes, etc.
#### Cusom Metaboxes on Post Types
To add a custom metabox callback method, declare it in the box source
~~~php
[
    'slug' => 'meta-box-slug',
    'title' => 'Meta Box Title',
    'source' => 'getGenerateBoxes', //This can be any public method in your PostDefinition
    'fields' => [] //Will be replaced with source callback if present
],
~~~
<hr>
### Custom Taxonomy
To create a custom taxonomy, extend the `PComm\WPUtils\Taxonomy\DefaultDefinition` and add definitions for slugs, terms, meta boxes, etc.
<hr>
### Custom Terms
To create a custom taxonomy, extend the `PComm\WPUtils\Taxonomy\DefaultDefinition` and add definitions for slugs, terms, meta boxes, etc.
<hr>
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

<hr>
### Dump Debug Tool
To get a highly detailed debug dump of input, simply run
```
dump($myVar)
```

You will get a screen like this:
![symphony dump](https://symfony.com/doc/current/_images/01-simple.png)

For more details and documentation, visit the [The VarDumper Component](https://symfony.com/doc/current/components/var_dumper.html) in Symphony's documentation.

## Changelog
* 1.1.9 - March 1, 2019
  * Add Symphony/VarDumper tool
* 1.1.8 - December 13, 2018
	* Enlarge textarea metaboxes
* 1.1.7 - September 18, 2018
	* Add ability to register rest fields for taxonomies in the same way as for posts