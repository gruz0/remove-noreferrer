<?php
declare(strict_types=1);

namespace Remove_Noreferrer\Admin;

/**
 * @coversDefaultClass \Remove_Noreferrer\Admin\Plugin
 */
class Plugin_Test extends \WP_UnitTestCase {
	/**
	 * Remove_Noreferrer\Admin\Plugin instance
	 *
	 * @since 1.3.0
	 * @access private
	 * @var Remove_Noreferrer\Admin\Plugin $_plugin
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

		$this->_plugin = new Plugin( new \Remove_Noreferrer\Core\Options() );
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
	 * @coversNothing
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_grn_parent_slug_constant(): void {
		$this->assertSame( 'options-general.php', $this->_plugin::GRN_PARENT_SLUG );
	}

	/**
	 * @coversNothing
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_grn_menu_slug_constant(): void {
		$this->assertSame( 'remove_noreferrer', $this->_plugin::GRN_MENU_SLUG );
	}

	/**
	 * @coversNothing
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_grn_nonce_value_constant(): void {
		$this->assertSame( 'gruz0_remove_noreferrer_nonce', $this->_plugin::GRN_NONCE_VALUE );
	}

	/**
	 * @coversNothing
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_grn_nonce_action_constant(): void {
		$this->assertSame( 'remove_noreferrer', $this->_plugin::GRN_NONCE_ACTION );
	}

	/**
	 * @covers \Remove_Noreferrer\Admin\Plugin::__construct
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
	 * @covers \Remove_Noreferrer\Admin\Plugin::__construct
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_construct_did_remove_noreferrer_admin_plugin_loaded_action(): void {
		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_admin_plugin_loaded' ) );
	}

	/**
	 * @covers \Remove_Noreferrer\Admin\Plugin::init
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_has_admin_menu_action() {
		do_action( 'init' );

		$this->assertEquals( 10, has_action( 'admin_menu', array( $this->_plugin, 'add_menu' ) ) );
	}

	/**
	 * @covers \Remove_Noreferrer\Admin\Plugin::init
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_has_admin_post_remove_noreferrer_update_options_action() {
		do_action( 'init' );

		$this->assertEquals(
			10,
			has_action(
				'admin_post_remove_noreferrer_update_options',
				array( $this->_plugin, 'update_options' )
			)
		);
	}

	/**
	 * @covers \Remove_Noreferrer\Admin\Plugin::init
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_did_remove_noreferrer_admin_plugin_initialized_action(): void {
		do_action( 'init' );

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_admin_plugin_initialized' ) );
	}

	/**
	 * @covers ::add_menu
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_plugin_renders_submenu_for_manage_options_capability(): void {
		global $submenu;
		global $menu;

		$submenu = array();
		$menu    = array();

		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );

		wp_set_current_user( $admin_user );
		set_current_screen( 'dashboard' );

		$this->_plugin->add_menu();

		list( $menu_title, $capability, $menu_slug, $page_title ) = $submenu[ $this->_plugin::GRN_PARENT_SLUG ][0];

		$this->assertEquals( 'Remove Noreferrer', $menu_title );
		$this->assertEquals( 'manage_options', $capability );
		$this->assertEquals( 'remove_noreferrer', $menu_slug );
		$this->assertEquals( 'Remove Noreferrer Options', $page_title );

		wp_delete_user( $admin_user );
	}

	/**
	 * @covers ::add_menu
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_plugin_does_not_render_submenu_for_user_without_manage_options_capability(): void {
		global $submenu;
		global $menu;

		$submenu = array();
		$menu    = array();

		$current_user = get_current_user_id();
		$editor_user  = self::factory()->user->create( array( 'role' => 'editor' ) );

		wp_set_current_user( $editor_user );
		set_current_screen( 'dashboard' );

		$this->_plugin->add_menu();

		$this->assertEmpty( $submenu );

		wp_delete_user( $editor_user );
	}
}
