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
	 * @var Remove_Noreferrer\Core\Options $_options
	 */
	private $_options;

	/**
	 * Remove_Noreferrer\Frontend\Links_Processor instance
	 *
	 * @since 2.0.0
	 * @access private
	 * @var Remove_Noreferrer\Frontend\Links_Processor $_links_processor
	 */
	private $_links_processor;

	/**
	 * Constructor
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @param \Remove_Noreferrer\Core\Options             $options Options class.
	 * @param \Remove_Noreferrer\Frontend\Links_Processor $links_processor Links_Processor class.
	 */
	public function __construct(
		\Remove_Noreferrer\Core\Options $options,
		\Remove_Noreferrer\Frontend\Links_Processor $links_processor
	) {
		$this->_options         = $options;
		$this->_links_processor = $links_processor;

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
		add_filter( 'widget_display_callback', array( & $this, 'remove_noreferrer_from_text_widget' ), 10, 3 );
		add_filter( 'widget_custom_html_content', array( & $this, 'remove_noreferrer_from_custom_html_widget' ), 10, 3 );

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
		if ( ! $this->is_current_page_allowed() ) {
			return $content;
		}

		$filtered_content = $this->remove_noreferrer( $content );
		if ( $this->can_remove_target_blank() ) {
			$filtered_content = $this->remove_target_blank( $filtered_content );
		}
		return $filtered_content;
	}

	/**
	 * Remove noreferrer from comments' links
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @param string          $comment_text Text of the current comment.
	 * @param WP_Comment|null $comment The comment object.
	 * @param array           $args An array of arguments.
	 * @return string
	 */
	public function remove_noreferrer_from_comment( $comment_text, $comment, $args ) {
		if ( ! $this->is_comments_processable( $this->get_option( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ) ) ) {
			return $comment_text;
		}

		return $this->remove_noreferrer( $comment_text );
	}

	/**
	 * Remove noreferrer from Text widget's content
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param array      $instance The current widget instance's settings.
	 * @param \WP_Widget $widget_class The current widget instance.
	 * @param array      $args An array of default widget arguments.
	 * @return mixed
	 */
	public function remove_noreferrer_from_text_widget( $instance, \WP_Widget $widget_class, $args ) {
		if ( ! is_a( $widget_class, 'WP_Widget_Text' ) ) {
			return $instance;
		}

		$processable = $this->is_widgets_processable(
			$this->get_option( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ),
			'text_widget'
		);

		if ( ! $processable ) {
			return $instance;
		}

		ob_start();

		$widget_class->widget( $args, $instance );

		$result = $this->remove_noreferrer( ob_get_clean() );

		echo $result;

		return false;
	}

	/**
	 * Remove noreferrer from Custom HTML widget's content
	 *
	 * @since 2.0.0
	 * @access public
	 *
	 * @param string                 $content The widget content.
	 * @param array                  $instance Array of settings for the current widget.
	 * @param \WP_Widget_Custom_HTML $widget_class Current Custom HTML widget instance.
	 * @return mixed
	 */
	public function remove_noreferrer_from_custom_html_widget( $content, $instance, \WP_Widget_Custom_HTML $widget_class ) {
		$processable = $this->is_widgets_processable(
			$this->get_option( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ),
			'custom_html_widget'
		);

		return $processable ? $this->remove_noreferrer( $content ) : $content;
	}

	/**
	 * Check if current page allowed to use by plugin
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @return bool
	 */
	private function is_current_page_allowed() {
		$where_should_the_plugin_work = $this->get_option( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY );

		return $this->is_single_processable( $where_should_the_plugin_work )
			|| $this->is_page_processable( $where_should_the_plugin_work )
			|| $this->is_posts_page_processable( $where_should_the_plugin_work );
	}

	/**
	 * Checks if the target blank attribute can be removed
	 *
	 * @since 2.0.1
	 * @access private
	 *
	 * @return bool
	 */
	private function can_remove_target_blank() {
		$where_should_the_plugin_work = $this->get_option( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY );

		return in_array( 'target_blank', $where_should_the_plugin_work, true );
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
		return $this->_options->get_option( $key );
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
		return is_single() && in_array( 'post', $options, true );
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
		return is_page() && in_array( 'page', $options, true );
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
		return is_home() && in_array( 'posts_page', $options, true );
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
	private function is_widgets_processable( $options, $key ) {
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
		return $this->_links_processor->call( $content );
	}

	/**
	 * Remove target blank attribute from the links
	 *
	 * @since 2.0.1
	 * @access private
	 *
	 * @param string $content Content.
	 * @return string
	 */
	private function remove_target_blank( $content ) {
		return $this->_links_processor->call( $content, 'target' );
	}
}

