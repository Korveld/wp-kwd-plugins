<?php
/*
Plugin Name: KWD Accordion categories widget
Description: Display categories as accordion dropdown
Plugin URI: https://github.com/Korveld
Version: 1.0
Author: Korveld WebDev
Author URI: https://github.com/Korveld
*/

add_action('widgets_init', 'kwd_cats');

function kwd_cats() {
  register_widget('KWD_Cats');
}

class KWD_Cats extends WP_Widget {

  public $kwd_cats_array;

  function __construct() {
    $args = array(
      'name'        => 'KWD Accordion categories',
      'description' => 'Display categories as accordion dropdown',
    );
    parent::__construct('kwd_cats', '', $args);
  }

  function form($instance) {
    extract($instance);
    /*print_r($instance);*/
    $title = !empty($title) ? esc_attr($title) : '';
    $disableLink = isset($disableLink) ? $disableLink : 'false';
    $eventType = isset($eventType) ? $eventType : 'hover';
    $hoverDelay = !empty($hoverDelay) ? $hoverDelay : '';
    $speed = !empty($speed) ? $speed : '';
    $exclude = !empty($exclude) ? $exclude : '';
    ?>

    <p>
      <label for="<?php echo $this->get_field_id('title') ?>">Title</label>
      <input
        type="text"
        name="<?php echo $this->get_field_name('title') ?>"
        id="<?php echo $this->get_field_id('title') ?>"
        value="<?php echo $title ?>"
        class="widefat"
      >
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('disableLink') ?>">Disable parent link</label>
      <select
        name="<?php echo $this->get_field_name('disableLink') ?>"
        id="<?php echo $this->get_field_id('disableLink') ?>"
        class="widefat"
      >
        <option value="true" <?php selected('true', $disableLink, true) ?>>Yes</option>
        <option value="false" <?php selected('false', $disableLink, true) ?>>No</option>
      </select>
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('eventType') ?>">Event Type</label>
      <select
        name="<?php echo $this->get_field_name('eventType') ?>"
        id="<?php echo $this->get_field_id('eventType') ?>"
        class="widefat"
      >
        <option value="click" <?php selected('click', $eventType, true) ?>>On click</option>
        <option value="hover" <?php selected('hover', $eventType, true) ?>>On hover</option>
      </select>
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('hoverDelay') ?>">Hover delay</label>
      <input
        type="number"
        name="<?php echo $this->get_field_name('hoverDelay') ?>"
        id="<?php echo $this->get_field_id('hoverDelay') ?>"
        value="<?php echo $hoverDelay ?>"
        placeholder="Default: 100"
        class="widefat"
      >
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('speed') ?>">Speed</label>
      <input
        type="number"
        name="<?php echo $this->get_field_name('speed') ?>"
        id="<?php echo $this->get_field_id('speed') ?>"
        value="<?php echo $speed ?>"
        placeholder="Default: 300"
        class="widefat"
      >
    </p>

    <p>
      <label for="<?php echo $this->get_field_id('exclude') ?>">Exclude categories (ID followed by comma)</label>
      <input
        type="text"
        name="<?php echo $this->get_field_name('exclude') ?>"
        id="<?php echo $this->get_field_id('exclude') ?>"
        value="<?php echo $exclude ?>"
        placeholder="example: 1, 10, 23"
        class="widefat"
      >
    </p>

    <?php
  }

  function widget($args, $instance) {
    extract($args);
    extract($instance);

    $this->kwd_cats_array = array(
      'eventType' => $eventType,
      'disableLink' => $disableLink,
      'hoverDelay' => $hoverDelay,
      'speed' => $speed
    );

    add_action('wp_footer', array($this, 'kwd_accordion_scripts'));

    $title = apply_filters('widget_title', $title);

    $cats = wp_list_categories(
      array(
        'title_li'  => '',
        'echo'      => false,
        'exclude'   => $exclude
      )
    );

    $cats = preg_replace('#title="[^"]+"#', '', $cats);

    $html = $before_widget;
    $html .= $before_title . $title . $after_title;
    $html .= '<div class="grey"><ul class="accordion js-accordion">';
    $html .= $cats;
    $html .= '</ul></div>';
    $html .= $after_widget;
    echo $html;
  }

  function kwd_accordion_scripts() {
    wp_register_style( 'dcaccordion_css', plugins_url( 'css/skins/grey.css' , __FILE__ ) );
    wp_register_script('kwd_jquery_cookie_js', plugins_url( 'js/jquery.cookie.js' , __FILE__ ), array('jquery'));
    wp_register_script('jquery_hoverIntent', plugins_url( 'js/jquery.hoverIntent.minified.js' , __FILE__ ), array('kwd_jquery_cookie_js'));
    wp_register_script('jquery_dcjqaccordion', plugins_url( 'js/jquery.dcjqaccordion.2.7.min.js' , __FILE__ ), array('jquery_hoverIntent'));
    wp_register_script('kwd_accordion_custom_scripts', plugins_url( 'js/kwd-accordion-custom-scripts.js' , __FILE__ ), array('jquery_dcjqaccordion'));

    wp_enqueue_style('dcaccordion_css');
    wp_enqueue_script('kwd_accordion_custom_scripts');

    wp_localize_script('kwd_accordion_custom_scripts', 'kwd_accordion_obj', $this->kwd_cats_array);
  }

}