<?php
/**
 * Frontend's part of the plugin : Plugin class
 *
 * Removes rel attributes from the links
 *
 * @package Remove_Noreferrer
 * @subpackage Frontend
 * @since 1.1.0
 */

namespace Remove_Noreferrer\Frontend;

/**
 * Frontend's part of the plugin
 *
 * @since 1.1.0
 */
class Plugin {
	/**
	 * Remove_Noreferrer\Admin instance
	 *
	 * @since 1.1.0
	 * @access private
	 * @var Remove_Noreferrer\Admin\Plugin $_admin
	 */
	private $_admin = null;

	/**
	 * Plugin's options
	 *
	 * @since 1.1.1
	 * @access private
	 * @var array $_options
	 */
	private $_options = array();

	/**
	 * Constructor
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @param Remove_Noreferrer\Admin\Plugin $admin Admin class.
	 */
	public function __construct( $admin ) {
		$this->_admin = $admin;
	}

	/**
	 * Initialize
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function init() {
		$this->load_options();
		$this->add_hooks();
	}

	/**
	 * Load options
	 *
	 * @since 1.1.1
	 * @access private
	 */
	private function load_options() {
		$this->_options = get_option( GRN_OPTION_KEY, $this->_admin->get_default_options() );
	}

	/**
	 * Add hooks
	 *
	 * @since 1.1.0
	 * @access private
	 */
	private function add_hooks() {
		add_filter( 'the_content', array( & $this, 'remove_noreferrer' ), 999 );
	}

	/**
	 * Remove referrer from "rel" attribute
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $content Content.
	 * @return string
	 */
	public function remove_noreferrer( $content ) {
		if ( ! $this->is_current_page_allowed() ) {
			return $content;
		}

		$replace = function( $matches ) {
			return sprintf( 'rel="%s"', trim( preg_replace( '/noreferrer\s*/i', '', $matches[1] ) ) );
		};

		return preg_replace_callback( '/rel="([^\"]+)"/i', $replace, $content );
	}

	/**
	 * Check if current page allowed to use by plugin
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @return bool
	 */
	private function is_current_page_allowed() {
		$where_should_the_plugin_work = $this->get_option( 'where_should_the_plugin_work' );

		return $this->is_single_processable( $where_should_the_plugin_work )
			|| $this->is_page_processable( $where_should_the_plugin_work )
			|| $this->is_posts_page_processable( $where_should_the_plugin_work );
	}

	/**
	 * Return option's value by key
	 *
	 * @since 1.1.1
	 * @access private
	 *
	 * @param string $key Key.
	 * @return mixed
	 */
	private function get_option( $key ) {
		return $this->_options[ $key ];
	}

	/**
	 * Checks if current page is a post and array contains `post`
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param array $options Options array.
	 * @return bool
	 */
	private function is_single_processable( $options ) {
		return is_single() && in_array( 'post', $options, true );
	}

	/**
	 * Checks if current page is a page and array contains `page`
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param array $options Options array.
	 * @return bool
	 */
	private function is_page_processable( $options ) {
		return is_page() && in_array( 'page', $options, true );
	}

	/**
	 * Checks if current page is a posts page and array contains `posts_page`
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param array $options Options array.
	 * @return bool
	 */
	private function is_posts_page_processable( $options ) {
		return is_home() && in_array( 'posts_page', $options, true );
	}
}

