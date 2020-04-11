<?php
declare(strict_types=1);

namespace Remove_Noreferrer\Frontend;

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
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function setUp(): void {
		set_current_screen( 'front' );

		$this->_plugin = new Plugin( new \Remove_Noreferrer\Core\Options(), new Links_Processor() );

		parent::setUp();
	}

	/**
	 * Finishes tests
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function tearDown(): void {
		unset( $GLOBALS['screen'] );
		unset( $GLOBALS['current_screen'] );

		parent::tearDown();
	}
}

