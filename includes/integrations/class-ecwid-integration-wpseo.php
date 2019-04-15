<?php

class Ecwid_Integration_WordPress_SEO_By_Yoast
{
	// Store intermediate sitemap generation results here
	protected $sitemap = array();
	protected $_og_drop = array( 'title', 'description', 'image', 'type', 'url' );
	protected $_twitter_drop = array( 'title', 'description', 'image', 'card_type' );
	
	public function __construct()
	{
		if ( !Ecwid_Api_V3::is_available() ) {
			return;
		}
		
		add_action( 'wp', array( $this, 'disable_seo_on_escaped_fragment' ) );
		add_action( 'template_redirect', array( $this, 'disable_rewrite_titles' ) );

		if ( ecwid_is_paid_account() && ecwid_is_store_page_available()) {
			add_filter( 'wpseo_sitemap_index', array( $this, 'wpseo_hook_sitemap_index' ) );
			add_filter( 'wpseo_do_sitemap_ecwid', array( $this, 'wpseo_hook_do_sitemap' ) );

			if ( ecwid_is_applicable_escaped_fragment() || Ecwid_Seo_Links::is_product_browser_url() ) {
				add_filter( 'wpseo_title', 'ecwid_seo_title' );
				remove_filter( 'wp_title', 'ecwid_seo_title' , 10000, 3 );
				add_filter( 'wpseo_metadesc', '__return_false' );

				add_filter( 'ecwid_og_tags', array( $this, 'filter_og_tags' ) );
				foreach ( $this->_og_drop as $name ) {
					add_filter( 'wpseo_og_' . "og_$name", '__return_empty_string' );
				}

				add_filter( 'wpseo_output_twitter_card', '__return_false' );
			}

			add_filter( 'ecwid_set_mainpage_metadesc', array( $this, 'ecwid_set_mainpage_metadesc_hook' ) );
		}

		add_filter( 'ecwid_title_separator', array( $this, 'get_title_separator' ) );

		add_action( 'init', array($this, 'clear_ecwid_sitemap_index') );
	}

	public function ecwid_set_mainpage_metadesc_hook( $set_metadesc ) {
		
		global $wpseo_front;

		if ( empty($wpseo_front) && class_exists('WPSEO_Frontend') ) {
			$wpseo_front = WPSEO_Frontend::get_instance();
		}

		$wpseo_metadesc = $wpseo_front->metadesc(false);
		
		if( empty($wpseo_metadesc) ) {
			return true;
		}
		
		return $set_metadesc;
	}

	public function clear_ecwid_sitemap_index() {

		if( strpos( $_SERVER['REQUEST_URI'], 'sitemap_index.xml' ) !== false ) {
			ob_start();
			add_action('shutdown', array($this, 'shutdown_hook_ecwid_sitemap_clear'), 0);
		}
	}

	public function shutdown_hook_ecwid_sitemap_clear()
	{
		$output = ob_get_contents();
	    ob_end_clean();

	    libxml_use_internal_errors(true);
	    $xml = simplexml_load_string($output);

	    if($xml !== false) {
		    foreach ($xml->sitemap as $sitemap) {
		    	if( strpos( (string) $sitemap->loc, 'ec-product') !== false ) {
		    		$dom = dom_import_simplexml($sitemap);
					$dom->parentNode->removeChild($dom);
		    	}
		    }

		    echo $xml->asXML();
		} else {
			echo $output;
		}
	}

	public function filter_og_tags( $tags )
	{
		unset( $tags['locale'] );
		unset( $tags['site_name'] );
		
		return $tags;
	}
	
	// Disable titles, descriptions and canonical link on ecwid _escaped_fragment_ pages
	public function disable_seo_on_escaped_fragment()
	{
		$is_store_page = Ecwid_Store_Page::is_store_page();
		$is_escaped_fragment = array_key_exists('_escaped_fragment_', $_GET);
		$is_seo_pb_url = Ecwid_Seo_Links::is_product_browser_url();

		$no_canonical_or_meta = $is_store_page && ( $is_escaped_fragment || $is_seo_pb_url );
		if ( !$no_canonical_or_meta ) {
			return;
		}

		global $wpseo_front;
		// Canonical

		if (empty($wpseo_front)) {
			$wpseo_front = WPSEO_Frontend::get_instance();
		}

		remove_action( 'wpseo_head', array( $wpseo_front, 'canonical' ), 20 );
		// Description
		remove_action( 'wpseo_head', array( $wpseo_front, 'metadesc' ), 10 );
	}

	public function disable_rewrite_titles()
	{
		global $wpseo_front;

		// Newer versions of Wordpress SEO assign their rewrite on this stage
		remove_action( 'template_redirect', array( $wpseo_front, 'force_rewrite_output_buffer' ), 99999 );
	}

	// Hook that new sitemap type to aiosp sitemap
	public function wpseo_hook_sitemap_index( )
	{
		$now = date('c', time());;
		$sitemap_url = $this->_get_base_url( 'ecwid-sitemap.xml' );
		return <<<XML
		<sitemap>
			<loc>$sitemap_url</loc>
			<lastmod>$now</lastmod>
		</sitemap>
XML;
	}

	// Hook that adds content to aiosp sitemap
	public function wpseo_hook_do_sitemap()
	{

		$this->sitemap = <<<XML
<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:image="http://www.google.com/schemas/sitemap-image/1.1" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
XML;


		ecwid_build_sitemap( array($this, 'sitemap_callback') );

		$this->sitemap .= '</urlset>';

		$sitemap = $this->sitemap;
		$this->sitemap = null;

		$GLOBALS['wpseo_sitemaps']->set_sitemap($sitemap);
	}

	// A callback for the streaming sitemap builder
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

	public function get_title_separator($separator)
	{
		if (class_exists('WPSEO_Option_Titles')) {
			$separator = wpseo_replace_vars( '%%sep%%', array() );
		}

		return $separator;
	}
	
	protected function _get_base_url( $page ) {
		if ( class_exists( 'WPSEO_Sitemaps_Router' ) && method_exists( 'WPSEO_Sitemaps_Router', 'get_base_url' ) ) {
			return WPSEO_Sitemaps_Router::get_base_url( $page );
		} else {
			return wpseo_xml_sitemaps_base_url ( $page );
		}
	}
}

$ecwid_integration_wpseo = new Ecwid_Integration_WordPress_SEO_By_Yoast();
