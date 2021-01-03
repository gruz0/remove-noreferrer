<?php
/**
 * Unit tests covering Options_Migrator functionality.
 *
 * @package Remove_Noreferrer
 * @subpackage Admin
 * @since 2.0.0
 */

namespace Remove_Noreferrer\Admin;

/**
 * Test admin/class-options-migrator.php
 *
 * @coversDefaultClass Remove_Noreferrer\Admin\Options_Migrator
 * @group admin
 */
class Options_Migrator_Test extends \WP_UnitTestCase {
	/**
	 * Options_Migrator instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Options_Migrator $options_migrator
	 */
	private $options_migrator;

	/**
	 * \Remove_Noreferrer\Core\Options stubbed instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var \Remove_Noreferrer\Core\Options $stubbed_options
	 */
	private $stubbed_options;

	/**
	 * Prepares environment
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->stubbed_options = $this
			->getMockBuilder( \Remove_Noreferrer\Core\Options::class )
			->setMethodsExcept( array( 'update_options', 'get_default_options' ) )
			->getMock();

		$this->options_migrator = new Options_Migrator( $this->stubbed_options );
	}

	/**
	 * @covers ::__construct
	 * @covers ::call
	 * @covers ::add_default_options
	 * @covers ::are_options_exist
	 * @covers ::get_options
	 * @covers ::update_options
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_call_did_remove_noreferrer_default_options_added_action_if_options_are_not_exist() {
		$this->options_migrator->call( null );

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_default_options_added' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::call
	 * @covers ::add_default_options
	 * @covers ::are_options_exist
	 * @covers ::get_options
	 * @covers ::update_options
	 * @covers \Remove_Noreferrer\Core\Options::get_default_options
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_call_adds_default_options_if_options_are_not_exist() {
		$this->stubbed_options->method( 'get_options' )->willReturn( $this->stubbed_options->get_default_options() );

		$this->options_migrator->call( null );

		$expected = array(
			GRN_PLUGIN_VERSION_KEY               => GRN_VERSION,
			GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array(),
			GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '0',
		);

		$this->assertEquals( $expected, get_option( GRN_OPTION_KEY ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::call
	 * @covers ::add_default_options
	 * @covers ::are_options_exist
	 * @covers ::get_options
	 * @covers ::update_options
	 * @covers \Remove_Noreferrer\Core\Options::get_default_options
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_call_did_not_remove_noreferrer_options_migrated_action_if_options_are_not_found() {
		$this->options_migrator->call( null );

		$this->assertEquals( 0, did_action( 'remove_noreferrer_options_migrated' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::call
	 * @covers ::add_default_options
	 * @covers ::are_options_exist
	 * @covers ::get_options
	 * @covers ::update_options
	 * @covers ::get_current_version
	 * @covers ::is_current_version_higher_or_equal_than_new_version
	 * @covers ::set_current_version
	 * @covers \Remove_Noreferrer\Core\Options::get_default_options
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_call_did_remove_noreferrer_current_version_is_equal_to_new_version_action_if_previous_version_is_equal_to_new_version() {
		add_option( GRN_OPTION_KEY, array( GRN_PLUGIN_VERSION_KEY => GRN_VERSION ) );

		$this->stubbed_options->method( 'get_options' )->willReturn( array( GRN_PLUGIN_VERSION_KEY => GRN_VERSION ) );

		$this->options_migrator->call( GRN_VERSION );

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_current_version_is_higher_or_equal_than_new_version' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::call
	 * @covers ::add_default_options
	 * @covers ::are_options_exist
	 * @covers ::get_options
	 * @covers ::update_options
	 * @covers ::get_current_version
	 * @covers ::is_current_version_higher_or_equal_than_new_version
	 * @covers ::set_current_version
	 * @covers \Remove_Noreferrer\Core\Options::get_default_options
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_call_did_not_remove_noreferrer_options_migrated_action_if_previous_version_is_equal_to_new_version() {
		add_option( GRN_OPTION_KEY, array( GRN_PLUGIN_VERSION_KEY => GRN_VERSION ) );

		$this->stubbed_options->method( 'get_options' )->willReturn( array( GRN_PLUGIN_VERSION_KEY => GRN_VERSION ) );

		$this->options_migrator->call( GRN_VERSION );

		$this->assertEquals( 0, did_action( 'remove_noreferrer_options_migrated' ) );

	}

	/**
	 * @covers ::__construct
	 * @covers ::call
	 * @covers ::add_default_options
	 * @covers ::are_options_exist
	 * @covers ::get_options
	 * @covers ::update_options
	 * @covers ::get_current_version
	 * @covers ::is_current_version_higher_or_equal_than_new_version
	 * @covers ::set_current_version
	 * @covers \Remove_Noreferrer\Core\Options::get_default_options
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_call_did_remove_noreferrer_current_version_is_higher_or_equal_than_new_version_action_if_previous_version_is_higher_than_new_version() {
		add_option( GRN_OPTION_KEY, array( GRN_PLUGIN_VERSION_KEY => '999.9.9' ) );

		$this->stubbed_options->method( 'get_options' )->willReturn( array( GRN_PLUGIN_VERSION_KEY => '999.9.9' ) );

		$this->options_migrator->call( GRN_VERSION );

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_current_version_is_higher_or_equal_than_new_version' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::call
	 * @covers ::add_default_options
	 * @covers ::are_options_exist
	 * @covers ::get_options
	 * @covers ::update_options
	 * @covers ::get_current_version
	 * @covers ::is_current_version_higher_or_equal_than_new_version
	 * @covers ::set_current_version
	 * @covers \Remove_Noreferrer\Core\Options::get_default_options
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_call_did_not_remove_noreferrer_options_migrated_action_if_previous_version_is_higher_than_new_version() {
		add_option( GRN_OPTION_KEY, array( GRN_PLUGIN_VERSION_KEY => '999.9.9' ) );

		$this->stubbed_options->method( 'get_options' )->willReturn( array( GRN_PLUGIN_VERSION_KEY => '999.9.9' ) );

		$this->options_migrator->call( GRN_VERSION );

		$this->assertEquals( 0, did_action( 'remove_noreferrer_options_migrated' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::call
	 * @covers ::add_default_options
	 * @covers ::are_options_exist
	 * @covers ::get_options
	 * @covers ::update_options
	 * @covers ::get_current_version
	 * @covers ::is_current_version_higher_or_equal_than_new_version
	 * @covers ::set_current_version
	 * @covers ::get_default_options
	 * @covers ::is_current_version_updateable_to
	 * @covers ::migrate_to_2_0_0
	 * @covers ::remove_extra_keys
	 * @covers ::mark_migration_applied
	 * @covers \Remove_Noreferrer\Core\Options::get_default_options
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_call_did_remove_noreferrer_options_migrated_action_if_previous_version_is_not_set() {
		add_option( GRN_OPTION_KEY, array( GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '1' ) );

		$this->stubbed_options->method( 'get_options' )->willReturn( array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array() ) );

		$this->options_migrator->call( GRN_VERSION );

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_options_migrated' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::call
	 * @covers ::add_default_options
	 * @covers ::are_options_exist
	 * @covers ::get_options
	 * @covers ::update_options
	 * @covers ::get_current_version
	 * @covers ::is_current_version_higher_or_equal_than_new_version
	 * @covers ::set_current_version
	 * @covers ::get_default_options
	 * @covers ::is_current_version_updateable_to
	 * @covers ::migrate_to_2_0_0
	 * @covers ::remove_extra_keys
	 * @covers ::mark_migration_applied
	 * @covers \Remove_Noreferrer\Core\Options::get_default_options
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_call_did_remove_noreferrer_options_migrated_action_if_previous_version_is_empty() {
		add_option( GRN_OPTION_KEY, array( GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '1' ) );

		$this->stubbed_options->method( 'get_options' )->willReturn( array( GRN_PLUGIN_VERSION_KEY => '' ) );

		$this->options_migrator->call( GRN_VERSION );

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_options_migrated' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::call
	 * @covers ::add_default_options
	 * @covers ::are_options_exist
	 * @covers ::get_options
	 * @covers ::update_options
	 * @covers ::get_current_version
	 * @covers ::is_current_version_higher_or_equal_than_new_version
	 * @covers ::set_current_version
	 * @covers ::get_default_options
	 * @covers ::is_current_version_updateable_to
	 * @covers ::migrate_to_2_0_0
	 * @covers ::remove_extra_keys
	 * @covers ::mark_migration_applied
	 * @covers \Remove_Noreferrer\Core\Options::get_default_options
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_call_did_remove_noreferrer_options_migrated_action_if_previous_version_is_lower_than_new_version() {
		add_option( GRN_OPTION_KEY, array( GRN_PLUGIN_VERSION_KEY => '1.2.0' ) );

		$this->stubbed_options->method( 'get_options' )->willReturn( array( GRN_PLUGIN_VERSION_KEY => '1.2.0' ) );

		$this->options_migrator->call( GRN_VERSION );

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_options_migrated' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::call
	 * @covers ::add_default_options
	 * @covers ::are_options_exist
	 * @covers ::get_options
	 * @covers ::update_options
	 * @covers ::get_current_version
	 * @covers ::is_current_version_higher_or_equal_than_new_version
	 * @covers ::set_current_version
	 * @covers ::get_default_options
	 * @covers ::is_current_version_updateable_to
	 * @covers ::migrate_to_2_0_0
	 * @covers ::remove_extra_keys
	 * @covers ::mark_migration_applied
	 * @covers \Remove_Noreferrer\Core\Options::get_default_options
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_call_did_remove_noreferrer_options_updated_action_if_previous_version_is_lower_than_new_version() {
		add_option( GRN_OPTION_KEY, array( GRN_PLUGIN_VERSION_KEY => '1.2.0' ) );

		$this->stubbed_options->method( 'get_options' )->willReturn( array( GRN_PLUGIN_VERSION_KEY => '1.2.0' ) );

		$this->options_migrator->call( GRN_VERSION );

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_options_updated' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::call
	 * @covers ::add_default_options
	 * @covers ::are_options_exist
	 * @covers ::get_options
	 * @covers ::update_options
	 * @covers ::get_current_version
	 * @covers ::is_current_version_higher_or_equal_than_new_version
	 * @covers ::set_current_version
	 * @covers ::get_default_options
	 * @covers ::is_current_version_updateable_to
	 * @covers ::migrate_to_2_0_0
	 * @covers ::remove_extra_keys
	 * @covers ::mark_migration_applied
	 * @covers \Remove_Noreferrer\Core\Options::get_default_options
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_call_removes_extra_options_after_migrations_applied() {
		$options = array(
			GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'post' ),
			'first'                              => 'value',
			GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '1',
			'second'                             => false,
		);

		add_option( GRN_OPTION_KEY, $options );

		$this->stubbed_options->method( 'get_options' )->willReturn( $options );

		$this->options_migrator->call( GRN_VERSION );

		$expected = array(
			GRN_PLUGIN_VERSION_KEY               => GRN_VERSION,
			GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'post' ),
			GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '1',
		);

		$this->assertEquals( $expected, get_option( GRN_OPTION_KEY ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::call
	 * @covers ::add_default_options
	 * @covers ::are_options_exist
	 * @covers ::get_options
	 * @covers ::update_options
	 * @covers ::get_current_version
	 * @covers ::is_current_version_higher_or_equal_than_new_version
	 * @covers ::set_current_version
	 * @covers ::get_default_options
	 * @covers ::is_current_version_updateable_to
	 * @covers ::migrate_to_2_0_0
	 * @covers ::remove_extra_keys
	 * @covers ::mark_migration_applied
	 * @covers \Remove_Noreferrer\Core\Options::get_default_options
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_call_did_remove_noreferrer_options_migrated_to_2_0_0_action() {
		add_option( GRN_OPTION_KEY, array( GRN_PLUGIN_VERSION_KEY => '1.2.0' ) );

		$this->stubbed_options->method( 'get_options' )->willReturn( array( GRN_PLUGIN_VERSION_KEY => '1.2.0' ) );

		$this->options_migrator->call( GRN_VERSION );

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_options_migrated_to_2.0.0' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::call
	 * @covers ::add_default_options
	 * @covers ::are_options_exist
	 * @covers ::get_options
	 * @covers ::update_options
	 * @covers ::get_current_version
	 * @covers ::is_current_version_higher_or_equal_than_new_version
	 * @covers ::set_current_version
	 * @covers ::get_default_options
	 * @covers ::is_current_version_updateable_to
	 * @covers ::migrate_to_2_0_0
	 * @covers ::remove_extra_keys
	 * @covers ::mark_migration_applied
	 * @covers \Remove_Noreferrer\Core\Options::get_default_options
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_call_sets_plugin_version_to_the_latest() {
		add_option( GRN_OPTION_KEY, array( GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '1' ) );

		$this->options_migrator->call( GRN_VERSION );

		$new_options = get_option( GRN_OPTION_KEY );

		// NOTE: This test must be updated every time on releasing a new version of the plugin.
		$this->assertEquals( '2.0.0', $new_options[ GRN_PLUGIN_VERSION_KEY ] );
	}
}

