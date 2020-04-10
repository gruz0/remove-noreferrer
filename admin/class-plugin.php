<?php
/**
 * Admin's part of the plugin : Plugin class
 *
 * Manages plugin's backend
 *
 * @package Remove_Noreferrer
 * @subpackage Admin
 * @since 1.1.0
 */

namespace Remove_Noreferrer\Admin;

/**
 * Admin's part of the plugin
 *
 * @since 1.1.0
 */
class Plugin {
	/**
	 * Where should plugin's menu item be located
	 *
	 * @since 1.1.0
	 * @access public
	 * @var string GRN_PARENT_SLUG
	 */
	const GRN_PARENT_SLUG = 'options-general.php';

	/**
	 * Plugin's menu slug
	 *
	 * @since 1.1.0
	 * @access public
	 * @var string GRN_MENU_SLUG
	 */
	const GRN_MENU_SLUG = 'remove_noreferrer';

	/**
	 * Plugin's nonce value
	 *
	 * @since 1.1.0
	 * @access public
	 * @var string GRN_NONCE_VALUE
	 */
	const GRN_NONCE_VALUE = 'gruz0_remove_noreferrer_nonce';

	/**
	 * Plugin's nonce action
	 *
	 * @since 1.1.0
	 * @access public
	 * @var string GRN_NONCE_ACTIOn
	 */
	const GRN_NONCE_ACTION = 'remove_noreferrer';

	/**
	 * Add options page under the Settings menu
	 *
	 * @since 1.1.0
	 * @access public
	 * @static
	 */
	public static function add_menu() {
		$page_title = __( 'Remove Noreferrer Options', 'remove-noreferrer' );
		$menu_title = __( 'Remove Noreferrer', 'remove-noreferrer' );
		$capability = 'manage_options';
		$function   = array( __CLASS__, 'render_options_page' );

		add_submenu_page( self::GRN_PARENT_SLUG, $page_title, $menu_title, $capability, self::GRN_MENU_SLUG, $function );
	}

	/**
	 * Validate and save options
	 *
	 * @since 1.1.0
	 * @access public
	 * @static
	 */
	public static function update_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Unauthorized user' );
		}

		if ( ! wp_verify_nonce( $_POST[ self::GRN_NONCE_VALUE ], self::GRN_NONCE_ACTION ) ) {
			wp_die( __( 'Invalid nonce', 'remove-noreferrer' ) );
		}

		update_option( GRN_OPTION_KEY, self::validate_options() );

		wp_redirect( admin_url( self::GRN_PARENT_SLUG . '?page=' . self::GRN_MENU_SLUG ), 303 );
		exit;
	}

	/**
	 * Render form
	 *
	 * @since 1.1.0
	 * @access public
	 * @static
	 */
	public static function render_options_page() {
		$options = get_option( GRN_OPTION_KEY, self::get_default_options() );
		self::options_page( $options )->render();
	}

	/**
	 * Return plugin's default options if options are not found in the database
	 *
	 * @since 1.1.0
	 * @access public
	 * @static
	 *
	 * @return array
	 */
	public static function get_default_options() {
		return array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => GRN_ALLOWED_VALUES );
	}

	/**
	 * Options Page class
	 *
	 * @since 1.1.0
	 * @access private
	 * @static
	 *
	 * @param array $options Options.
	 *
	 * @return Remove_Noreferrer\Admin\Options_Page
	 */
	private static function options_page( $options ) {
		return new Options_Page( $options );
	}

	/**
	 * Validate and sanitize options
	 *
	 * @since 1.1.0
	 * @access private
	 * @static
	 *
	 * @return array
	 */
	private static function validate_options() {
		$new_values = $_POST['remove_noreferrer'] ?? array();

		return self::options_validator()->call( $new_values );
	}

	/**
	 * Options Validator class
	 *
	 * @since 1.1.0
	 * @access private
	 * @static
	 *
	 * @return Remove_Noreferrer\Admin\Options_Validator
	 */
	private static function options_validator() {
		return new Options_Validator();
	}
}

