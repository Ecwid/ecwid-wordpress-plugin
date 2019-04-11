<?php

class Ecwid_Admin {

	const ADMIN_SLUG = 'ec-store';
	const AJAX_ACTION_UPDATE_MENU = 'ecwid_update_menu';
	const OPTION_ENABLE_AUTO_MENUS = 'ecwid_enable_auto_menus';
	const OPTION_ENABLE_AUTO_MENUS_ON = 'on';
	const OPTION_ENABLE_AUTO_MENUS_OFF = 'off';
	const OPTION_ENABLE_AUTO_MENUS_AUTO = 'auto';

	public function __construct()
	{
		if ( is_admin() ) {
			add_action( 'current_screen', array( $this, 'do_ec_redirect' ) );
			add_action( 'admin_menu', array( $this, 'build_menu' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_ajax_' . self::AJAX_ACTION_UPDATE_MENU, array( $this, 'ajax_update_menu' ) );
		}
	}
	
	public function enqueue_scripts() {
		$menu = self::_get_menus();
		
		wp_enqueue_script( 'ecwid-admin-menu', ECWID_PLUGIN_URL . 'js/admin-menu.js', array(), get_option( 'ecwid_plugin_version' ) );

		wp_localize_script( 'ecwid-admin-menu', 'ecwid_admin_menu', array(
			'dashboard' => __('Dashboard', 'ecwid-shopping-cart'),
			'dashboard_url' => Ecwid_Admin::get_relative_dashboard_url(),
			'menu' => self::are_auto_menus_enabled() ? $menu : array(),
			'baseSlug' => self::ADMIN_SLUG,
			'enableAutoMenus' => self::are_auto_menus_enabled(),
			'actionUpdateMenu' => self::AJAX_ACTION_UPDATE_MENU
		));		
	}

	public function build_menu()
	{
		$is_newbie = ecwid_is_demo_store();
		if ( Ecwid_Admin_Main_Page::is_forced_reconnect() ) {
			$is_newbie = true;
		}
		
		$page = new Ecwid_Admin_Main_Page();
		
		add_menu_page(
			sprintf(__('%s shopping cart settings', 'ecwid-shopping-cart'), Ecwid_Config::get_brand()),
			sprintf(__('%s', 'ecwid-shopping-cart'), Ecwid_Config::get_brand()),
			'manage_options',
			self::ADMIN_SLUG,
			array( $page, 'do_page' ),
			'',
			'2.562347345'
		);

		if ($is_newbie) {
			$title = __('Setup', 'ecwid-shopping-cart');
		} else {
			$title = __('Dashboard', 'ecwid-shopping-cart');
		}
		if ( !self::are_auto_menus_enabled() || !in_array( self::ADMIN_SLUG, $this->_get_menus() ) ) {
			add_submenu_page(
				self::ADMIN_SLUG,
				$title,
				$title,
				'manage_options',
				self::ADMIN_SLUG,
				array( $page, 'do_page' )
			);
		}
		
		global $ecwid_oauth;
		if (!$is_newbie && $ecwid_oauth->has_scope('allow_sso') && Ecwid_Api_V3::is_available() ) {
			
			if ( !self::are_auto_menus_enabled() ) {
				add_submenu_page(
					self::ADMIN_SLUG,
					__('Sales', 'ecwid-shopping-cart'),
					__('Sales', 'ecwid-shopping-cart'),
					'manage_options',
					self::ADMIN_SLUG . '-admin-orders',
					'ecwid_admin_orders_do_page'
				);
				add_submenu_page(
					self::ADMIN_SLUG,
					__('Products', 'ecwid-shopping-cart'),
					__('Products', 'ecwid-shopping-cart'),
					'manage_options',
					self::ADMIN_SLUG . '-admin-products',
					'ecwid_admin_products_do_page'
				);
			} else {
				$menu = $this->_get_menus();
				
				foreach ( $menu as $item ) {
					if ( @$item['slug'] ) {
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
					} else {
						add_submenu_page(
							self::ADMIN_SLUG,
							$item['title'],
							$item['title'],
							self::get_capability(),
							'',
							array( $this, 'do_admin_page' )
						);
					}
				}
			}

			if( !Ecwid_Config::is_wl() ) {
				add_submenu_page( 
					'plugins.php',
					__('Online Store Apps', 'ecwid-shopping-cart'),
					__('Online Store Apps', 'ecwid-shopping-cart'),
					'manage_options',
					'admin.php?page=ec-store-admin-appmarket'
				); 
			}
		}
		
		if ( !$is_newbie || ( isset($_GET['page']) && $_GET['page'] == 'ec-store-advanced' ) ) {
			add_submenu_page(
				self::ADMIN_SLUG,
				__('Advanced settings', 'ecwid-shopping-cart'),
				__('Advanced', 'ecwid-shopping-cart'),
				self::get_capability(),
				self::ADMIN_SLUG . '-advanced',
				'ecwid_advanced_settings_do_page'
			);
		}

		add_submenu_page('', 'Ecwid debug', '', 'manage_options', 'ec_debug', 'ecwid_debug_do_page');
		add_submenu_page('', 'Ecwid get mobile app', '', 'manage_options', 'ec-admin-mobile', 'ecwid_admin_mobile_do_page');
		add_submenu_page('', 'Ecwid params', '', 'manage_options', 'ec-params', 'ecwid_params_do_page');

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
		
		if ( self::are_auto_menus_enabled() && Ecwid_Api_V3::is_available() && !ecwid_is_demo_store() ) {
			add_options_page(
				__( 'Store', 'ecwid-shopping-cart' ),
				__( 'Store', 'ecwid-shopping-cart' ),
				self::get_capability(),
				'admin.php?page=' . self::ADMIN_SLUG . '-admin-store-profile'
			);

			add_users_page(
				__( 'Customers', 'ecwid-shopping-cart' ),
				__( 'Customers', 'ecwid-shopping-cart' ),
				self::get_capability(),
				'admin.php?page=' . self::ADMIN_SLUG . '-admin-customers'
			);

			add_theme_page(
				__( 'Store', 'ecwid-shopping-cart' ),
				__( 'Store', 'ecwid-shopping-cart' ),
				self::get_capability(),
				'admin.php?page=' . self::ADMIN_SLUG . '-admin-design'
			);
		}
	}

	public function do_admin_page()
	{
		$menus = $this->_get_menus();
		
		$admin_prefix = self::ADMIN_SLUG . '-admin-';
		$wp_slug = get_current_screen()->base;
		$slug = substr( get_current_screen()->base, strpos( $wp_slug, $admin_prefix ) );
		
		$menu = $this->_get_menus();
		
		foreach ($menu as $item) {
			if ( @$item['slug'] == $slug ) {
				$hash = $item['hash'];
				break;
			}
			if ( @$item['children'] ) foreach ( $item['children'] as $child ) {
				if ($child['slug'] == $slug) {
					$hash = $child['hash'];
					break;
				}	
			}
		}
		
		
		
		// Yeah, in some case there might be a collision between the wp slug and ecwid hash if some hashes collide into the same slug
		ecwid_admin_do_page( $hash );	
	}
	
	public function ajax_update_menu()
	{
		if (! current_user_can( self::get_capability() ) ) {
			die();
		}
		
		if (!isset( $_POST['menu'] ) ) {
			die();
		}
		
		EcwidPlatform::set( 'admin_menu', $_POST['menu'] );
		
		echo json_encode( $this->_get_menus() );
		die();
	}
	
	protected function _get_menus()
	{
		$menu = EcwidPlatform::get( 'admin_menu' );
		
		if ( is_null( $menu ) ) {
			$menu = $this->_get_default_menu();
		}
		
		$slugs = array();
		
		$result = array();
		
		foreach ( $menu as $item ) {

			$menu_item = array();
			
			if ( isset( $item['path'] ) && $item['path'] == 'dashboard' ) {
				unset( $menu[$item['path']] );
				continue;
			}
			
			
			$menu_item['title'] = stripslashes($item['title']);
			
			if ( @$item['type'] != 'separator' ) {
				$slug = $this->_slugify_ecwid_cp_hash( $item['path'], $slugs );
				$menu_item['url'] = 'admin.php?page=' . $slug;
				$menu_item['slug'] = $slug;
				$menu_item['hash'] = $item['path'];
				$slugs[] = $slug;
			} else {
				$menu_item['type'] = 'separator';
			}
			
			if ( @$item['items'] ) foreach ( $item['items'] as $item2 ) {
				
				$slug2 = $this->_slugify_ecwid_cp_hash( $item2['path'], $slugs );
				$slugs[] = $slug2;
				$item2['url'] = 'admin.php?page=' . $slug2;
				$item2['slug'] = $slug2;
				$item2['hash'] = $item2['path'];
				$item2['title'] = stripslashes( $item2['title'] );

				$menu_item['children'][] = $item2;
			}
			
			$result[] = $menu_item;
		}
		
		return $result;
	}
	
	protected function _slugify_ecwid_cp_hash( $hash, $slugs ) {
		
		if ( strpos( $hash, ':' ) === false && !in_array( self::ADMIN_SLUG . '-admin-' . $hash, $slugs ) ) {
			$slug = $hash;
		} else {
			$match = array();
			
			$slug = $hash;
			
			$result = preg_match_all( '#[\p{L}0-9\-_]+#u', $slug, $match );
	
			if ( $result && count( @$match[0] ) > 0 ) {
				$slug = implode('-', $match[0] );
			}
			
			$prefix = '';
			while( in_array( $slug . $prefix, $slugs ) ) {
				$prefix = intval( $prefix ) + 1;
			}
			
			if ( $prefix ) {
				$slug .= $prefix;
			}
		}
		
		$slug = self::ADMIN_SLUG . '-admin-' . $slug;
		
		return $slug;
	}

	protected function _get_default_menu() {
		static $default_menu = array();

		if ( !empty( $default_menu ) ) return $default_menu;

		$default_menu = array(
			array(
				'title' => __( 'Sales', 'ecwid-shopping-cart' ),
				'path' => 'orders',
			),
			array(
				'title' => __( 'Products', 'ecwid-shopping-cart' ),
				'path' => 'products'
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
	
	static public function are_auto_menus_enabled()
	{
		if ( get_option( self::OPTION_ENABLE_AUTO_MENUS ) == self::OPTION_ENABLE_AUTO_MENUS_OFF ) {
			return false;
		}

		if ( get_option( self::OPTION_ENABLE_AUTO_MENUS ) == self::OPTION_ENABLE_AUTO_MENUS_ON ) {
			return true;
		}
		
		return true;
	}

	static public function disable_dashboard() {
		
		if ( !isset( $_GET['reconnect'] ) ) {
			if ( get_option( 'ecwid_disable_dashboard' ) == 'on' ) {
				return true;
			} elseif ( get_option( 'ecwid_disable_dashboard' ) != 'off' && @$_COOKIE[ 'ecwid_is_safari' ] == 'true' ) {
				return true;
			}
		}
		
		return false;
	}
}

$ecwid_admin = new Ecwid_Admin();
