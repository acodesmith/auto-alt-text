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
}