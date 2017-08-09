<?php 


class Ecwid_API {
	public static function is_store_page( $page_id ) {
		require_once ECWID_PLUGIN_DIR . '/class-ecwid-store-page.php';
		
		Ecwid_Store_Page::is_store_page( $page_id );
	}
}