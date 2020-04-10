<?php
declare(strict_types=1);

namespace Remove_Noreferrer;

class Remove_Noreferrer_Test extends \WP_UnitTestCase {
	/**
	 * Test plugin has GRN_OPTION_KEY constant
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_valid_option_key_constant(): void {
		$this->assertSame( 'remove_noreferrer', GRN_OPTION_KEY );
	}

	/**
	 * Test plugin has GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY constant
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_valid_where_should_the_plugin_work_key_constant(): void {
		$this->assertSame( 'where_should_the_plugin_work', GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY );
	}

	/**
	 * Test plugin has GRN_ALLOWED_VALUES constant
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_valid_allowed_values_constant(): void {
		$this->assertSame(
			array(
				'post',
				'posts_page',
				'page',
				'comments',
				'text_widget',
				'custom_html_widget',
			),
			GRN_ALLOWED_VALUES
		);
	}

	/**
	 * Test run_plugin() returns plugin's instance
	 *
	 * @covers \Remove_Noreferrer\run_plugin()
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_returns_valid_instance(): void {
		$this->assertInstanceOf( 'Remove_Noreferrer\Core\Plugin', \Remove_Noreferrer\run_plugin() );
	}
}
