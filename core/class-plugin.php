<?php
/**
 * Core class
 *
 * @package Remove_Noreferrer
 * @subpackage Core
 * @since 2.0.0
 */

namespace Remove_Noreferrer\Core;

/**
 * Core class
 *
 * @since 2.0.0
 */
class Plugin extends \Remove_Noreferrer\Base\Plugin {
	/**
	 * Activates plugin
	 *
	 * @since 2.0.0
	 * @access public
	 * @static
	 */
	public static function activate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		do_action( 'remove_noreferrer_core_plugin_activated' );
	}

	/**
	 * Deactivates plugin
	 *
	 * @since 2.0.0
	 * @access public
	 * @static
	 */
	public static function deactivate() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		do_action( 'remove_noreferrer_core_plugin_deactivated' );
	}

	/**
	 * Uninstalls plugin
	 *
	 * @since 2.0.0
	 * @access public
	 * @static
	 */
	public static function uninstall() {
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$options = new Options();

		if ( '1' === $options->get_option( 'remove_settings_on_uninstall' ) ) {
			$options->delete_options();
		}

		do_action( 'remove_noreferrer_core_plugin_uninstalled' );
	}
}

