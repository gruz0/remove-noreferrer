<?php
/**
 * Core class
 *
 * @package Remove_Noreferrer
 * @subpackage Core
 * @since 1.3.0
 */

namespace Remove_Noreferrer\Core;

use Remove_Noreferrer\Admin\Plugin as Admin;
use Remove_Noreferrer\Frontend\Plugin as Frontend;
use Remove_Noreferrer\Frontend\Links_Processor as Links_Processor;

/**
 * Core class
 *
 * @since 1.3.0
 */
class Plugin {
	/**
	 * Remove_Noreferrer\Core\Options instance
	 *
	 * @since 1.3.0
	 * @access private
	 * @var Remove_Noreferrer\Core\Options $_options
	 */
	private $_options;

	/**
	 * Constructor
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @param \Remove_Noreferrer\Core\Options $options Options class.
	 */
	public function __construct( \Remove_Noreferrer\Core\Options $options ) {
		$this->_options = $options;
	}

	/**
	 * Loads plugin depends on current WordPress's area
	 *
	 * @since 1.3.0
	 *
	 * @return Remove_Noreferrer\Core\Plugin
	 */
	public function run() {
		if ( is_admin() ) {
			new Admin( $this->_options );
		} else {
			new Frontend( $this->_options, new Links_Processor() );
		}

		return $this;
	}
}
