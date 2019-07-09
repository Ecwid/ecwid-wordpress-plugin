<?php

if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'Google-Site-Verification' ) ) {
	
	add_action( 'wp_head', 'ecwid_add_kliken_code' );
	
	function ecwid_add_kliken_code() {
		$api = new Ecwid_Api_V3();
	
		$info = $api->get_starter_site_info();
	
		if ( !$info || !isset( $info->customHeaderHtmlCode ) ) return;
	
		$pattern = "%" .
		           "(<!--Kliken Google Site Verification Token Tag-->\s*" .
		           "<meta name='google-site-verification' content='(.*)' />\s*" .
		           "<!--Kliken Google Site Verification Token Tag-->)%s";
	
	
		$matches = array();
		if ( preg_match( $pattern, $info->customHeaderHtmlCode, $matches ) ) {
			echo $matches[1];
		}
	}
}