<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_2012 extends Ecwid_Theme_Base
{
	protected $name = 'Twenty Twelve';

	protected $adjust_pb_scroll = true;

	public function __construct()
	{
		parent::__construct();

		if (ecwid_page_has_productbrowser()) {
			wp_enqueue_script( 'ecwid-theme', plugins_url( 'ecwid-shopping-cart/js/themes/2012.js' ), array( 'jquery' ), get_option('ecwid_plugin_version'), true );
		}
	}
}

$ecwid_current_theme = new Ecwid_Theme_2012();