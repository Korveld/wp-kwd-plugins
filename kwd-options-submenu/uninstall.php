<?php
if(!defined('WP_UNINSTALL_PLUGIN'))
  exit;

delete_option('kwd_theme_options_body');
delete_option('kwd_theme_options_header');