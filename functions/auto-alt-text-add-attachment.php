<?php
/**
 * add_attachment hook setting up a single cron event
 *
 * @see auto_alt_text_add_attachment_schedule_event
 * @param $attachment_id
 */
function auto_alt_text_add_attachment_hook( $attachment_id ) {
	wp_schedule_single_event( time(), 'auto_alt_text_add_attachment_schedule_event', [ $attachment_id ] );
}

/**
 * Processing a single image with the attachment_id provided by the add_attachment hook and cron
 *
 * @see auto_alt_text_add_attachment_hook
 * @param $attachment_id
 */
function auto_alt_text_add_attachment_schedule_event( $attachment_id ) {
	/** @var array $images */
	$images = Auto_Alt_Text_Db::get_images_needing_alt_text_by_attachment_id( $attachment_id );

	if ( ! empty( $images ) ) {

		//@todo abstract based on user's selected service
		Alt_Text_Service_Switch::$service = Alt_Text_Service_Switch::SERVICE_AWS;

		$service = Alt_Text_Service_Switch::instance();

		/** @var Auto_Alt_Text_Service_Interface $service */
		if ( $service ) {
			$service->run( $images );
		}
	}
}
