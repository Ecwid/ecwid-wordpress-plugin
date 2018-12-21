<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_Themify_Music extends Ecwid_Theme_Base
{
	protected $name = 'Boundless';

	public function __construct()
	{
		parent::__construct();

		add_filter( Ecwid_Ajax_Defer_Renderer::FILTER_ENABLED, array( $this, 'filter_enabled_defer_rendering' ) );
	}
	
	public function filter_enabled_defer_rendering() {
		$data = themify_get_data('disable_ajax');
		
		return @$data['setting-disable_ajax'] != 'on';
	}
}

return new Ecwid_Theme_Themify_Music();