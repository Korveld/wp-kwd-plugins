<?php
/*
Plugin Name: KWD Simple auth form captcha
Description: Plugin adds human check for auth form
Plugin URI: https://github.com/Korveld
Version: 1.0
Author: Korveld WebDev
Author URI: https://github.com/Korveld
*/

add_action( 'login_form', 'kwd_captcha_auth' );
add_filter( 'authenticate', 'kwd_auth_signin', 30, 3 );

function kwd_captcha_auth() {
  echo '<div style="margin-bottom:10px;"><p>
    <label style="font-size:12px;" for="check"><input name="check" type="checkbox" id="check" value="check" checked> Uncheck me</label>
  </p></div>';
}

function kwd_auth_signin($user, $username, $password) {
  $error_message = '<b>Error</b>: authentication error';
  if ( isset( $_POST['check'] ) && $_POST['check'] == 'check' ) {
    $user = new WP_Error( 'kwd_error_code', $error_message );
  }
  if ( isset( $user->errors['incorrect_password'] ) || isset( $user->errors['invalid_username'] ) ) {
    return new WP_Error( 'kwd_error_code', $error_message );
  }
  return $user;
}