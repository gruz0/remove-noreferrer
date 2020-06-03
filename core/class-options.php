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
	 * @var array $_options
	 */
	private $_options = array();

	/**
	 * Returns options if exist otherwise returns default options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function get_options() {
		if ( ! empty( $this->_options ) ) {
			return $this->_options;
		}

		$this->_options = get_option( GRN_OPTION_KEY, $this->get_default_options() );

		return $this->_options;
	}

	/**
	 * Return plugin's default options if options are not found in the database
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @return array
	 */
	private function get_default_options() {
		return array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => GRN_ALLOWED_VALUES );
	}

	/**
	 * Return options by key
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $key Option's key.
	 * @param mixed  $default Default option's value.
	 *
	 * @return mixed
	 */
	public function get_option( $key, $default = null ) {
		if ( empty( $this->_options ) ) {
			$this->get_options();
		}

		return $this->_options[ $key ] ?? $default;
	}
}

