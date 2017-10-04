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
			
			foreach ($menu as $item) {
				add_submenu_page(
					self::ADMIN_SLUG,
					$item['title'],
					$item['title'],
					self::get_capability(),
					$item['slug'],
					array( $this, 'do_admin_page' )
				);

				if (@$item['children']) foreach ($item['children'] as $subitem) {
					add_submenu_page(
						null,
						$subitem['title'],
						$subitem['title'],
						self::get_capability(),
						$subitem['slug'],
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

		if ( is_null( $menu ) ) {
			$menu = $this->_get_default_menu();
		}
		
		foreach ( $menu as $hash => $item ) {
			
			$title = '';
			if ( is_string( $item ) ) {
				$menu[$hash] = $item = array(
					'title' => $item
				);
			}
			
			$slug = $this->_slugify_ecwid_cp_hash( $hash, $item['title'] );
			$menu[$hash]['url'] = 'admin.php?page=' . $slug;
			$menu[$hash]['slug'] = $slug;
			$menu[$hash]['hash'] = $hash;

			if ( is_array( $item ) && @$item['children'] ) foreach ( $item['children'] as $hash2 => $item2 ) {
				
				if ( is_string( $item2 ) ) {
					$item2 = array(
						'title' => $item2
					);
				}
				
				$slug2 = $this->_slugify_ecwid_cp_hash( $hash2, $item2['title'] );
				$item2['url'] = 'admin.php?page=' . $slug2;
				$item2['slug'] = $slug2;
				$item2['hash'] = $hash2;

				$menu[$hash]['children'][$hash2] = $item2;
			}
		}
		
		return $menu;
	}
	
	protected function _slugify_ecwid_cp_hash( $hash, $title ) {
		
		if ( strpos( $hash, ':' ) === false ) {
			$slug = $hash;
		} else {
			$match = array();
			
			$slug = strtolower( $title );
			$result = preg_match_all( '#[\p{L}0-9\-_]+#u', strtolower( $title ), $match );
	
			if ( $result && count( @$match[0] ) > 0 ) {
				$slug = implode('-', $match[0] );
			}
		}
		
		$slug = self::ADMIN_SLUG . '-admin-' . $slug;
		
		return $slug;
	}

	protected function _get_default_menu() {
		static $default_menu = array();

		if ( !empty( $default_menu ) ) return $default_menu;

		$default_menu = array(
			'orders' => array(
				'title' => __( 'My Sales', 'ecwid-shopping-cart' ),
				'children' => array(
					'orders' 			=> __( 'Orders', 'ecwid-shopping-cart' ),
					'incomplete-orders' => __( 'Abandoned Carts', 'ecwid-shopping-cart' ),
					'customers'			=> __( 'Customers', 'ecwid-shopping-cart' ),
					'memberships'		=> __( 'Customer Groups', 'ecwid-shopping-cart' ),
					'app:name=ecwid-edit-orders' => __( 'Edit Orders', 'ecwid-shopping-cart' ),
				)
			),
			'products' => array(
				'title' => __( 'Catalog', 'ecwid-shopping-cart' ),
				'children' => array(
					'products' 					=> __( 'Products', 'ecwid-shopping-cart' ),
					'category:id=0&mode=edit' 	=> __( 'Categories', 'ecwid-shopping-cart' ),
					'product-classes'			=> __( 'Product Types', 'ecwid-shopping-cart' )
				)
			),
			'reports' => __( 'Reports', 'ecwid-shopping-cart' ),
			'discount-coupons' => array(
				'title' => __( 'Promotions', 'ecwid-shopping-cart' ),
				'children' => array(
					'discount-coupons' 	=> __( 'Discount Coupons', 'ecwid-shopping-cart' ),
					'discounts' 		=> __( 'Discounts', 'ecwid-shopping-cart' ),
					'facebook-app'		=> __( 'Sell on Facebook', 'ecwid-shopping-cart' ),
					'marketplaces'		=> __( 'Marketplaces', 'ecwid-shopping-cart' ),
					'paypal-credit'		=> __( 'Paypal Credit', 'ecwid-shopping-cart' ),
					'ebay-inventory'	=> __( 'eBay', 'ecwid-shopping-cart' ),
					'mobile'			=> __( 'Mobile', 'ecwid-shopping-cart' )
				)
			),
			'sales-channel' => __( 'Sales Channels', 'ecwid-shopping-cart' ),
			'store-profile' => array(
				'title' => __( 'Settings', 'ecwid-shopping-cart' ),
				'children' => array(
					'store-profile' => __( 'General', 'ecwid-shopping-cart' ),
					'zones' 		=> __( 'Zones', 'ecwid-shopping-cart' ),
					'shipping' 		=> __( 'Shipping & Pickup', 'ecwid-shopping-cart' ),
					'taxes' 		=> __( 'Taxes', 'ecwid-shopping-cart' ),
					'payments' 		=> __( 'Payment', 'ecwid-shopping-cart' ),
					'design' 		=> __( 'Design', 'ecwid-shopping-cart' ),
					'mail' 			=> __( 'Mail', 'ecwid-shopping-cart' ),
					'invoice'		=> __( 'Invoice', 'ecwid-shopping-cart' ),
					'social-tools'	=> __( 'Social Tools', 'ecwid-shopping-cart' ),
					'pos' 			=> __( 'POS', 'ecwid-shopping-cart' ),
					'app:name=storefront-label-editor' => __( 'Edit Store Labels', 'ecwid-shopping-cart' )
				)
			),
			'appmarket' => array(
				'title' => __( 'Apps', 'ecwid-shopping-cart' ),
				'children' => array(
					'appmarket'	=> __( 'App Market', 'ecwid-shopping-cart' ),
					'my_apps' 	=> __( 'My Apps', 'ecwid-shopping-cart' )
				)
			),
			'profile' => array(
				'title' => __( 'My Profile', 'ecwid-shopping-cart' ),
				'children' => array(
					'profile' 		=> __( 'Profile', 'ecwid-shopping-cart' ),
					'store-team' 	=> __( 'Store Team', 'ecwid-shopping-cart' ),
					'billing' 		=> __( 'Billing & Plans', 'ecwid-shopping-cart' )
				)
			)
		);

		return $default_menu;
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