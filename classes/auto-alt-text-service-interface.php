<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface Auto_Alt_Text_Service_Interface {
	public static function instance();

	public function load();

	public function make();

	public function auth();

	public function run( $images );
}
