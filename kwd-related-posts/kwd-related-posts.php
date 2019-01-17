<?php
/*
Plugin Name: KWD Related posts
Description: Display related posts from the same category.
Plugin URI: https://github.com/Korveld
Version: 1.0
Author: Korveld WebDev
Author URI: https://github.com/Korveld
*/

add_action('wp_enqueue_scripts', 'kwd_related_scripts');

function kwd_related_scripts() {
  wp_register_style( 'kwd_styles', plugins_url( 'css/kwd-styles.css' , __FILE__ ) );
  wp_register_script('kwd_jquery_tools', plugins_url( 'js/jquery.tools.min.js' , __FILE__ ), array('jquery'), '', null);
  wp_register_script('kwd_scripts', plugins_url( 'js/kwd-scripts.js' , __FILE__ ), array('jquery', 'kwd_jquery_tools'), '', null);

  wp_enqueue_script('kwd_jquery_tools');
  wp_enqueue_script('kwd_scripts');
  wp_enqueue_style('kwd_styles');
}

add_filter( 'the_content', 'kwd_related_posts' );

function kwd_related_posts($content) {
  if ( !is_single() ) return $content;

  $id = get_the_ID();
  $categories = get_the_category( $id );

  foreach ($categories as $category) {
    $cats_id[] = $category->cat_ID;
  }

  $related_posts = new WP_Query(
    array(
      'posts_per_page'  => 4,
      'category__in'    => $cats_id,
      'orderby'         => 'rand',
      'post__not_in'    => array($id)
    )
  );

  if ($related_posts->have_posts()) {
    $content .= '<div class="kwd-related-posts"><h3>Related posts:</h3>';
    while( $related_posts->have_posts() ) {
      $related_posts->the_post();
      if ( has_post_thumbnail() ) {
        $img = get_the_post_thumbnail( get_the_ID(), array(100,100), array( 'alt' => get_the_title(), 'title' => get_the_title() ) );
      } else {
        $img = '<img src="' . plugins_url('images/no_img.jpg', __FILE__) . '" alt="' . get_the_title() . '" title="' . get_the_title() . '" width="100" height="100">';
      }
      $content .= '<a href="' . get_the_permalink() . '" class="kwd-img-blk">' . $img . '</a>';
    }
    $content .= '</div>';
    wp_reset_query();
  }

  return $content;
}

