<?php
namespace Remove_Noreferrer\Base;

class Plugin_Test extends \PHPUnit\Framework\TestCase {
	/**
	 * Remove_Noreferrer\Base\Demo_Plugin instance
	 *
	 * @since 1.3.0
	 * @access private
	 * @var Remove_Noreferrer\Base\Demo_Plugin $_plugin
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
		parent::setUp();

		$this->_plugin = new \Remove_Noreferrer\Base\Demo_Plugin();
	}

	/**
	 * @coversNothing
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_plugin_extended_from_base_plugin(): void {
		$this->assertInstanceOf( 'Remove_Noreferrer\Base\Plugin', $this->_plugin );
	}

	/**
	 * @covers \Remove_Noreferrer\Base\Plugin::__construct
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_construct_has_init_action(): void {
		$this->assertEquals( 10, has_action( 'init', array( $this->_plugin, 'init' ) ) );
	}

	/**
	 * @covers \Remove_Noreferrer\Base\Plugin::__construct
	 * @covers \Remove_Noreferrer\Base\Plugin::stringify_called_class
	 * @covers \Remove_Noreferrer\Base\Plugin::format_action
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_construct_did_remove_noreferrer_base_demo_plugin_loaded_action(): void {
		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_base_demo_plugin_loaded' ) );
	}

	/**
	 * @covers \Remove_Noreferrer\Base\Plugin::init
	 * @covers \Remove_Noreferrer\Base\Plugin::stringify_called_class
	 * @covers \Remove_Noreferrer\Base\Plugin::format_action
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_did_remove_noreferrer_base_demo_plugin_initialized_action(): void {
		do_action( 'init' );

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_base_demo_plugin_initialized' ) );
	}
}

class Demo_Plugin extends Plugin {}

