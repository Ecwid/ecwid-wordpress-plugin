<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_Bridge extends Ecwid_Theme_Base
{
	protected $name = 'Bridge';

	public function __construct()
	{
		parent::__construct();

		add_filter( Ecwid_Ajax_Defer_Renderer::FILTER_ENABLED, array( $this, 'filter_enabled_defer_rendering' ) );
	}

	public function filter_enabled_defer_rendering()
	{
		if( function_exists( 'bridge_qode_options' ) ) {
			return bridge_qode_options()->getOptionValue('page_transitions');
		}

		return false;
	}
}

return new Ecwid_Theme_Bridge();