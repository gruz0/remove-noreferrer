<?php
/*
Plugin Name: Remove noreferrer
Description: This plugin removes rel="noreferrer" from post's links on the frontend
Author: Alexander Kadyrov
Author URI: https://github.com/gruz0
Text Domain: remove-noreferrer
Version: 1.0.0
*/

/**
 * Remove referrer from "rel" attribute
 *
 * @param string $content Content.
 * @return string
 */
function gruz0_remove_referrer( $content ) {
	// @todo: Add options where filter will be applied (single post, single page, index page)
	if ( ! is_single() ) {
		return $content;
	}

	$replace = function( $matches ) {
		return sprintf( 'rel="%s"', preg_replace( '/noreferrer\s*/i', '', $matches[1] ) );
	};

	return preg_replace_callback( '/rel="([^\"]+)"/i', $replace, $content );
}
add_filter( 'the_content', 'gruz0_remove_referrer', 999 );

