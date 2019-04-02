<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Vantage extends Ecwid_Theme_Base
{
	protected $name = 'Vantage';

	public function __construct()
	{
		parent::__construct();

		add_action( 'ecwid_plugin_installed', array( $this, 'on_ecwid_plugin_installed' ) );
	}

	public function on_ecwid_plugin_installed()
	{
		$option_fixed_position = get_option( Ecwid_Floating_Minicart::OPTION_FIXED_POSITION );

		update_option( Ecwid_Floating_Minicart::OPTION_FIXED_POSITION, 'BOTTOM_LEFT' );
	}
}

return new Ecwid_Vantage();