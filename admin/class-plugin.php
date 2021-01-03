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
class Plugin extends \Remove_Noreferrer\Base\Plugin {
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
	 * @var string GRN_NONCE_ACTION
	 */
	const GRN_NONCE_ACTION = 'remove_noreferrer';

	/**
	 * \Remove_Noreferrer\Core\Options instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var \Remove_Noreferrer\Core\Options $options
	 */
	private $options;

	/**
	 * \Remove_Noreferrer\Core\Adapter instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var \Remove_Noreferrer\Core\Adapter $adapter
	 */
	private $adapter;

	/**
	 * Options_Page instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Options_Page $options_page
	 */
	private $options_page;

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param \Remove_Noreferrer\Core\Options $options      Options class.
	 * @param \Remove_Noreferrer\Core\Adapter $adapter      Adapter class.
	 * @param Options_Page                    $options_page Options_Page class.
	 */
	public function __construct(
		\Remove_Noreferrer\Core\Options $options,
		\Remove_Noreferrer\Core\Adapter $adapter,
		Options_Page $options_page
	) {
		$this->options      = $options;
		$this->adapter      = $adapter;
		$this->options_page = $options_page;

		add_action( 'remove_noreferrer_admin_plugin_loaded', array( & $this, 'add_hooks' ) );

		parent::__construct();
	}

	/**
	 * Initializes plugin
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function add_hooks() {
		add_action( 'admin_menu', array( & $this, 'add_menu' ) );
		add_action( 'admin_post_remove_noreferrer_update_options', array( & $this, 'update_options' ) );

		$this->do_action( 'hooks_added' );
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

		// phpcs:disable WordPress.Security.NonceVerification.Missing
		if ( empty( $_POST[ self::GRN_NONCE_VALUE ] ) ) {
			wp_die( 'Nonce must be set' );
		}

		if ( ! $this->adapter->wp_verify_nonce( $_POST[ self::GRN_NONCE_VALUE ], self::GRN_NONCE_ACTION ) ) {
			wp_die( 'Invalid nonce' );
		}

		if ( empty( $_POST['remove_noreferrer'] ) ) {
			wp_die( 'No options given' );
		}

		$options     = $this->options->get_options();
		$new_options = array_merge( $options, $this->validate_options( $_POST['remove_noreferrer'] ) );
		// phpcs:enable WordPress.Security.NonceVerification.Missing

		$this->options->update_options( $new_options );

		$args = add_query_arg(
			array(
				'page'    => self::GRN_MENU_SLUG,
				'updated' => true,
				'tab'     => $this->get_current_tab(),
			),
			admin_url( self::GRN_PARENT_SLUG )
		);

		// We use adapter to cover this case with tests.
		$this->adapter->wp_safe_redirect( $args, 303 );
	}

	/**
	 * Render form
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function render_options_page() {
		// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
		echo $this->options_page->render( $this->options->get_options(), $this->get_current_tab() );
		// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Validate and sanitize options
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param mixed $new_values New values.
	 *
	 * @return array
	 */
	private function validate_options( $new_values ) {
		return $this->options_validator()->call( $new_values );
	}

	/**
	 * Options Validator class
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @return Options_Validator
	 */
	private function options_validator() {
		return new Options_Validator();
	}

	/**
	 * Returns current tab
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @return string
	 */
	private function get_current_tab() {
		// phpcs:disable WordPress.Security.NonceVerification.Recommended
		return ( ! empty( $_GET['tab'] ) ) ? $_GET['tab'] : 'general';
		// phpcs:enable WordPress.Security.NonceVerification.Recommended
	}
}

