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
class Plugin {
	/**
	 * Remove_Noreferrer\Core\Options instance
	 *
	 * @since 1.1.1
	 * @access private
	 * @static
	 * @var Remove_Noreferrer\Core\Options $_options
	 */
	private static $_options;

	/**
	 * Remove_Noreferrer\Frontend\Links_Processor instance
	 *
	 * @since 1.3.0
	 * @access private
	 * @static
	 * @var Remove_Noreferrer\Frontend\Links_Processor $_links_processor
	 */
	private static $_links_processor;

	/**
	 * Constructor
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @param Remove_Noreferrer\Core\Options             $options Options class.
	 * @param Remove_Noreferrer\Frontend\Links_Processor $links_processor Links_Processor class.
	 */
	public function __construct( $options, $links_processor ) {
		self::$_options         = $options;
		self::$_links_processor = $links_processor;
	}

	/**
	 * Remove noreferrer from posts, pages and home page
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param string $content Content.
	 * @return string
	 */
	public static function remove_noreferrer_from_content( $content ) {
		if ( ! self::is_current_page_allowed() ) {
			return $content;
		}

		return self::remove_noreferrer( $content );
	}

	/**
	 * Remove noreferrer from comments' links
	 *
	 * @since 1.2.0
	 * @access public
	 * @static
	 *
	 * @param string          $comment_text Text of the current comment.
	 * @param WP_Comment|null $comment The comment object.
	 * @param array           $args An array of arguments.
	 * @return string
	 */
	public static function remove_noreferrer_from_comment( $comment_text, $comment, $args ) {
		if ( ! self::is_comments_processable( self::get_option( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ) ) ) {
			return $comment_text;
		}

		return self::remove_noreferrer( $comment_text );
	}

	/**
	 * Remove noreferrer from Text widget's content
	 *
	 * @since 1.3.0
	 * @access public
	 * @static
	 *
	 * @param array     $instance The current widget instance's settings.
	 * @param WP_Widget $widget_class The current widget instance.
	 * @param array     $args An array of default widget arguments.
	 * @return mixed
	 */
	public static function remove_noreferrer_from_text_widget( $instance, $widget_class, $args ) {
		if ( ! is_a( $widget_class, 'WP_Widget_Text' ) ) {
			return $instance;
		}

		$processable = self::is_widgets_processable(
			self::get_option( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ),
			'text_widget'
		);

		if ( ! $processable ) {
			return $instance;
		}

		ob_start();

		$widget_class->widget( $args, $instance );

		$result = self::remove_noreferrer( ob_get_clean() );

		echo $result;

		return false;
	}

	/**
	 * Remove noreferrer from Custom HTML widget's content
	 *
	 * @since 1.3.0
	 * @access public
	 * @static
	 *
	 * @param string                $content The widget content.
	 * @param array                 $instance Array of settings for the current widget.
	 * @param WP_Widget_Custom_HTML $widget_class Current Custom HTML widget instance.
	 * @return mixed
	 */
	public static function remove_noreferrer_from_custom_html_widget( $content, $instance, $widget_class ) {
		$processable = self::is_widgets_processable(
			self::get_option( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ),
			'custom_html_widget'
		);

		return $processable ? self::remove_noreferrer( $content ) : $content;
	}

	/**
	 * Check if current page allowed to use by plugin
	 *
	 * @since 1.1.0
	 * @access private
	 * @static
	 *
	 * @return bool
	 */
	private static function is_current_page_allowed() {
		$where_should_the_plugin_work = self::get_option( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY );

		return self::is_single_processable( $where_should_the_plugin_work )
			|| self::is_page_processable( $where_should_the_plugin_work )
			|| self::is_posts_page_processable( $where_should_the_plugin_work );
	}

	/**
	 * Return option's value by key
	 *
	 * @since 1.1.1
	 * @access private
	 * @static
	 *
	 * @param string $key Key.
	 * @return mixed
	 */
	private static function get_option( $key ) {
		return self::$_options->get_option( $key );
	}

	/**
	 * Checks if current page is a post and array contains `post`
	 *
	 * @since 1.1.0
	 * @access private
	 * @static
	 *
	 * @param array $options Options array.
	 * @return bool
	 */
	private static function is_single_processable( $options ) {
		return is_single() && in_array( 'post', $options, true );
	}

	/**
	 * Checks if current page is a page and array contains `page`
	 *
	 * @since 1.1.0
	 * @access private
	 * @static
	 *
	 * @param array $options Options array.
	 * @return bool
	 */
	private static function is_page_processable( $options ) {
		return is_page() && in_array( 'page', $options, true );
	}

	/**
	 * Checks if current page is a posts page and array contains `posts_page`
	 *
	 * @since 1.1.0
	 * @access private
	 * @static
	 *
	 * @param array $options Options array.
	 * @return bool
	 */
	private static function is_posts_page_processable( $options ) {
		return is_home() && in_array( 'posts_page', $options, true );
	}

	/**
	 * Checks if array contains `comments`
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @param array $options Options array.
	 * @return bool
	 */
	private static function is_comments_processable( $options ) {
		return in_array( 'comments', $options, true );
	}

	/**
	 * Checks if array contains widgets' options
	 *
	 * @since 1.3.0
	 * @access private
	 * @static
	 *
	 * @param array  $options Options array.
	 * @param string $key Option key.
	 * @return bool
	 */
	private static function is_widgets_processable( $options, $key ) {
		return in_array( $key, $options, true );
	}

	/**
	 * Remove noreferrer from the links
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @param string $content Content.
	 * @return string
	 */
	private static function remove_noreferrer( $content ) {
		return self::$_links_processor->call( $content );
	}
}

