<?php
/**
 * Links Processor removes noreferrer in `rel` attribute from the links
 *
 * Removes rel attributes from the links
 *
 * @package Remove_Noreferrer
 * @subpackage Frontend
 * @since 2.0.0
 */

namespace Remove_Noreferrer\Frontend;

/**
 * Links Processor
 *
 * @since 2.0.0
 */
class Links_Processor {
	/**
	 * Call
	 *
	 * @since 2.0.0
	 * @access public
	 * @static
	 *
	 * @param string $input Input.
	 * @param string $attribute_to_remove defines which attribute to remove.
	 *
	 * @return string
	 */
	public static function call( $input, $attribute_to_remove = 'noreferrer' ) {
		if ( ! self::is_links_found( $input ) ) {
			return $input;
		}

		if ( 'target' === $attribute_to_remove ) {
			if ( ! self::is_target_blank_found( $input ) ) {
				return $input;
			}

			return self::remove_target_blank( $input );
		}

		return self::remove_noreferrer( $input );
	}

	/**
	 * Checks is links found
	 *
	 * @since 2.0.0
	 * @access private
	 * @static
	 *
	 * @param string $input Input.
	 *
	 * @return bool
	 */
	private static function is_links_found( $input ) {
		return ! ( false === stripos( $input, '<a ' ) );
	}

	/**
	 * Checks is noreferrer found
	 *
	 * @since 2.0.0
	 * @access private
	 * @static
	 *
	 * @param string $input Input.
	 *
	 * @return bool
	 */
	private static function is_noreferrer_found( $input ) {
		return ! ( false === stripos( $input, 'noreferrer' ) );
	}

	/**
	 * Checks is target blank found
	 *
	 * @since 2.0.1
	 * @access private
	 * @static
	 *
	 * @param string $input Input.
	 *
	 * @return bool
	 */
	private static function is_target_blank_found( $input ) {
		return ! ( false === stripos( $input, '_blank' ) );
	}

	/**
	 * Removes noreferrer
	 *
	 * @since 2.0.0
	 * @access private
	 * @static
	 *
	 * @param string $input Input.
	 *
	 * @return string
	 */
	private static function remove_noreferrer( $input ) {
		// phpcs:disable Squiz.Commenting.InlineComment.InvalidEndChar
		$replace = function( $matches ) {
			// For input string '<a class="test"   rel="nofollow noreferrer" name="test">test</a>' it returns:
			return sprintf(
				'%s%s%s%s%s%s',
				$matches[1], // returns: '<a class="test"   rel='
				$matches[2], // returns: '"'
				self::remove_extra_spaces( str_ireplace( 'noreferrer', '', $matches[3] ) ), // returns: 'nofollow'
				$matches[2], // returns: '"'
				$matches[4], // returns: ' name="test"'
				$matches[5] // returns: '>test</a>'
			);
		};
		// phpcs:enable Squiz.Commenting.InlineComment.InvalidEndChar

		$regex = "/(<a\s.*rel=)([\"\']??)(.+)\\2([^>]*)(>.*<\/a>)/siU";

		return preg_replace_callback( $regex, $replace, $input );
	}

	/**
	 * Removes target blank attribute.
	 *
	 * @since 2.0.1
	 * @access private
	 * @static
	 *
	 * @param string $input Input.
	 *
	 * @return string
	 */
	private static function remove_target_blank( $input ) {
		// phpcs:disable Squiz.Commenting.InlineComment.InvalidEndChar
		$replace = function( $matches ) {
			// For input string '<a class="test"   target="_blank" name="test">test</a>' it returns:
			return sprintf(
				'%s%s%s%s%s%s',
				$matches[1], // returns: '<a class="test"   target='
				$matches[2], // returns: '"'
				self::remove_extra_spaces( str_ireplace( '_blank', '', $matches[3] ) ), // returns: '_blank'
				$matches[2], // returns: '"'
				$matches[4], // returns: ' name="test"'
				$matches[5] // returns: '>test</a>'
			);
		};
		// phpcs:enable Squiz.Commenting.InlineComment.InvalidEndChar

		$regex = "/(<a\s.*target=)([\"\']??)(.+)\\2([^>]*)(>.*<\/a>)/siU";

		return preg_replace_callback( $regex, $replace, $input );
	}

	/**
	 * Removes extra spaces from the string
	 *
	 * @since 2.0.0
	 * @access private
	 * @static
	 *
	 * @param string $input Input.
	 *
	 * @return string
	 */
	private static function remove_extra_spaces( $input ) {
		return trim( preg_replace( '/\s+/', ' ', $input ) );
	}
}
