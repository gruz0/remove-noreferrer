<?php
/**
 * Core class
 *
 * @package Remove_Noreferrer
 * @subpackage Admin
 * @since 1.3.0
 */

namespace Remove_Noreferrer;

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
	 * @return Remove_Noreferrer\Plugin
	 */
	public function run() {
		$admin = new Admin();

		if ( is_admin() ) {
			$admin->init();
		} else {
			$links_processor = new Links_Processor();

			$frontend = new Frontend( $admin, $links_processor );
			$frontend->init();
		}

		return $this;
	}
}
