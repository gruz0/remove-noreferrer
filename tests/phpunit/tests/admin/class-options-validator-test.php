<?php
declare(strict_types=1);

namespace Remove_Noreferrer\Admin;

/**
 * @coversDefaultClass \Remove_Noreferrer\Admin\Options_Validator
 */
class Options_Validator_Test extends \PHPUnit\Framework\TestCase {
	/**
	 * Remove_Noreferrer\Admin\Options_Validator instance
	 *
	 * @since 1.3.0
	 * @access private
	 * @var Remove_Noreferrer\Admin\Options_Validator $_validator
	 */
	private $_validator;

	/**
	 * Prepares environment
	 *
	 * @return void
	 */
	protected function setUp(): void {
		parent::setUp();

		$this->_validator = new Options_Validator();
	}

	/**
	 * @dataProvider data_call
	 *
	 * @covers ::call
	 * @covers ::validate_where_should_the_plugin_work
	 * @covers ::is_input_valid
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @param string $input The input string.
	 * @param string $expected The expected function output.
	 *
	 * @return void
	 */
	public function test_call( $input, $expected ) {
		$result = $this->_validator->call( $input );

		$this->assertSame( $expected, $result );
	}

	/**
	 * Data provider
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return array
	 */
	public function data_call() {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		return array(
			"pass string instead of array's key" => array(
				'',
				array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array() ),
			),
			'key "where_should_the_plugin_work" not found' => array(
				array(),
				array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array() ),
			),
			"pass string instead of array's values" => array(
				array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => 'test' ),
				array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array() ),
			),
			'not allowed value passed' => array(
				array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'test' ) ),
				array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array() ),
			),
			'remove not allowed value' => array(
				array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'page', 'test' ) ),
				array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'page' ) ),
			),
			'value has extra whitespace' => array(
				array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( '   page   ', '  post ' ) ),
				array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'page', 'post' ) ),
			),
			'allowed values' => array(
				array(
					GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array(
						'post',
						'posts_page',
						'page',
						'comments',
						'text_widget',
						'custom_html_widget',
					),
				),
				array(
					GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array(
						'comments',
						'custom_html_widget',
						'page',
						'post',
						'posts_page',
						'text_widget',
					),
				),
			),
			'remove duplicates' => array(
				array(
					GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array(
						'post',
						'posts_page',
						'post',
						'test',
						'comments',
						'comments',
						'post',
					),
				),
				array(
					GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array(
						'comments',
						'post',
						'posts_page',
					),
				),
			),
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}
}

