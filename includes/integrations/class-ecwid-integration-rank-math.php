<?php

class Ecwid_Integration_Rank_Math {

	protected $sitemap = array();

	public $sitemap_url_pattern = 'ecstore-%d-sitemap.xml';
	public $sitemap_urls        = array();

	public function __construct() {

		if ( ecwid_is_store_page_available() ) {
			require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-sitemap-builder.php';

            //phpcs:disable WordPress.Security
			$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
			if ( strpos( $request_uri, 'sitemap_index.xml' ) !== false || preg_match( '/ecstore-([0-9]+)-sitemap\.xml/i', $request_uri ) ) {
				$num_pages = EcwidSitemapBuilder::get_num_pages();
				for ( $i = 1; $i <= $num_pages; $i++ ) {
					$this->sitemap_urls[] = sprintf( $this->sitemap_url_pattern, $i );

					add_filter( 'rank_math/sitemap/ecstore-' . $i . '/content', array( $this, 'sitemap_content' ) );
				}
			}
            //phpcs:enable WordPress.Security

			add_action( 'rank_math/sitemap/index', array( $this, 'sitemap_index' ), 10, 1 );
			add_filter( 'rank_math/sitemap/exclude_post_type', array( $this, 'exclude_post_type' ), 10, 2 );
			add_filter( 'rank_math/excluded_post_types', array( $this, 'excluded_post_types' ), 10, 1 );
		}//end if

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

	public function sitemap_index( $xml ) {
		$now = date( 'c', time() );

		foreach ( $this->sitemap_urls as $url ) {
			$sitemap_url = RankMath\Sitemap\Router::get_base_url( $url );

			$xml .= "
                <sitemap>
                    <loc>$sitemap_url</loc>
                    <lastmod>$now</lastmod>
                </sitemap>";
		}

		return $xml;
	}

	public function sitemap_content( $content ) {
		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		foreach ( $this->sitemap_urls as $url ) {
			if ( strpos( $request_uri, $url ) !== false ) {
				preg_match( '/ecstore-([0-9]+)-sitemap\.xml/i', $url, $m );
				$page_num = $m[1];

				$this->sitemap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

				ecwid_build_sitemap( array( $this, 'sitemap_callback' ), $page_num );

				$this->sitemap .= '</urlset>';

				$content       = $this->sitemap;
				$this->sitemap = null;
			}
		}

		return $content;
	}

	public function sitemap_callback( $url, $priority, $frequency, $obj ) {
		$url        = htmlspecialchars( $url );
		$lastmod    = '';
		$image_code = '';
		$image      = @$obj->originalImageUrl; //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		if ( $image ) {
			$image      = htmlspecialchars( $image );
			$title      = htmlspecialchars( $obj->name );
			$image_code = '<image:image><image:title>' . $title . '</image:title><image:loc>' . $image . '</image:loc></image:image>';
		}

		if ( isset( $obj->updated ) ) {
			$lastmod = '<lastmod>' . $obj->updated . '</lastmod>';
		}

		$this->sitemap .= "
	<url>
		<loc>$url</loc>
		<changefreq>$frequency</changefreq>
		<priority>$priority</priority>
		$image_code $lastmod
	</url>";
	}
}

$ecwid_integration_rank_math = new Ecwid_Integration_Rank_Math();
