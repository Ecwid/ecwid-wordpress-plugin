<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_Customizr extends Ecwid_Theme_Base
{
	protected $name = 'Customizr';

	protected $adjust_pb_scroll = true;

	public function __construct()
	{
		parent::__construct();

		wp_enqueue_script( 'ecwid-theme-js', plugins_url( 'ecwid-shopping-cart/js/themes/customizr.js' ), array( 'jquery' ), get_option('ecwid_plugin_version'), true );
		wp_enqueue_style( 'ecwid-theme-fixes' , plugins_url( 'ecwid-shopping-cart/css/themes/customizr.css' ), array(), get_option('ecwid_plugin_version'), 'all' );
	}
}

$ecwid_current_theme = new Ecwid_Theme_Customizr();