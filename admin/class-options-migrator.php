<?php
/**
 * Options_Migrator class
 *
 * @package Remove_Noreferrer
 * @subpackage Admin
 * @since 2.0.0
 */

namespace Remove_Noreferrer\Admin;

/**
 * Options_Migrator class
 *
 * @since 2.0.0
 */
class Options_Migrator {
	/**
	 * Options instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var \Remove_Noreferrer\Core\Options $options
	 */
	private $options = array();

	/**
	 * Current plugin's version from the database
	 *
	 * @since 2.0.0
	 * @access private
	 * @var mixed $current_version
	 */
	private $current_version = null;

	/**
	 * Contructor
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param \Remove_Noreferrer\Core\Options $options Options class.
	 */
	public function __construct( \Remove_Noreferrer\Core\Options $options ) {
		$this->options = $options;
	}

	/**
	 * Migrates options
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string $new_version New version.
	 */
	public function call( $new_version ) {
		// NOTE: Used for testing purpose only.
		do_action( 'remove_noreferrer_options_migration_started' );

		if ( ! $this->are_options_exist() ) {
			$this->add_default_options();

			return;
		}

		$new_options = $this->get_options();

		$this->set_current_version( $this->get_current_version( $new_options ) );

		if ( $this->is_current_version_higher_or_equal_than_new_version( $new_version ) ) {
			do_action( 'remove_noreferrer_current_version_is_higher_or_equal_than_new_version' );

			return;
		}

		if ( $this->is_current_version_updateable_to( '2.0.0' ) ) {
			$new_options = $this->migrate_to_2_0_0( $new_options );
		}

		$this->update_options( $this->remove_extra_keys( $new_options ) );

		do_action( 'remove_noreferrer_options_migrated' );
	}

	/**
	 * Checks options are exist in the database
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @return boolean
	 */
	private function are_options_exist() {
		return false !== get_option( GRN_OPTION_KEY );
	}

	/**
	 * Adds default options
	 *
	 * @since 2.0.0
	 * @access private
	 */
	private function add_default_options() {
		$this->update_options( $this->get_options() );

		do_action( 'remove_noreferrer_default_options_added' );
	}

	/**
	 * Returns options from the database
	 *
	 * @see \Remove_Noreferrer\Core\Options::get_options
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @return array
	 */
	private function get_options() {
		return $this->options->get_options();
	}

	/**
	 * Stores current version from the database to instance variable
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param string $version Version.
	 */
	private function set_current_version( $version ) {
		$this->current_version = $version;
	}

	/**
	 * Returns current version from the database
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param array $new_options New options.
	 * @return mixed
	 */
	private function get_current_version( $new_options ) {
		return empty( $new_options[ GRN_PLUGIN_VERSION_KEY ] ) ? null : $new_options[ GRN_PLUGIN_VERSION_KEY ];
	}

	/**
	 * Checks difference between current version and new version
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param string $new_version New version.
	 * @return boolean
	 */
	private function is_current_version_higher_or_equal_than_new_version( $new_version ) {
		return version_compare( $this->current_version, $new_version, '>=' );
	}

	/**
	 * Checks is current version required to be update
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param string $new_version New version.
	 * @return boolean
	 */
	private function is_current_version_updateable_to( $new_version ) {
		return version_compare( $this->current_version, $new_version, '<' );
	}

	/**
	 * Removes extra keys
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param array $new_options New options.
	 * @return array
	 */
	private function remove_extra_keys( $new_options ) {
		$diff = array_diff_key( $new_options, $this->get_default_options() );

		return array_diff_key( $new_options, $diff );
	}

	/**
	 * Returns default options
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @return array
	 */
	private function get_default_options() {
		return $this->options->get_default_options();
	}

	/**
	 * Updates options
	 *
	 * @see \Remove_Noreferrer\Core\Options::update_options
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param mixed $new_options New options.
	 */
	private function update_options( $new_options ) {
		$this->options->update_options( $new_options );
	}

	/**
	 * Migrates options to version 2.0.0
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param array $options Options.
	 * @return array
	 */
	private function migrate_to_2_0_0( $options ) {
		$new_version = '2.0.0';

		if ( ! isset( $options[ GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ] ) ) {
			$options[ GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ] = array();
		}

		if ( ! isset( $options[ GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY ] ) ) {
			$options[ GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY ] = '0';
		}

		// NOTE: Do not forget to add this step to the end of the migration.
		return $this->mark_migration_applied( $options, $new_version );
	}

	/**
	 * Marks migration as applied and fires action
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param array  $options     Options.
	 * @param string $new_version New version.
	 * @return array
	 */
	private function mark_migration_applied( $options, $new_version ) {
		$options[ GRN_PLUGIN_VERSION_KEY ] = $new_version;

		$this->set_current_version( $new_version );

		do_action( 'remove_noreferrer_options_migrated_to_' . $new_version );

		return $options;
	}
}

