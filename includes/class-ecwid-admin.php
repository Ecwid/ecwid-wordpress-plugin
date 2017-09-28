<?php

class Ecwid_Admin {

	const ADMIN_SLUG = 'ec-store';

	public function __construct()
	{
		if ( is_admin() ) {
			add_action( 'current_screen', array( $this, 'do_ec_redirect' ) );
			add_action('admin_menu', array( $this, 'build_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}
	
	public function enqueue_scripts() {
		$menu = self::_get_menus();
		
		wp_enqueue_script('ecwid-admin-menu', ECWID_PLUGIN_URL . 'js/admin-menu.js', array(), get_option('ecwid_plugin_version'));

		wp_localize_script('ecwid-admin-menu', 'ecwid_admin_menu', array(
			'dashboard' => __('Dashboard', 'ecwid-shopping-cart'),
			'dashboard_url' => Ecwid_Admin::get_relative_dashboard_url(),
			'menu' => $menu,
			'baseSlug' => self::ADMIN_SLUG
		));		
	}

	public function build_menu()
	{

		$is_newbie = get_ecwid_store_id() == ECWID_DEMO_STORE_ID;

		add_menu_page(
			sprintf(__('%s shopping cart settings', 'ecwid-shopping-cart'), Ecwid_Config::get_brand()),
			sprintf(__('%s Store', 'ecwid-shopping-cart'), Ecwid_Config::get_brand()),
			'manage_options',
			self::ADMIN_SLUG,
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
			self::ADMIN_SLUG,
			$title,
			$title,
			'manage_options',
			self::ADMIN_SLUG,
			'ecwid_general_settings_do_page'
		);

		
		global $ecwid_oauth;
		if (!$is_newbie && $ecwid_oauth->has_scope('allow_sso') && !get_option('ecwid_disable_dashboard')) {
			
			$menu = $this->_get_menus();
			
			foreach ($menu as $slug => $item) {
				add_submenu_page(
					self::ADMIN_SLUG,
					$item['name'],
					$item['name'],
					self::get_capability(),
					self::ADMIN_SLUG . '-admin-' . $slug,
					array( $this, 'do_admin_page' )
				);

				if (@$item['items']) foreach ($item['items'] as $subslug => $subitem) {
					add_submenu_page(
						null,
						$item['name'],
						$item['name'],
						self::get_capability(),
						self::ADMIN_SLUG . '-admin-' . $subslug,
						array( $this, 'do_admin_page' )
					);
				}
			}
		}
		
		if (!$is_newbie || (isset($_GET['page']) && $_GET['page'] == 'ecwid-advanced')) {
			add_submenu_page(
				self::ADMIN_SLUG,
				__('Advanced settings', 'ecwid-shopping-cart'),
				__('Advanced', 'ecwid-shopping-cart'),
				self::get_capability(),
				self::ADMIN_SLUG . '-advanced',
				'ecwid_advanced_settings_do_page'
			);
		}

		add_submenu_page('', 'Ecwid debug', '', self::get_capability(), 'ec_debug', 'ecwid_debug_do_page');
		add_submenu_page('', 'Ecwid get mobile app', '', self::get_capability(), 'ec-admin-mobile', 'ecwid_admin_mobile_do_page');

		if (!Ecwid_Config::is_wl()) {
			add_submenu_page(
				self::ADMIN_SLUG,
				__('Help', 'ecwid-shopping-cart'),
				__('Help', 'ecwid-shopping-cart'),
				'manage_options', self::ADMIN_SLUG . '-help', 'ecwid_help_do_page'
			);
		}

		add_submenu_page('', 'Install ecwid theme', '', 'manage_options', 'ecwid-install-theme', 'ecwid_install_theme');

		add_submenu_page('', 'Ecwid sync', '', 'manage_options', 'ec-sync', 'ecwid_sync_do_page');

		$pages = array(
			'ecwid',
			'ecwid-admin-orders',
			'ecwid-admin-products',
			'ecwid-appearance',
			'ecwid-advanced',
			'ecwid-help',
			'ecwid-debug',
			'ecwid-sync'
		);

		foreach ($pages as $page) {
			add_submenu_page( '', 'Legacy', '', 'manage_options', $page, array( $this, 'do_ec_redirect' ) );
		}
	}

	public function do_admin_page()
	{
		$menus = $this->_get_menus();
		
		$admin_prefix = self::ADMIN_SLUG . '-admin-';
		$slug = get_current_screen()->base;
		$slug = substr(get_current_screen()->base, strpos($slug, $admin_prefix) + strlen($admin_prefix)); 

		ecwid_admin_do_page($menus[$slug]);	
	}
	
	protected function _get_menus()
	{
		$menu = EcwidPlatform::get('admin_menu');

		if (is_null($menu)) {
			$menu = array(
				'products' => array(
					'name' => __('Products', 'ecwid-shopping-cart'),
					'place' => 'products'
				),
				'orders' => array(
					'name' => __('Sales', 'ecwid-shopping-cart'),
					'place' => 'orders'
				),
				'discount-coupons' => array(
					'name' => __('Discount Coupons', 'ecwid-shopping-cart'),
					'place' => 'discount-coupons',
					'items' => array(
						'marketplaces' => array(
							'name' => 'Marketplaces',
							'place' => 'marketplaces'
						),
						'facebookapp' => array(
							'name' => 'Fb app',
							'place' => 'facebook-app'
						)
					)
				)
			);
		}
		
		foreach ($menu as $slug => $item) {
			$menu[$slug]['url'] = Ecwid_Admin::get_relative_dashboard_url() . '-admin-' . $slug;
			$menu[$slug]['slug'] = $slug;
			if (@$item['items']) foreach ($item['items'] as $slug2 => $item2) {
				$menu[$slug]['items'][$slug2]['url'] = Ecwid_Admin::get_relative_dashboard_url() . '-admin-' . $slug2;
				$menu[$slug]['items'][$slug2]['slug'] = $slug2;
			}
		}
		
		return $menu;
	}
	
	public function do_ec_redirect() {

		$screen = get_current_screen();

		$base = $screen->base;
		if ( strpos( $base, 'admin_page_ecwid' ) === false ) return;

		$page = str_replace('admin_page_ecwid', Ecwid_Admin::ADMIN_SLUG, $base );

		wp_redirect( admin_url('admin.php?page=' . $page ), 301 );
		exit();
	}

	static public function get_capability() {
		return 'manage_options';
	}
	
	static public function get_dashboard_url() {
		return admin_url( self::get_relative_dashboard_url() );
	}
	
	static public function get_relative_dashboard_url() {
		return 'admin.php?page=' . Ecwid_Admin::ADMIN_SLUG;
	}
}

$ecwid_admin = new Ecwid_Admin();