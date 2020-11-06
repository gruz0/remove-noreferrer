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
	 * Render options page
	 *
	 * @since 1.1.0
	 * @access public
	 *
	 * @param array  $options Options.
	 * @param string $tab Tab.
	 * @return string
	 */
	public function render( $options, $tab ) {
		ob_start();
		?>
		<div class="wrap">
			<h1>Remove Noreferrer</h1>

			<?php
			$this->render_tabs( $tab );

			switch ( $tab ) {
				case 'additional-settings':
					$this->render_additional_settings_tab( $tab, $options );

					break;

				case 'support':
					$this->render_support_tab();

					break;

				default:
					$this->render_general_tab( $tab, $options );

					break;
			}
			?>

		</div>

		<hr />

		<?php $this->render_buy_me_a_coffee(); ?>

		<?php
			return ob_get_clean();
	}

	/**
	 * Render navigation tabs
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param string $current Current tab.
	 */
	private function render_tabs( $current = 'general' ) {
		$tabs = array(
			'general'             => __( 'General' ),
			'additional-settings' => __( 'Additional settings' ),
			'support'             => __( 'Support' ),
		);

		echo '<h2 class="nav-tab-wrapper">';

		foreach ( $tabs as $tab => $name ) {
			$class = ( $tab === $current ) ? ' nav-tab-active' : '';

			$url = add_query_arg(
				array(
					'page' => Plugin::GRN_MENU_SLUG,
					'tab'  => $tab,
				),
				admin_url( Plugin::GRN_PARENT_SLUG )
			);

			$format = '<a class="nav-tab%s" href="%s">%s</a>';

			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			echo sprintf( $format, esc_attr( $class ), esc_url( $url ), esc_html( $name ) );
			// phpcs:enable WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		echo '</h2>';
	}

	/**
	 * Render General tab
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param string $tab     Current tab.
	 * @param array  $options Options.
	 */
	private function render_general_tab( $tab, $options ) {
		ob_start();
		?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<?php echo esc_html__( 'Posts' ) . ', ' . esc_html__( 'Pages' ); ?>
					</th>
					<td>
						<fieldset>
							<?php
								$this->render_checkbox_where_should_the_plugin_work(
									$options,
									'post',
									__( 'Post' )
								);

								$this->render_checkbox_where_should_the_plugin_work(
									$options,
									'posts_page',
									__( 'Posts Page' )
								);

								$this->render_checkbox_where_should_the_plugin_work(
									$options,
									'page',
									__( 'Single Page' )
								);

								$this->render_checkbox_where_should_the_plugin_work(
									$options,
									'comments',
									__( 'Comments' )
								);
							?>
						</fieldset>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<?php echo esc_html( __( 'Widgets' ) ); ?>
					</th>
					<td>
						<fieldset>
							<?php
								$this->render_checkbox_where_should_the_plugin_work(
									$options,
									'text_widget',
									__( 'Text' )
								);

								$this->render_checkbox_where_should_the_plugin_work(
									$options,
									'custom_html_widget',
									__( 'Custom HTML' )
								);
							?>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
		$this->render_form( $tab, ob_get_clean() );
	}

	/**
	 * Render Additional settings tab
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param string $tab     Current tab.
	 * @param array  $options Options.
	 */
	private function render_additional_settings_tab( $tab, $options ) {
		ob_start();
		?>
		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<?php echo esc_html__( 'Remove Settings on Uninstall', 'remove-noreferrer' ); ?>
					</th>
					<td>
						<fieldset>
							<?php $this->render_checkbox_remove_settings_on_uninstall( $options, '1' ); ?>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
		<?php
		$this->render_form( $tab, ob_get_clean() );
	}

	/**
	 * Render form
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param string $tab     Current tab.
	 * @param string $content Form content.
	 */
	private function render_form( $tab, $content ) {
		$url = add_query_arg( array( 'tab' => $tab ), admin_url( 'admin-post.php' ) );
		?>
		<form method="post" action="<?php echo esc_url_raw( $url ); ?>">
			<?php $this->render_nonce(); ?>
			<?php $this->render_action(); ?>
			<?php $this->render_hidden_grn_tab( $tab ); ?>

			<?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>

			<p class="submit">
				<input type="submit" value="<?php echo esc_attr( __( 'Save Changes' ) ); ?>" class="button button-primary button-large">
			</p>
		</form>
		<?php
	}

	/**
	 * Render Support tab
	 *
	 * @since 2.0.0
	 * @access private
	 */
	private function render_support_tab() {
		?>
		<p><strong>This plugin is an open source project and I would love you to help me make it better.</strong></p>

		<p><a href="https://wordpress.org/support/plugin/remove-noreferrer/reviews/#new-post" target="_blank" rel="noreferrer nofollow" class="button button-primary">Rate the plugin! 😍</a></p>

		<p>If you want a new feature will be implemented in this plugin, you can open a <a href="https://github.com/gruz0/remove-noreferrer/issues/new" target="_blank" rel="noreferrer noopener">GitHub Issue</a>.<br />If you don't have a GitHub Account you can send me email to <a href="mailto:alexander@kadyrov.dev" target="_blank">alexander@kadyrov.dev</a>.</p>

		<p>You can find more detailed information about the plugin on <a href="https://wordpress.org/plugins/remove-noreferrer/" target="_blank" rel="noreferrer nofollow">WordPress website</a>.</p>
		<?php
	}

	/**
	 * Render buymeacoffee.com banner
	 *
	 * @since 2.0.0
	 * @access private
	 */
	private function render_buy_me_a_coffee() {
		// phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
		?>
		<p>
			<style>.bmc-button img{height: 34px !important;width: 35px !important;margin-bottom: 1px !important;box-shadow: none !important;border: none !important;vertical-align: middle !important;}.bmc-button{padding: 7px 10px 7px 10px !important;line-height: 35px !important;height:51px !important;min-width:217px !important;text-decoration: none !important;display:inline-flex !important;color:#ffffff !important;background-color:#FF813F !important;border-radius: 5px !important;border: 1px solid transparent !important;padding: 7px 10px 7px 10px !important;font-size: 28px !important;letter-spacing:0.6px !important;box-shadow: 0px 1px 2px rgba(190, 190, 190, 0.5) !important;-webkit-box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;margin: 0 auto !important;font-family:'Cookie', cursive !important;-webkit-box-sizing: border-box !important;box-sizing: border-box !important;-o-transition: 0.3s all linear !important;-webkit-transition: 0.3s all linear !important;-moz-transition: 0.3s all linear !important;-ms-transition: 0.3s all linear !important;transition: 0.3s all linear !important;}.bmc-button:hover, .bmc-button:active, .bmc-button:focus {-webkit-box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;text-decoration: none !important;box-shadow: 0px 1px 2px 2px rgba(190, 190, 190, 0.5) !important;opacity: 0.85 !important;color:#ffffff !important;}</style><link href="https://fonts.googleapis.com/css?family=Cookie" rel="stylesheet"><a class="bmc-button" target="_blank" href="https://www.buymeacoffee.com/gruz0"><img src="https://cdn.buymeacoffee.com/buttons/bmc-new-btn-logo.svg" alt="Buy me a coffee"><span style="margin-left:15px;font-size:28px !important;">Buy me a coffee</span></a>
		</p>
		<?php
		// phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedStylesheet
	}

	/**
	 * Render nonce field
	 *
	 * @since 2.0.0
	 * @access private
	 */
	private function render_nonce() {
		wp_nonce_field( Plugin::GRN_NONCE_ACTION, Plugin::GRN_NONCE_VALUE );
	}

	/**
	 * Renders hidden field with hook's action
	 *
	 * @since 2.0.0
	 * @access private
	 */
	private function render_action() {
		echo '<input type="hidden" name="action" value="remove_noreferrer_update_options" />';
	}

	/**
	 * Renders hidden field with current tab value
	 *
	 * @since 2.0.0
	 * @access private
	 *
	 * @param string $tab Current tab.
	 */
	private function render_hidden_grn_tab( $tab ) {
		echo '<input type="hidden" name="remove_noreferrer[grn_tab]" value="' . esc_attr( $tab ) . '" />';
	}

	/**
	 * Render checkbox for option `where_should_the_plugin_work`
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param array  $options Options.
	 * @param string $value Item's value.
	 * @param string $label Checkbox's label.
	 */
	private function render_checkbox_where_should_the_plugin_work( $options, $value, $label ) {
		?>
			<label>
				<input
					type="checkbox"
					name="remove_noreferrer[<?php echo esc_attr( GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ); ?>][]"
					value="<?php echo esc_attr( $value ); ?>"
					<?php
						checked(
							in_array( $value, $options[ GRN_WHERE_SHOULD_THE_PLUGIN_WORK_KEY ], true ),
							true
						);
					?>
				/>
				<?php echo esc_html( $label ); ?>
			</label>
			<br />
		<?php
	}

	/**
	 * Render checkbox for option `remove_settings_on_uninstall`
	 *
	 * @since 1.1.0
	 * @access private
	 *
	 * @param array  $options Options.
	 * @param string $value Item's value.
	 */
	private function render_checkbox_remove_settings_on_uninstall( $options, $value ) {
		?>
			<label>
				<input
					type="checkbox"
					name="remove_noreferrer[<?php echo esc_attr( GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY ); ?>]"
					value="<?php echo esc_attr( $value ); ?>"
					<?php checked( $value === $options[ GRN_REMOVE_SETTINGS_ON_UNINSTALL_KEY ], true ); ?>
				/>
			</label>
		<?php
	}
}

