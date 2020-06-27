<?php
/**
 * Options Validator: Options_Validator class
 *
 * Validates plugin's options on saving
 *
 * @package Remove_Noreferrer
 * @subpackage Admin
 * @since 1.1.0
 */

namespace Remove_Noreferrer\Admin;

/**
 * Options Validator
 *
 * @since 1.1.0
 */
class Options_Validator {
	/**
	 * Validator
	 *
	 * @since 1.1.0
	 * @access public
	 * @static
	 *
	 * @param array $input Input values.
	 * @return array
	 */
	public static function call( $input ) {
		return array(
			GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY => self::validate_where_should_the_plugin_work( $input ),
		);
	}

	/**
	 * Validate `where_should_the_plugin_work` option
	 *
	 * @since 1.1.0
	 * @access private
	 * @static
	 *
	 * @param array $input Input values.
	 * @return array
	 */
	private static function validate_where_should_the_plugin_work( $input ) {
		if ( ! self::is_input_valid( $input ) ) {
			return array();
		}

		$result = array_unique(
			array_filter(
				array_map( 'trim', $input[ GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ] ),
				function( $v ) {
					return in_array( $v, GRN_ALLOWED_VALUES, true );
				}
			)
		);

		sort( $result );

		return $result;
	}

	/**
	 * Checks is input valid
	 *
	 * @since 2.0.0
	 * @access private
	 * @static
	 *
	 * @param array $input Input values.
	 * @return bool
	 */
	private static function is_input_valid( $input ) {
		if ( ! is_array( $input ) ) {
			return false;
		}

		if ( empty( $input[ GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ] ) ) {
			return false;
		}

		if ( ! is_array( $input[ GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ] ) ) {
			return false;
		}

		return true;
	}
}

