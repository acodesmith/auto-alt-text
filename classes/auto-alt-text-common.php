<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
    exit;

if( ! class_exists('Alt_Text_Service_Switch') )
    require( __DIR__ . 'auto-alt-text-service-switch.php' );

class Auto_Alt_Text_Common
{
    const NONCE_NAMESPACE               = 'aat_nonce_action';
    const AUTH_FILE_NAMESPACE           = 'att_a_l';
    const CONFIDENCE_NAMESPACE          = 'att_c';
    const ALT_PREFIX_NAMESPACE          = 'att_p';
    const SELECTED_SERVICE_NAMESPACE    = 'aat_ss';

    /**
     * Confidence is returned from the API. A higher confidence score, the more likely the
     * API results are correct. Lower confidence will result in less accurate alt tags.
     *
     * @var int
     */
    public static $confidence   = 70;

    /**
     * Default image alt tag prefix. Overwritten by stored wp_option
     *
     * @var string
     */
    public static $altPrefix    = 'Image may contain:';

    /**
     * Get stored wp_option value or default value for image recognition confidence*
     *
     * @return int|mixed
     */
    public static function getConfidence()
    {
        $stored = get_option( self::CONFIDENCE_NAMESPACE );

        return ! empty( $stored ) ? $stored : self::$confidence;
    }

    /**
     * Custom language for the alt tag prefix.
     *
     * @return mixed|string
     */
    public static function getAltPrefix()
    {
        $stored = get_option( self::ALT_PREFIX_NAMESPACE );

        return ! empty( $stored ) ? $stored : __( self::$altPrefix, 'autoalttext' );
    }

    /**
     * Selected Service is defaulted to aws until the plugin supports Microsoft
     *
     * @return mixed|string
     */
    public static function getSelectedService()
    {
        $stored = get_option( self::SELECTED_SERVICE_NAMESPACE );

        return ! empty( $stored ) ? $stored : Alt_Text_Service_Switch::SERVICE_AWS;
    }
}