<?php
/*
Plugin Name: KWD Photo Gallery
Description: Use shortcode [gallery ids="1,2,3"]. Ids is the ID of images
Plugin URI: https://github.com/Korveld
Version: 1.0
Author: Korveld WebDev
Author URI: https://github.com/Korveld
*/

add_action('wp_enqueue_scripts', 'kwd_gallery_scripts');
remove_shortcode( 'gallery' );
add_shortcode( 'gallery', 'kwd_gallery' );

function kwd_gallery_scripts() {
  wp_register_style( 'kwd_lightbox_styles', plugins_url( 'css/lightbox.min.css' , __FILE__ ) );
  wp_register_style( 'kwd_gallery_styles', plugins_url( 'css/kwd-lightbox.css' , __FILE__ ) );
  wp_register_script('kwd_gallery_scripts', plugins_url( 'js/lightbox.min.js' , __FILE__ ), array('jquery'));

  wp_enqueue_script('kwd_gallery_scripts');
  wp_enqueue_style('kwd_lightbox_styles');
  wp_enqueue_style('kwd_gallery_styles');
}

function kwd_gallery($atts){
	$img_id = explode(',', $atts['ids']);
	if( !$img_id[0] ) return '<div class="kwd-gallery">There is no images in gallery</div>';
	$html = '<div class="kwd-gallery">';
	foreach($img_id as $item) {
		$img_data = get_posts( array(
			'p' => $item,
			'post_type' => 'attachment'
		) );

		$img_desc = $img_data[0]->post_content;
		$img_caption = $img_data[0]->post_excerpt;
		$img_title = $img_data[0]->post_title;
		$img_thumb = wp_get_attachment_image_src( $item );
		$img_full = wp_get_attachment_image_src( $item, 'full' );

		$html .= "<a href='{$img_full[0]}' data-lightbox='gallery' data-title='{$img_caption}'><img src='{$img_thumb[0]}' width='{$img_thumb[1]}' height='{$img_thumb[2]}' alt='{$img_title}'></a>";
	}
	$html .= '</div>';
	return $html;
}