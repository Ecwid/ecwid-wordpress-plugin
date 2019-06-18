<?php

class Ecwid_Kliken {
	
	const OPTION_KLIKEN_CODE = 'ecwid_kliken_code';
	
	public function __construct() {
		if ( $this->_need_to_try_fetching_code() ) {
			add_action( 'template_redirect', array( $this, 'maybe_fetch_code' ) );
		}
		
		add_action( 'wp_head', array( $this, 'add_code' ) );
	}

	public function add_code() {
		$code = get_option( self::OPTION_KLIKEN_CODE, false );
		
		if ( !$code ) return;
		
		echo $code;
	}
	
	public function maybe_fetch_code() {
		
		if ( !strpos( $_SERVER['HTTP_USER_AGENT'], 'Google-Site-Verification' ) ) return;
		
		$api = new Ecwid_Api_V3();
		
		$info = $api->get_starter_site_info();

		if ( !$info || !isset( $info->customHeaderHtmlCode ) ) return;
		
		$pattern = "%" .
			"(<!--Kliken Google Site Verification Token Tag-->\s*" .
			"<meta name='google-site-verification' content='(.*)' />\s*" .
			"<!--Kliken Google Site Verification Token Tag-->)%s";
		
		
		$matches = [];
		if ( preg_match( $pattern, $info->customHeaderHtmlCode, $matches ) ) {
			update_option( self::OPTION_KLIKEN_CODE, $matches[1] );
		}
	}
	
	protected function _need_to_try_fetching_code() {
		return
			strpos( $_SERVER['HTTP_USER_AGENT'], 'Google-Site-Verification' )
			&& !get_option( self::OPTION_KLIKEN_CODE, false );
	}
}

$_ecwid_kliken = new Ecwid_Kliken();