<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_Pagelines extends Ecwid_Theme_Base
{
	protected $name = 'Pagelines';

	protected $adjust_pb_scroll = true;

	public function __construct()
	{
		parent::__construct();

		wp_enqueue_script( 'ecwid-theme-js', plugins_url( 'ecwid-shopping-cart/js/themes/pagelines.js' ), array( 'jquery' ), get_option('ecwid_plugin_version'), true );
	}
}

$ecwid_current_theme = new Ecwid_Theme_Pagelines();