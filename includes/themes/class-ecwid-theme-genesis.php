<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_Genesis extends Ecwid_Theme_Base
{
	protected $name = 'Genesis';

	public function __construct()
	{
		parent::__construct();

		if ( array_key_exists( '_escaped_fragment_', $_GET ) && Ecwid_Store_Page::is_store_page() ) {
			remove_action( 'wp_head', 'genesis_canonical', 5 );
		}
	}
}

$ecwid_current_theme = new Ecwid_Theme_Genesis();