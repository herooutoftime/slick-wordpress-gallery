<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.herooutoftime.com
 * @since      1.0.0
 *
 * @package    Slick_Wordpress_Gallery
 * @subpackage Slick_Wordpress_Gallery/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Slick_Wordpress_Gallery
 * @subpackage Slick_Wordpress_Gallery/includes
 * @author     Andreas Bilz <andreas.bilz@gmail.com>
 */
class Slick_Wordpress_Gallery_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'slick-wordpress-gallery',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
