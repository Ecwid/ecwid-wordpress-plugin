<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_ResonsiveBoat extends Ecwid_Theme_Base
{
	protected $name = 'ResponsiveBoat';

	public function __construct()
	{
		parent::__construct();

		wp_enqueue_style( 'ecwid-theme-fixes' , ECWID_PLUGIN_URL . 'css/themes/responsiveboat.css', array(), get_option('ecwid_plugin_version'), 'all' );
	}
}

$ecwid_current_theme = new Ecwid_Theme_ResonsiveBoat();