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
	 *
	 * @return string
	 */
	public static function call( $input ) {
		if ( ! self::are_links_found( $input ) ) {
			return $input;
		}

		if ( ! self::is_noreferrer_found( $input ) ) {
			return $input;
		}

		return self::remove_noreferrer( $input );
	}

	/**
	 * Checks are links found
	 *
	 * @since 2.0.0
	 * @access private
	 * @static
	 *
	 * @param string $input Input.
	 *
	 * @return bool
	 */
	private static function are_links_found( $input ) {
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
