<?php
declare(strict_types=1);

namespace Remove_Noreferrer\Core;

/**
 * @coversDefaultClass \Remove_Noreferrer\Core\Plugin
 */
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
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->_plugin = new Plugin( new Options() );
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
		parent::tearDown();

		unset( $GLOBALS['screen'] );
		unset( $GLOBALS['current_screen'] );
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
	 * @covers \Remove_Noreferrer\Core\Plugin::__construct
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
	 * @covers \Remove_Noreferrer\Core\Plugin::__construct
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_construct_did_remove_noreferrer_core_plugin_loaded_action(): void {
		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_core_plugin_loaded' ) );
	}

	/**
	 * @covers \Remove_Noreferrer\Core\Plugin::init
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_did_remove_noreferrer_core_plugin_initialized_action(): void {
		do_action( 'init' );

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_core_plugin_initialized' ) );
	}

	/**
	 * @covers ::activate
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_activate_did_remove_noreferrer_core_plugin_activated_action_if_administrator(): void {
		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );

		wp_set_current_user( $admin_user );

		$this->_plugin->activate();

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_core_plugin_activated' ) );

		wp_delete_user( $admin_user );
	}

	/**
	 * @covers ::activate
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_activate_did_not_remove_noreferrer_core_plugin_activated_action_if_non_administrator(): void {
		$editor_user = self::factory()->user->create( array( 'role' => 'editor' ) );

		wp_set_current_user( $editor_user );

		$this->_plugin->activate();

		$this->assertEquals( 0, did_action( 'remove_noreferrer_core_plugin_activated' ) );

		wp_delete_user( $editor_user );
	}

	/**
	 * @covers ::deactivate
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_deactivate_did_remove_noreferrer_core_plugin_deactivated_action_if_administrator(): void {
		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );

		wp_set_current_user( $admin_user );

		$this->_plugin->deactivate();

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_core_plugin_deactivated' ) );

		wp_delete_user( $admin_user );
	}

	/**
	 * @covers ::deactivate
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_deactivate_did_not_remove_noreferrer_core_plugin_deactivated_action_if_non_administrator(): void {
		$editor_user = self::factory()->user->create( array( 'role' => 'editor' ) );

		wp_set_current_user( $editor_user );

		$this->_plugin->deactivate();

		$this->assertEquals( 0, did_action( 'remove_noreferrer_core_plugin_deactivated' ) );

		wp_delete_user( $editor_user );
	}

	/**
	 * @covers ::uninstall
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_uninstall_did_remove_noreferrer_core_plugin_uninstalled_action_if_administrator(): void {
		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );

		wp_set_current_user( $admin_user );

		$this->_plugin->uninstall();

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_core_plugin_uninstalled' ) );

		wp_delete_user( $admin_user );
	}

	/**
	 * @covers ::uninstall
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_uninstall_did_not_remove_noreferrer_core_plugin_uninstalled_action_if_non_administrator(): void {
		$editor_user = self::factory()->user->create( array( 'role' => 'editor' ) );

		wp_set_current_user( $editor_user );

		$this->_plugin->uninstall();

		$this->assertEquals( 0, did_action( 'remove_noreferrer_core_plugin_uninstalled' ) );

		wp_delete_user( $editor_user );
	}
}

