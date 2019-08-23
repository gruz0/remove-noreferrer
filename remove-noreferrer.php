<?php
/*
Plugin Name: Remove noreferrer
Description: This plugin removes rel="noreferrer" from post's links on the frontend
Author: Alexander Kadyrov
Author URI: https://github.com/gruz0
Text Domain: remove-noreferrer
Version: 1.1.0
*/

define( 'GRN_PARENT_SLUG', 'options-general.php' );
define( 'GRN_MENU_SLUG', 'remove_noreferrer' );
define( 'GRN_NONCE_VALUE', 'gruz0_remove_noreferrer_nonce' );
define( 'GRN_NONCE_ACTION', 'remove_noreferrer' );

/**
 * Remove referrer from "rel" attribute
 *
 * @since 1.0.0
 *
 * @param string $content Content.
 * @return string
 */
function gruz0_remove_noreferrer( $content ) {
	if ( ! gruz0_remove_noreferrer_is_current_page_allowed() ) {
		return $content;
	}

	$replace = function( $matches ) {
		return sprintf( 'rel="%s"', trim( preg_replace( '/noreferrer\s*/i', '', $matches[1] ) ) );
	};

	return preg_replace_callback( '/rel="([^\"]+)"/i', $replace, $content );
}
add_filter( 'the_content', 'gruz0_remove_noreferrer', 999 );

/**
 * Check if current page allowed to use by plugin
 *
 * @since 1.1.0
 *
 * @return bool
 */
function gruz0_remove_noreferrer_is_current_page_allowed() {
	$options                      = get_option( 'remove_noreferrer', gruz0_remove_noreferrer_default_options() );
	$where_should_the_plugin_work = $options['where_should_the_plugin_work'];

	return gruz0_remove_noreferrer_is_single( $where_should_the_plugin_work )
		|| gruz0_remove_noreferrer_is_page( $where_should_the_plugin_work )
		|| gruz0_remove_noreferrer_is_posts_page( $where_should_the_plugin_work );
}

/**
 * Checks if current page is a post and array contains `post`
 *
 * @since 1.1.0
 *
 * @param array $options Options array.
 * @return bool
 */
function gruz0_remove_noreferrer_is_single( $options ) {
	return is_single() && in_array( 'post', $options, true );
}

/**
 * Checks if current page is a page and array contains `page`
 *
 * @since 1.1.0
 *
 * @param array $options Options array.
 * @return bool
 */
function gruz0_remove_noreferrer_is_page( $options ) {
	return is_page() && in_array( 'page', $options, true );
}

/**
 * Checks if current page is a posts page and array contains `posts_page`
 *
 * @since 1.1.0
 *
 * @param array $options Options array.
 * @return bool
 */
function gruz0_remove_noreferrer_is_posts_page( $options ) {
	return is_home() && in_array( 'posts_page', $options, true );
}

/**
 * Load options page template
 *
 * @since 1.1.0
 */
function gruz0_remove_noreferrer_update_options() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'Unauthorized user' );
	}

	if ( ! wp_verify_nonce( $_POST[ GRN_NONCE_VALUE ], GRN_NONCE_ACTION ) ) {
		wp_die( __( 'Invalid nonce', 'remove-noreferrer' ) );
	}

	update_option( 'remove_noreferrer', gruz0_remove_noreferrer_validate_options() );

	wp_redirect( admin_url( GRN_PARENT_SLUG . '?page=' . GRN_MENU_SLUG ), 303 );
	exit;
}
add_action( 'admin_post_remove_noreferrer_update_options', 'gruz0_remove_noreferrer_update_options' );

/**
 * Render form
 *
 * @since 1.1.0
 */
function gruz0_remove_noreferrer_show_form() {
	$options = get_option( 'remove_noreferrer', gruz0_remove_noreferrer_default_options() );

	include_once 'admin/options.php';
}

/**
 * Return plugin's default options if options are not found in the database
 *
 * @since 1.1.0
 *
 * @return array
 */
function gruz0_remove_noreferrer_default_options() {
	return [
		'where_should_the_plugin_work' => [ 'post', 'posts_page', 'page' ],
	];
}

/**
 * Return array with empty options
 *
 * @since 1.1.0
 *
 * @return array
 */
function gruz0_remove_noreferrer_unset_options() {
	return [
		'where_should_the_plugin_work' => [],
	];
}

/**
 * Add options page to General menu
 *
 * @since 1.1.0
 */
function gruz0_remove_noreferrer_options_page() {
	$page_title = __( 'Remove Noreferrer Options', 'remove-noreferrer' );
	$menu_title = __( 'Remove Noreferrer', 'remove-noreferrer' );
	$capability = 'manage_options';
	$function   = 'gruz0_remove_noreferrer_show_form';

	add_submenu_page( GRN_PARENT_SLUG, $page_title, $menu_title, $capability, GRN_MENU_SLUG, $function );
}
add_action( 'admin_menu', 'gruz0_remove_noreferrer_options_page' );

/**
 * Validate and sanitize options
 *
 * @since 1.1.0
 *
 * @return array
 */
function gruz0_remove_noreferrer_validate_options() {
	if ( empty( $_POST['remove_noreferrer'] ) ) {
		return gruz0_remove_noreferrer_unset_options();
	}

	$input = (array) $_POST['remove_noreferrer'];

	return [
		'where_should_the_plugin_work' => gruz0_remove_noreferrer_validate_where_should_the_plugin_work( $input ),
	];
}

/**
 * Validate `where_should_the_plugin_work` option
 *
 * @since 1.1.0
 *
 * @param array $input Input values.
 * @return array
 */
function gruz0_remove_noreferrer_validate_where_should_the_plugin_work( $input ) {
	if ( empty( $input['where_should_the_plugin_work'] ) ) {
		return [];
	}

	$result         = [];
	$allowed_values = [ 'post', 'posts_page', 'page' ];

	foreach ( $allowed_values as $value ) {
		if ( ! in_array( $value, $input['where_should_the_plugin_work'], true ) ) {
			continue;
		}

		$result[] = $value;
	}

	// @todo: Add array_unique
	return $result;
}

/**
 * Render checkbox for option `where_should_the_plugin_work`
 *
 * @since 1.1.0
 *
 * @param string $value   Item's value.
 * @param array  $options Options array.
 * @param string $label   Checkbox's label.
 */
function gruz0_remove_noreferrer_render_where_should_the_plugin_work( $value, $options, $label ) {
	?>
	<label>
		<input
			type="checkbox"
			name="remove_noreferrer[where_should_the_plugin_work][]"
			value="<?php echo esc_attr( $value ); ?>"
			<?php checked( in_array( $value, $options, true ), true ); ?>
		/>
		<?php echo esc_html( $label ); ?>
	</label>
	<br />
	<?php
}

