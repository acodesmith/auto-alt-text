<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
    exit;

class Auto_Alt_Text_Admin
{
    const HAS_RAN_BATCH_ONCE_NAMESPACE = '_aat_has_ran_batch';

    /**
     * @return bool
     */
    public static function has_ran_batch_at_least_once()
    {
        return ! empty( get_option( self::HAS_RAN_BATCH_ONCE_NAMESPACE ) );
    }

    /**
     * Enqueue all the needed scripts for admin styles and interactions.
     * Using the WordPress thickbox include.
     *
     * @dependency jQuery, thickbox
     */
    public static function scripts()
    {
        add_thickbox();
        wp_enqueue_script('aat-admin-js', AAT_PLUGIN_URI . 'assets/admin.js', ['jquery'], '0.0.1', true);
        wp_enqueue_style('aat-admin-styles', AAT_PLUGIN_URI . 'assets/admin.css', [], '0.0.1' );
    }

    /**
     * Handle all the $_POST data from the admin form.
     * Check the nonce and then process the postmeta and options data
     */
    public static function process_post()
    {
        //First validate our nonce
        if (!empty($_POST['aat_wpnonce']) && wp_verify_nonce($_POST['aat_wpnonce'], Auto_Alt_Text_Common::NONCE_NAMESPACE)) {

            if( ! empty( $_POST['aat_selected_service'] ) ) {

                self::processServiceAuth( $_POST['aat_selected_service'] );
            }

            if ( ! empty( $_POST['aat_confidence'] ) ) {

                $confidence = intval($_POST['aat_confidence']);

                if ($confidence > 1 && $confidence < 101) {
                    update_option(Auto_Alt_Text_Common::CONFIDENCE_NAMESPACE, $confidence);
                }
            }

            if ( ! empty( $_POST['aat_prefix'] ) ) {

                update_option(Auto_Alt_Text_Common::ALT_PREFIX_NAMESPACE, sanitize_text_field( $_POST['aat_prefix'] ) );
            }
        }
    }

    /**
     * Save Authentication information based upon the selected service.
     * Currently only supports AWS
     *
     * @todo integrate MS AI Authentication
     *
     * @param $selected_service
     */
    public static function processServiceAuth( $selected_service)
    {

        Alt_Text_Service_Switch::$service = $selected_service;

        switch( $selected_service ) {

            case Alt_Text_Service_Switch::SERVICE_AWS:

                //Check for AWS Key and Secret
                if ( ! empty( $_POST['aat_aws_key'] ) && ! empty( $_POST['aat_aws_secret'] ) ) {

                    if( ! class_exists( 'Auto_Alt_Text_Aws' ) )
                        Alt_Text_Service_Switch::loadClass();

                    update_option(Auto_Alt_Text_Aws::AWS_KEY_NAMESPACE,  sanitize_text_field( $_POST['aat_aws_key'] ) );
                    update_option(Auto_Alt_Text_Aws::AWS_SECRET_NAMESPACE, sanitize_text_field( $_POST['aat_aws_secret'] ) );
                }

                break;
            case Alt_Text_Service_Switch::SERVICE_MICROSOFT:
                //@todo Save MS AI Authentication Information
                break;
        }
    }
}
