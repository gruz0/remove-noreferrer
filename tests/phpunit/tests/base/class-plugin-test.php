<?php
namespace Remove_Noreferrer\Base;

class Plugin_Test extends \PHPUnit\Framework\TestCase {
	/**
	 * Remove_Noreferrer\Base\Demo_Plugin instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Remove_Noreferrer\Base\Demo_Plugin $plugin
	 */
	private $plugin;

	/**
	 * Prepares environment
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->plugin = new Demo_Plugin();
	}

	/**
	 * @coversNothing
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_plugin_extended_from_base_plugin() {
		$this->assertInstanceOf( 'Remove_Noreferrer\Base\Plugin', $this->plugin );
	}

	/**
	 * @covers \Remove_Noreferrer\Base\Plugin::__construct
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_construct_has_init_action() {
		$this->assertEquals( 10, has_action( 'init', array( $this->plugin, 'init' ) ) );
	}

	/**
	 * @covers \Remove_Noreferrer\Base\Plugin::__construct
	 * @covers \Remove_Noreferrer\Base\Plugin::stringify_called_class
	 * @covers \Remove_Noreferrer\Base\Plugin::format_action
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_construct_did_remove_noreferrer_base_demo_plugin_loaded_action() {
		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_base_demo_plugin_loaded' ) );
	}

	/**
	 * @covers \Remove_Noreferrer\Base\Plugin::init
	 * @covers \Remove_Noreferrer\Base\Plugin::stringify_called_class
	 * @covers \Remove_Noreferrer\Base\Plugin::format_action
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_did_remove_noreferrer_base_demo_plugin_initialized_action() {
		do_action( 'init' );

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_base_demo_plugin_initialized' ) );
	}
}

// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound
class Demo_Plugin extends Plugin {}
// phpcs:enable Generic.Files.OneObjectStructurePerFile.MultipleFound

