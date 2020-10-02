<?php
/**
 * Unit tests covering Plugin functionality.
 *
 * @package Remove_Noreferrer
 * @subpackage Admin
 * @since 2.0.0
 */

namespace Remove_Noreferrer\Admin;

/**
 * Test admin/class-plugin.php
 *
 * @coversDefaultClass \Remove_Noreferrer\Admin\Plugin
 * @covers \Remove_Noreferrer\Admin\Plugin::__construct
 * @group admin
 *
 * @uses \Remove_Noreferrer\Base\Plugin
 */
class Plugin_Test extends \WP_UnitTestCase {
	/**
	 * Remove_Noreferrer\Admin\Plugin instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Remove_Noreferrer\Admin\Plugin $plugin
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

		$this->plugin = new Plugin( new \Remove_Noreferrer\Core\Options() );
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
		$this->assertInstanceOf( 'Remove_Noreferrer\Base\Plugin', $this->plugin );
	}

	/**
	 * @coversNothing
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_grn_parent_slug_constant() {
		$this->assertSame( 'options-general.php', $this->plugin::GRN_PARENT_SLUG );
	}

	/**
	 * @coversNothing
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_grn_menu_slug_constant() {
		$this->assertSame( 'remove_noreferrer', $this->plugin::GRN_MENU_SLUG );
	}

	/**
	 * @coversNothing
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_grn_nonce_value_constant() {
		$this->assertSame( 'gruz0_remove_noreferrer_nonce', $this->plugin::GRN_NONCE_VALUE );
	}

	/**
	 * @coversNothing
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_grn_nonce_action_constant() {
		$this->assertSame( 'remove_noreferrer', $this->plugin::GRN_NONCE_ACTION );
	}

	/**
	 * @covers ::__construct
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
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_construct_did_remove_noreferrer_admin_plugin_loaded_action() {
		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_admin_plugin_loaded' ) );
	}

	/**
	 * @covers ::init
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_has_admin_menu_action() {
		$this->plugin->init();

		$this->assertEquals( 10, has_action( 'admin_menu', array( $this->plugin, 'add_menu' ) ) );
	}

	/**
	 * @covers ::init
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_has_admin_post_remove_noreferrer_update_options_action() {
		$this->plugin->init();

		$this->assertEquals(
			10,
			has_action(
				'admin_post_remove_noreferrer_update_options',
				array( $this->plugin, 'update_options' )
			)
		);
	}

	/**
	 * @covers ::init
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_did_remove_noreferrer_admin_plugin_initialized_action() {
		$this->plugin->init();

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_admin_plugin_initialized' ) );
	}

	/**
	 * @covers ::add_menu
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_plugin_renders_submenu_for_manage_options_capability() {
		global $submenu;
		global $menu;

		$submenu = array();
		$menu    = array();

		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );

		wp_set_current_user( $admin_user );
		set_current_screen( 'dashboard' );

		$this->plugin->add_menu();

		list( $menu_title, $capability, $menu_slug, $page_title ) = $submenu[ $this->plugin::GRN_PARENT_SLUG ][0];

		$this->assertEquals( 'Remove Noreferrer', $menu_title );
		$this->assertEquals( 'manage_options', $capability );
		$this->assertEquals( 'remove_noreferrer', $menu_slug );
		$this->assertEquals( 'Remove Noreferrer Options', $page_title );

		wp_delete_user( $admin_user );
	}

	/**
	 * @covers ::add_menu
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_plugin_does_not_render_submenu_for_user_without_manage_options_capability() {
		global $submenu;
		global $menu;

		$submenu = array();
		$menu    = array();

		$editor_user = self::factory()->user->create( array( 'role' => 'editor' ) );

		wp_set_current_user( $editor_user );
		set_current_screen( 'dashboard' );

		$this->plugin->add_menu();

		$this->assertEmpty( $submenu );

		wp_delete_user( $editor_user );
	}

	/**
	 * @covers ::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_update_options_throws_exception_when_user_does_not_have_manage_options_capability() {
		$editor_user = self::factory()->user->create( array( 'role' => 'editor' ) );

		wp_set_current_user( $editor_user );
		set_current_screen( 'dashboard' );

		$this->setExpectedException( '\WPDieException', 'Unauthorized user' );
		$this->plugin->update_options();

		wp_delete_user( $editor_user );
	}

	/**
	 * @covers ::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_update_options_throws_exception_when_nonce_is_not_set() {
		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );

		wp_set_current_user( $admin_user );
		set_current_screen( 'dashboard' );

		$this->setExpectedException( '\WPDieException', 'Nonce must be set' );
		$this->plugin->update_options();

		wp_delete_user( $admin_user );
	}

	/**
	 * @covers ::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_update_options_throws_exception_when_nonce_is_invalid() {
		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );

		wp_set_current_user( $admin_user );
		set_current_screen( 'dashboard' );

		$_POST[ Plugin::GRN_NONCE_VALUE ] = 'invalid';

		$this->setExpectedException( '\WPDieException', 'Invalid nonce' );
		$this->plugin->update_options();

		wp_delete_user( $admin_user );
	}
}

