<div class="wrap">
	<h1><?php esc_html_e( 'Auto Alt Text Settings', 'aat' ); ?> <button data-aat-run="batch" class="button"><?php esc_html_e( 'Run Batch', 'aat' ); ?></button></h1>

	<form method="post">
		<input type="hidden" id="aat_wpnonce" name="aat_wpnonce" value="<?php echo wp_create_nonce( $nonce_action ) ?>">
		<input type="hidden" name="_wp_http_referer" value="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ); ?>">
		<?php /** @var string $selected_service */ ?>
		<input type="hidden" name="aat_selected_service" value="<?php echo esc_attr( $selected_service ); ?>">
		<table class="form-table">
			<tbody>
			<?php /** @var boolean $has_auth */ ?>
			<?php if ( $has_auth ) : ?>
				<tr>
					<td colspan="2">
						<div class="notice notice-success">
							<p><?php esc_html_e( 'AUTHENTICATION ACTIVE', 'aat' ); ?></p>
						</div>
						<?php /** @var boolean $has_batched */ ?>
						<?php if ( ! $has_batched ) : ?>
						<div class="notice notice-warning">
							<p>
								<?php echo esc_html( 'Auto Alt Text <strong>highly</strong> recommends <a class="button thickbox" href="#TB_inline?width=600&height=250&inlineId=batch-thickbox-content" data-aat-run="batch">running a batch</a> on all images.', 'aat' ); ?>
							</p>
						</div>
						<?php endif; ?>
					</td>
				</tr>
			<?php endif; ?>
				<tr>
				<th scope="row">
					<?php esc_html_e( 'AWS Authentication', 'aat' ); ?>
					<p class="description">
						<?php esc_html_e( 'Confirm the AWS IAM user has full rekognition privileges.', 'aat' ); ?>
					</p>
				</th>
				<td id="front-static-pages">
					<fieldset>
						<legend class="screen-reader-text"><span><?php esc_html_e( 'AWS Authentication Key and Secret', 'aat' ); ?></span></legend>
						<p>
							<label><?php esc_html_e( 'Key', 'aat' ); ?></label>
							<input type="text" name="aat_aws_key" class="large-text" placeholder="XXXXXXXXXXXXXXXXXXXX">
						</p>
						<p>
							<label><?php esc_html_e( 'Secret', 'aat' ); ?></label>
							<input type="text" name="aat_aws_secret" class="large-text" placeholder="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx">
						</p>
						<div style="display: none;">
							<hr>
							<label><?php esc_html_e( 'Alternative Option: Configuration File', 'aat' ); ?></label>
							<p class="description">
								Full file path to <code>json</code> authentication file.
							</p>
							<input type="text" name="aat_aws_auth_location" class="large-text" placeholder="/var/www/html/aws_auth.json">
						</div>
					</fieldset>
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="aat_confidence"><?php esc_html_e( 'Confidence', 'aat' ); ?></label></th>
				<td>
					<?php /** @var integer $confidence */ ?>
					<input name="aat_confidence" type="number" step="1" min="1" max="100" id="aat_confidence" value="<?php echo esc_attr( $confidence ); ?>" class="small-text"> %
				</td>
			</tr>
			<tr>
				<th scope="row"><label for="aat_prefix"><?php esc_html_e( 'Alt Tag Prefix', 'aat' ); ?></label></th>
				<td>
					<?php /** @var string $prefix */ ?>
					<input name="aat_prefix" type="text" id="aat_prefix" value="<?php echo esc_attr( $prefix ); ?>" class="medium-text">
				</td>
			</tr>
			</tbody>
		</table>

		<p class="submit">
			<input type="submit" name="submit" id="alt_auto_text_submit" class="button button-primary" value="<?php esc_attr_e( 'Save Changes', 'aat' ); ?>">
		</p>
	</form>
</div>
