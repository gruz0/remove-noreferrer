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

if ( ! defined( 'WPINC' ) ) {
	die();
}

require_once( plugin_dir_path( __FILE__ ) . '/inc/autoloader.php' );

/**
 * Plugin's option key
 *
 * @since 1.1.1
 */
define( 'GRN_OPTION_KEY', 'remove_noreferrer' );

/**
 * Plugin's option key stores `where_should_the_plugin_work` values
 *
 * @since 1.3.0
 */
define( 'GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY', 'where_should_the_plugin_work' );

/**
 * Allowed values
 *
 * @since 1.3.0
 */
define(
	'GRN_ALLOWED_VALUES',
	array(
		'post',
		'posts_page',
		'page',
		'comments',
		'text_widget',
		'custom_html_widget',
	)
);

// Load plugin's core.
add_action( 'plugins_loaded', 'Remove_Noreferrer\run_plugin' );

register_activation_hook( __FILE__, array( 'Remove_Noreferrer\Core\Plugin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Remove_Noreferrer\Core\Plugin', 'deactivate' ) );
register_uninstall_hook( __FILE__, array( 'Remove_Noreferrer\Core\Plugin', 'uninstall' ) );

/**
 * Runs plugin
 *
 * @since 1.3.0
 *
 * @return Remove_Noreferrer\Admin\Plugin|Remove_Noreferrer\Frontend\Plugin
 */
function run_plugin() {
	$options = new \Remove_Noreferrer\Core\Options();
	$plugin  = new \Remove_Noreferrer\Core\Plugin( $options );

	return $plugin->run();
}
