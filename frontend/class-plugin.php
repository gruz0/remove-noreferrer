<?php
/**
 * Frontend's part of the plugin : Plugin class
 *
 * Removes rel attributes from the links
 *
 * @package Remove_Noreferrer
 * @subpackage Frontend
 * @since 1.1.0
 */

namespace Remove_Noreferrer\Frontend;

/**
 * Frontend's part of the plugin
 *
 * @since 1.1.0
 */
class Plugin extends \Remove_Noreferrer\Base\Plugin {
	/**
	 * Remove_Noreferrer\Core\Options instance
	 *
	 * @since 1.1.1
	 * @access private
	 * @var Remove_Noreferrer\Core\Options $options
	 */
	private $options;

	/**
	 * Remove_Noreferrer\Frontend\Links_Processor instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Remove_Noreferrer\Frontend\Links_Processor $links_processor
	 */
	private $links_processor;

	/**
	 * Remove_Noreferrer\Core\Adapter instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Remove_Noreferrer\Core\Adapter $adapter
	 */
	private $adapter;

	/**
	 * Cached value of an option `where_should_the_plugin_work`
	 *
	 * @since 2.0.0
	 * @access private
	 * @var mixed $where_should_the_plugin_work
	 */
	private $where_should_the_plugin_work = null;

	/**
	 * Constructor
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @param \Remove_Noreferrer\Core\Options             $options Options class.
	 * @param \Remove_Noreferrer\Frontend\Links_Processor $links_processor Links_Processor class.
	 * @param \Remove_Noreferrer\Core\Adapter             $adapter Adapter class.
	 */
	public function __construct(
		\Remove_Noreferrer\Core\Options $options,
		\Remove_Noreferrer\Frontend\Links_Processor $links_processor,
		\Remove_Noreferrer\Core\Adapter $adapter
	) {
		$this->options         = $options;
		$this->links_processor = $links_processor;
		$this->adapter         = $adapter;

		parent::__construct();
	}

	/**
	 * Initializes plugin
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function init() {
		add_filter( 'the_content', array( & $this, 'remove_noreferrer_from_content' ), 999 );
		add_filter( 'comment_text', array( & $this, 'remove_noreferrer_from_comment' ), 20, 3 );
		add_filter( 'widget_display_callback', array( & $this, 'remove_noreferrer_from_widgets' ), 10, 3 );

		parent::init();
	}

	/**
	 * Remove noreferrer from posts, pages and home page
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $content Content.
	 * @return string
	 */
	public function remove_noreferrer_from_content( $content ) {
		$options = $this->where_should_the_plugin_work();

		if ( empty( $options ) ) {
			return $content;
		}

		if ( ! $this->is_current_page_allowed( $options ) ) {
			return $content;
		}

		return $this->remove_noreferrer( $content );
	}

	/**
	 * Remove noreferrer from comments' links
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param string $comment_text Text of the current comment.
	 * @return string
	 */
	public function remove_noreferrer_from_comment( $comment_text ) {
		$options = $this->where_should_the_plugin_work();

		if ( empty( $options ) ) {
			return $comment_text;
		}

		if ( ! $this->is_comments_processable( $options ) ) {
			return $comment_text;
		}

		return $this->remove_noreferrer( $comment_text );
	}

	/**
	 * Remove noreferrer from selected widgets
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array      $instance The current widget instance's settings.
	 * @param \WP_Widget $widget_class The current widget instance.
	 * @param array      $args An array of default widget arguments.
	 * @return mixed
	 */
	public function remove_noreferrer_from_widgets( $instance, \WP_Widget $widget_class, $args ) {
		$options = $this->where_should_the_plugin_work();

		if ( empty( $options ) ) {
			return $instance;
		}

		$class_name        = get_class( $widget_class );
		$supported_widgets = array( 'WP_Widget_Text', 'WP_Widget_Custom_HTML' );

		if ( ! in_array( $class_name, $supported_widgets, true ) ) {
			return $instance;
		}

		$processable = false;

		switch ( $class_name ) {
			case 'WP_Widget_Text':
				$processable = $this->is_widget_processable( $options, 'text_widget' );
				break;

			case 'WP_Widget_Custom_HTML':
				$processable = $this->is_widget_processable( $options, 'custom_html_widget' );
				break;
		}

		if ( ! $processable ) {
			return $instance;
		}

		ob_start();

		$widget_class->widget( $args, $instance );

		$result = $this->remove_noreferrer( ob_get_clean() );

		echo $result; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		return false;
	}

	/**
	 * Check if current page allowed to use by plugin
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param array $options Options array.
	 * @return bool
	 */
	private function is_current_page_allowed( $options ) {
		return $this->is_single_processable( $options )
			|| $this->is_page_processable( $options )
			|| $this->is_posts_page_processable( $options );
	}

	/**
	 * Gets and validates option `where_should_the_plugin_work`
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @return array
	 */
	private function where_should_the_plugin_work() {
		if ( ! is_array( $this->where_should_the_plugin_work ) ) {
			$option = $this->get_option( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY );

			$this->where_should_the_plugin_work = is_array( $option ) ? $option : array();
		}

		return $this->where_should_the_plugin_work;
	}

	/**
	 * Return option's value by key
	 *
	 * @since 1.1.1
	 * @access private
	 *
	 * @param string $key Key.
	 * @return mixed
	 */
	private function get_option( $key ) {
		return $this->options->get_option( $key );
	}

	/**
	 * Checks if current page is a post and array contains `post`
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param array $options Options array.
	 * @return bool
	 */
	private function is_single_processable( $options ) {
		return $this->adapter->is_single() && in_array( 'post', $options, true );
	}

	/**
	 * Checks if current page is a page and array contains `page`
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param array $options Options array.
	 * @return bool
	 */
	private function is_page_processable( $options ) {
		return $this->adapter->is_page() && in_array( 'page', $options, true );
	}

	/**
	 * Checks if current page is a posts page and array contains `posts_page`
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param array $options Options array.
	 * @return bool
	 */
	private function is_posts_page_processable( $options ) {
		return $this->adapter->is_posts_page() && in_array( 'posts_page', $options, true );
	}

	/**
	 * Checks if array contains `comments`
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @param array $options Options array.
	 * @return bool
	 */
	private function is_comments_processable( $options ) {
		return in_array( 'comments', $options, true );
	}

	/**
	 * Checks if array contains widgets' options
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param array  $options Options array.
	 * @param string $key Option key.
	 * @return bool
	 */
	private function is_widget_processable( $options, $key ) {
		return in_array( $key, $options, true );
	}

	/**
	 * Remove noreferrer from the links
	 *
	 * @since 1.2.0
	 * @access private
	 *
	 * @param string $content Content.
	 * @return string
	 */
	private function remove_noreferrer( $content ) {
		return $this->links_processor->call( $content );
	}
}

