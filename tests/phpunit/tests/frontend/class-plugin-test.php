<?php
/**
 * Unit tests covering Plugin functionality.
 *
 * @package Remove_Noreferrer
 * @subpackage Frontend
 * @since 2.0.0
 */

namespace Remove_Noreferrer\Frontend;

/**
 * Test frontend/class-plugin.php
 *
 * @coversDefaultClass \Remove_Noreferrer\Frontend\Plugin
 * @covers \Remove_Noreferrer\Frontend\Plugin::__construct
 * @group frontend
 *
 * @uses \Remove_Noreferrer\Base\Plugin
 */
class Plugin_Test extends \WP_UnitTestCase {
	/**
	 * Remove_Noreferrer\Frontend\Plugin instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Remove_Noreferrer\Frontend\Plugin $_plugin
	 */
	private $_plugin;

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

		set_current_screen( 'front' );

		$this->_plugin = new Plugin( new \Remove_Noreferrer\Core\Options(), new Links_Processor() );
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
		$this->assertInstanceOf( 'Remove_Noreferrer\Base\Plugin', $this->_plugin );
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
		$this->assertEquals( 10, has_action( 'init', array( $this->_plugin, 'init' ) ) );
	}

	/**
	 * @covers ::__construct
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_construct_did_remove_noreferrer_frontend_plugin_loaded_action() {
		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_frontend_plugin_loaded' ) );
	}

	/**
	 * @covers ::init
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_has_the_content_filter() {
		$this->_plugin->init();

		$this->assertEquals( 999, has_filter( 'the_content', array( $this->_plugin, 'remove_noreferrer_from_content' ) ) );
	}

	/**
	 * @covers ::init
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_has_comment_text_filter() {
		$this->_plugin->init();

		$this->assertEquals( 20, has_filter( 'comment_text', array( $this->_plugin, 'remove_noreferrer_from_comment' ) ) );
	}

	/**
	 * @covers ::init
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_has_widget_display_callback_filter() {
		$this->_plugin->init();

		$this->assertEquals( 10, has_filter( 'widget_display_callback', array( $this->_plugin, 'remove_noreferrer_from_text_widget' ) ) );
	}

	/**
	 * @covers ::init
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_has_widget_custom_html_content_filter() {
		$this->_plugin->init();

		$this->assertEquals( 10, has_filter( 'widget_custom_html_content', array( $this->_plugin, 'remove_noreferrer_from_custom_html_widget' ) ) );
	}

	/**
	 * @covers ::init
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_did_remove_noreferrer_frontend_plugin_initialized_action() {
		$this->_plugin->init();

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_frontend_plugin_initialized' ) );
	}
}

