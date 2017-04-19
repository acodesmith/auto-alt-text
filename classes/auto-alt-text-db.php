<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Auto_Alt_Text_Db {
	/**
	 * Returns either the total number of images needing processing or
	 * all the images without alt tags in array of objects.
	 *
	 * @param $limit
	 * @param $offset
	 * @param bool $count
	 * @return array|null|object
	 */
	public static function get_images_needing_alt_text( $limit, $offset = -1, $count = false ) {
		global $wpdb;

		$select      = $count ? "SELECT COUNT(ID) as total FROM {$wpdb->posts}" : "SELECT * FROM {$wpdb->posts} ";
		$limit_query = false !== $limit ? 'LIMIT ' . ( $offset > 0 ? "{$limit}, {$offset}" : "{$limit}" ) : '';

		return $wpdb->get_results( "
			{$select} WHERE ID NOT IN (
			SELECT ID from {$wpdb->posts}
			JOIN {$wpdb->postmeta} on {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
			WHERE $wpdb->postmeta.meta_key = '_wp_attachment_image_alt'
			AND {$wpdb->posts}.post_type = 'attachment'
			) AND {$wpdb->posts}.post_type = 'attachment'
			{$limit_query}
		" );
	}

	/**
	 * @param $post_id
	 * @param $limit
	 * @return array|null|object
	 */
	public static function get_images_needing_alt_text_by_post( $post_id, $limit = 1 ) {
		global $wpdb;

		$limit_query = ( false !== $limit ) ? "LIMIT {$limit}" : '';

		return $wpdb->get_results( "
			SELECT * FROM {$wpdb->posts} WHERE ID NOT IN (
			SELECT ID from {$wpdb->posts}
			JOIN {$wpdb->postmeta} on {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
			WHERE {$wpdb->postmeta}.meta_key = '_wp_attachment_image_alt'
			AND {$wpdb->posts}.post_type = 'attachment'
			) AND {$wpdb->posts}.post_type = 'attachment' AND {$wpdb->posts}.post_parent = {$post_id}
			{$limit_query}
		" );
	}

	/**
	 * @param $attachment_id
	 * @param $limit
	 * @return array|null|object
	 */
	public static function get_images_needing_alt_text_by_attachment_id( $attachment_id, $limit = 1 ) {
		global $wpdb;

		$limit_query = false !== $limit ? "LIMIT $limit" : '';

		return $wpdb->get_results( "
			SELECT * FROM {$wpdb->posts} WHERE ID NOT IN (
			SELECT ID from {$wpdb->posts}
			JOIN {$wpdb->postmeta} on {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
			WHERE {$wpdb->postmeta}.meta_key = '_wp_attachment_image_alt'
			AND {$wpdb->posts}.post_type = 'attachment'
			) AND {$wpdb->posts}.post_type = 'attachment' AND {$wpdb->posts}.ID = {$attachment_id}
			{$limit_query}
		" );
	}
}
