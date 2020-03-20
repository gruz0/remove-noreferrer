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
	 *
	 * @param array $input Input values.
	 * @return array
	 */
	public function validate( $input ) {
		return array(
			'where_should_the_plugin_work' => $this->validate_where_should_the_plugin_work( $input ),
		);
	}

	/**
	 * Validate `where_should_the_plugin_work` option
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param array $input Input values.
	 * @return array
	 */
	private function validate_where_should_the_plugin_work( $input ) {
		if ( empty( $input['where_should_the_plugin_work'] ) ) {
			return array();
		}

		$result         = array();
		$allowed_values = array( 'post', 'posts_page', 'page', 'comments' );

		foreach ( $allowed_values as $value ) {
			if ( ! in_array( $value, $input['where_should_the_plugin_work'], true ) ) {
				continue;
			}

			$result[] = $value;
		}

		// @todo: Add array_unique
		return $result;
	}
}

