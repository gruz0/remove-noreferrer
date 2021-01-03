<?php
/**
 * Remove Noreferrer
 *
 * @package           Remove_Noreferrer
 * @author            Alexander Kadyrov <alexander@kadyrov.dev>
 * @copyright         2019-2021 Alexander Kadyrov
 * @license           GPL-2.0-or-later
 *
 * @wordpress-plugin
 * Plugin Name:       Remove Noreferrer
 * Plugin URI:        https://wordpress.org/plugins/remove-noreferrer/
 * Github URI:        https://github.com/gruz0/remove-noreferrer
 * Description:       Removes rel="noreferrer" attribute from links on your website on-the-fly
 * Version:           2.0.0
 * Requires at least: 5.1
 * Requires PHP:      5.6
 * Author:            Alexander Kadyrov
 * Author URI:        https://kadyrov.dev/
 * Text Domain:       remove-noreferrer
 * License:           GPLv2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace Remove_Noreferrer;

if ( ! defined( 'WPINC' ) ) {
	die();
}

require_once plugin_dir_path( __FILE__ ) . '/inc/autoloader.php';

/**
 * Current plugin's version
 *
 * @since 2.0.0
 */
define( 'GRN_VERSION', '2.0.0' );

/**
 * Plugin's option key
 *
 * @since 1.1.1
 */
define( 'GRN_OPTION_KEY', 'remove_noreferrer' );

/**
 * Stores `plugin_version` value
 *
 * @since 2.0.0
 */
define( 'GRN_PLUGIN_VERSION_KEY', 'plugin_version' );

/**
 * Stores `where_should_the_plugin_work` values
 *
 * @since 2.0.0
 */
define( 'GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY', 'where_should_the_plugin_work' );

/**
 * Stores `remove_settings_on_uninstall` value
 *
 * @since 2.0.0
 */
define( 'GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY', 'remove_settings_on_uninstall' );

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
 * @codeCoverageIgnore
 */
function run_plugin() {
	$options = new Core\Options();
	$adapter = new Core\Adapter();
	$plugin  = new Plugin( $options, $adapter );

	$plugin->run();
}

