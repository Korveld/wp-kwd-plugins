<?php
/*
Plugin Name: KWD Google maps v.2
Description: To display map use shortcode [map cords1="59.969530" cords2="30.350632" zoom="8" api="YOUR_GOOGLE_API_KEY"]
Plugin URI: https://github.com/Korveld
Version: 2.0
Author: Korveld WebDev
Author URI: https://github.com/Korveld
*/

add_shortcode('map', 'kwd_map_2');

$kwd_maps_array = array();
$api_key = '';

function kwd_map_2($atts) {
  global $kwd_maps_array;
  global $api_key;
  $atts = shortcode_atts(
    array(
      'cords1'  => 59.969530,
      'cords2'  => 30.350632,
      'zoom'    => 8,
      'api'     => ''
    ), $atts
  );
  extract($atts);

  if ( $api == '' ) {
    return '<p>Please add your google api via api="YOUR_GOOGLE_API_KEY".<br>You can get api key from this <a href="https://developers.google.com/maps/documentation/embed/get-api-key" target="_blank">page</a>.</p>';
    die();
  }

  $kwd_maps_array = array(
    'cords1'  => $cords1,
    'cords2'  => $cords2,
    'zoom' => $zoom
  );

  $api_key = $api;

  add_action( 'wp_footer', 'kwd_styles_scripts' );
  add_filter( 'script_loader_tag', 'kwd_add_async_attribute', 10, 2 );

  return '<div id="map-canvas" style="width:100%;height:450px;"></div>';
}

function kwd_styles_scripts() {
  global $kwd_maps_array;
  global $api_key;
  wp_register_script('kwd_google_scripts', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key . '&callback=initMap&language=en', '', array('kwd_maps_2'), null);
  wp_register_script('kwd_maps_2', plugins_url( 'js/kwd-maps-2.js' , __FILE__ ), '', '', null);

  wp_enqueue_script('kwd_maps_2');
  wp_enqueue_script('kwd_google_scripts');

  wp_localize_script('kwd_maps_2', 'kwdObj', $kwd_maps_array);
}

function kwd_add_async_attribute( $tag, $handle ) {
  $handles = array(
    'kwd_google_scripts'
  );
  foreach( $handles as $defer_script) {
    if ( $defer_script === $handle ) {
      return str_replace( ' src', ' async defer src', $tag );
    }
  }
  return $tag;
}
