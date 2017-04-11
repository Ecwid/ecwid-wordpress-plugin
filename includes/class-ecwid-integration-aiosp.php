<?php

class Ecwid_Integration_All_In_One_SEO_Pack
{
	// Store intermediate sitemap generation results here
	protected $sitemap = array();

	public function __construct()
	{
		add_action( 'wp', array( $this, 'disable_seo_on_escaped_fragment' ) );

		add_filter( 'aiosp_sitemap_extra', array( $this, 'aiosp_hook_sitemap_extra' ) );
		add_filter( 'aiosp_sitemap_custom_ecwid', array( $this, 'aiosp_hook_sitemap_content') );
	}

	// Disable titles, descriptions and canonical link on ecwid _escaped_fragment_ pages
	public function disable_seo_on_escaped_fragment()
	{
		if (!array_key_exists('_escaped_fragment_', $_GET) || !Ecwid_Store_Page::is_store_page()) {
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