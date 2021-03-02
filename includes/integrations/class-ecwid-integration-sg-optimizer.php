<?php

class Ecwid_Integration_SG_Optimizer
{
	public function __construct()
	{
		add_filter( 'sgo_javascript_combine_excluded_inline_content', array( $this, 'exclude_inline_js' ) );
	}

	public function exclude_inline_js( $js_exclude ) {
		
		$js_exclude[] = 'window.ec.';
		
		if ( Ecwid_Static_Page::is_enabled_static_home_page() ) {
			$js_exclude[] = 'EcStaticPageUtils';
		} else {
			$js_exclude[] = 'createClass';
		}

		return $js_exclude;
	}
}

$ecwid_integration_sg_optimizer = new Ecwid_Integration_SG_Optimizer();