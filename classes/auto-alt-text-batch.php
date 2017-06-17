<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Auto_Alt_Text_Batch {
	/**
	 * The number of images processed in each batch.
	 * Very important for memory usage.
	 * Each image file contents is sent directly to the API.
	 * Limiting the number of images per batch helps with memory max errors.
	 *
	 * @var int $limit
	 */
	public static $limit = 4;

	/**
	 * Not currently used. Could be used for selecting certain sets of images if multiple batches were running.
	 * @todo Keep in code or remove?
	 *
	 * @var int $offset
	 */
	public static $offset = 0;

	/**
	 * Process the images needing batching.
	 * Either all of them in a while loop or just a group.
	 * The group size is determined by the $limit property
	 *
	 * @todo abstract $limit based on user input. It could greatly vary depending on server memory limit.
	 *
	 * @param $service
	 * @param bool $group
	 */
	public static function run( $service, $group = true ) {
		$total_images = self::total_images();
		if ( $total_images ) {

			$count = 0;
			$total_groups = ceil( $total_images / self::$limit );

			if ( $group ) {
				while ( $count < $total_groups ) {
					$count = self::group( $count, $service );
				}
			} else {

				$images = Auto_Alt_Text_Db::get_images_needing_alt_text( self::$limit, self::$offset );

				if ( ! empty( $images ) ) {
					$service->run( $images );
				}
			}
		}
	}

	/**
	 * Utility function to return the number of images needing processing.
	 *
	 * @return bool
	 */
	public static function total_images() {
		$images = Auto_Alt_Text_Db::get_images_needing_alt_text( false, false, true );

		if ( ! empty( $images ) ) {
			$images = reset( $images );

			return $images->total;
		}

		return 0;
	}

	/**
	 * Process a batch of images based on the limit.
	 * Returns a counter for future processing tracking.
	 *
	 * @todo remove counter option
	 *
	 * @param $count
	 * @param $service
	 * @return mixed
	 */
	public static function group( $count, $service ) {
		self::$offset = $count * self::$limit;

		$images = Auto_Alt_Text_Db::get_images_needing_alt_text( self::$limit, self::$offset );

		if ( ! empty( $images ) ) {
			$service->run( $images );
		}

		return $count + 1;
	}

}
