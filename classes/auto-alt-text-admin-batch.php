<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
    exit;

class Auto_Alt_Text_Admin_Batch
{
    const STAGE_START = 'start';
    const STAGE_PROCESSING = 'processing';
    const STAGE_COMPLETE = 'complete';

    public static function respond( $html, $stage, $percentage = 0 )
    {
        echo json_encode([
            'html'          => $html,
            'stage'         => $stage,
            'percentage'    => $percentage,
        ]);
    }

    public static function start()
    {
        $total = Auto_Alt_Text_Batch::totalImages();

        $html = '<h3>Starting Batch</h3>';
        $html.= "<h4>Total number of images needing updates: $total</h4>";

        self::respond( $html, self::STAGE_PROCESSING, 2 );
    }

    public static function batch()
    {
        Alt_Text_Service_Switch::$service = Alt_Text_Service_Switch::SERVICE_AWS;

        if( $service = Alt_Text_Service_Switch::instance() ) {

            Auto_Alt_Text_Batch::run( $service, false );

            $total = Auto_Alt_Text_Batch::totalImages();

            if( $total > 0 ) {
                $percentage = 100 - ($total / Auto_Alt_Text_Batch::$limit);

//                if( floatval( $percentage ) > 1 ) {
//
//                }

                self::respond('', self::STAGE_PROCESSING, $percentage);
            }else{

                $html = '<h3>All done! Woot Woot!</h3>';

                self::respond($html, self::STAGE_COMPLETE, 100);
            }

        }
    }
}