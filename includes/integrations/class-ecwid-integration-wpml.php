<?php

class Ecwid_Integration_WPML
{
	public function __construct()
	{
		add_filter( 'ecwid_lang', array( $this, 'force_scriptjs_lang' ) );
	}

	public function force_scriptjs_lang( $lang ) 
	{
		return ICL_LANGUAGE_CODE;
	}
}

$ecwid_integration_wpml = new Ecwid_Integration_WPML();