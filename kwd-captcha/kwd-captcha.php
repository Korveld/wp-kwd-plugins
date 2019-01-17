<?php
/*
Plugin Name: KWD Simple comment form captcha
Description: Plugin deletes website field and add checkbox for human check
Plugin URI: https://github.com/Korveld
Version: 1.0
Author: Korveld WebDev
Author URI: https://github.com/Korveld
*/

add_filter( 'comment_form_fields', 'kwd_move_comment_field_to_bottom');
add_filter( 'comment_form_default_fields', 'kwd_unset_website_field' );
add_filter( 'comment_form_field_comment', 'kwd_captcha' );
add_filter( 'preprocess_comment', 'kwd_check_captcha' );

function kwd_move_comment_field_to_bottom($fields) {
  $comment_field = $fields['comment'];
  unset( $fields['comment'] );
  $fields['comment'] = $comment_field;
  return $fields;
}

function kwd_unset_website_field($fields) {
  unset($fields['url']);
  return $fields;
}

function kwd_captcha($comment_field) {
  if ( is_user_logged_in() ) return $comment_field;
  
  $comment_field .= '<p>
  <label for="captcha">Captcha<span class="required">*</span></label>
  <input type="checkbox" name="captcha" id="captcha" required>
  </p>';
  return $comment_field;
}

function kwd_check_captcha($commentdata) {
  if ( is_user_logged_in() ) return $commentdata;

  $message = __('<b>Error</b>: Please submit the captcha checkbox', 'kwd-captcha');
  if ( !isset($_POST['captcha']) ) {
    wp_die( $message );
  }
  return $commentdata;
}
