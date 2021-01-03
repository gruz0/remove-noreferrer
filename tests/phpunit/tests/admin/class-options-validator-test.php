<?php
/**
 * Unit tests covering Options_Validator functionality.
 *
 * @package Remove_Noreferrer
 * @subpackage Admin
 * @since 2.0.0
 */

namespace Remove_Noreferrer\Admin;

/**
 * Test admin/class-options-validator.php
 *
 * @coversDefaultClass Remove_Noreferrer\Admin\Options_Validator
 * @group admin
 */
class Options_Validator_Test extends \PHPUnit\Framework\TestCase {
	/**
	 * Options_Validator instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Options_Validator $validator
	 */
	private $validator;

	/**
	 * Prepares environment
	 *
	 * @return void
	 */
	protected function setUp() {
		parent::setUp();

		$this->validator = new Options_Validator();
	}

	/**
	 * @dataProvider data_call_validates_where_should_the_plugin_work
	 *
	 * @covers ::call
	 * @covers ::validate_where_should_the_plugin_work
	 * @covers ::allowed_values
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $input The input string.
	 * @param string $expected The expected function output.
	 *
	 * @return void
	 */
	public function test_call_validates_where_should_the_plugin_work( $input, $expected ) {
		$result = $this->validator->call( $input );

		$this->assertSame( $expected, $result );
	}

	/**
	 * Data provider
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function data_call_validates_where_should_the_plugin_work() {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		return array(
			'pass string instead of array' => array(
				'',
				array(),
			),
			'key "where_should_the_plugin_work" not found' => array(
				array(),
				array(),
			),
			'pass empty array' => array(
				array(
					'grn_tab' => 'general',
					GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array(),
				),
				array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array() ),
			),
			"pass string instead of array's values" => array(
				array(
					'grn_tab' => 'general',
					GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => 'test',
				),
				array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array() ),
			),
			'not allowed value passed' => array(
				array(
					'grn_tab' => 'general',
					GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'test' ),
				),
				array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array() ),
			),
			'remove not allowed value' => array(
				array(
					'grn_tab' => 'general',
					GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'page', 'test' ),
				),
				array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'page' ) ),
			),
			'value has extra whitespace' => array(
				array(
					'grn_tab' => 'general',
					GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( '   page   ', '  post ' ),
				),
				array( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'page', 'post' ) ),
			),
			'allowed values' => array(
				array(
					'grn_tab' => 'general',
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
					'grn_tab' => 'general',
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
			'sort values' => array(
				array(
					'grn_tab' => 'general',
					GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'post', 'page' ),
				),
				array(
					GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => array( 'page', 'post' ),
				),
			),
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}

	/**
	 * @dataProvider data_call_validates_remove_settings_on_uninstall
	 *
	 * @covers ::call
	 * @covers ::validate_remove_settings_on_uninstall
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $input The input string.
	 * @param string $expected The expected function output.
	 *
	 * @return void
	 */
	public function test_call_validates_remove_settings_on_uninstall( $input, $expected ) {
		$result = $this->validator->call( $input );

		$this->assertSame( $expected, $result );
	}

	/**
	 * Data provider
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return array
	 */
	public function data_call_validates_remove_settings_on_uninstall() {
		// phpcs:disable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
		return array(
			'pass string instead of array' => array(
				'',
				array(),
			),
			'key "grn_tab" not found' => array(
				array(),
				array(),
			),
			'option unchecked' => array(
				array( 'grn_tab' => 'additional-settings' ),
				array( GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '0' ),
			),
			'not allowed value passed' => array(
				array(
					'grn_tab' => 'additional-settings',
					GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => 'true',
				),
				array( GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '0' ),
			),
			'value has extra whitespace' => array(
				array(
					'grn_tab' => 'additional-settings',
					GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => ' 1 ',
				),
				array( GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '0' ),
			),
			'allowed values' => array(
				array(
					'grn_tab' => 'additional-settings',
					GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '1',
				),
				array( GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY => '1' ),
			),
		);
		// phpcs:enable WordPress.Arrays.MultipleStatementAlignment.DoubleArrowNotAligned
	}
}

