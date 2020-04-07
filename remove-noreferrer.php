<?php
/*
Plugin Name: Remove noreferrer
Description: This plugin removes rel="noreferrer" from links in Posts, Pages, Home Page and comments
Author: Alexander Kadyrov
Author URI: https://github.com/gruz0
Text Domain: remove-noreferrer
Version: 1.2.0
*/

namespace Remove_Noreferrer;

/**
 * Plugin's option key
 *
 * @since 1.1.1
 */
define( 'GRN_OPTION_KEY', 'remove_noreferrer' );

if ( ! defined( 'WPINC' ) ) {
	die();
}

require_once( plugin_dir_path( __FILE__ ) . '/inc/autoloader.php' );

add_action( 'plugins_loaded', 'Remove_Noreferrer\run_plugin' );

/**
 * Runs plugin
 *
 * @since 1.3.0
 *
 * @return Remove_Noreferrer\Plugin
 */
function run_plugin() {
	$plugin = new Plugin();

	return $plugin->run();
}
