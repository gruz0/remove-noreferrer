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
	 * Initialize
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function init() {
		$this->add_hooks();
	}

	/**
	 * Add hooks
	 *
	 * @since 1.1.0
	 * @access private
	 */
	private function add_hooks() {
		add_action( 'admin_menu', array( & $this, 'add_menu' ) );
		add_action( 'admin_post_remove_noreferrer_update_options', array( & $this, 'update_options' ) );
	}

	/**
	 * Add options page under the Settings menu
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function add_menu() {
		$page_title = __( 'Remove Noreferrer Options', 'remove-noreferrer' );
		$menu_title = __( 'Remove Noreferrer', 'remove-noreferrer' );
		$capability = 'manage_options';
		$function   = array( & $this, 'render_options_page' );

		add_submenu_page( self::GRN_PARENT_SLUG, $page_title, $menu_title, $capability, self::GRN_MENU_SLUG, $function );
	}

	/**
	 * Validate and save options
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function update_options() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( 'Unauthorized user' );
		}

		if ( ! wp_verify_nonce( $_POST[ self::GRN_NONCE_VALUE ], self::GRN_NONCE_ACTION ) ) {
			wp_die( __( 'Invalid nonce', 'remove-noreferrer' ) );
		}

		update_option( GRN_OPTION_KEY, $this->validate_options() );

		wp_redirect( admin_url( self::GRN_PARENT_SLUG . '?page=' . self::GRN_MENU_SLUG ), 303 );
		exit;
	}

	/**
	 * Render form
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function render_options_page() {
		$options = get_option( GRN_OPTION_KEY, $this->get_default_options() );
		$this->options_page( $options )->render();
	}

	/**
	 * Options Page class
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param array $options Options.
	 *
	 * @return Remove_Noreferrer\Admin\Options_Page
	 */
	private function options_page( $options ) {
		return new Options_Page( $options );
	}

	/**
	 * Return plugin's default options if options are not found in the database
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_default_options() {
		return array(
			'where_should_the_plugin_work' => array( 'post', 'posts_page', 'page' ),
		);
	}

	/**
	 * Validate and sanitize options
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @return array
	 */
	private function validate_options() {
		if ( empty( $_POST['remove_noreferrer'] ) ) {
			return $this->unset_options();
		}

		return $this->options_validator()->validate( (array) $_POST['remove_noreferrer'] );
	}

	/**
	 * Options Validator class
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @return Remove_Noreferrer\Admin\Options_Validator
	 */
	private function options_validator() {
		return new Options_Validator();
	}

	/**
	 * Return array with empty options
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @return array
	 */
	private function unset_options() {
		return array(
			'where_should_the_plugin_work' => array(),
		);
	}
}

