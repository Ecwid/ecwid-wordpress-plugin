<?php

class Ecwid_Integration_UrbanGo
{
	public function __construct()
	{
		add_action( 'generate_rewrite_rules', array( $this, 'rewrite_rules' ), 1000 );
	}

	public function rewrite_rules($wp_rewrite) {

		$patterns = Ecwid_Seo_Links::get_seo_links_patterns();
		foreach ( $patterns as $pattern ) {

			$theme_rules['listing-item/([^/]+)/' . $pattern] = 'index.php?listing-item=$matches[1]';

			if( function_exists('urbango_edge_options') ) {
				$custom_slug = urbango_edge_options()->getOptionValue( 'listing_single_slug' );

				if( !empty($custom_slug) ) {
					$theme_rules[ $custom_slug . '/([^/]+)/' . $pattern] = 'index.php?listing-item=$matches[1]';
				}
			}
		}

		$wp_rewrite->rules = $theme_rules + $wp_rewrite->rules;
	}
}

$ecwid_integration_urbango = new Ecwid_Integration_UrbanGo();