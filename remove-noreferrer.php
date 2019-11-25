<?php
/*
Plugin Name: Remove noreferrer
Description: This plugin removes rel="noreferrer" from post's links on the frontend
Author: Alexander Kadyrov
Author URI: https://github.com/gruz0
Text Domain: remove-noreferrer
Version: 1.1.0
*/

namespace Remove_Noreferrer;

use Remove_Noreferrer\Admin\Plugin as Admin;
use Remove_Noreferrer\Frontend\Plugin as Frontend;

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
		$frontend = new Frontend( $admin );
		$frontend->init();
	}
}

