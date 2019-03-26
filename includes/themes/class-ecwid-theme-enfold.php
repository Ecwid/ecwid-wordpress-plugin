<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_Enfold extends Ecwid_Theme_Base
{
	public function __construct()
	{
		parent::__construct();

		add_action( 'wp_ajax_avia_ajax_text_to_preview', array( $this, 'disable_shortcode_content' ) );
	}
	
	public function disable_shortcode_content()
	{
		if( isset($_REQUEST['avia_request']) && isset($_REQUEST['text']) && has_shortcode($_REQUEST['text'], 'ecwid')) {
			echo __('To see the items you want to publish the page or turn on Preview.');
			die();
		}
	}
}

return new Ecwid_Theme_Enfold();