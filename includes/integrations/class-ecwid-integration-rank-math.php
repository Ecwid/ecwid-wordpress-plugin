<?php

class Ecwid_Integration_Rank_Math
{
	public function __construct()
	{
		add_action( 'wp', array( $this, 'filter_meta_tags' ) );
	}

	public function filter_meta_tags()
	{
		if ( Ecwid_Store_Page::is_store_page() ) {
			$html_catalog_params = Ecwid_Seo_Links::maybe_extract_html_catalog_params();
			$is_home_page = empty( $html_catalog_params );
			
			if( !$is_home_page ) {
				add_filter( 'rank_math/frontend/title', '__return_false', 1000 );
				add_filter( 'rank_math/frontend/canonical', '__return_false', 1000 );
				add_filter( 'rank_math/frontend/description', '__return_false', 1000 );
				
				add_filter( 'rank_math/json_ld', '__return_false', 1000 );

				add_filter( 'rank_math/opengraph/type', '__return_false', 1000 );
				add_filter( 'rank_math/opengraph/facebook/og_site_name', '__return_false', 1000 );
				add_filter( 'rank_math/opengraph/facebook/article_publisher', '__return_false', 1000 );
				add_filter( 'rank_math/opengraph/facebook/article_author', '__return_false', 1000 );
				add_filter( 'rank_math/opengraph/facebook/article_published_time', '__return_false', 1000 );
				add_filter( 'rank_math/opengraph/facebook/article_modified_time', '__return_false', 1000 );
				add_filter( 'rank_math/opengraph/twitter/twitter_card', '__return_false', 1000 );
			}
		}
	}
}

$ecwid_integration_rank_math = new Ecwid_Integration_Rank_Math();