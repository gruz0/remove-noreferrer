<?php
namespace Remove_Noreferrer\Base;

/**
 * Test base/class-plugin.php
 *
 * @coversDefaultClass Remove_Noreferrer\Base\Plugin
 * @group base
 */
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
	 * @covers ::__construct
	 * @covers ::format_action
	 * @covers ::stringify_called_class
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
	 * @covers ::__construct
	 * @covers ::stringify_called_class
	 * @covers ::format_action
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
	 * @covers ::init
	 * @covers ::stringify_called_class
	 * @covers ::format_action
	 * @covers ::__construct
	 * @covers Remove_Noreferrer\Frontend\Plugin::init
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
class Demo_Plugin extends \Remove_Noreferrer\Base\Plugin {}
// phpcs:enable Generic.Files.OneObjectStructurePerFile.MultipleFound

