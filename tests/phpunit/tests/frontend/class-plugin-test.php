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
 * @coversDefaultClass Remove_Noreferrer\Frontend\Plugin
 * @group frontend
 *
 * @uses Remove_Noreferrer\Base\Plugin
 */
class Plugin_Test extends \WP_UnitTestCase {
	const CONTENT  = '<a href="link" rel="noreferrer nofollow">text</a>';
	const EXPECTED = '<a href="link" rel="nofollow">text</a>';

	/**
	 * Remove_Noreferrer\Frontend\Plugin instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Remove_Noreferrer\Frontend\Plugin $plugin
	 */
	private $plugin;

	/**
	 * Remove_Noreferrer\Core\Options stubbed instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Remove_Noreferrer\Core\Options $stubbed_options
	 */
	private $stubbed_options;

	/**
	 * Remove_Noreferrer\Core\Adapter stubbed instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Remove_Noreferrer\Core\Adapter $stubbed_adapter
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

		set_current_screen( 'front' );

		$this->stubbed_options = $this->createMock( \Remove_Noreferrer\Core\Options::class );
		$this->stubbed_adapter = $this->createMock( \Remove_Noreferrer\Core\Adapter::class );

		$this->plugin = new Plugin(
			$this->stubbed_options,
			new Links_Processor(),
			$this->stubbed_adapter
		);
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
	public function test_construct_did_remove_noreferrer_frontend_plugin_loaded_action() {
		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_frontend_plugin_loaded' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::init
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_has_the_content_filter() {
		$this->plugin->init();

		$this->assertEquals( 999, has_filter( 'the_content', array( $this->plugin, 'remove_noreferrer_from_content' ) ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::init
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_has_comment_text_filter() {
		$this->plugin->init();

		$this->assertEquals( 20, has_filter( 'comment_text', array( $this->plugin, 'remove_noreferrer_from_comment' ) ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::init
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_has_widget_display_callback_filter() {
		$this->plugin->init();

		$this->assertEquals( 10, has_filter( 'widget_display_callback', array( $this->plugin, 'remove_noreferrer_from_widgets' ) ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::init
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_init_did_remove_noreferrer_frontend_plugin_initialized_action() {
		$this->plugin->init();

		$this->assertGreaterThan( 0, did_action( 'remove_noreferrer_frontend_plugin_initialized' ) );
	}

	/**
	 * @covers ::__construct
	 * @covers ::remove_noreferrer_from_content
	 * @covers ::where_should_the_plugin_work
	 * @covers ::get_option
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_remove_noreferrer_from_content_returns_original_if_option_value_is_not_an_array() {
		$this->stub_get_option_key_with( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY, 123 );

		$result = $this->plugin->remove_noreferrer_from_content( self::CONTENT );

		$this->assertEquals( self::CONTENT, $result );
	}

	/**
	 * @covers ::__construct
	 * @covers ::remove_noreferrer_from_content
	 * @covers ::where_should_the_plugin_work
	 * @covers ::get_option
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_remove_noreferrer_from_content_returns_original_if_option_value_is_an_empty_array() {
		$this->stub_get_option_key_with( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY, array() );

		$result = $this->plugin->remove_noreferrer_from_content( self::CONTENT );

		$this->assertEquals( self::CONTENT, $result );
	}

	/**
	 * @covers ::__construct
	 * @covers ::remove_noreferrer_from_content
	 * @covers ::where_should_the_plugin_work
	 * @covers ::is_current_page_allowed
	 * @covers ::is_single_processable
	 * @covers ::get_option
	 * @covers ::remove_noreferrer
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::call
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::is_links_found
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::is_noreferrer_found
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::remove_extra_spaces
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::remove_noreferrer
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_remove_noreferrer_from_content_removes_noreferrer_from_single_post() {
		$this->stub_get_option_key_with( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY, array( 'post' ) );

		$this->stubbed_adapter->method( 'is_single' )->willReturn( true );

		$result = $this->plugin->remove_noreferrer_from_content( self::CONTENT );

		$this->assertEquals( self::EXPECTED, $result );
	}

	/**
	 * @covers ::__construct
	 * @covers ::remove_noreferrer_from_content
	 * @covers ::where_should_the_plugin_work
	 * @covers ::is_current_page_allowed
	 * @covers ::is_page_processable
	 * @covers ::is_single_processable
	 * @covers ::get_option
	 * @covers ::remove_noreferrer
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::call
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::is_links_found
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::is_noreferrer_found
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::remove_extra_spaces
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::remove_noreferrer
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_remove_noreferrer_from_content_removes_noreferrer_from_page() {
		$this->stub_get_option_key_with( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY, array( 'page' ) );

		$this->stubbed_adapter->method( 'is_page' )->willReturn( true );

		$result = $this->plugin->remove_noreferrer_from_content( self::CONTENT );

		$this->assertEquals( self::EXPECTED, $result );
	}

	/**
	 * @covers ::__construct
	 * @covers ::remove_noreferrer_from_content
	 * @covers ::where_should_the_plugin_work
	 * @covers ::is_current_page_allowed
	 * @covers ::is_posts_page_processable
	 * @covers ::get_option
	 * @covers ::is_page_processable
	 * @covers ::is_single_processable
	 * @covers ::remove_noreferrer
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::call
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::is_links_found
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::is_noreferrer_found
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::remove_extra_spaces
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::remove_noreferrer
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_remove_noreferrer_from_content_removes_noreferrer_from_posts_page() {
		$this->stub_get_option_key_with( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY, array( 'posts_page' ) );

		$this->stubbed_adapter->method( 'is_posts_page' )->willReturn( true );

		$result = $this->plugin->remove_noreferrer_from_content( self::CONTENT );

		$this->assertEquals( self::EXPECTED, $result );
	}

	/**
	 * @covers ::__construct
	 * @covers ::remove_noreferrer_from_comment
	 * @covers ::where_should_the_plugin_work
	 * @covers ::get_option
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_remove_noreferrer_from_comment_returns_original_if_option_value_is_not_an_array() {
		$this->stub_get_option_key_with( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY, 123 );

		$result = $this->plugin->remove_noreferrer_from_comment( self::CONTENT );

		$this->assertEquals( self::CONTENT, $result );
	}

	/**
	 * @covers ::__construct
	 * @covers ::remove_noreferrer_from_comment
	 * @covers ::where_should_the_plugin_work
	 * @covers ::get_option
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_remove_noreferrer_from_comment_returns_original_if_option_value_is_an_empty_array() {
		$this->stub_get_option_key_with( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY, array() );

		$result = $this->plugin->remove_noreferrer_from_comment( self::CONTENT );

		$this->assertEquals( self::CONTENT, $result );
	}

	/**
	 * @covers ::__construct
	 * @covers ::remove_noreferrer_from_comment
	 * @covers ::where_should_the_plugin_work
	 * @covers ::is_comments_processable
	 * @covers ::get_option
	 * @covers ::remove_noreferrer
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::call
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::is_links_found
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::is_noreferrer_found
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::remove_extra_spaces
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::remove_noreferrer
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_remove_noreferrer_from_comment_removes_noreferrer() {
		$this->stub_get_option_key_with( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY, array( 'comments' ) );

		$result = $this->plugin->remove_noreferrer_from_comment( self::CONTENT );

		$this->assertEquals( self::EXPECTED, $result );
	}

	/**
	 * @covers ::__construct
	 * @covers ::remove_noreferrer_from_widgets
	 * @covers ::where_should_the_plugin_work
	 * @covers ::get_option
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_remove_noreferrer_from_widgets_returns_original_if_option_value_is_not_an_array() {
		$this->stub_get_option_key_with( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY, 123 );

		$instance = $this->get_text_widget_instance();
		$widget   = new \WP_Widget_Text();
		$args     = $this->get_widget_default_args();

		$result = $this->plugin->remove_noreferrer_from_widgets( $instance, $widget, $args );

		$this->assertEquals( $instance, $result );
	}

	/**
	 * @covers ::__construct
	 * @covers ::remove_noreferrer_from_widgets
	 * @covers ::where_should_the_plugin_work
	 * @covers ::get_option
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_remove_noreferrer_from_widgets_returns_original_if_option_value_is_an_empty_array() {
		$this->stub_get_option_key_with( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY, array() );

		$instance = $this->get_text_widget_instance();
		$widget   = new \WP_Widget_Text();
		$args     = $this->get_widget_default_args();

		$result = $this->plugin->remove_noreferrer_from_widgets( $instance, $widget, $args );

		$this->assertEquals( $instance, $result );
	}

	/**
	 * @covers ::__construct
	 * @covers ::remove_noreferrer_from_widgets
	 * @covers ::where_should_the_plugin_work
	 * @covers ::is_widget_processable
	 * @covers ::get_option
	 * @covers ::remove_noreferrer
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::call
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::is_links_found
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::is_noreferrer_found
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::remove_extra_spaces
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::remove_noreferrer
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_remove_noreferrer_from_widgets_returns_false_for_effectively_short_circuit_display_of_the_widget() {
		$this->stub_get_option_key_with( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY, array( 'text_widget' ) );

		$instance = $this->get_text_widget_instance();
		$widget   = new \WP_Widget_Text();
		$args     = $this->get_widget_default_args();

		ob_start();

		$result = $this->plugin->remove_noreferrer_from_widgets( $instance, $widget, $args );

		ob_get_clean();

		$this->assertEquals( false, $result );
	}

	/**
	 * @covers ::__construct
	 * @covers ::remove_noreferrer_from_widgets
	 * @covers ::where_should_the_plugin_work
	 * @covers ::is_widget_processable
	 * @covers ::get_option
	 * @covers ::remove_noreferrer
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::call
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::is_links_found
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::is_noreferrer_found
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::remove_extra_spaces
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::remove_noreferrer
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_remove_noreferrer_from_widgets_removes_noreferrer_from_text_widget() {
		$this->stub_get_option_key_with( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY, array( 'text_widget' ) );

		$instance = $this->get_text_widget_instance();
		$widget   = new \WP_Widget_Text();
		$args     = $this->get_widget_default_args();

		ob_start();

		$this->plugin->remove_noreferrer_from_widgets( $instance, $widget, $args );

		$result = trim( ob_get_clean() );

		$this->assertEquals( '<div class="textwidget"><p>' . self::EXPECTED . "</p>\n</div>", $result );
	}

	/**
	 * @covers ::__construct
	 * @covers ::remove_noreferrer_from_widgets
	 * @covers ::where_should_the_plugin_work
	 * @covers ::is_widget_processable
	 * @covers ::get_option
	 * @covers ::remove_noreferrer
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::call
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::is_links_found
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::is_noreferrer_found
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::remove_extra_spaces
	 * @covers Remove_Noreferrer\Frontend\Links_Processor::remove_noreferrer
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_remove_noreferrer_from_widgets_removes_noreferrer_from_custom_html_widget() {
		$this->stub_get_option_key_with( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY, array( 'custom_html_widget' ) );

		$instance = array(
			'title'   => '',
			'content' => self::CONTENT,
		);
		$widget   = new \WP_Widget_Custom_HTML();
		$args     = $this->get_widget_default_args();

		ob_start();

		$this->plugin->remove_noreferrer_from_widgets( $instance, $widget, $args );

		$result = trim( ob_get_clean() );

		$this->assertEquals( '<div class="textwidget custom-html-widget">' . self::EXPECTED . '</div>', $result );
	}

	/**
	 * @coversNothing
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param string $key   Option's key.
	 * @param mixed  $value Stubbed value.
	 */
	private function stub_get_option_key_with( $key, $value ) {
		$this->stubbed_options->method( 'get_option' )
			->with( $key )
			->willReturn( $value );
	}

	/**
	 * Returns default attributes for WP_Widget_Text
	 *
	 * @coversNothing
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @return array
	 */
	private function get_text_widget_instance() {
		return array(
			'title'  => '',
			'text'   => self::CONTENT,
			'filter' => true,
			'visual' => true,
		);
	}

	/**
	 * Returns default args for widgets
	 *
	 * @coversNothing
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @return array
	 */
	private function get_widget_default_args() {
		return array(
			'before_widget' => '',
			'after_widget'  => '',
			'class'         => '',
		);
	}
}

