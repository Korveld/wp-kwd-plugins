<?php
/*
Plugin Name: KWD Posts from vk.com wall
Description: Plugin creates widgets for displaying posts from vk.com wall
Plugin URI: https://github.com/Korveld
Version: 1.0
Author: Korveld WebDev
Author URI: https://github.com/Korveld
*/

add_action('widgets_init', 'kwd_vk');

function kwd_vk() {
  register_widget('KWD_VK');
}

class KWD_VK extends WP_Widget {
  public $title, $count;

  function __construct() {
    $args = array(
      'description' => 'Widgets for displaying posts from vk.com wall'
    );
    parent::__construct('kwd_vk', 'Posts from vk.com wall', $args);
  }

  function form($instance) {
    extract($instance);
    $title = !empty($title) ? esc_attr($title) : '';
    $count = isset($count) ? $count : 3;
    ?>
    <p>
      <label for="<?php echo $this->get_field_id('title') ?>">VK profile (ID or short url)</label>
      <input
        type="text"
        name="<?php echo $this->get_field_name('title') ?>"
        id="<?php echo $this->get_field_id('title') ?>"
        value="<?php echo $title; ?>"
        class="widefat"
      >
    </p>
    <p>
      <label for="<?php echo $this->get_field_id('count') ?>">Number of posts (max 100)</label>
      <input
        type="text"
        name="<?php echo $this->get_field_name('count') ?>"
        id="<?php echo $this->get_field_id('count') ?>"
        value="<?php echo $count; ?>"
        class="widefat"
      >
    </p>
    <?php
  }

  function widget($args, $instance) {
    extract($args);
    extract($instance);
    /*$title = 'teidar'; // error test 1237547
    $count = 3;*/

    $this->title = $title;
    $this->count = $count;
    $data = $this->kwd_get_posts_vk();
    if ($data === false) {
      $data = '<p>Error getting vk wall posts, please check your url</p>';
    } elseif (empty($data)) {
      $data = '<p>There are no posts to display</p>';
    }
    //var_dump($data);
    echo $before_widget;
    echo $before_title . "VK wall posts of {$title}" . $after_title;
    echo $data;
    echo $after_widget;
  }

  function update($new_instance, $old_instance) {
    $new_instance['title'] = !empty($new_instance['title']) ? strip_tags($new_instance['title']) : '';
    $new_instance['count'] = ((int)$new_instance['count']) ? $new_instance['count'] : 3;
    return $new_instance;
  }

  private function kwd_substr($str) {
    $str_arr = explode(' ', $str);
    $str_arr2 = array_slice($str_arr, 0, 100);
    $str = implode(' ', $str_arr2);
    if (count($str_arr) > 100) {
      $str .= '...';
    }
    return $str;
  }

  private function kwd_get_posts_vk() {
    // http://api.vk.com/method/wall.get?{$id}&filter=owner&count={$count}
    if (is_numeric($this->title)) {
      $id = "owner_id={$this->title}";
      $this->title = "id{$this->title}";
    } elseif ($this->title == '') {
      $id = "owner_id=USER_ID";
    } else {
      $id = "domain={$this->title}";
    }
    if (!(int)$this->count) $this->count = 3;
    $count = $this->count;
    $url = "https://api.vk.com/method/wall.get?v=5.52&access_token=56788db68175bed51179727ad9569197d101d19fd41d248e9a697a35d647c7941138c178151d9af378dca&{$id}&filter=all&count={$count}";
    $vk_posts = wp_remote_get($url);
    $vk_posts = json_decode($vk_posts['body']);

    if (isset($vk_posts->error)) return false;

    $html = '<div class="kwd-vk">';
    foreach ($vk_posts->response->items as $item) {
      $text = $this->kwd_substr($item->text);
      if (!empty($item->attachments)) {
        $html .= "<div class='vk-post'><p>" . nl2br($text) . "</p><div class='img-wrap'>";
        foreach ($item->attachments as $attachment) {
          $html .= "<img src='{$attachment->photo->photo_1280}' alt=''>";
        }
        $html .= "</div><p><a href='https://vk.com/{$this->title}' target='_blank'>Read more</a></p></div>";
      } else {
        $html .= "<div class='vk-post'><p>" . nl2br($text) . "</p><p><a href='https://vk.com/{$this->title}' target='_blank'>Read more</a></p></div>";
      }
    }
    $html .= '</div>';

    return $html;
  }
}
