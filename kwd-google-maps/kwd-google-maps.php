<?php
/*
Plugin Name: KWD Google maps
Description: To display map use shortcode [map location="city, country" width="600" height="300" zoom="13" api="YOUR_GOOGLE_API"]Map description[/map]
Plugin URI: https://github.com/Korveld
Version: 1.0
Author: Korveld WebDev
Author URI: https://github.com/Korveld
*/

add_shortcode('map', 'kwd_map');

function kwd_map($atts, $content) {
  $atts = shortcode_atts(
    array(
      'location'  => 'Kiev, Ukraine',
      'width'     => 600,
      'height'    => 300,
      'zoom'      => 13,
      'content'   => !empty($content) ? "<h2>$content</h2>" : "<h2>Google map</h2>",
      'api'       => ''
    ), $atts
  );
  extract($atts);
  if ( $api == '' ) {
    return $map = 'Please add your google api via api="YOUR_GOOGLE_API".<br>You can get api from this <a href="https://developers.google.com/maps/documentation/embed/get-api-key" target="_blank">page</a>.';
  }
  $map = $content;
  $map .= '<img src="https://maps.googleapis.com/maps/api/staticmap?center=' . $location . '&zoom=' . $zoom . '&size=' . $width . 'x' . $height . '&maptype=roadmap
&markers=color:blue%7Clabel:S%7C40.702147,-74.015794&markers=color:green%7Clabel:G%7C40.711614,-74.012318
&markers=color:red%7Clabel:C%7C40.718217,-73.998284
&key=' . $api . '" alt="">';
  return $map;
}
