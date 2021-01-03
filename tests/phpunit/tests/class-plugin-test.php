<?php
/**
 * Unit tests covering Plugin functionality.
 *
 * @package Remove_Noreferrer
 * @since 2.0.0
 */

namespace Remove_Noreferrer;

/**
 * Test class-plugin.php
 *
 * @coversDefaultClass Remove_Noreferrer\Plugin
 *
 * @uses \Remove_Noreferrer\Base\Plugin
 */
class Plugin_Test extends \WP_UnitTestCase {
	/**
	 * Plugin instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Plugin $plugin
	 */
	private $plugin;

	/**
	 * Core\Options stubbed instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Core\Options $stubbed_options
	 */
	private $stubbed_options;

	/**
	 * Core\Adapter stubbed instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Core\Adapter $stubbed_adapter
	 */
	private $stubbed_adapter;

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

		$this->stubbed_options = $this
			->getMockBuilder( Core\Options::class )
			->setMethodsExcept( array( 'get_default_options' ) )
			->getMock();

		$this->stubbed_adapter = $this->createMock( Core\Adapter::class );

		$this->plugin = new Plugin( $this->stubbed_options, $this->stubbed_adapter );
	}

	/**
	 * Finishes tests
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function tearDown() {
		parent::tearDown();

		unset( $GLOBALS['screen'] );
		unset( $GLOBALS['current_screen'] );
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
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_did_remove_noreferrer_plugin_loaded_action() {
		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_plugin_loaded' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::run
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::__construct
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::add_default_options
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::are_options_exist
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::call
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::get_options
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::update_options
	 * @covers \Remove_Noreferrer\Admin\Plugin::__construct
	 * @covers \Remove_Noreferrer\Admin\Plugin::add_hooks
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_run_returns_admin_plugin_on_admin_part() {
		$this->stubbed_adapter->method( 'is_admin' )->willReturn( true );

		$this->assertInstanceOf( '\Remove_Noreferrer\Admin\Plugin', $this->plugin->run() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::run
	 * @covers \Remove_Noreferrer\Frontend\Plugin::__construct
	 * @covers \Remove_Noreferrer\Frontend\Plugin::add_hooks
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_run_returns_frontend_plugin_on_frontend_part() {
		$this->assertInstanceOf( '\Remove_Noreferrer\Frontend\Plugin', $this->plugin->run() );
	}

	/**
	 * @covers ::__construct
	 * @covers ::run
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::__construct
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::add_default_options
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::are_options_exist
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::call
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::get_options
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::update_options
	 * @covers \Remove_Noreferrer\Admin\Plugin::__construct
	 * @covers \Remove_Noreferrer\Admin\Plugin::add_hooks
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_run_did_remove_noreferrer_default_options_added_action_if_options_are_not_found() {
		$this->stubbed_adapter->method( 'is_admin' )->willReturn( true );

		delete_option( GRN_OPTION_KEY );

		$this->plugin->run();

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_default_options_added' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::run
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::__construct
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::are_options_exist
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::call
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::get_current_version
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::get_default_options
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::get_options
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::is_current_version_higher_or_equal_than_new_version
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::is_current_version_updateable_to
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::migrate_to_2_0_0
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::remove_extra_keys
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::set_current_version
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::update_options
	 * @covers \Remove_Noreferrer\Admin\Options_Migrator::mark_migration_applied
	 * @covers \Remove_Noreferrer\Admin\Plugin::__construct
	 * @covers \Remove_Noreferrer\Admin\Plugin::add_hooks
	 * @covers \Remove_Noreferrer\Core\Options::get_default_options
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_run_did_remove_noreferrer_options_migrated_action_on_admin_area_if_migrations_needed() {
		$this->stubbed_adapter->method( 'is_admin' )->willReturn( true );

		add_option( GRN_OPTION_KEY, array( GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '1' ) );

		$this->plugin->run();

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_options_migrated' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::run
	 * @covers \Remove_Noreferrer\Frontend\Plugin::__construct
	 * @covers \Remove_Noreferrer\Frontend\Plugin::add_hooks
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_run_did_not_remove_noreferrer_options_migration_started_action_on_frontend_area() {
		$this->plugin->run();

		$this->assertEquals( 0, did_action( 'remove_noreferrer_options_migration_started' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::run
	 * @covers \Remove_Noreferrer\Frontend\Plugin::__construct
	 * @covers \Remove_Noreferrer\Frontend\Plugin::add_hooks
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_run_did_not_remove_noreferrer_options_migrated_action_on_frontend_area() {
		$this->plugin->run();

		$this->assertEquals( 0, did_action( 'remove_noreferrer_options_migrated' ) );
	}
}

