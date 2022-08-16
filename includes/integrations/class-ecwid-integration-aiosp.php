<?php

class Ecwid_Integration_All_In_One_SEO_Pack {

	/** Store intermediate sitemap generation results here */
	protected $sitemap = array();

	public $sitemap_url = 'ecstore-sitemap.xml';
	public $plugin_version;

	public function __construct() {
		if ( ! Ecwid_Api_V3::is_available() ) {
			return;
		}

		add_action( 'wp', array( $this, 'disable_seo_if_needed' ) );

		$plugin_data          = get_file_data( WP_PLUGIN_DIR . '/all-in-one-seo-pack/all_in_one_seo_pack.php', array( 'version' => 'Version' ), 'plugin' );
		$this->plugin_version = $plugin_data['version'];

		if ( version_compare( $this->plugin_version, '4.0.0', '>=' ) ) {
			add_filter( 'aioseo_sitemap_indexes', array( $this, 'add_sitemap_to_indexes' ) );
			add_action( 'init', array( $this, 'is_sitemap_page' ) );
		} else {
			add_filter( 'aiosp_sitemap_extra', array( $this, 'aiosp_hook_sitemap_extra' ) );
			add_filter( 'aiosp_sitemap_custom_ecwid', array( $this, 'aiosp_hook_sitemap_content' ) );
			add_filter( 'aiosp_sitemap_prio_item_filter', array( $this, 'aiosp_hook_sitemap_prio_item_filter' ), 10, 3 );
		}
	}

	// Disable titles, descriptions and canonical link on ecwid _escaped_fragment_ pages
	public function disable_seo_if_needed() {

		if ( ! Ecwid_Store_Page::is_store_page() ) {
			return;
		}

		if ( Ecwid_Store_Page::is_store_page_with_default_category() && Ecwid_Store_Page::is_store_home_page() ) {
			add_filter( 'ecwid_static_page_field_canonicalurl', '__return_false' );
		}

		if ( ! Ecwid_Store_Page::is_store_home_page() ) {
			add_filter( 'aioseo_facebook_tags', '__return_empty_array' );
			add_filter( 'aioseo_twitter_tags', '__return_empty_array' );

			if ( version_compare( $this->plugin_version, '4.0.0', '>=' ) ) {
				if ( class_exists( 'Ecwid_Static_Page' ) ) {
					add_filter( 'aioseo_title', 'Ecwid_Static_Page::get_title' );
				}

				add_filter( 'aioseo_description', '__return_null' );
				add_filter( 'aioseo_canonical_url', '__return_null' );
			} else {
				global $aioseop_options;
				$aioseop_options['aiosp_can'] = false;

				add_filter( 'aioseop_title', '__return_null' );
				add_filter( 'aioseop_description', '__return_null' );
				add_filter( 'aioseop_canonical_url', '__return_null' );
			}

			add_filter( 'aioseo_schema_disable', '__return_true' );
		}//end if
	}

	public function add_sitemap_to_indexes( $indexes ) {
		$indexes[] = array(
			'loc'     => home_url( $this->sitemap_url ),
			'lastmod' => '',
		);
		return $indexes;
	}

    // phpcs:disable
	public function is_sitemap_page() {
        $request_uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
        
        if ( strpos( $request_uri, $this->sitemap_url ) !== false ) {
		    echo $this->do_sitemap();
            exit;
        }
	}
    // phpcs:enable

	public function do_sitemap() {
		$charset = get_option( 'blog_charset' );
		header( "Content-Type: text/xml; charset=$charset", true );
		header( 'X-Robots-Tag: noindex, follow', true );
		header( 'HTTP/1.1 200 OK' );

		$this->sitemap  = '<?xml version="1.0" encoding="UTF-8"?>';
		$this->sitemap .= '<?xml-stylesheet type="text/xsl" href="default.xsl?sitemap=ecstore"?>';
		$this->sitemap .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml" xmlns:image="https://www.google.com/schemas/sitemap-image/1.1">';

		ecwid_build_sitemap( array( $this, 'get_sitemap_items_callback' ) );

		$this->sitemap .= '</urlset>';

		return $this->sitemap;
	}

	// A callback for the streaming sitemap builder
	public function get_sitemap_items_callback( $url, $priority, $frequency, $obj ) {
		$url        = htmlspecialchars( $url );
		$image_code = '';
		$image      = @$obj->originalImageUrl; //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		if ( $image ) {
			$image      = htmlspecialchars( $image );
			$title      = htmlspecialchars( $obj->name );
			$image_code = "
				<image:image>
					<image:title>$title</image:title>
					<image:loc>$image</image:loc>
				</image:image>";
		}

		$this->sitemap .= "
            <url>
                <loc>$url</loc>
                <changefreq>$frequency</changefreq>
                <priority>$priority</priority> $image_code
            </url>";
	}

	public function aiosp_hook_sitemap_prio_item_filter( $pr_info, $post, $args ) {
		$post_type = (string) $post->post_type;

		if ( 'ec-product' === $post_type ) {
			return false;
		}

		if ( 'attachment' === $post_type && strpos( $pr_info['loc'], Ecwid_Store_Page::get_store_url() ) === 0 ) {
			return false;
		}

		return $pr_info;
	}

	// Hook that new sitemap type to aiosp sitemap
	public function aiosp_hook_sitemap_extra( $params ) {
		return array_merge( $params, array( 'ecwid' ) );
	}

	// Hook that adds content to aiosp sitemap
	public function aiosp_hook_sitemap_content() {
		$this->sitemap = array();

		ecwid_build_sitemap( array( $this, 'sitemap_callback' ) );

		$sitemap       = $this->sitemap;
		$this->sitemap = null;

		return $sitemap;
	}

	// A callback for the streaming sitemap builder
	public function sitemap_callback( $url, $priority, $frequency, $item ) {
		array_push(
			$this->sitemap,
			array(
				'loc'        => $url,
				'priority'   => $priority,
				'changefreq' => $frequency,
			)
		);
	}
}

$ecwid_integration_aiosp = new Ecwid_Integration_All_In_One_SEO_Pack();
