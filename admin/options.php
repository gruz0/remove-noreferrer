<?php
/**
 * Options Page
 *
 * @package remove-noreferrer
 * @since 1.1.0
 */

?>

<div class="wrap">
	<h1><?php _e( 'Remove Noreferrer', 'remove-noreferrer' ); ?></h1>

	<form method="post" action="<?php echo admin_url( 'admin-post.php' ); ?>">
		<?php wp_nonce_field( GRN_NONCE_ACTION, GRN_NONCE_VALUE ); ?>
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
								gruz0_remove_noreferrer_render_where_should_the_plugin_work(
									'post',
									$options['where_should_the_plugin_work'],
									__( 'Single Post', 'remove-noreferrer' )
								);

								gruz0_remove_noreferrer_render_where_should_the_plugin_work(
									'posts_page',
									$options['where_should_the_plugin_work'],
									__( 'Posts page', 'remove-noreferrer' )
								);

								gruz0_remove_noreferrer_render_where_should_the_plugin_work(
									'page',
									$options['where_should_the_plugin_work'],
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
