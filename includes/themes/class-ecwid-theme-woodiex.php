<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_Woodiex extends Ecwid_Theme_Base
{

	public function __construct()
	{
		parent::__construct();

		add_filter('wp_get_nav_menu_items', array( $this, 'process_menu_items' ));
	}

	public function process_menu_items($items)
	{

		if( is_array($items) ) {
			foreach($items as $key => $item) {
				if( !isset($item->ecwid_page_type) || $item->ecwid_page_type != 'category' ) {
					continue;
				}

				$items[$key]->ID = 0;
			}
		}

		return $items;
	}
}

return new Ecwid_Theme_Woodiex();