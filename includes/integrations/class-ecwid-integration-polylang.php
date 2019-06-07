<?php

class Ecwid_Integration_Polylang
{
	public function __construct() {
		add_filter( 'ecwid_lang', array( $this, 'force_scriptjs_lang' ) );
	}

	public function force_scriptjs_lang( $lang ) {
		
		$lang = pll_current_language();

		return $lang;
	}
}

$ecwid_integration_polylang = new Ecwid_Integration_Polylang();