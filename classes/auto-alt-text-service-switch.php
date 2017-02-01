<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
    exit;

class Alt_Text_Service_Switch
{
    /**
     * Currently only supported service.
     */
    const SERVICE_AWS = 'aws';

    /**
     * As suggested by Jeff | https://github.com/jblz
     */
    const SERVICE_MICROSOFT = 'microsoft';

    private static $__instance = NULL;
    private function __construct(){}
    private function __clone(){}

    /**
     * Namespace for the loading path and class name
     * Currently defaults to aws.
     *
     * @example loading file - service/aws/core.php
     * @example new class name - Auto_Alt_Text_Aws
     *
     * @var string $service
     */
    public static $service;

    /**
     * Returns the singleton instance of the class
     *
     * @return bool|null|stdClass
     */
    public static function instance()
    {
        if( ! self::$__instance ) {

            $instance = new self();

            self::$__instance = $instance->load();
        }

        return self::$__instance;
    }

    /**
     * First load the file based on the service name.
     * Second create a singleton instance of the class.
     *
     * @return bool|stdClass
     */
    public function load()
    {
        if( self::loadClass() ) {

            /** @var Auto_Alt_Text_Service_Interface $class */
            $class = "Auto_Alt_Text_" . ucfirst( self::$service );

            if( class_exists( $class ) ) {
                return $class::instance();
            }
        }

        return null;
    }

    /**
     * Load the class based upon an assumed location.
     * @todo Keep the name core.php?
     *
     * @return bool
     */
    public static function loadClass()
    {
        $classFilePath = AAT_PLUGIN_PATH . "service/" . self::$service . "/core.php";

        if( file_exists( $classFilePath ) ) {

            if( ! class_exists( 'Auto_Alt_Text_' . ucfirst( self::$service ) ) )
                require( $classFilePath );

            return true;
        }

        return false;
    }
}