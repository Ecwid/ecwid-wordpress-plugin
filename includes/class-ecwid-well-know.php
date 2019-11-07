<?php

class Ecwid_Well_Know {

	public function __construct()
	{
		add_filter('query_vars', array($this, 'query_vars'));
		add_action('parse_request', array($this, 'delegate_request'), 1000);
		add_action('generate_rewrite_rules', array($this, 'rewrite_rules'), 1000);
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
		if (array_key_exists('well-known', $wp->query_vars)) {
			$id = $wp->query_vars['well-known'];

			do_action("well-known", $wp->query_vars);
			do_action("well_known_{$id}", $wp->query_vars);
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