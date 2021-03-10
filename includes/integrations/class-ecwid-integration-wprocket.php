<?php

class Ecwid_Integration_WPRocket
{
	public function __construct()
	{
		add_filter( 'rocket_excluded_inline_js_content', array( $this, 'hook_excluded_inline_js' ) );
		add_filter( 'rocket_exclude_defer_js', array( $this, 'hook_excluded_defer_js' ) );
	}

	public function hook_excluded_inline_js($excluded_inline_js) {
		
		$excluded_inline_js[] = 'window.ec.';

		if ( Ecwid_Static_Page::is_enabled_static_home_page() ) {
			$excluded_inline_js[] = 'EcStaticPageUtils';
		} else {
			$excluded_inline_js[] = 'createClass';
		}

		return $excluded_inline_js;
	}

	public function hook_excluded_defer_js($exclude_defer_js) {
		$exclude_defer_js[] = Ecwid_Config::get_scriptjs_domain();

		return $exclude_defer_js;
	}
}

$ecwid_integration_wprocket = new Ecwid_Integration_WPRocket();