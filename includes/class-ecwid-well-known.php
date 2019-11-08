<?php

class Ecwid_Well_Known {

	public function __construct()
	{
		// rule for .well-known/* path
		add_filter( 'query_vars', array($this, 'query_vars') );
		add_action( 'parse_request', array($this, 'delegate_request'), 1000 );
		add_action( 'generate_rewrite_rules', array($this, 'rewrite_rules'), 1000 );

		// if permalinks are disabled
		// add_action( 'permalink_structure_changed', array($this, 'hook_permalink_structure_changed'), 10, 2 );
		// add_action( 'activated_plugin', array($this, 'hook_activated_plugin'), 10, 2 );

		// Well-Known URIs
		add_action( "ec_well_known_apple-developer-merchantid-domain-association", array($this, "apple_pay_verification" ) );
	}

	public function query_vars($vars) {
		$vars[] = 'ec-well-known';
		return $vars;
	}

	public function rewrite_rules($wp_rewrite) {
		$well_known_rules = array(
			'.well-known/(.+)' => 'index.php?ec-well-known='.$wp_rewrite->preg_index(1),
		);
		$wp_rewrite->rules = $well_known_rules + $wp_rewrite->rules;
	}

	public function delegate_request($wp) {
		if( array_key_exists('ec-well-known', $wp->query_vars) ) {
			$uri_suffix = str_replace( '/', '', $wp->query_vars['ec-well-known'] );

			do_action( "ec_well_known", $wp->query_vars );
			do_action( "ec_well_known_{$uri_suffix}", $wp->query_vars );
		}
	}

	/*
	public function save_mod_rewrite_rules() {
		if( !Ecwid_Seo_Links::is_feature_available() ) {
			error_log( 'save_mod_rewrite_rules' );

			$home_path     = get_home_path();
			$htaccess_file = $home_path . '.htaccess';

			$rules = array();
			$rules[] = '<IfModule mod_rewrite.c>';
			$rules[] = 'RewriteEngine On';
			$rules[] = 'RewriteRule ^\.well-known/(.+)$ index.php?ec-well-known=$1 [L]';
			$rules[] = '</IfModule>';

			// $brand = Ecwid_Config::get_brand();
			// insert_with_markers( $htaccess_file, $brand, $rules );
			insert_with_markers( $htaccess_file, 'WordPress', $rules );
		}
	}

	public function hook_permalink_structure_changed( $plugin, $network_wide ) {
		$this->save_mod_rewrite_rules();
	}

	public function hook_activated_plugin( $plugin, $network_wide ) {

		error_log( $plugin );
		error_log( class_exists('Ecwid_Well_Know') );

		if( class_exists('Ecwid_Well_Know') ) {
			$this->save_mod_rewrite_rules();
		}
	}
	*/

	public function apple_pay_verification( $query_vars ) {
		$api = new Ecwid_Api_V3();
		$profile = $api->get_store_profile(true);

		if( $profile && !empty($profile->payment->applePay->verificationFileUrl) ) {
			
			$response = wp_remote_get(
				$profile->payment->applePay->verificationFileUrl,
				array(
					'timeout' => 60
				)
			);

			$body = wp_remote_retrieve_body( $response );

			if( !empty( $body ) ) {
				echo $body;
			}

			exit();
		}
	}

}
$ecwid_well_know = new Ecwid_Well_Known();

