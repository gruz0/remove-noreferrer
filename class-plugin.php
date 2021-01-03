<?php
/**
 * Plugin class
 *
 * @package Remove_Noreferrer
 * @since 2.0.0
 */

namespace Remove_Noreferrer;

/**
 * Plugin
 *
 * @since 2.0.0
 */
class Plugin extends Base\Plugin {
	/**
	 * Core\Options instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Core\Options $options
	 */
	private $options;

	/**
	 * Core\Adapter instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Core\Adapter $adapter
	 */
	private $adapter;

	/**
	 * Constructor
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param Core\Options $options Options class.
	 * @param Core\Adapter $adapter Adapter class.
	 */
	public function __construct( Core\Options $options, Core\Adapter $adapter ) {
		$this->options = $options;
		$this->adapter = $adapter;

		parent::__construct();
	}

	/**
	 * Executes part of the plugin depends on frontend or admin area
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @return mixed
	 */
	public function run() {
		if ( $this->adapter->is_admin() ) {
			$options_migrator = new Admin\Options_Migrator( $this->options );
			$options_migrator->call( GRN_VERSION );

			$options_page = new Admin\Options_Page();

			return new Admin\Plugin( $this->options, $this->adapter, $options_page );
		}

		$links_processor = new Frontend\Links_Processor();

		return new Frontend\Plugin( $this->options, $links_processor, $this->adapter );
	}
}
