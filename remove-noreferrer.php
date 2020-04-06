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

use Remove_Noreferrer\Admin\Plugin as Admin;
use Remove_Noreferrer\Frontend\Plugin as Frontend;
use Remove_Noreferrer\Frontend\Links_Processor as Links_Processor;

if ( ! defined( 'WPINC' ) ) {
	die();
}

require_once( plugin_dir_path( __FILE__ ) . '/inc/autoloader.php' );

add_action( 'plugins_loaded', 'Remove_Noreferrer\remove_noreferrer' );

/**
 * Load plugin depends on current WordPress's area
 *
 * @since 1.1.0
 */
function remove_noreferrer() {
	$admin = new Admin();

	if ( is_admin() ) {
		$admin->init();
	} else {
		$links_processor = new Links_Processor();

		$frontend = new Frontend( $admin, $links_processor );
		$frontend->init();
	}
}

