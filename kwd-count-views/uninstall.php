<?php
if ( !defined('WP_UNINSTALL_PLUGIN') ) exit;

include dirname(__FILE__) . '/kwd-check.php';

if (kwd_check_field('kwd_views')) {
  global $wpdb;
  $query = "ALTER TABLE $wpdb->posts DROP kwd_views";
  $wpdb->query($query);
}