<?php
/**
 * Remove Noreferrer
 *
 * @package           Remove_Noreferrer
 * @author            Alexander Kadyrov
 * @copyright         2019-2020 Alexander Kadyrov
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Remove Noreferrer
 * Plugin URI:        https://wordpress.org/plugins/remove-noreferrer/
 * Description:       This plugin removes rel="noreferrer" from links in Posts, Pages, Home Page and comments
 * Version:           2.0.0
 * Requires at least: 5.3
 * Requires PHP:      7.1
 * Author:            Alexander Kadyrov
 * Author URI:        https://kadyrov.dev/
 * Text Domain:       remove-noreferrer
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
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
 * @since 2.0.0
 */
define( 'GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY', 'where_should_the_plugin_work' );

/**
 * Allowed values
 *
 * @since 2.0.0
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
 * @since 2.0.0
 *
 * @return Remove_Noreferrer\Admin\Plugin|Remove_Noreferrer\Frontend\Plugin
 */
function run_plugin() {
	$options = new \Remove_Noreferrer\Core\Options();

	// TODO: Если после запуска Core нет нужных опций, то их надо создать
	// В этом случае в $options надо перечитать значения, т.к. они будут закешированными.
	//
	// TODO: Возможно будет смысл вообще убрать разбиение на фронтовую и админскую части, т.к. сейчас получается,
	// что после запуска тестов всегда инициализируется фронтендовая часть и аффектит тесты.

	if ( is_admin() ) {
		return new \Remove_Noreferrer\Admin\Plugin( $options );
	}

	return new \Remove_Noreferrer\Frontend\Plugin( $options, new \Remove_Noreferrer\Frontend\Links_Processor() );
}

