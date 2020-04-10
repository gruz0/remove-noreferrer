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

// Admin part.
add_action( 'admin_menu', array( 'Remove_Noreferrer\Admin\Plugin', 'add_menu' ) );
add_action( 'admin_post_remove_noreferrer_update_options', array( 'Remove_Noreferrer\Admin\Plugin', 'update_options' ) );

// Frontend part.
add_filter( 'the_content', array( 'Remove_Noreferrer\Frontend\Plugin', 'remove_noreferrer_from_content' ), 999 );
add_filter( 'comment_text', array( 'Remove_Noreferrer\Frontend\Plugin', 'remove_noreferrer_from_comment' ), 20, 3 );
add_filter( 'widget_display_callback', array( 'Remove_Noreferrer\Frontend\Plugin', 'remove_noreferrer_from_text_widget' ), 10, 3 );
add_filter( 'widget_custom_html_content', array( 'Remove_Noreferrer\Frontend\Plugin', 'remove_noreferrer_from_custom_html_widget' ), 10, 3 );

// Load plugin's core.
add_action( 'plugins_loaded', 'Remove_Noreferrer\run_plugin' );

/**
 * Runs plugin
 *
 * @since 1.3.0
 *
 * @return Remove_Noreferrer\Core\Plugin
 */
function run_plugin() {
	$options = new \Remove_Noreferrer\Core\Options();
	$plugin  = new \Remove_Noreferrer\Core\Plugin( $options );

	return $plugin->run();
}
