<?php

class Ecwid_Admin {
	public function __construct()
	{
		if ( is_admin() ) {
			add_action( 'current_screen', array( $this, 'do_ec_redirect' ) );
			add_action('admin_menu', array( $this, 'build_menu' ) );
		}
	}

	public function build_menu() {

		$is_newbie = get_ecwid_store_id() == ECWID_DEMO_STORE_ID;

		add_menu_page(
			__('Ecwid shopping cart settings', 'ecwid-shopping-cart'),
			__('Ecwid Store', 'ecwid-shopping-cart'),
			'manage_options',
			'ec',
			'ecwid_general_settings_do_page',
			'',
			'2.562347345'
		);

		if ($is_newbie) {
			$title = __('Setup', 'ecwid-shopping-cart');
		} else {
			$title = __('Dashboard', 'ecwid-shopping-cart');
		}
		add_submenu_page(
			'ec',
			$title,
			$title,
			'manage_options',
			'ecwid',
			'ecwid_general_settings_do_page'
		);

		global $ecwid_oauth;
		if (!$is_newbie && $ecwid_oauth->has_scope( 'allow_sso' )) {
			add_submenu_page(
				'ec',
				__('Sales', 'ecwid-shopping-cart'),
				__('Sales', 'ecwid-shopping-cart'),
				'manage_options',
				'ec-admin-orders',
				'ecwid_admin_orders_do_page'
			);


			add_submenu_page(
				'ec',
				__('Products', 'ecwid-shopping-cart'),
				__('Products', 'ecwid-shopping-cart'),
				'manage_options',
				'ec-admin-products',
				'ecwid_admin_products_do_page'
			);
		}
		if (get_option('ecwid_hide_appearance_menu') != 'Y') {
			add_submenu_page(
				'ec',
				__('Appearance settings', 'ecwid-shopping-cart'),
				__('Appearance', 'ecwid-shopping-cart'),
				'manage_options',
				'ec-appearance',
				'ecwid_appearance_settings_do_page'
			);
		}

		if (!$is_newbie || (isset($_GET['page']) && $_GET['page'] == 'ecwid-advanced')) {
			add_submenu_page(
				'ec',
				__('Advanced settings', 'ecwid-shopping-cart'),
				__('Advanced', 'ecwid-shopping-cart'),
				'manage_options',
				'ec-advanced',
				'ecwid_advanced_settings_do_page'
			);
		}

		add_submenu_page('', 'Ecwid debug', '', 'manage_options', 'ec_debug', 'ecwid_debug_do_page');
		add_submenu_page('', 'Ecwid get mobile app', '', 'manage_options', 'ec-admin-mobile', 'ecwid_admin_mobile_do_page');
		add_submenu_page(
			'ec',
			__('Help', 'ecwid-shopping-cart'),
			__('Help', 'ecwid-shopping-cart'),
			'manage_options', 'ec-help', 'ecwid_help_do_page'
		);
		add_submenu_page('', 'Install ecwid theme', '', 'manage_options', 'ecwid-install-theme', 'ecwid_install_theme');

		add_submenu_page('', 'Ecwid sync', '', 'manage_options', 'ec-sync', 'ecwid_sync_do_page');

		$pages = array(
			'ecwid',
			'ecwid-admin-orders',
			'ecwid-admin-products',
			'ecwid-appearance',
			'ecwid-advanced',
			'ecwid-help',
			'ecwid_debug',
			'ecwid-sync'
		);

		foreach ($pages as $page) {
			add_submenu_page('', 'Legacy', '', 'manage_options', $page, 'ecwid_do_ec_redirect');
		}
	}

	public function do_ec_redirect() {

		$screen = get_current_screen();

		$base = $screen->base;
		if ( strpos( $base, 'admin_page_ecwid' ) === false ) return;

		$page = str_replace('admin_page_ecwid', 'ec', $base );

		wp_redirect( admin_url('admin.php?page=' . $page ), 301 );
		exit();
	}

	static public function get_dashboard_url() {
		return admin_url( 'admin.php?page=ec' );
	}
}

$ecwid_admin = new Ecwid_Admin();