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
	 * Demo_Plugin instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Demo_Plugin $plugin
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
		$this->assertInstanceOf( '\Remove_Noreferrer\Base\Plugin', $this->plugin );
	}

	/**
	 * @covers ::__construct
	 * @covers ::stringify_called_class
	 * @covers ::format_action
	 * @covers ::do_action
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_construct_did_remove_noreferrer_base_demo_plugin_loaded_action() {
		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_base_demo_plugin_loaded' ) );
	}
}

// phpcs:disable Generic.Files.OneObjectStructurePerFile.MultipleFound
class Demo_Plugin extends Plugin {}
// phpcs:enable Generic.Files.OneObjectStructurePerFile.MultipleFound

