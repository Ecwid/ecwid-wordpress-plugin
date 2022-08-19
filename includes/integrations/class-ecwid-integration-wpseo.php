<?php

class Ecwid_Integration_WordPress_SEO_By_Yoast {

	/** Store intermediate sitemap generation results here */
	protected $sitemap = array();
	protected $og_drop = array( 'title', 'desc', 'type', 'url', 'site_name' );

	public function __construct() {
		if ( ! Ecwid_Api_V3::is_available() ) {
			return;
		}

		add_action( 'wp', array( $this, 'disable_seo_on_escaped_fragment' ) );
		add_action( 'template_redirect', array( $this, 'disable_rewrite_titles' ) );

		if ( ecwid_is_store_page_available() ) {

			add_filter( 'wpseo_title', 'ecwid_seo_title' );
			remove_filter( 'wp_title', 'ecwid_seo_title', 10000, 3 );

			add_filter( 'ecwid_set_mainpage_metadesc', array( $this, 'ecwid_set_mainpage_metadesc_hook' ) );

			add_filter( 'wpseo_output_twitter_card', '__return_false' );

			add_filter( 'wpseo_sitemap_index', array( $this, 'wpseo_hook_sitemap_index' ) );
			add_filter( 'wpseo_do_sitemap_ecstore', array( $this, 'wpseo_hook_do_sitemap' ) );
		}

		add_filter( 'ecwid_title_separator', array( $this, 'get_title_separator' ) );
		add_action( 'init', array( $this, 'clear_ecwid_sitemap_index' ) );

		add_action( 'template_redirect', array( $this, 'force_clear_metatags' ) );
	}

	public function ecwid_set_mainpage_metadesc_hook( $set_metadesc ) {
		$post_id = get_the_ID();

		if ( class_exists( 'WPSEO_Meta' ) ) {
			$wpseo_metadesc = WPSEO_Meta::get_value( 'metadesc', $post_id );
		}

		if ( empty( $wpseo_metadesc ) ) {
			return true;
		}

		return $set_metadesc;
	}

	// Disable titles, descriptions and canonical link on ecwid _escaped_fragment_ pages
	public function disable_seo_on_escaped_fragment() {
		 add_filter( 'wpseo_canonical', array( $this, 'clear_canonical' ) );

		$is_store_page = Ecwid_Store_Page::is_store_page();
		$is_home_page  = Ecwid_Store_Page::is_store_home_page();

		if ( $is_store_page && ! $is_home_page ) {
			add_filter( 'wpseo_metadesc', '__return_false' );
			add_filter( 'wpseo_json_ld_output', '__return_false' );

			foreach ( $this->og_drop as $name ) {
				add_filter( 'wpseo_opengraph_' . $name, '__return_false' );
			}
		}

		$is_escaped_fragment = array_key_exists( '_escaped_fragment_', $_GET );
		$is_seo_pb_url       = Ecwid_Seo_Links::is_product_browser_url();

		$no_canonical_or_meta = $is_store_page && ( $is_escaped_fragment || $is_seo_pb_url );
		if ( ! $no_canonical_or_meta ) {
			return;
		}

		global $wpseo_front;
		// Canonical

		if ( empty( $wpseo_front ) && class_exists( 'WPSEO_Frontend' ) ) {
			$wpseo_front = WPSEO_Frontend::get_instance();
		}

		remove_action( 'wpseo_head', array( $wpseo_front, 'canonical' ), 20 );
		// Description
		remove_action( 'wpseo_head', array( $wpseo_front, 'metadesc' ), 10 );
	}

	public function clear_canonical( $canonical ) {

		if ( Ecwid_Store_Page::is_store_page() ) {

			$is_home_page                        = Ecwid_Store_Page::is_store_home_page();
			$is_store_page_with_default_category = Ecwid_Store_Page::is_store_page_with_default_category();

			if ( ! $is_home_page || $is_store_page_with_default_category ) {
				return false;
			}
		}

		return $canonical;
	}

	public function disable_rewrite_titles() {
		global $wpseo_front;

		// Newer versions of WordPress SEO assign their rewrite on this stage
		remove_action( 'template_redirect', array( $wpseo_front, 'force_rewrite_output_buffer' ), 99999 );
	}

	public function force_clear_metatags() {
		$is_store_page = Ecwid_Store_Page::is_store_page();
		$is_home_page  = Ecwid_Store_Page::is_store_home_page();

		if ( $is_store_page && ! $is_home_page ) {
			ob_start();
			add_action( 'shutdown', array( $this, 'clear_output_metatags' ), 0 );
		}
	}

	public function clear_output_metatags() {
		$output = ob_get_contents();
		ob_end_clean();

		if ( substr_count( $output, '"og:image"' ) >= 1 ) {

			$og_tags = array( '', ':width', ':height', ':type' );
			foreach ( $og_tags as $og_tag ) {
				$output = preg_replace(
					'/<meta property="og:image' . $og_tag . '" content=".*?" \/>[[:space:]]*/',
					'',
					$output,
					1
				);
			}
		}

		echo $output; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function clear_ecwid_sitemap_index() {

		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';

		if ( strpos( $request_uri, 'sitemap_index.xml' ) !== false ) {
			ob_start();
			add_action( 'shutdown', array( $this, 'sitemap_clear' ), 0 );
		}
	}

	public function sitemap_clear() {
		$output = ob_get_contents();
		ob_end_clean();

		libxml_use_internal_errors( true );
		$xml = simplexml_load_string( $output );

		if ( false !== $xml ) {
			foreach ( $xml->sitemap as $sitemap ) {
				if ( strpos( (string) $sitemap->loc, 'ec-product' ) !== false ) {
					$dom = dom_import_simplexml( $sitemap );
					$dom->parentNode->removeChild( $dom );
				}
			}

			echo $xml->asXML(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			echo $output; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	// Hook that new sitemap type to sitemap
	public function wpseo_hook_sitemap_index() {
		$now = date( 'c', time() );

		$sitemap_url = $this->_get_base_url( 'ecstore-sitemap.xml' );
		return '<sitemap><loc>' . $sitemap_url . '</loc><lastmod>' . $now . '</lastmod></sitemap>';
	}

	// Hook that adds content to sitemap
	public function wpseo_hook_do_sitemap() {
		$this->sitemap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

		ecwid_build_sitemap( array( $this, 'sitemap_callback' ) );

		$this->sitemap .= '</urlset>';

		$sitemap       = $this->sitemap;
		$this->sitemap = null;

		$GLOBALS['wpseo_sitemaps']->set_sitemap( $sitemap );
	}

	// A callback for the streaming sitemap builder
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

	public function get_title_separator( $separator ) {
		if ( class_exists( 'WPSEO_Option_Titles' ) ) {
			$separator = wpseo_replace_vars( '%%sep%%', array() );
		}

		return $separator;
	}

	protected function _get_base_url( $page ) {
		if ( class_exists( 'WPSEO_Sitemaps_Router' ) && method_exists( 'WPSEO_Sitemaps_Router', 'get_base_url' ) ) {
			return WPSEO_Sitemaps_Router::get_base_url( $page );
		} else {
			return wpseo_xml_sitemaps_base_url( $page );
		}
	}
}

$ecwid_integration_wpseo = new Ecwid_Integration_WordPress_SEO_By_Yoast();
