<?php
declare(strict_types=1);

namespace Remove_Noreferrer\Frontend;

use Remove_Noreferrer\Admin\Plugin as Admin;

class Plugin_Test extends \WP_UnitTestCase {
	/**
	 * Remove_Noreferrer\Frontend\Plugin instance
	 *
	 * @since 1.3.0
	 * @access private
	 * @var Remove_Noreferrer\Frontend\Plugin $_plugin
	 */
	private $_plugin;

	/**
	 * Prepares environment
	 *
	 * @return void
	 */
	public function setUp(): void {
		$admin           = new Admin();
		$links_processor = new Links_Processor();

		$this->_plugin = new Plugin( $admin, $links_processor );
	}
}
