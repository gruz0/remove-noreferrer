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
	 * Loads plugin depends on current WordPress's area
	 *
	 * @since 1.3.0
	 *
	 * @return Remove_Noreferrer\Core\Plugin
	 */
	public function run() {
		if ( ! is_admin() ) {
			new Frontend( new Admin(), new Links_Processor() );
		}

		return $this;
	}
}
