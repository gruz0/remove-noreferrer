<?php
declare(strict_types=1);

namespace Remove_Noreferrer\Core;

class Plugin_Test extends \WP_UnitTestCase {
	/**
	 * Remove_Noreferrer\Core\Plugin instance
	 *
	 * @since 1.3.0
	 * @access private
	 * @var Remove_Noreferrer\Core\Plugin $_plugin
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
