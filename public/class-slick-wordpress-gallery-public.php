<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.herooutoftime.com
 * @since      1.0.0
 *
 * @package    Slick_Wordpress_Gallery
 * @subpackage Slick_Wordpress_Gallery/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Slick_Wordpress_Gallery
 * @subpackage Slick_Wordpress_Gallery/public
 * @author     Andreas Bilz <andreas.bilz@gmail.com>
 */
class Slick_Wordpress_Gallery_Public
{

  /**
   * The ID of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string $plugin_name The ID of this plugin.
   */
  private $plugin_name;

  /**
   * The version of this plugin.
   *
   * @since    1.0.0
   * @access   private
   * @var      string $version The current version of this plugin.
   */
  private $version;

  /**
   * Initialize the class and set its properties.
   *
   * @since    1.0.0
   * @param      string $plugin_name The name of the plugin.
   * @param      string $version The version of this plugin.
   */
  public function __construct($plugin_name, $version)
  {

    $this->plugin_name = $plugin_name;
    $this->version = $version;

  }

  /**
   * Register the stylesheets for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_styles()
  {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Slick_Wordpress_Gallery_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Slick_Wordpress_Gallery_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/slick-wordpress-gallery-public.css', array(), $this->version, 'all');

  }

  /**
   * Register the JavaScript for the public-facing side of the site.
   *
   * @since    1.0.0
   */
  public function enqueue_scripts()
  {

    /**
     * This function is provided for demonstration purposes only.
     *
     * An instance of this class should be passed to the run() function
     * defined in Slick_Wordpress_Gallery_Loader as all of the hooks are defined
     * in that particular class.
     *
     * The Slick_Wordpress_Gallery_Loader will then create the relationship
     * between the defined hooks and the functions defined in this
     * class.
     */

    wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/slick-wordpress-gallery-public.js', array('jquery'), $this->version, false);

  }

  public function post_gallery( $output = '', $atts, $instance ) {
    $return = $output; // fallback
    if((bool) $atts['slick_use_slick'])
      $my_result = $this->gallery_content( $atts );

    // boolean false = empty, see http://php.net/empty
    if( !empty( $my_result ) ) {
      $return = $my_result;
    }
    return $return;
  }

  public function gallery_content($atts)
  {
    $atts = array_merge(array(
      'slick_arrows' => true,
      'slick_autoplay' => true,
      'slick_autoplay_speed' => 5,
    ), $atts);
    $bool_keys = array('dots', 'arrows', 'infinite', 'draggable', 'fade', 'centerMode', 'adaptiveHeight', 'autoplay');
    foreach($atts as $k => $v) {
      // Convert properties to valid slick properties
      $_nk = lcfirst(str_replace('_', '', ucwords($k, '_')));
      $atts[$_nk] = $v;
      // Remove old properties
      if($_nk !== $k)
        unset($atts[$k]);
      // Remove prefix `slick`
      if(strpos($k, 'slick') !== FALSE) {
        $slick_atts[lcfirst(str_replace('slick', '', $_nk))] = (int) $v;
      }
    }
    // Convert specified int values to bool values
    foreach ($bool_keys as $bool_key) {
      $slick_atts[$bool_key] = (bool) $slick_atts[$bool_key];
    }
    // Convert seconds to milliseconds
    if($slick_atts['autoplaySpeed'] > 0)
      $slick_atts['autoplaySpeed'] = $slick_atts['autoplaySpeed'] * 1000;

    $ids = explode(',', $atts['ids']);
    $include = explode(',', $atts['include']);
    // Not the same - return: some issue
    if (count(array_diff($ids, $include)) > 0)
      return;
    // Get gallery items
    foreach ($include as $item) {
      $meta = get_post($item);
      $img_html = wp_get_attachment_image($item, $size, false, array('class' => 'img-responsive'));
      $image = <<<IMAGE
      <figure id="image-$item">
        $img_html
        <figcaption>{$meta->post_excerpt}</figcaption>
      </figure>
IMAGE;
      $o[] = $image;
    }

    $slick_class = '';
    $slick_attr = '';
    if ($atts['slickUseSlick']) {
      $slick_class = "class='slick'";
      $slick_attr = "data-slick='".json_encode($slick_atts) . "'";
//      $slick_attr = "data-slick='$slick_attr'";
    }
    return "<div $slick_class $slick_attr><div>" . implode('</div><div>', $o) . "</div></div>";
  }
}
