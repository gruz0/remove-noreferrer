<?php
/**
 * Unit tests covering Options_Page functionality.
 *
 * @package Remove_Noreferrer
 * @subpackage Admin
 * @since 2.0.0
 */

namespace Remove_Noreferrer\Admin;

/**
 * Test admin/class-options-page.php
 *
 * @coversDefaultClass \Remove_Noreferrer\Admin\Options_Page
 * @group admin
 */
class Options_Page_Test extends \WP_UnitTestCase {
	/**
	 * Options_Page's instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var \Remove_Noreferrer\Admin\Options_Page $_options_page
	 */
	private $_options_page;

	/**
	 * Options
	 *
	 * @since 2.0.0
	 * @access private
	 * @var array $_options
	 */
	private $_options;

	/**
	 * Prepares environment
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->_options = array(
			'where_should_the_plugin_work' => array(
				'post',
				'posts_page',
				'page',
				'comments',
				'text_widget',
				'custom_html_widget',
			),
		);

		$this->_options_page = new Options_Page();
	}

	/**
	 * @covers ::render
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_render_h1_tag(): void {
		$content = $this->_options_page->render( $this->_options );

		$this->assertTrue( false !== preg_match( '/<h1>Remove Noreferrer<\/h1>/', $content ) );
	}

	/**
	 * @covers ::render_nonce
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_render_has_hidden_nonce_field(): void {
		$content = $this->_options_page->render( $this->_options );

		$nonce = 'gruz0_remove_noreferrer_nonce';
		$found = preg_match( '/<input type="hidden" id="' . $nonce . '" name="' . $nonce . '" value=".+" \/>/', $content );

		$this->assertEquals( 1, $found );
	}

	/**
	 * @covers ::render_action
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_render_has_hidden_action_field(): void {
		$content = $this->_options_page->render( $this->_options );

		$found = preg_match( '/<input type="hidden" name="action" value="remove_noreferrer_update_options" \/>/', $content );

		$this->assertEquals( 1, $found );
	}

	/**
	 * @covers ::render
	 * @covers ::render_checkbox
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_render_has_valid_checkboxes_count(): void {
		$content = $this->_options_page->render( $this->_options );

		$matches = array();

		preg_match_all( '/<input\s+type="checkbox"\s+name="remove_noreferrer\[where_should_the_plugin_work\]\[\]"[^>]*>/', $content, $matches );

		$this->assertCount( 6, $matches[0] );
	}

	/**
	 * @dataProvider data_checkboxes
	 *
	 * @covers ::render_checkbox
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param mixed $input Function's result.
	 * @param int   $expected Expected.
	 *
	 * @return void
	 */
	public function test_checkboxes( $input, $expected ): void {
		$this->assertEquals( $expected, $input );
	}

	/**
	 * Data provider
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function data_checkboxes(): array {
		return array(
			'checked post'                 => array( $this->find_checked_checkbox( 'post', 'Single Post' ), 1 ),
			'unchecked post'               => array( $this->find_unchecked_checkbox( 'post', 'Single Post' ), 1 ),
			'checked posts_page'           => array( $this->find_checked_checkbox( 'posts_page', 'Posts page \(Home Page, etc.\)' ), 1 ),
			'unchecked posts_page'         => array( $this->find_unchecked_checkbox( 'posts_page', 'Posts page \(Home Page, etc.\)' ), 1 ),
			'checked page'                 => array( $this->find_checked_checkbox( 'page', 'Single Page' ), 1 ),
			'unchecked page'               => array( $this->find_unchecked_checkbox( 'page', 'Single Page' ), 1 ),
			'checked comments'             => array( $this->find_checked_checkbox( 'comments', 'Comments' ), 1 ),
			'unchecked comments'           => array( $this->find_unchecked_checkbox( 'comments', 'Comments' ), 1 ),
			'checked text_widget'          => array( $this->find_checked_checkbox( 'text_widget', 'Text Widget' ), 1 ),
			'unchecked text_widget'        => array( $this->find_unchecked_checkbox( 'text_widget', 'Text Widget' ), 1 ),
			'checked custom_html_widget'   => array( $this->find_checked_checkbox( 'custom_html_widget', 'Custom HTML Widget' ), 1 ),
			'unchecked custom_html_widget' => array( $this->find_unchecked_checkbox( 'custom_html_widget', 'Custom HTML Widget' ), 1 ),
		);
	}

	/**
	 * @coversNothing
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param string $value Checkbox value.
	 * @param string $label Label.
	 *
	 * @return mixed
	 */
	private function find_checked_checkbox( $value, $label ) {
		$options = array(
			'where_should_the_plugin_work' => array(
				'post',
				'posts_page',
				'page',
				'comments',
				'text_widget',
				'custom_html_widget',
			),
		);

		$content = ( new Options_Page() )->render( $options );

		$checked = "checked='checked'";

		return $this->regex_match( $content, $value, $label, $checked );
	}

	/**
	 * @coversNothing
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param string $value Checkbox value.
	 * @param string $label Label.
	 *
	 * @return mixed
	 */
	private function find_unchecked_checkbox( $value, $label ) {
		$options = array(
			'where_should_the_plugin_work' => array(
				'post',
				'posts_page',
				'page',
				'comments',
				'text_widget',
				'custom_html_widget',
			),
		);

		$new_options = array_diff( $options['where_should_the_plugin_work'], array( $value ) );

		$options['where_should_the_plugin_work'] = $new_options;

		$content = ( new Options_Page() )->render( $options );

		return $this->regex_match( $content, $value, $label, '' );
	}

	/**
	 * @coversNothing
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param string $content Content.
	 * @param string $value Checkbox value.
	 * @param string $label Label.
	 * @param string $checked Checked attribute.
	 *
	 * @return mixed
	 */
	private function regex_match( $content, $value, $label, $checked ) {
		$regex = '/<label>\s+<input\s+type="checkbox"\s+name="remove_noreferrer\[where_should_the_plugin_work\]\[\]"\s+value="%s"\s+%s\s+\/>\s+%s\s+<\/label>/';
		$regex = sprintf( $regex, $value, $checked, $label );

		return preg_match( $regex, $content );
	}
}

