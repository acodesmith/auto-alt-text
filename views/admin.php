<div class="wrap">
    <h1>Auto Alt Text Settings <button data-aat-run="batch" class="button">Run Batch</button></h1>

    <form method="post">
        <input type="hidden" id="aat_wpnonce" name="aat_wpnonce" value="<?php echo wp_create_nonce( $nonce_action ) ?>">
        <input type="hidden" name="_wp_http_referer" value="<?php echo $_SERVER['REQUEST_URI'] ; ?>">
        <?php /** @var string $selected_service */ ?>
        <input type="hidden" name="aat_selected_service" value="<?php echo $selected_service ; ?>">
        <table class="form-table">
            <tbody>
            <?php /** @var boolean $has_auth */ ?>
            <?php if( $has_auth ): ?>
                <tr>
                    <td colspan="2">
                        <div class="notice notice-success">
                            <p><?php _e( 'AUTHENTICATION ACTIVE', 'sample-text-domain' ); ?></p>
                        </div>
                        <?php /** @var boolean $has_batched */ ?>
                        <?php if( ! $has_batched ): ?>
                        <div class="notice notice-warning">
                            <p>
                                <?php _e( 'Auto Alt Text <strong>highly</strong> recommends <a class="button thickbox" href="#TB_inline?width=600&height=250&inlineId=batch-thickbox-content" data-aat-run="batch">running a batch</a> on all images.', 'sample-text-domain' ); ?>
                            </p>
                        </div>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endif; ?>
                <tr>
                <th scope="row">
                    AWS Authentication
                    <p class="description">
                        Confirm the AWS IAM user has full rekognition privileges.
                    </p>
                </th>
                <td id="front-static-pages">
                    <fieldset>
                        <legend class="screen-reader-text"><span>AWS Authentication Key and Secret</span></legend>
                        <p>
                            <label>Key</label>
                            <input type="text" name="aat_aws_key" class="large-text" placeholder="XXXXXXXXXXXXXXXXXXXX">
                        </p>
                        <p>
                            <label>Secret</label>
                            <input type="text" name="aat_aws_secret" class="large-text" placeholder="xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx">
                        </p>
                        <div style="display: none;">
                            <hr>
                            <label>Alternative Option: Configuration File</label>
                            <p class="description">
                                Full file path to <code>json</code> authentication file.
                            </p>
                            <input type="text" name="aat_aws_auth_location" class="large-text" placeholder="/var/www/html/aws_auth.json">
                        </div>
                    </fieldset>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="aat_confidence">Confidence</label></th>
                <td>
                    <?php /** @var integer $confidence */ ?>
                    <input name="aat_confidence" type="number" step="1" min="1" max="100" id="aat_confidence" value="<?php echo $confidence; ?>" class="small-text"> %
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="aat_prefix">Alt Tag Prefix</label></th>
                <td>
                    <?php /** @var string $prefix */ ?>
                    <input name="aat_prefix" type="text" id="aat_prefix" value="<?php echo $prefix; ?>" class="medium-text">
                </td>
            </tr>
            </tbody>
        </table>

        <p class="submit">
            <input type="submit" name="submit" id="alt_auto_text_submit" class="button button-primary" value="Save Changes">
        </p>
    </form>
</div>
