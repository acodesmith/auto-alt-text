<?php

class Auto_Alt_Text_Aws implements Auto_Alt_Text_Service_Interface
{
    const AWS_KEY_NAMESPACE     = 'att_aws_k';
    const AWS_SECRET_NAMESPACE  = 'att_aws_s';

    public static $__instance;
    public static $__service;

    public static $support_image_types = ['jpg', 'jpeg', 'png'];

    /**
     * @return Auto_Alt_Text_Aws
     */
    public static function instance()
    {
        if( self::$__instance == null ) {

            self::$__instance = new self();
        }

        return self::$__instance;
    }

    /**
     *
     */
    public function load()
    {
        if( ! class_exists( 'AwsClient' ) )
            require( AAT_PLUGIN_PATH . 'service/aws/vendor/autoload.php' );
    }

    /**
     * @return array|bool
     */
    public function auth()
    {
        $key    = get_option( self::AWS_KEY_NAMESPACE );
        $secret = get_option( self::AWS_SECRET_NAMESPACE );

        if( ! empty( $key ) && ! empty( $secret ) ) {
            return [
                'credentials' => [
                    'key'       => $key,
                    'secret'    => $secret
                ]
            ];
        }

        return false;
    }

    /**
     * @return \Aws\Rekognition\RekognitionClient|bool
     */
    public function make()
    {
        $this->load();

        if( empty( self::$__service ) ) {

            if( $auth = $this->auth() ) {

                self::$__service = new Aws\Rekognition\RekognitionClient(array_merge([
                    'version'   => 'latest', //no reason for legacy image recognition
                    'region'    => 'us-east-1', //currently only supported region
                ], $auth));
            }
        }

        return self::$__service;
    }

    public function run( $images )
    {
        $uploadDir      = wp_upload_dir();
        $count          = 0;

        if( ! empty( $images ) ) {
            foreach( $images as $image ) {

                if( ! empty( $image->guid ) ) {

                    $urlInfo        = parse_url( $image->guid );
                    $fullImagePath  = null;

                    //@todo figure out a better way to get image full path
                    if( ! empty( $urlInfo['path'] ) ) {
                        $imageFolderLocation = explode( 'uploads', $urlInfo['path'] );
                        $fullImagePath = $uploadDir['basedir'] . end($imageFolderLocation);
                    }

                    if( $fullImagePath && file_exists( $fullImagePath ) ) {

                        $imageExt = pathinfo( $fullImagePath, PATHINFO_EXTENSION );

                        if( in_array( $imageExt, self::$support_image_types ) ) {

                            if( $labels = self::detectLabels( $fullImagePath ) ) {

                                if( $altText = self::concatenateResults( $labels ) ) {

                                    update_post_meta( $image->ID, '_wp_attachment_image_alt', $altText );
                                }

                                $count++;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $fileFullPath
     * @return bool|mixed|null
     */
    public static function detectLabels( $fileFullPath )
    {
        $instance = self::instance();
        $awsRekognition = $instance->make();

        $labels = $awsRekognition->detectLabels([
            'Image' => [
                'Bytes' => file_get_contents( $fileFullPath ),
            ]
        ])->get('Labels');

        return ! empty( $labels ) ? $labels : false;
    }

    /**
     * @param array $results
     * @return string
     */
    public static function concatenateResults( $results = [] )
    {
        if( empty( $results ) )
            return false;

        $prefix     = Auto_Alt_Text_Common::getAltPrefix();
        $confidence = Auto_Alt_Text_Common::getConfidence();
        $text       = '';

        foreach( $results as $key => $result ) {

            if( intval( $result['Confidence'] ) > $confidence ) {
                $text.=  ( $key !== 0 ? ', ' : '' ) . $result['Name'];
            }
        }

        if( empty( $text ) )
            return false;

        return $prefix . ' ' . $text;
    }
}