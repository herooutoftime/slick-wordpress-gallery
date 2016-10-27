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

		if(WP_SLICK_CSS !== FALSE) {
			wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/slick-wordpress-gallery-public.css', array(), $this->version, 'all');
			wp_enqueue_style($this->plugin_name . '_slickcss_base', plugin_dir_url(__FILE__) . 'bower_components/slick-carousel/slick/slick.css', array(), $this->version, 'all');
			wp_enqueue_style($this->plugin_name . '_slickcss_theme', plugin_dir_url(__FILE__) . 'bower_components/slick-carousel/slick/slick-theme.css', array(), $this->version, 'all');
		}
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
		if(WP_SLICK_JS !== FALSE) {
			$suffix = '.min';
			if(WP_SLICK_DEV)
				$suffix = '';
			var_dump(plugin_dir_url(__FILE__) . 'bower_components/slick-carousel/slick/slick'.$suffix.'.js');
			wp_enqueue_script($this->plugin_name . '_slickjs', plugin_dir_url(__FILE__) . 'bower_components/slick-carousel/slick/slick'.$suffix.'.js', array('jquery'), $this->version, false);
			wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/slick-wordpress-gallery-public.js', array($this->plugin_name . '_slickjs'), $this->version, false);
			wp_localize_script( $this->plugin_name, 'WP_SLICK', array(
				'dev_mode' => WP_SLICK_DEV,
				'css' => WP_SLICK_CSS,
				'js' => WP_SLICK_JS,
			));
		}
	}

	public function post_gallery( $output = '', $atts, $instance ) {
		$return = $output; // fallback
		if((bool) $atts['slick_use_slick'])
			$slickified = $this->gallery_content( $atts );

		// boolean false = empty, see http://php.net/empty
		if( !empty( $slickified ) ) {
			$return = $slickified;
		}
		return $return;
	}

	public function gallery_content($atts)
	{
		$slick_slides = array();
		$atts = array_merge(array(
			'slick_arrows' => true,
			'slick_autoplay' => true,
			'slick_autoplay_speed' => 5,
			'size' => 'thumbnail',
			'link' => 'post',
		), $atts);

		$ids = explode(',', $atts['ids']);
		$include = explode(',', $atts['include']);
		// Not the same - return: some issue
		if (count(array_diff($ids, $include)) > 0)
			return false;

		$bool_keys = array('dots', 'arrows', 'infinite', 'draggable', 'fade', 'centerMode', 'adaptiveHeight', 'autoplay');
		$dur_keys = array('autoplaySpeed', 'speed');
		$suffix_keys = array('centerPadding' => 'px');
		foreach($atts as $k => $v) {
			// Skip possible empty properties which would be converted to bool false and therefore would override defaults
			if($v === '')
				continue;
			// Convert properties to valid slick properties
			$_nk = lcfirst(str_replace('_', '', ucwords($k, '_')));
			$v = preg_replace('/(\w+)\s{0,1}:/', '"\1":', str_replace(array("\r\n", "\r", "\n", "\t"), "", $v));
			$atts[$_nk] = $v;
			// Remove old properties
			if($_nk !== $k)
				unset($atts[$k]);
			// Remove prefix `slick`
			if(strpos($k, 'slick') !== FALSE) {
				if(filter_var($v, FILTER_VALIDATE_FLOAT))
					$slick_atts[lcfirst(str_replace('slick', '', $_nk))] = (float) $v;
				if(filter_var($v, FILTER_VALIDATE_INT))
					$slick_atts[lcfirst(str_replace('slick', '', $_nk))] = (int) $v;
			}

			// Convert specified int values to bool values
			if(in_array(lcfirst(str_replace('slick', '', $_nk)), $bool_keys)) {
				$slick_atts[lcfirst(str_replace('slick', '', $_nk))] = (bool) $v;
			}
			if(in_array(lcfirst(str_replace('slick', '', $_nk)), $dur_keys)) {
				$slick_atts[lcfirst(str_replace('slick', '', $_nk))] = $v * 1000;
			}
			if(in_array(lcfirst(str_replace('slick', '', $_nk)), array_keys($suffix_keys))) {
				$slick_atts[lcfirst(str_replace('slick', '', $_nk))] = $v . $suffix_keys[lcfirst(str_replace('slick', '', $_nk))];
			}
			if(lcfirst(str_replace('slick', '', $_nk)) === 'responsive') {
				// Make `responsive` property valid
				$slick_atts[lcfirst(str_replace('slick', '', $_nk))] = (array) json_decode('[' . $v . ']', true);
			}
		}

		// Get gallery items
		foreach ($include as $item) {
			$meta = get_post($item);
			if ( ! empty( $atts['link'] ) && 'file' === $atts['link'] ) {
				$img_html = wp_get_attachment_link( $item, $atts['size'], false, false, false, array('class' => 'img-responsive') );
			} elseif ( ! empty( $atts['link'] ) && 'none' === $atts['link'] ) {
				$img_html = wp_get_attachment_image($item, $size, false, array('class' => 'img-responsive'));
			} else {
				$img_html = wp_get_attachment_link( $item, $atts['size'], true, false, false, array('class' => 'img-responsive') );
			}

			if(locate_template('slick/slider-item.php')) {
				ob_start();
				include(locate_template('slick/slider-item.php'));
				$slick_slides[] = ob_get_contents();
				ob_end_clean();
			} else {
				$slick_slides[] = "<figure id='image-{$item}'>{$img_html}<figcaption>{$meta->post_excerpt}</figcaption></figure>";
			}
		}

		if(WP_SLICK_DEV && locate_template('slick/slider-item.php'))
			$dev_notices[] = "Item template in theme folder `slick/` used";

		if(count($slick_slides) === 0)
			return false;

		$slick_class = '';
		$slick_attr = '';

		$slick_class = "slick";
		// Remove as it's not relevant for the slider itself
		unset($slick_atts['useSlick']);
		$slick_attr = "data-slick='".json_encode($slick_atts) . "'";

		// Allows custom templates for slider items & wrapper
		if(locate_template('slick/slider-wrapper.php')) {
			if(WP_SLICK_DEV)
				$dev_notices[] = "Wrapper template in theme folder `slick/` used";
			ob_start();
			include(locate_template('slick/slider-wrapper.php'));
			$wrapper = ob_get_contents();
			ob_end_clean();
		} else {
			$wrapper = "<div class='$slick_class' $slick_attr><div>" . implode('</div><div>', $slick_slides) . "</div></div>";
		}
		if(WP_SLICK_DEV)
			$dev_notices[] = json_encode($slick_atts, JSON_PRETTY_PRINT);
			$wrapper = '<pre>' . implode("\n", $dev_notices) . '</pre>' . $wrapper;
		return $wrapper;
	}
}
