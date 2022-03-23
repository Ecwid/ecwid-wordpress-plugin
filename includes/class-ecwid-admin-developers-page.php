<?php
class Ecwid_Admin_Developers_Page
{
	const ADMIN_SLUG = 'ec-store-developers';
	public static $templates_dir;
	
	public function __construct() {

	}

	public static function do_page() {

		self::$templates_dir = ECWID_PLUGIN_DIR . '/templates/admin/';

        echo Ecwid_Admin_UI_Framework::print_fix_js();
		require_once self::$templates_dir . 'developers.php';

		// require_once ECWID_PLUGIN_DIR . 'templates/admin-footer.php';
	}
}

$_ecwid_admin_developers_page = new Ecwid_Admin_Developers_Page();