<?php
/**
 * Bootstrap
 *
 * @since 1.3.0
 */

require_once __DIR__ . '/../../inc/autoloader.php';
require_once __DIR__ . '/../../vendor/autoload.php';

define( 'WP_TESTS_DIR', __DIR__ . '/../wordpress-dev/src/tests/phpunit/' );
define( 'TEST_PLUGIN_FILE', __DIR__ . '/../../remove-noreferrer.php' );

require_once WP_TESTS_DIR . 'includes/functions.php';

// phpcs:disable Squiz.Commenting.FunctionComment.Missing
function _manually_load_plugin() {
	require TEST_PLUGIN_FILE;
}
// phpcs:enable Squiz.Commenting.FunctionComment.Missing

tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require WP_TESTS_DIR . 'includes/bootstrap.php';
