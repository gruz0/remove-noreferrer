<?php
/**
 * Unit tests covering Options functionality.
 *
 * @package Remove_Noreferrer
 * @subpackage Core
 * @since 2.0.0
 */

namespace Remove_Noreferrer\Core;

/**
 * Test core/class-options.php
 *
 * @coversDefaultClass Remove_Noreferrer\Core\Options
 * @group core
 */
class Options_Test extends \WP_UnitTestCase {
	/**
	 * Remove_Noreferrer\Core\Options instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Remove_Noreferrer\Core\Options $options
	 */
	private $options;

	/**
	 * Prepares environment
	 *
	 * @return void
	 */
	public function setUp() {
		parent::setUp();

		$this->options = new Options();
	}

	/**
	 * @covers ::get_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_options_returns_empty_array_if_options_are_not_exist() {
		$this->assertEquals( array(), $this->options->get_options() );
	}

	/**
	 * @covers ::get_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_options_returns_existed_options() {
		$options = array(
			GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array(),
			GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '0',
		);

		add_option( GRN_OPTION_KEY, $options );

		$this->assertEquals( $options, $this->options->get_options() );
	}

	/**
	 * @covers ::get_options
	 * @covers ::get_option
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_option_executes_get_options_if_options_are_empty() {
		$this->options->get_option( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY );

		$this->assertEquals( array(), $this->options->get_options() );
	}

	/**
	 * @covers ::get_option
	 * @covers ::get_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_option_throws_invalid_argument_exception_if_key_is_not_exists() {
		$this->setExpectedException( '\InvalidArgumentException', 'Key some_key does not exist' );

		$this->options->get_option( 'some_key' );
	}

	/**
	 * @covers ::get_option
	 * @covers ::get_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_option_returns_valid_option() {
		add_option( GRN_OPTION_KEY, array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'page' ) ) );

		$this->assertEquals( array( 'page' ), $this->options->get_option( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ) );
	}

	/**
	 * @covers ::delete_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_delete_options_did_remove_noreferrer_options_deleted_action() {
		$this->options->delete_options();

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_options_deleted' ) );
	}

	/**
	 * @covers ::delete_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_delete_options_deletes_existed_options() {
		add_option( GRN_OPTION_KEY, array( GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '1' ) );

		$this->options->delete_options();

		$this->assertEquals( false, get_option( GRN_OPTION_KEY ) );
	}

	/**
	 * @covers ::migrate_options
	 * @covers ::get_options
	 * @covers ::add_default_options
	 * @covers ::get_default_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_migrate_options_did_remove_noreferrer_options_created_action_if_options_are_not_exist() {
		$this->options->migrate_options();

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_options_created' ) );
	}

	/**
	 * @covers ::migrate_options
	 * @covers ::get_options
	 * @covers ::add_default_options
	 * @covers ::get_default_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_migrate_options_did_not_remove_noreferrer_options_migrated_action_if_options_are_not_exist() {
		$this->options->migrate_options();

		$this->assertEquals( 0, did_action( 'remove_noreferrer_options_migrated' ) );
	}

	/**
	 * @covers ::migrate_options
	 * @covers ::get_options
	 * @covers ::add_default_options
	 * @covers ::get_default_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_migrate_options_creates_default_options_if_options_are_not_exist() {
		$this->options->migrate_options();

		$expected = array(
			GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array(),
			GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '0',
		);

		$this->assertEquals( $expected, get_option( GRN_OPTION_KEY ) );
	}

	/**
	 * @covers ::migrate_options
	 * @covers ::get_options
	 * @covers ::migrate
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_migrate_options_did_remove_noreferrer_options_migrated_action_if_has_new_options() {
		add_option( GRN_OPTION_KEY, array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'page' ) ) );

		$this->options->migrate_options();

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_options_migrated' ) );
	}

	/**
	 * @covers ::migrate_options
	 * @covers ::get_options
	 * @covers ::migrate
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_migrate_options_adds_new_options() {
		add_option( GRN_OPTION_KEY, array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'page' ) ) );

		$this->options->migrate_options();

		$expected = array(
			GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'page' ),
			GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '0',
		);

		$this->assertEquals( $expected, get_option( GRN_OPTION_KEY ) );
	}
}

