<?php

namespace PComm\WPUtils\Helpers;

/** Setup Mock Settings */
$globalSettings = [];
function get_option( $key) {
  global $globalSettings;
  return $globalSettings[$key];
}

/**
 * Fake The esc_attr
 *
 * @param mixed $var
 * @return void
 */
function esc_attr($var) {
  return $var;
}

/**
 * Settings Helper Tests
 */
class SettingsHelpersTest extends \PHPUnit\Framework\TestCase {

  public function testFetchingSetting()
  {
    global $globalSettings;
    $expectation = "Winner, Winner, Chicken Dinner";
    $globalSettings['foo'] = [
      'foo-bar' => $expectation
    ];

    $setting = \PComm\WPUtils\Helpers\getSetting('foo', 'bar');
    $this->assertEquals($setting, $expectation);
  }

}