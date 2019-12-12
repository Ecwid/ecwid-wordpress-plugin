<?php

class Ecwid_Integration_WPRocket
{
	public function __construct()
	{
		add_filter( 'rocket_excluded_inline_js_content', array( $this, 'hook_excluded_inline_js' ) );
	}

	public function hook_excluded_inline_js($inline_js) {
		$code = ecwid_get_search_js_code();
		
		if ( Ecwid_Static_Page::is_enabled_static_home_page() ) {
			$exclude_defer_js[] = 'EcStaticPageUtils';
			$exclude_defer_js[] = 'window.ec.storefront';
		} else {
			$exclude_defer_js[] = 'createClass';
		}

		return $exclude_defer_js;
	}
}

$ecwid_integration_wprocket = new Ecwid_Integration_WPRocket();