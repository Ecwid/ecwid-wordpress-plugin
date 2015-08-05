<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_2014 extends Ecwid_Theme_Base
{
	protected $name = 'Twenty Fourteen';

	protected $adjust_pb_scroll = true;

	public function __construct()
	{
		parent::__construct();

		if (ecwid_page_has_productbrowser()) {
			wp_enqueue_style( 'ecwid-theme', plugins_url( 'ecwid-shopping-cart/css/themes/2014.css' ), array('twentyfourteen-style'), get_option('ecwid_plugin_version') );
		}
	}
}

$ecwid_current_theme = new Ecwid_Theme_2014();