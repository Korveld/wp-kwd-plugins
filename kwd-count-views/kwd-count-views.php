<?php
/*
Plugin Name: KWD Posts views
Description: Plugin counts and displays views of the each post.
Plugin URI: https://github.com/Korveld
Version: 1.0
Author: Korveld WebDev
Author URI: https://github.com/Korveld
*/

include dirname(__FILE__) . '/kwd-check.php';

register_activation_hook(__FILE__, 'kwd_create_field');
add_filter( 'the_content', 'kwd_post_views' );
add_action( 'wp_head', 'kwd_add_view' );

function kwd_create_field() {
  global $wpdb;
  if (!kwd_check_field('kwd_views')) {
    $query = "ALTER TABLE $wpdb->posts ADD kwd_views INT NOT NULL DEFAULT '0'";
    $wpdb->query($query);
  }
}

function kwd_post_views($content) {
  if ( is_page() ) return $content;
  global $post;
  $views = $post->kwd_views;
  if ( is_single() ) $views += 1;
  return $content . "<p><b>Post views:</b> <mark>" . $views . '</mark></p>';
}

function kwd_add_view() {
  if ( !is_single() ) return;
  global $post, $wpdb;
  $kwd_id = $post->ID;
  $views = $post->kwd_views + 1;
  $wpdb->update(
    $wpdb->posts,
    array( 'kwd_views' => $views ),
    array( 'ID' => $kwd_id )
  );
}
