<?php

class Ecwid_Integration_Autoptimize
{
	public function __construct()
	{
		add_filter( 'ecwid_shortcode_content', array( $this, 'ecwid_shortcode_content' ) );

		add_filter( 'autoptimize_filter_js_movelast', array( $this, 'hook_js_movelast' ) );
		add_filter( 'autoptimize_filter_js_exclude', array( $this, 'hook_js_exclude' ) );
	}

	public function ecwid_shortcode_content($content) {
		return '<!-- noptimize -->' . $content . '<!-- /noptimize -->';
	}

	public function hook_js_movelast($scripts) {
		if ((bool)get_option('ecwid_use_chameleon')) {
			$scripts[] = 'chameleon.js';
		}

		return $scripts;
	}

	public function hook_js_exclude($exclude) {
		$code = ecwid_get_search_js_code();
		
		if ( Ecwid_Static_Home_Page::is_enabled() ) {
			$code .= ", " . Ecwid_Static_Home_Page::HANDLE_STATIC_PAGE . ".js";
		}
		
		return $exclude . ", $code";
	}
}

$ecwid_integration_autoptimize = new Ecwid_Integration_Autoptimize();