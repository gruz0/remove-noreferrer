<?php
namespace Remove_Noreferrer;

class Remove_Noreferrer_Test extends \WP_UnitTestCase {
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
	public function test_has_grn_option_key_constant() {
		$this->assertSame( 'remove_noreferrer', GRN_OPTION_KEY );
	}

	/**
	 * @coversNothing
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_grn_where_should_the_plugin_work_key_constant() {
		$this->assertSame( 'where_should_the_plugin_work', GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY );
	}

	/**
	 * @coversNothing
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_grn_allowed_values_function() {
		$this->assertSame(
			array(
				'post',
				'posts_page',
				'page',
				'comments',
				'text_widget',
				'target_blank',
				'custom_html_widget',
			),
			\Remove_Noreferrer\grn_allowed_values()
		);
	}

	/**
	 * @coversNothing
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_returns_admin_plugin_on_admin_part() {
		set_current_screen( 'dashboard' );

		$this->assertInstanceOf( 'Remove_Noreferrer\Admin\Plugin', \Remove_Noreferrer\run_plugin() );
	}

	/**
	 * @coversNothing
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_returns_frontend_plugin_on_frontend_part() {
		$this->assertInstanceOf( 'Remove_Noreferrer\Frontend\Plugin', \Remove_Noreferrer\run_plugin() );
	}
}

