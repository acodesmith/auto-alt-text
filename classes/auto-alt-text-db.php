<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
    exit;

class Auto_Alt_Text_Db
{
    /**
     * Returns either the total number of images needing processing or
     * all the images without alt tags in array of objects.
     *
     * @param $limit
     * @param $offset
     * @param bool $count
     * @return array|null|object
     */
    public static function getImagesNeedingAltText( $limit, $offset = -1, $count = false )
    {
        global $wpdb;

        $post       = $wpdb->prefix . 'posts';
        $postmeta   = $wpdb->prefix . 'postmeta';
        $select     = $count ? "SELECT COUNT(ID) as total FROM `$post`" : "SELECT * FROM `$post` ";
        $limitQuery = $limit !== false ? "LIMIT $limit" . ( $offset > 0 ? ", $offset" : "" ) : '';

        return $wpdb->get_results("
            $select WHERE ID NOT IN (
            SELECT `ID` from `$post`
            JOIN `$postmeta` on `$post`.`ID` = `$postmeta`.`post_id`
            WHERE `$postmeta`.`meta_key` = '_wp_attachment_image_alt'
            AND `$post`.`post_type` = 'attachment'
            ) AND `$post`.`post_type` = 'attachment'
            $limitQuery
        ");
    }

    /**
     * @param $post_id
     * @param $limit
     * @return array|null|object
     */
    public static function getImagesNeedingAltTextByPost( $post_id, $limit = 1 )
    {
        global $wpdb;

        $post       = $wpdb->prefix . 'posts';
        $postmeta   = $wpdb->prefix . 'postmeta';
        $limitQuery = $limit !== false ? "LIMIT $limit" : '';

        return $wpdb->get_results("
            SELECT * FROM `$post` WHERE ID NOT IN (
            SELECT `ID` from `$post`
            JOIN `$postmeta` on `$post`.`ID` = `$postmeta`.`post_id`
            WHERE `$postmeta`.`meta_key` = '_wp_attachment_image_alt'
            AND `$post`.`post_type` = 'attachment'
            ) AND `$post`.`post_type` = 'attachment' AND `$post`.`post_parent` = $post_id
            $limitQuery
        ");
    }

    /**
     * @param $attachment_id
     * @param $limit
     * @return array|null|object
     */
    public static function getImagesNeedingAltTextByAttachmentId( $attachment_id, $limit = 1 )
    {
        global $wpdb;

        $post       = $wpdb->prefix . 'posts';
        $postmeta   = $wpdb->prefix . 'postmeta';
        $limitQuery = $limit !== false ? "LIMIT $limit" : '';

        return $wpdb->get_results("
            SELECT * FROM `$post` WHERE ID NOT IN (
            SELECT `ID` from `$post`
            JOIN `$postmeta` on `$post`.`ID` = `$postmeta`.`post_id`
            WHERE `$postmeta`.`meta_key` = '_wp_attachment_image_alt'
            AND `$post`.`post_type` = 'attachment'
            ) AND `$post`.`post_type` = 'attachment' AND `$post`.`ID` = $attachment_id
            $limitQuery
        ");
    }
}