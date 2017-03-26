<?php
/**
 *
 * Plugin Name: Auto Alt Text
 * Version: 0.0.1
 * Plugin URI: http://www.acodesmith.com
 * Description: Generate Alt Tags Using AI Image Recognition
 * Author: Adam Smith
 * Author URI: http://www.acodesmith.com/
 * Requires at least: 4.0
 * Tested up to: 4.7
 *
 * @package WordPress
 * @author Adam Smith
 * @since 0.0.1
 **/

define( 'AAT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'AAT_PLUGIN_URI', plugin_dir_url( __FILE__ ) );

/**
 * Load all the custom classes
 */
function auto_alt_text_init() {
	auto_alt_text_load();
	add_filter( 'add_attachment', 'auto_alt_text_add_attachment_hook' );
}
add_action( 'init', 'auto_alt_text_init' );

/**
 * Add Auto Alt Text submenu item to the settings admin section
 */
function auto_alt_text_setup() {
	add_submenu_page(
		'options-general.php',
		__( 'Auto Alt Text', 'aat' ),
		__( 'Auto Alt Text', 'aat' ),
		'manage_options',
		'auto-alt-text',
		'auto_alt_text_admin_page'
	);
}
add_action( 'admin_menu', 'auto_alt_text_setup' );

/**
 * Admin Page for updating all Auto Alt Text settings
 */
function auto_alt_text_admin_page() {
	Auto_Alt_Text_Admin::scripts();

	if ( ! empty( $_POST ) ) {
		Auto_Alt_Text_Admin::processPost();
	}

	/** Variables for the view */
	$nonce_action    = Auto_Alt_Text_Common::NONCE_NAMESPACE;
	$confidence      = Auto_Alt_Text_Common::getConfidence();
	$prefix          = Auto_Alt_Text_Common::getAltPrefix();
	$hasBatched      = Auto_Alt_Text_Admin::hasRanBatchAtLeastOnce();
	$selectedService = Auto_Alt_Text_Common::getSelectedService();
	$hasAuth         = false;

	Alt_Text_Service_Switch::$service = $selectedService;

	/** @var Auto_Alt_Text_Aws $service */
	if ( $service = Alt_Text_Service_Switch::instance() ) {

		$hasAuth = ! empty( $service->auth() );
	}

	include( __DIR__ .'/views/batch.php' );
	include( __DIR__ . '/views/admin.php' );
}

/**
 * @param array $groups
 */
function auto_alt_text_load() {
	// Classes
	require( __DIR__ . '/classes/auto-alt-text-service-interface.php');
	require( __DIR__ . '/classes/auto-alt-text-service-switch.php');
	require( __DIR__ . '/classes/auto-alt-text-admin.php');
	require( __DIR__ . '/classes/auto-alt-text-batch.php' );
	require( __DIR__ . '/classes/auto-alt-text-admin-batch.php');
	require( __DIR__ . '/classes/auto-alt-text-common.php');
	require( __DIR__ . '/classes/auto-alt-text-db.php' );

	// Functions
	require( __DIR__ . '/functions/auto-alt-text-add-attachment.php' );
}

/**
 * Stages based on the admin batch process.
 */
function auto_alt_text_batch_button() {
	switch( $_GET['stage'] ) {
		case 'start':
			Auto_Alt_Text_Admin_Batch::start();
			break;
		case 'processing':
			Auto_Alt_Text_Admin_Batch::batch();
			break;
	}

	die;
}
add_action( 'wp_ajax_aat_batch', 'auto_alt_text_batch_button' );


/** Used for testing the service on init */
//function test_alttext()
//{
////    auto_alt_text_add_attachment_hook( 88 );
//
//    Alt_Text_Service_Switch::$service = Alt_Text_Service_Switch::SERVICE_AWS;
//
//    if( $service = Alt_Text_Service_Switch::instance() ) {
//
//        Auto_Alt_Text_Batch::run($service);
//
//    }
//}
//add_action( 'init', 'test_alttext' );
