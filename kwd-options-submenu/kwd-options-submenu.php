<?php
/*
Plugin Name: KWD Options submenu
Description: Изучаем API опций и настроек. Создание секции меню с подменю
Plugin URI: https://github.com/Korveld
Version: 1.0
Author: Korveld WebDev
Author URI: https://github.com/Korveld
*/

add_action( 'admin_menu', 'kwd_admin_menu' );
add_action( 'admin_init', 'kwd_admin_settings' );

register_deactivation_hook(__FILE__, 'kwd_delete_options');

function kwd_delete_options() {
  delete_option('kwd_theme_options_body');
  delete_option('kwd_theme_options_header');
}

function kwd_admin_menu() {
	// $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position
	add_menu_page(
	  'Theme options (title)',
    'Theme options',
    'manage_options',
    __FILE__,
    'kwd_option_page',
    'dashicons-hammer'
  );

	// $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function
	add_submenu_page(
	  __FILE__,
    'Header options',
    'Header options',
    'manage_options',
    'kwd-header-options',
    'kwd_options_submenu'
  );
}

function kwd_admin_settings() {
	// $option_group, $option_name, $sanitize_callback
	register_setting(
	  'kwd_group_body',
    'kwd_theme_options_body',
    'kwd_theme_options_sanitize'
  );
	register_setting(
	  'kwd_group_header',
    'kwd_theme_options_header',
    'kwd_theme_options_sanitize'
  );

	// $id, $title, $callback, $page
	add_settings_section(
	  'kwd_section_body_id',
    'Body section',
    '',
    __FILE__
  );
	add_settings_section(
	  'kwd_section_header_id',
    'Header section',
    '',
    'kwd-header-options'
  );

	// $id, $title, $callback, $page, $section, $args
	add_settings_field(
	  'kwd_setting_body_id',
    'Background color',
    'kwd_theme_body_cb',
    __FILE__,
    'kwd_section_body_id',
    array('label_for' => 'kwd_setting_body_id')
  );
	add_settings_field(
	  'kwd_setting_body_id2',
    'Font color',
    'kwd_theme_body_cb2',
    __FILE__,
    'kwd_section_body_id',
    array('label_for' => 'kwd_setting_body_id2')
  );

	add_settings_field(
	  'kwd_setting_header_id',
    'Header color',
    'kwd_theme_header_cb',
    'kwd-header-options',
    'kwd_section_header_id',
    array('label_for' => 'kwd_setting_header_id')
  );
	add_settings_field(
	  'kwd_setting_header_id2',
    'Header font',
    'kwd_theme_header_cb2',
    'kwd-header-options',
    'kwd_section_header_id',
    array('label_for' => 'kwd_setting_header_id2')
  );
}

function kwd_theme_body_cb() {
	$options = get_option('kwd_theme_options_body');
	?>

<input
  type="text"
  name="kwd_theme_options_body[body]"
  id="kwd_setting_body_id"
  value="<?php echo esc_attr($options['body']); ?>"
  class="regular-text"
>

	<?php
}

function kwd_theme_body_cb2() {
	$options = get_option('kwd_theme_options_body');
	?>

<input
  type="text"
  name="kwd_theme_options_body[color]"
  id="kwd_setting_body_id2"
  value="<?php echo esc_attr($options['color']); ?>"
  class="regular-text"
>

	<?php
}

function kwd_theme_header_cb() {
	$options = get_option('kwd_theme_options_header');
	?>

<input
  type="text"
  name="kwd_theme_options_header[header]"
  id="kwd_setting_header_id"
  value="<?php echo esc_attr($options['header']); ?>"
  class="regular-text"
>

	<?php
}

function kwd_theme_header_cb2() {
	$options = get_option('kwd_theme_options_header');
	?>

<input
  type="text"
  name="kwd_theme_options_header[color]"
  id="kwd_setting_header_id2"
  value="<?php echo esc_attr($options['color']); ?>"
  class="regular-text"
>

	<?php
}

function kwd_option_page() {
	?>

<div class="wrap">
	<h2>Theme options</h2>
	<p>Plugin settings. Body section</p>
	<form action="options.php" method="post">
		<?php settings_fields( 'kwd_group_body' ); ?>
		<?php do_settings_sections( __FILE__ ); ?>
		<?php submit_button(); ?>
	</form>
</div>

	<?php
}

function kwd_options_submenu() {
	?>

<div class="wrap">
	<h2>Theme options</h2>
	<p>Plugin settings. Header section</p>
	<form action="options.php" method="post">
		<?php settings_fields( 'kwd_group_header' ); ?>
		<?php do_settings_sections( 'kwd-header-options' ); ?>
		<?php submit_button(); ?>
	</form>
</div>

	<?php
}

function kwd_theme_options_sanitize($options) {
	$clean_options = array();
	foreach($options as $k => $v){
		$clean_options[$k] = strip_tags($v);
	}
	return $clean_options;
}