<?php
class Ecwid_Admin_Developers_Page {

	const ADMIN_SLUG = 'ec-store-developers';
	public static $templates_dir;

	public function __construct() {

	}

	public static function do_page() {

		global $current_user;
		$admin_email = $current_user->user_email;

		if ( ! ecwid_is_demo_store() ) {
			$api         = new Ecwid_Api_V3();
			$profile     = $api->get_store_profile();
			$admin_email = $profile->account->accountEmail;
		}

		self::$templates_dir = ECWID_PLUGIN_DIR . '/templates/admin/';

		Ecwid_Admin_UI_Framework::print_fix_js();
		require_once self::$templates_dir . 'developers.php';
	}
}

$_ecwid_admin_developers_page = new Ecwid_Admin_Developers_Page();
