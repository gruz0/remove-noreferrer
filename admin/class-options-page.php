<?php
/**
 * Options Page: Options_Page class
 *
 * Renders plugin's options
 *
 * @package Remove_Noreferrer
 * @subpackage Admin
 * @since 1.1.0
 */

namespace Remove_Noreferrer\Admin;

/**
 * Options Page
 *
 * @since 1.1.0
 */
class Options_Page {
	/**
	 * Options
	 *
	 * @since 1.1.0
	 * @access private
	 * @var array $_options
	 */
	private $_options = array();

	/**
	 * Constructor
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @param array $options Options.
	 */
	public function __construct( $options ) {
		$this->_options = $options;
	}

	/**
	 * Render options page
	 *
	 * @since 1.1.0
	 * @access public
	 */
	public function render() {
		?>
		<div class="wrap">
			<h1><?php _e( 'Remove Noreferrer', 'remove-noreferrer' ); ?></h1>

			<form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
				<?php wp_nonce_field( Plugin::GRN_NONCE_ACTION, Plugin::GRN_NONCE_VALUE ); ?>
				<input type="hidden" name="action" value="remove_noreferrer_update_options">

				<table class="form-table">
					<tbody>
						<tr>
							<th scope="row">
								<?php _e( 'Where should the plugin work?', 'remove-noreferrer' ); ?>
							</th>
							<td>
								<fieldset>
									<?php
										$this->render_where_should_the_plugin_work(
											'post',
											__( 'Single Post', 'remove-noreferrer' )
										);

										$this->render_where_should_the_plugin_work(
											'posts_page',
											__( 'Posts page (Home Page, etc.)', 'remove-noreferrer' )
										);

										$this->render_where_should_the_plugin_work(
											'page',
											__( 'Single Page', 'remove-noreferrer' )
										);
									?>
								</fieldset>
							</td>
						</tr>
					</tbody>
				</table>

				<p class="submit">
					<input type="submit" value="<?php _e( 'Save', 'remove-noreferrer' ); ?>" class="button button-primary button-large">
				</p>
			</form>
		</div>
		<?php
	}

	/**
	 * Render checkbox for option `where_should_the_plugin_work`
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param string $value Item's value.
	 * @param string $label Checkbox's label.
	 */
	private function render_where_should_the_plugin_work( $value, $label ) {
		?>
			<label>
				<input
					type="checkbox"
					name="remove_noreferrer[where_should_the_plugin_work][]"
					value="<?php echo esc_attr( $value ); ?>"
					<?php checked( in_array( $value, $this->_options['where_should_the_plugin_work'], true ), true ); ?>
				/>
				<?php echo esc_html( $label ); ?>
			</label>
			<br />
		<?php
	}
}

