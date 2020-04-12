<?php
declare(strict_types=1);

namespace Remove_Noreferrer\Core;

/**
 * @coversDefaultClass \Remove_Noreferrer\Core\Options
 */
class Options_Test extends \WP_UnitTestCase {
	/**
	 * Remove_Noreferrer\Core\Options instance
	 *
	 * @since 1.3.0
	 * @access private
	 * @var Remove_Noreferrer\Core\Options $_options
	 */
	private $_options;

	/**
	 * Prepares environment
	 *
	 * @return void
	 */
	public function setUp(): void {
		parent::setUp();

		$this->_options = new Options();
	}

	/**
	 * @covers ::get_options
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_options_returns_valid_array_size(): void {
		$this->assertCount( 1, $this->_options->get_options() );
	}

	/**
	 * @covers ::get_default_options
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_options_returns_default_options(): void {
		$this->assertEquals(
			array(
				'where_should_the_plugin_work' => array(
					'post',
					'posts_page',
					'page',
					'comments',
					'text_widget',
					'custom_html_widget',
				),
			),
			$this->_options->get_options()
		);
	}

	/**
	 * @covers ::get_options
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_options_returns_valid_options_array_size(): void {
		$result = $this->_options->get_options();
		$this->assertCount( 6, $result['where_should_the_plugin_work'] );
	}

	/**
	 * @covers ::get_options
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_options_returns_existed_options(): void {
		add_option(
			GRN_OPTION_KEY,
			array(
				GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array(
					'page',
					'comments',
					'custom_html_widget',
				),
			)
		);

		$this->assertEquals(
			array(
				'where_should_the_plugin_work' => array(
					'page',
					'comments',
					'custom_html_widget',
				),
			),
			$this->_options->get_options()
		);
	}

	/**
	 * @covers ::get_default_options
	 * @covers ::get_options
	 * @covers ::get_option
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_option_populates_default_options(): void {
		$this->_options->get_option( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY );

		$this->assertEquals(
			array(
				'where_should_the_plugin_work' => array(
					'post',
					'posts_page',
					'page',
					'comments',
					'text_widget',
					'custom_html_widget',
				),
			),
			$this->_options->get_options()
		);
	}

	/**
	 * @covers ::get_option
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_option_returns_null_if_key_is_not_exists(): void {
		$this->assertEquals( null, $this->_options->get_option( 'some_key' ) );
	}

	/**
	 * @covers ::get_option
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_option_returns_default_value_if_key_is_not_exists(): void {
		$this->assertEquals( 'default', $this->_options->get_option( 'some_key', 'default' ) );
	}

	/**
	 * @covers ::get_option
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_get_option_returns_valid_option(): void {
		add_option( GRN_OPTION_KEY, array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'page' ) ) );

		$this->assertEquals( array( 'page' ), $this->_options->get_option( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ) );
	}
}

