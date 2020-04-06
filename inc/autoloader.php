<?php
/**
 * Autoloader for PHP classes
 *
 * @since 1.1.0
 */

spl_autoload_register( 'remove_noreferrer_autoload' );

function remove_noreferrer_autoload( $class_name ) {
	if ( false === strpos( $class_name, 'Remove_Noreferrer' ) ) {
		return;
	}

	$file_parts = explode( '\\', $class_name );
	$namespace  = '';

	for ( $i = count( $file_parts ) - 1; $i > 0; $i-- ) {
		$current = strtolower( $file_parts[ $i ] );
		$current = str_ireplace( '_', '-', $current );

		if ( count( $file_parts ) - 1 === $i ) {
			$file_name = "class-$current.php";
		} else {
			$namespace = '/' . $current . $namespace;
		}

		// Now build a path to the file using mapping to the file location.
		$filepath  = dirname( dirname( __FILE__ ) ) . $namespace . '/';
		$filepath .= $file_name;
	}

	// If the file exists in the specified path, then include it.
	if ( file_exists( $filepath ) ) {
		include_once( $filepath );
	} else {
		wp_die(
			esc_html( "The file attempting to be loaded at $filepath does not exist." )
		);
	}
}

