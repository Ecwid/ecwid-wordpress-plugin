<?php
class Ecwid_Integration_LitespeedCache
{
	public function __construct()
	{
		add_filter( 'litespeed_optimize_js_excludes', array( $this, 'hook_js_exclude' ) );
		add_filter( 'litespeed_optimize_css_excludes', array( $this, 'hook_css_exclude' ) );
	}

	public function hook_css_exclude($exclude) {

		$exclude[] = 'ecwid-shopping-cart/css/fonts.css';

		return $exclude;
	}

	public function hook_js_exclude($exclude) {
		$exclude[] = Ecwid_Static_Page::HANDLE_STATIC_PAGE . '.js';
		$exclude[] = 'script.js?' . EcwidPlatform::get_store_id();

		return $exclude;
	}
}

$ecwid_integration_litespeed_cache = new Ecwid_Integration_LitespeedCache();