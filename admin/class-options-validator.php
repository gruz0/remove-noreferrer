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
		$result = array();

		if ( ! is_array( $input ) ) {
			return $result;
		}

		$grn_tab = ( ! empty( $input['grn_tab'] ) ) ? $input['grn_tab'] : false;

		switch ( $grn_tab ) {
			case 'general':
				$result[ GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ]
					= self::validate_where_should_the_plugin_work( $input );

				break;

			case 'additional-settings':
				$result[ GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY ]
					= self::validate_remove_settings_on_uninstall( $input );

				break;
		}

		return $result;
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
		if ( empty( $input[ GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ] ) ) {
			return array();
		}

		if ( ! is_array( $input[ GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ] ) ) {
			return array();
		}

		$result = array_unique(
			array_filter(
				array_map( 'trim', $input[ GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ] ),
				function( $v ) {
					return in_array( $v, self::allowed_values(), true );
				}
			)
		);

		sort( $result );

		return $result;
	}

	/**
	 * Validate `remove_settings_on_uninstall` option
	 *
	 * @since 2.0.0
	 * @access private
	 * @static
	 *
	 * @param array $input Input values.
	 * @return boolean
	 */
	private static function validate_remove_settings_on_uninstall( $input ) {
		if ( empty( $input[ GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY ] ) ) {
			return '0';
		}

		return '1' === $input[ GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY ] ? '1' : '0';
	}

	/**
	 * Allowed values
	 *
	 * @since 2.0.0
	 * @access private
	 * @static
	 *
	 * @return array
	 */
	private static function allowed_values() {
		return array(
			'post',
			'posts_page',
			'page',
			'comments',
			'text_widget',
			'custom_html_widget',
		);
	}
}

