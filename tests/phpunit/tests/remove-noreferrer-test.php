<?php
/**
 * Unit tests covering plugin's functionality.
 *
 * @package Remove_Noreferrer
 * @since 2.0.0
 */

namespace Remove_Noreferrer;

/**
 * Test remove-noreferrer.php
 */
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
	public function test_has_grn_version_constant() {
		$this->assertSame( '2.0.0', GRN_VERSION );
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
	public function test_has_grn_plugin_version_key_constant() {
		$this->assertSame( 'plugin_version', GRN_PLUGIN_VERSION_KEY );
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
}

