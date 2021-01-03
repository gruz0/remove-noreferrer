<?php
/**
 * Options class
 *
 * @package Remove_Noreferrer
 * @subpackage Core
 * @since 2.0.0
 */

namespace Remove_Noreferrer\Core;

/**
 * Options class
 *
 * @since 2.0.0
 */
class Options {
	/**
	 * Options array
	 *
	 * @since 2.0.0
	 * @access private
	 * @var array $options
	 */
	private $options = null;

	/**
	 * Allowed options keys
	 *
	 * @since 2.0.0
	 * @access private
	 * @var array $allowed_options_keys
	 */
	private $allowed_options_keys = array(
		GRN_PLUGIN_VERSION_KEY,
		GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY,
		GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY,
	);

	/**
	 * Returns options from database
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_options() {
		$this->set_options();

		return $this->options;
	}

	/**
	 * Returns option by key
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $key Option's key.
	 * @param mixed  $default Default option's value.
	 *
	 * @return mixed
	 * @throws \InvalidArgumentException If key does not exist.
	 */
	public function get_option( $key, $default = null ) {
		if ( ! in_array( $key, $this->allowed_options_keys, true ) ) {
			throw new \InvalidArgumentException( "Key ${key} does not exist" );
		}

		$this->set_options();

		return ( ! empty( $this->options[ $key ] ) ) ? $this->options[ $key ] : $default;
	}

	/**
	 * Updates options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param mixed $new_options New options.
	 */
	public function update_options( $new_options ) {
		update_option( GRN_OPTION_KEY, $new_options );

		do_action( 'remove_noreferrer_options_updated' );
	}

	/**
	 * Deletes options
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function delete_options() {
		delete_option( GRN_OPTION_KEY );

		do_action( 'remove_noreferrer_options_deleted' );
	}

	/**
	 * Returns default options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_default_options() {
		return array(
			GRN_PLUGIN_VERSION_KEY               => GRN_VERSION,
			GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array(),
			GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '0',
		);
	}

	/**
	 * Stores options from database in local variable
	 *
	 * @since 2.0.0
	 * @access private
	 */
	private function set_options() {
		$this->options = get_option( GRN_OPTION_KEY, $this->get_default_options() );
	}
}

