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
	 * Options instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Options $options
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
	 * @covers ::get_default_options
	 * @covers ::set_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_options_returns_default_options_if_options_are_not_exist() {
		delete_option( GRN_OPTION_KEY );

		$options = array(
			GRN_PLUGIN_VERSION_KEY               => GRN_VERSION,
			GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array(),
			GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '0',
		);

		$this->assertEquals( $options, $this->options->get_options() );
	}

	/**
	 * @covers ::get_options
	 * @covers ::get_default_options
	 * @covers ::set_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_options_returns_existed_options() {
		$options = array(
			GRN_PLUGIN_VERSION_KEY               => '999.9.9',
			GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'some' => 'value' ),
			GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '1',
		);

		update_option( GRN_OPTION_KEY, $options );

		$this->assertEquals( $options, $this->options->get_options() );
	}

	/**
	 * @covers ::get_option
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_option_throws_invalid_argument_exception_if_key_is_not_allowed() {
		$this->setExpectedException( '\InvalidArgumentException', 'Key some_key does not exist' );

		$this->options->get_option( 'some_key' );
	}

	/**
	 * @covers ::get_option
	 * @covers ::get_default_options
	 * @covers ::set_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_option_returns_valid_option() {
		update_option( GRN_OPTION_KEY, array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'page' ) ) );

		$this->assertEquals( array( 'page' ), $this->options->get_option( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ) );
	}

	/**
	 * @covers ::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_update_options_did_remove_noreferrer_options_updated_action() {
		$options = array( GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '1' );

		$this->options->update_options( $options );

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_options_updated' ) );
	}

	/**
	 * @covers ::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_update_options_updates_options() {
		$options = array( GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '1' );

		$this->options->update_options( $options );

		$this->assertEquals( $options, get_option( GRN_OPTION_KEY ) );
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
}

