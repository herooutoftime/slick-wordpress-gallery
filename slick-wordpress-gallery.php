<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.herooutoftime.com
 * @since             1.0.0
 * @package           Slick_Wordpress_Gallery
 *
 * @wordpress-plugin
 * Plugin Name:       Slick Wordpress Gallery
 * Plugin URI:        https://github.com/herooutoftime/slick-wordpress-gallery
 * Description:       Easily turn WP gallery into a Slick slider. Enable the plugin, add/edit your galleries and set slick properties through custom gallery sidebar options.
 * Version:           1.0.0
 * Author:            Andreas Bilz
 * Author URI:        http://www.herooutoftime.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       slick-wordpress-gallery
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-slick-wordpress-gallery-activator.php
 */
function activate_slick_wordpress_gallery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-slick-wordpress-gallery-activator.php';
	Slick_Wordpress_Gallery_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-slick-wordpress-gallery-deactivator.php
 */
function deactivate_slick_wordpress_gallery() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-slick-wordpress-gallery-deactivator.php';
	Slick_Wordpress_Gallery_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_slick_wordpress_gallery' );
register_deactivation_hook( __FILE__, 'deactivate_slick_wordpress_gallery' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-slick-wordpress-gallery.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_slick_wordpress_gallery() {

	$plugin = new Slick_Wordpress_Gallery();
	$plugin->run();

}
run_slick_wordpress_gallery();
