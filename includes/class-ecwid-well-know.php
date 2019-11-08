<?php

class Ecwid_Well_Know {

	public function __construct()
	{
		add_filter( 'query_vars', array($this, 'query_vars') );
		add_action( 'parse_request', array($this, 'delegate_request'), 1000 );
		add_action( 'generate_rewrite_rules', array($this, 'rewrite_rules'), 1000 );
		add_action( 'permalink_structure_changed', array($this, 'save_mod_rewrite_rules'), 2, 1000 );
	}

	public function query_vars($vars) {
		$vars[] = 'well-known';

		return $vars;
	}

	public function rewrite_rules($wp_rewrite) {
		$well_known_rules = array(
			'.well-known/(.+)' => 'index.php?well-known='.$wp_rewrite->preg_index(1),
		);

		$wp_rewrite->rules = $well_known_rules + $wp_rewrite->rules;
	}

	public function delegate_request($wp) {
		if( array_key_exists('well-known', $wp->query_vars) ) {
			$id = str_replace( '/', '', $wp->query_vars['well-known'] );

			do_action( "well-known", $wp->query_vars );
			do_action( "well_known_{$id}", $wp->query_vars );
		}
	}

	public function save_mod_rewrite_rules( $old_permalink_structure, $permalink_structure ) {

		if( !Ecwid_Seo_Links::is_feature_available() ) {
			error_log( 'save_mod_rewrite_rules' );

			$home_path     = get_home_path();
			$htaccess_file = $home_path . '.htaccess';

			$rules = array();
			$rules[] = '<IfModule mod_rewrite.c>';
			$rules[] = 'RewriteEngine On';
			$rules[] = 'RewriteRule ^\.well-known/(.+)$ index.php?well-known=$1 [L]';
			$rules[] = '</IfModule>';

			// $brand = Ecwid_Config::get_brand();
			// insert_with_markers( $htaccess_file, $brand . ' - ApplePay Verification', $rules );
			insert_with_markers( $htaccess_file, 'WordPress', $rules );
		}
	}
}

$ecwid_well_know = new Ecwid_Well_Know();

add_action( "well_known_apple-developer-merchantid-domain-association", "ecwid_stripe_applepay_verification" );

function ecwid_stripe_applepay_verification( $query_vars ) {
	
	$api = new Ecwid_Api_V3();
	$profile = $api->get_store_profile(); // отключить кеширование

	// echo '<pre>';
	// print_r( $profile );
	// echo '</pre>';

	var_dump( $query_vars );
}