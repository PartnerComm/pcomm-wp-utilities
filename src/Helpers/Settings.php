<?php

namespace PComm\WPUtils\Helpers;


/**
 * Fetch a namespaced setting value
 *
 * @param [string] $slug
 * @param [string] $field
 * @return mixed
 */
function getSetting($slug, $field)
{
  $settings = (array) get_option( $slug );
  $field = $slug . '-' . $field;
  $value = (!empty($settings[$field])) ? esc_attr( $settings[$field] ) : false;

  return $value;
}