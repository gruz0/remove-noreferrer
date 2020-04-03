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

										$this->render_where_should_the_plugin_work(
											'comments',
											__( 'Comments', 'remove-noreferrer' )
										);

										$this->render_where_should_the_plugin_work(
											'text_widget',
											__( 'Text Widget', 'remove-noreferrer' )
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

				<hr />

				<h3>Useful links</h3>

				<p>
					<ul>
						<li>
							* <a href="https://wordpress.org/support/plugin/remove-noreferrer/reviews/" target="_blank" rel="noopener nofollow">
								Let me know what you think about the plugin with a review
							</a>
						</li>
						<li>
							* <a href="mailto:alexander@kadyrov.dev" target="_blank">
								Send me email
							</a>
						</li>
						<li>
							* <a href="https://t.me/gruz0" target="_blank">My Telegram</a>
						</li>
						<li>
							* <a href="https://www.facebook.com/gruz0" target="_blank">My Facebook</a>
						</li>
						<li>
							* <a href="https://twitter.com/gruz0" target="_blank">My Twitter</a>
						</li>
					</ul>
				</p>

				<p>
					<style>.bmc-button img{height: 34px !important;width: 35px !important;margin-bottom: 1px !important;box-shadow: none !important;border: none !important;vertical-align: middle !important;}.bmc-button{padding: 7px 10px 7px 10px !important;line-height: 35px !important;height:51px !important;min-width:217px !important;text-decoration: none !important;display:inline-flex !important;color:#ffffff !important;background-color:#FF813F !important;border-radius: 5px !important;border: 1px solid transparent !important;padding: 7px 10px 7px 10px !important;font-size: 28px !important;letter-spacing:0.6px !important;box-shadow: 0px 1px 2px rgba(190, 190, 190, 0.5) !important;-webkit-box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;margin: 0 auto !important;font-family:'Cookie', cursive !important;-webkit-box-sizing: border-box !important;box-sizing: border-box !important;-o-transition: 0.3s all linear !important;-webkit-transition: 0.3s all linear !important;-moz-transition: 0.3s all linear !important;-ms-transition: 0.3s all linear !important;transition: 0.3s all linear !important;}.bmc-button:hover, .bmc-button:active, .bmc-button:focus {-webkit-box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;text-decoration: none !important;box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;opacity: 0.85 !important;color:#ffffff !important;}</style><link href="https://fonts.googleapis.com/css?family=Cookie" rel="stylesheet"><a class="bmc-button" target="_blank" href="https://www.buymeacoffee.com/gruz0"><img src="https://cdn.buymeacoffee.com/buttons/bmc-new-btn-logo.svg" alt="Buy me a coffee"><span style="margin-left:15px;font-size:28px !important;">Buy me a coffee</span></a>
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

