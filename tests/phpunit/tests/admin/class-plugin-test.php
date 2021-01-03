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
 * @coversDefaultClass Remove_Noreferrer\Admin\Plugin
 * @group admin
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
	 * \Remove_Noreferrer\Core\Adapter stubbed instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var \Remove_Noreferrer\Core\Adapter $stubbed_adapter
	 */
	private $stubbed_adapter;

	/**
	 * Options_Page stubbed instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Options_Page $stubbed_options_page
	 */
	private $stubbed_options_page;

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

		$options                    = new \Remove_Noreferrer\Core\Options();
		$this->stubbed_adapter      = $this->createMock( \Remove_Noreferrer\Core\Adapter::class );
		$this->stubbed_options_page = $this->createMock( Options_Page::class );

		$this->plugin = new Plugin( $options, $this->stubbed_adapter, $this->stubbed_options_page );
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
	 * @coversNothing
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_grn_parent_slug_constant() {
		$this->assertSame( 'options-general.php', Plugin::GRN_PARENT_SLUG );
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
		$this->assertSame( 'remove_noreferrer', Plugin::GRN_MENU_SLUG );
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
		$this->assertSame( 'gruz0_remove_noreferrer_nonce', Plugin::GRN_NONCE_VALUE );
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
		$this->assertSame( 'remove_noreferrer', Plugin::GRN_NONCE_ACTION );
	}

	/**
	 * @covers ::__construct
	 * @covers ::add_hooks
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_did_remove_noreferrer_admin_plugin_loaded_action() {
		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_admin_plugin_loaded' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::add_hooks
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_did_remove_noreferrer_admin_plugin_hooks_added_action() {
		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_admin_plugin_hooks_added' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::add_hooks
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_admin_menu_action() {
		$this->assertEquals( 10, has_action( 'admin_menu', array( $this->plugin, 'add_menu' ) ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::add_hooks
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_admin_post_remove_noreferrer_update_options_action() {
		$this->assertEquals(
			10,
			has_action(
				'admin_post_remove_noreferrer_update_options',
				array( $this->plugin, 'update_options' )
			)
		);
	}

	/**
	 * @covers ::__construct
	 * @covers ::add_menu
	 * @covers ::add_hooks
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

		list( $menu_title, $capability, $menu_slug, $page_title ) = $submenu[ Plugin::GRN_PARENT_SLUG ][0];

		$this->assertEquals( 'Remove Noreferrer', $menu_title );
		$this->assertEquals( 'manage_options', $capability );
		$this->assertEquals( 'remove_noreferrer', $menu_slug );
		$this->assertEquals( 'Remove Noreferrer Options', $page_title );

		wp_delete_user( $admin_user );
	}

	/**
	 * @covers ::__construct
	 * @covers ::add_menu
	 * @covers ::add_hooks
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
	 * @covers ::__construct
	 * @covers ::update_options
	 * @covers ::add_hooks
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
	 * @covers ::__construct
	 * @covers ::update_options
	 * @covers ::add_hooks
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
	 * @covers ::__construct
	 * @covers ::update_options
	 * @covers ::add_hooks
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

	/**
	 * @covers ::__construct
	 * @covers ::update_options
	 * @covers ::add_hooks
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_update_options_throws_exception_if_no_options_given() {
		$this->stubbed_adapter->method( 'wp_verify_nonce' )->willReturn( true );

		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );

		wp_set_current_user( $admin_user );
		set_current_screen( 'dashboard' );

		$_POST[ Plugin::GRN_NONCE_VALUE ] = 'valid';

		$this->setExpectedException( '\WPDieException', 'No options given' );
		$this->plugin->update_options();

		wp_delete_user( $admin_user );
	}

	/**
	 * @covers ::__construct
	 * @covers ::update_options
	 * @covers ::add_hooks
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_update_options_throws_exception_if_empty_options_given() {
		$this->stubbed_adapter->method( 'wp_verify_nonce' )->willReturn( true );

		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );

		wp_set_current_user( $admin_user );
		set_current_screen( 'dashboard' );

		$_POST[ Plugin::GRN_NONCE_VALUE ] = 'valid';
		$_POST['remove_noreferrer']       = array();

		$this->setExpectedException( '\WPDieException', 'No options given' );
		$this->plugin->update_options();

		wp_delete_user( $admin_user );
	}

	/**
	 * @covers ::__construct
	 * @covers ::update_options
	 * @covers ::add_hooks
	 * @covers ::get_current_tab
	 * @covers ::options_validator
	 * @covers ::validate_options
	 * @covers \Remove_Noreferrer\Admin\Options_Validator::call
	 * @covers \Remove_Noreferrer\Admin\Options_Validator::validate_where_should_the_plugin_work
	 * @covers \Remove_Noreferrer\Core\Options::get_default_options
	 * @covers \Remove_Noreferrer\Core\Options::get_options
	 * @covers \Remove_Noreferrer\Core\Options::set_options
	 * @covers \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_update_options_did_remove_noreferrer_options_updated_action() {
		$this->stubbed_adapter->method( 'wp_verify_nonce' )->willReturn( true );

		$admin_user = self::factory()->user->create( array( 'role' => 'administrator' ) );

		wp_set_current_user( $admin_user );
		set_current_screen( 'dashboard' );

		$_POST[ Plugin::GRN_NONCE_VALUE ] = 'valid';
		$_POST['remove_noreferrer']       = array( 'grn_tab' => 'general' );

		$this->plugin->update_options();

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_options_updated' ) );

		wp_delete_user( $admin_user );
	}

	/**
	 * @covers ::render_options_page
	 * @covers ::__construct
	 * @covers ::add_hooks
	 * @covers ::get_current_tab
	 * @covers \Remove_Noreferrer\Core\Options::get_default_options
	 * @covers \Remove_Noreferrer\Core\Options::get_options
	 * @covers \Remove_Noreferrer\Core\Options::set_options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_render_options_page() {
		$this->stubbed_options_page->method( 'render' )->willReturn( 'content' );

		ob_start();

		$this->plugin->render_options_page();

		$result = ob_get_clean();

		$this->assertEquals( 'content', $result );
	}
}

