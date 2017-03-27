<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Auto_Alt_Text_Admin_Batch {
	const STAGE_START = 'start';
	const STAGE_PROCESSING = 'processing';
	const STAGE_COMPLETE = 'complete';

	public static function respond( $html, $stage, $percentage = 0 ) {
		echo json_encode([
			'html'       => $html,
			'stage'      => $stage,
			'percentage' => $percentage,
		]);
	}

	public static function start() {
		$total = Auto_Alt_Text_Batch::total_images();

		$html = sprintf( '<h3>%1$s</h3><h4>%2$s %3$s</h4>',
			__( 'Starting Batch', 'aat' ),
			__( 'Total number of images needing updates:', 'aat' ),
			esc_html( $total )
		);

		self::respond( $html, self::STAGE_PROCESSING, 2 );
	}

	public static function batch() {
		Alt_Text_Service_Switch::$service = Alt_Text_Service_Switch::SERVICE_AWS;
		$service = Alt_Text_Service_Switch::instance();

		if ( $service ) {

			Auto_Alt_Text_Batch::run( $service, false );

			$total = Auto_Alt_Text_Batch::total_images();

			if ( $total > 0 ) {

				$percentage = 100 - ( $total / Auto_Alt_Text_Batch::$limit );
				self::respond( '', self::STAGE_PROCESSING, $percentage );

			} else {

				$html = '<h3>' . __( 'All done! Woot Woot!', 'aat' ) . '</h3>';

				self::respond( $html, self::STAGE_COMPLETE, 100 );
			}
		}
	}
}
