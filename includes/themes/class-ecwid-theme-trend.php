<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_Trend extends Ecwid_Theme_Base
{
	protected $name = 'Trend';

	public function __construct()
	{
		parent::__construct();

		add_filter('ecwid_disable_widgets', __return_true);
	}
}

$ecwid_current_theme = new Ecwid_Theme_Bretheon();