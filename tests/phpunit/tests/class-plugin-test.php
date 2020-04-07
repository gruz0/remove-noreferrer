<?php
declare(strict_types=1);

namespace Remove_Noreferrer;

class Plugin_Test extends \WP_UnitTestCase {
	/**
	 * Test plugin has GRN_OPTION_KEY constant
	 *
	 * @since 1.3.0
	 * @access public
	 *
	 * @return void
	 */
	public function test_has_valid_option_key(): void {
		$this->assertSame( 'remove_noreferrer', GRN_OPTION_KEY );
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
	public function test_has_valid_instance(): void {
		$this->assertInstanceOf( 'Remove_Noreferrer\Plugin', run_plugin() );
	}
}
