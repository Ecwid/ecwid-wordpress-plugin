<?php

class Ecwid_Integration_Rank_Math {

	protected $sitemap = array();

	public function __construct() {

		if ( ecwid_is_store_page_available() ) {
			add_action( 'rank_math/sitemap/index', array( $this, 'sitemap_index' ) );
			add_filter( 'rank_math/sitemap/ecwid/content', array( $this, 'sitemap_content' ) );
			add_filter( 'rank_math/sitemap/exclude_post_type', array( $this, 'exclude_post_type' ), 10, 2 );
			add_filter( 'rank_math/excluded_post_types', array( $this, 'excluded_post_types' ), 10, 1 );
		}

		add_action( 'wp', array( $this, 'filter_meta_tags' ), 1000 );
	}

	public function exclude_post_type( $bool, $post_type ) {

		if ( $post_type == 'ec-product' ) {
			return true;
		}

		return $bool;
	}

	public function excluded_post_types( $accessible_post_types ) {

		if ( isset( $accessible_post_types['ec-product'] ) ) {
			unset( $accessible_post_types['ec-product'] );
		}

		return $accessible_post_types;
	}

	public function filter_meta_tags() {
		if ( Ecwid_Store_Page::is_store_page() ) {
			$is_home_page = Ecwid_Store_Page::is_store_home_page();

			if ( ! $is_home_page ) {
				remove_all_actions( 'rank_math/head' );
				add_action( 'wp_head', array( $this, 'render_title_tag' ), 1 );
			}
		}
	}

	public function render_title_tag() {
		$title = _ecwid_get_seo_title();
		echo '<title>' . esc_html( $title ) . '</title>' . "\n";
	}

	public function sitemap_index() {
		$now         = date( 'c', time() );
		$sitemap_url = RankMath\Sitemap\Router::get_base_url( 'ecwid-sitemap.xml' );

		return "
		<sitemap>
			<loc>$sitemap_url</loc>
			<lastmod>$now</lastmod>
		</sitemap>";
	}

	public function sitemap_content( $content ) {

		$this->sitemap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		ecwid_build_sitemap( array( $this, 'sitemap_callback' ) );

		$this->sitemap .= '</urlset>';

		$sitemap       = $this->sitemap;
		$this->sitemap = null;

		return $sitemap;
	}

	public function sitemap_callback( $url, $priority, $frequency, $obj ) {
		$url        = htmlspecialchars( $url );
		$image_code = '';
		$image      = @$obj->originalImageUrl; //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		if ( $image ) {
			$image      = htmlspecialchars( $image );
			$title      = htmlspecialchars( $obj->name );
			$image_code = '<image:image><image:title>' . $title . '</image:title><image:loc>' . $image . '</image:loc></image:image>';
		}

		$this->sitemap .= "
	<url>
		<loc>$url</loc>
		<changefreq>$frequency</changefreq>
		<priority>$priority</priority>
		$image_code
	</url>";
	}
}

$ecwid_integration_rank_math = new Ecwid_Integration_Rank_Math();
