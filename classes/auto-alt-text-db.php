<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Auto_Alt_Text_Db {

	/**
	 * Get transient key
	 *
	 * @return string
	 */
	private static function get_transient_key( $key ) {
		return 'aat_' . md5( join( '.', func_get_args() ) );
	}

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

		$cache_key = self::get_transient_key( $limit, $offset, $count );
		$query = wp_cache_get( $cache_key );

		if ( false === $query ) {

			$query = $wpdb->get_results( "
				{$select} WHERE ID NOT IN (
				SELECT ID from {$wpdb->posts}
				JOIN {$wpdb->postmeta} on {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
				WHERE $wpdb->postmeta.meta_key = '_wp_attachment_image_alt'
				AND {$wpdb->posts}.post_type = 'attachment'
				) AND {$wpdb->posts}.post_type = 'attachment'
				{$limit_query}
			" );

			// set our cache to expire in 1 hour
			wp_cache_set( $cache_key, $query, 60 * MINUTES_IN_SECONDS );
		}

		return $query;
	}

	/**
	 * @param $post_id
	 * @param $limit
	 * @return array|null|object
	 */
	public static function get_images_needing_alt_text_by_post( $post_id, $limit = 1 ) {
		global $wpdb;

		$limit_query = ( false !== $limit ) ? "LIMIT {$limit}" : '';

		$cache_key = self::get_transient_key( $post_id, $limit );
		$query = wp_cache_get( $cache_key );

		if ( false === $query ) {

			return $wpdb->get_results( "
				SELECT * FROM {$wpdb->posts} WHERE ID NOT IN (
				SELECT ID from {$wpdb->posts}
				JOIN {$wpdb->postmeta} on {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
				WHERE {$wpdb->postmeta}.meta_key = '_wp_attachment_image_alt'
				AND {$wpdb->posts}.post_type = 'attachment'
				) AND {$wpdb->posts}.post_type = 'attachment' AND {$wpdb->posts}.post_parent = {$post_id}
				{$limit_query}
			" );

			// set our cache to expire in 1 hour
			wp_cache_set( $cache_key, $query, 60 * MINUTES_IN_SECONDS );
		}

		return $query;
	}

	/**
	 * @param $attachment_id
	 * @param $limit
	 * @return array|null|object
	 */
	public static function get_images_needing_alt_text_by_attachment_id( $attachment_id, $limit = 1 ) {
		global $wpdb;

		$limit_query = false !== $limit ? "LIMIT $limit" : '';

		$cache_key = self::get_transient_key( $attachment_id, $limit );
		$query = wp_cache_get( $cache_key );

		if ( false === $query ) {
			return $wpdb->get_results( "
				SELECT * FROM {$wpdb->posts} WHERE ID NOT IN (
				SELECT ID from {$wpdb->posts}
				JOIN {$wpdb->postmeta} on {$wpdb->posts}.ID = {$wpdb->postmeta}.post_id
				WHERE {$wpdb->postmeta}.meta_key = '_wp_attachment_image_alt'
				AND {$wpdb->posts}.post_type = 'attachment'
				) AND {$wpdb->posts}.post_type = 'attachment' AND {$wpdb->posts}.ID = {$attachment_id}
				{$limit_query}
			" );

			// set our cache to expire in 1 hour
			wp_cache_set( $cache_key, $query, 60 * MINUTES_IN_SECONDS );
		}

		return $query;
	}
}
