<?php
declare(strict_types=1);

namespace Remove_Noreferrer\Frontend;

/**
 * @coversDefaultClass \Remove_Noreferrer\Frontend\Plugin
 */
class Plugin_Test extends \WP_UnitTestCase {
	/**
	 * Remove_Noreferrer\Frontend\Plugin instance
	 *
	 * @since 1.3.0
	 * @access private
	 * @var Remove_Noreferrer\Frontend\Plugin $_plugin
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

		set_current_screen( 'front' );

		$this->_plugin = new Plugin( new \Remove_Noreferrer\Core\Options(), new Links_Processor() );
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
		unset( $GLOBALS['screen'] );
		unset( $GLOBALS['current_screen'] );

		parent::tearDown();
	}

	/**
	 * @covers ::__construct
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
	 * @covers ::__construct
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_construct_did_remove_noreferrer_frontend_plugin_loaded_action(): void {
		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_frontend_plugin_loaded' ) );
	}

	/**
	 * @covers ::init
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_has_the_content_filter() {
		do_action( 'init' );

		$this->assertEquals( 999, has_filter( 'the_content', array( $this->_plugin, 'remove_noreferrer_from_content' ) ) );
	}

	/**
	 * @covers ::init
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_has_comment_text_filter() {
		do_action( 'init' );

		$this->assertEquals( 20, has_filter( 'comment_text', array( $this->_plugin, 'remove_noreferrer_from_comment' ) ) );
	}

	/**
	 * @covers ::init
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_has_widget_display_callback_filter() {
		do_action( 'init' );

		$this->assertEquals( 10, has_filter( 'widget_display_callback', array( $this->_plugin, 'remove_noreferrer_from_text_widget' ) ) );
	}

	/**
	 * @covers ::init
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_has_widget_custom_html_content_filter() {
		do_action( 'init' );

		$this->assertEquals( 10, has_filter( 'widget_custom_html_content', array( $this->_plugin, 'remove_noreferrer_from_custom_html_widget' ) ) );
	}
}

