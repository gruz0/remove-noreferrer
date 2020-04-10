<?php
declare(strict_types=1);

namespace Remove_Noreferrer\Admin;

class Plugin_Test extends \WP_UnitTestCase {
	/**
	 * Remove_Noreferrer\Admin\Plugin instance
	 *
	 * @since 1.3.0
	 * @access private
	 * @var Remove_Noreferrer\Admin\Plugin $_plugin
	 */
	private $_plugin;

	/**
	 * Prepares environment
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->_plugin = new Plugin();
	}
}
