<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_Vantage extends Ecwid_Theme_Base
{
	public function __construct()
	{
		parent::__construct();

		wp_enqueue_script( 'ecwid-theme', ECWID_PLUGIN_URL . 'js/themes/vantage.js', array( 'jquery' ), get_option('ecwid_plugin_version'), true );
	}
}

return  new Ecwid_Theme_Vantage();