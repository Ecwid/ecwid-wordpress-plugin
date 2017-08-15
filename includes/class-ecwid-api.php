<?php 


class Ecwid_API {
	public static function page_contains_product_browser( $page_id = 0 ) {
		require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-store-page.php';
		
		return Ecwid_Store_Page::is_store_page( $page_id );
	}
}