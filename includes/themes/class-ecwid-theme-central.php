<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_Central extends Ecwid_Theme_Base
{
	protected $name = 'Central';

	protected $adjust_pb_scroll = true;

	public function __construct()
	{
		parent::__construct();

		add_filter('body_class', array( $this, 'body_class') );
	}

	public function body_class($classes) {
		// Yeah, we have to to turn off these ajax click handling routines that break our links
		if ( Ecwid_Store_Page::is_store_page() ) {
			$classes[] = 'woocommerce';
		}

		return $classes;
	}

}

$ecwid_current_theme = new Ecwid_Theme_Central();