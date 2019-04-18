<?php

class Ecwid_Integration_All_In_One_SEO_Pack
{
	// Store intermediate sitemap generation results here
	protected $sitemap = array();

	public function __construct()
	{
		if ( !Ecwid_Api_V3::is_available() ) {
			return;
		}

		add_action( 'wp', array( $this, 'disable_seo_if_needed' ) );

		add_filter( 'aiosp_sitemap_extra', array( $this, 'aiosp_hook_sitemap_extra' ) );
		add_filter( 'aiosp_sitemap_custom_ecwid', array( $this, 'aiosp_hook_sitemap_content') );
		add_filter( 'aiosp_sitemap_prio_item_filter', array($this, 'aiosp_hook_sitemap_prio_item_filter'), 10, 3 );
	}

	public function aiosp_hook_sitemap_prio_item_filter($pr_info, $post, $args)
	{
		$post_type = (string)$post->post_type;

		if( $post_type == 'ec-product' ) {
			return false;
		}

		if( $post_type == 'attachment' && strpos($pr_info['loc'], Ecwid_Store_Page::get_store_url()) === 0 ){
			return false;
		}

		return $pr_info;
	}

	// Disable titles, descriptions and canonical link on ecwid _escaped_fragment_ pages
	public function disable_seo_if_needed()
	{
		if (! Ecwid_Store_Page::is_store_page() ) {
			return;
		}

		$is_escaped_fragment = array_key_exists('_escaped_fragment_', $_GET);
		$is_seo_links_store_page = Ecwid_Seo_Links::is_enabled() && Ecwid_Seo_Links::is_product_browser_url();
		
		if ( !$is_escaped_fragment && !$is_seo_links_store_page ) {
			return;
		}

		global $aioseop_options;

		$aioseop_options['aiosp_can'] = false;
		add_filter( 'aioseop_title', '__return_null' );
		add_filter( 'aioseop_description', '__return_null' );
	}

	// Hook that new sitemap type to aiosp sitemap
	public function aiosp_hook_sitemap_extra( $params )
	{
		return array_merge($params, array('ecwid'));
	}

	// Hook that adds content to aiosp sitemap
	public function aiosp_hook_sitemap_content()
	{

		$this->sitemap = array();

		ecwid_build_sitemap( array($this, 'sitemap_callback') );

		$sitemap = $this->sitemap;
		$this->sitemap = null;

		return $sitemap;
	}

	// A callback for the streaming sitemap builder
	public function sitemap_callback($url, $priority, $frequency)
	{
		array_push($this->sitemap, array(
			'loc' => $url,
			'priority' => $priority,
			'changefreq' => $frequency
		));
	}
}

$ecwid_integration_aiosp = new Ecwid_Integration_All_In_One_SEO_Pack();