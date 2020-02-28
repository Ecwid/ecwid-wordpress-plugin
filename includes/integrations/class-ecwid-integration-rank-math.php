<?php

class Ecwid_Integration_Rank_Math
{
	protected $sitemap = array();

	public function __construct() {
		add_action( 'wp', array( $this, 'filter_meta_tags' ) );

		if ( ecwid_is_paid_account() && ecwid_is_store_page_available()) {
			add_action( 'rank_math/sitemap/index', array( $this, 'sitemap_index' ) );
			add_filter( 'rank_math/sitemap/ecwid/content', array( $this, 'sitemap_content' ) );
		}
	}

	public function filter_meta_tags() {
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

	public function init() {
		add_rewrite_rule( 'ecwid-sitemap\.xml$', 'index.php?sitemap=ecwid', 'top' );
	}

	public function sitemap_index()
	{
		$now = date('c', time());;
		$sitemap_url = RankMath\Sitemap\Router::get_base_url( 'ecwid-sitemap.xml' );

		return <<<XML
		<sitemap>
			<loc>$sitemap_url</loc>
			<lastmod>$now</lastmod>
		</sitemap>
XML;
	}

	public function sitemap_content( $content )
	{
		$this->sitemap = <<<XML
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
XML;

		ecwid_build_sitemap( array($this, 'sitemap_callback') );

		$this->sitemap .= '</urlset>';

		$sitemap = $this->sitemap;
		$this->sitemap = null;

		return $sitemap;
	}

	public function sitemap_callback($url, $priority, $frequency, $obj)
	{
		$url = htmlspecialchars($url);
		$imageCode = '';
		$image = @$obj->originalImageUrl;
		if ($image) {
			$image = htmlspecialchars($image);
			$title = htmlspecialchars($obj->name);
			$imageCode = <<<XML
				<image:image>
					<image:title>$title</image:title>
					<image:loc>$image</image:loc>

				</image:image>
XML;
    }

		$this->sitemap .= <<<XML
	<url>
		<loc>$url</loc>
		<changefreq>$frequency</changefreq>
		<priority>$priority</priority>
		$imageCode
	</url>

XML;
	}
}

$ecwid_integration_rank_math = new Ecwid_Integration_Rank_Math();