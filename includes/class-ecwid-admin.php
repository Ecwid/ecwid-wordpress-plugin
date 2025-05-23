<?php

class Ecwid_Admin {

	const ADMIN_SLUG                    = 'ec-store';
	const AJAX_ACTION_UPDATE_MENU       = 'ecwid_update_menu';
	const OPTION_ENABLE_AUTO_MENUS      = 'ecwid_enable_auto_menus';
	const OPTION_ENABLE_AUTO_MENUS_ON   = 'on';
	const OPTION_ENABLE_AUTO_MENUS_OFF  = 'off';
	const OPTION_ENABLE_AUTO_MENUS_AUTO = 'auto';

	public function __construct() {
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

		wp_localize_script(
			'ecwid-admin-menu',
			'ecwid_admin_menu',
			array(
				'dashboard'        => __( 'Dashboard', 'ecwid-shopping-cart' ),
				'dashboard_url'    => self::get_relative_dashboard_url(),
				'menu'             => self::are_auto_menus_enabled() ? $menu : array(),
				'baseSlug'         => self::ADMIN_SLUG,
				'enableAutoMenus'  => self::are_auto_menus_enabled(),
				'actionUpdateMenu' => self::AJAX_ACTION_UPDATE_MENU,
				'ajaxNonce'        => wp_create_nonce( 'ec_admin' ),
			)
		);
	}

	public function build_menu() {
		$is_newbie = ecwid_is_demo_store();
		if ( Ecwid_Admin_Main_Page::is_forced_reconnect() ) {
			$is_newbie = true;
		}

		$page = new Ecwid_Admin_Main_Page();

		add_menu_page(
			sprintf( __( '%s shopping cart settings', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ),
			sprintf( __( '%s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ),
			self::get_capability(),
			self::ADMIN_SLUG,
			array( $page, 'do_page' ),
			'',
			'2.562347345'
		);

		if ( $is_newbie ) {
			$title = __( 'Setup', 'ecwid-shopping-cart' );
		} else {
			$title = __( 'Dashboard', 'ecwid-shopping-cart' );
		}
		if ( ! self::are_auto_menus_enabled() || ! in_array( self::ADMIN_SLUG, $this->_get_menus() ) ) {
			add_submenu_page(
				self::ADMIN_SLUG,
				$title,
				$title,
				self::get_capability(),
				self::ADMIN_SLUG,
				array( $page, 'do_page' )
			);
		}

		global $ecwid_oauth;

		if ( ! $is_newbie && Ecwid_Api_V3::is_available() && ! self::disable_dashboard() ) {
			if ( ! self::are_auto_menus_enabled() ) {
				add_submenu_page(
					self::ADMIN_SLUG,
					__( 'Sales', 'ecwid-shopping-cart' ),
					__( 'Sales', 'ecwid-shopping-cart' ),
					self::get_capability(),
					self::ADMIN_SLUG . '-admin-orders',
					'ecwid_admin_orders_do_page'
				);
				add_submenu_page(
					self::ADMIN_SLUG,
					__( 'Products', 'ecwid-shopping-cart' ),
					__( 'Products', 'ecwid-shopping-cart' ),
					self::get_capability(),
					self::ADMIN_SLUG . '-admin-products',
					'ecwid_admin_products_do_page'
				);
			} else {
				$menu = $this->_get_menus();

				foreach ( $menu as $item ) {
					if ( isset( $item['function'] ) ) {
						add_submenu_page(
							self::ADMIN_SLUG,
							$item['title'],
							$item['title'],
							self::get_capability(),
							$item['slug'],
							$item['function']
						);
					} elseif ( isset( $item['slug'] ) ) {
						add_submenu_page(
							self::ADMIN_SLUG,
							$item['title'],
							$item['title'],
							self::get_capability(),
							$item['slug'],
							array( $this, 'do_admin_page' )
						);

						if ( isset( $item['children'] ) ) {
							foreach ( $item['children'] as $subitem ) {
								add_submenu_page(
									'',
									$subitem['title'],
									$subitem['title'],
									self::get_capability(),
									$subitem['slug'],
									array( $this, 'do_admin_page' )
								);
							}
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
					}//end if
				}//end foreach
			}//end if

			if ( ! Ecwid_Config::is_wl() ) {
				add_submenu_page(
					'plugins.php',
					__( 'Online Store Apps', 'ecwid-shopping-cart' ),
					__( 'Online Store Apps', 'ecwid-shopping-cart' ),
					self::get_capability(),
					'admin.php?page=ec-store-admin-appmarket'
				);
			}
		}//end if

		if ( ! $is_newbie && ! Ecwid_Api_V3::is_available() || ecwid_is_demo_store() || isset( $_GET['reconnect'] ) || self::disable_dashboard() ) {
			if ( current_user_can( 'edit_pages' ) ) {
				add_submenu_page(
					self::ADMIN_SLUG,
					__( 'Storefront', 'ecwid-shopping-cart' ),
					__( 'Storefront', 'ecwid-shopping-cart' ),
					self::get_capability(),
					Ecwid_Admin_Storefront_Page::ADMIN_SLUG,
					'Ecwid_Admin_Storefront_Page::do_page'
				);
			}

			if ( ! Ecwid_Config::is_wl() ) {
				add_submenu_page(
					self::ADMIN_SLUG,
					__( 'Developers', 'ecwid-shopping-cart' ),
					__( 'Developers', 'ecwid-shopping-cart' ),
					self::get_capability(),
					Ecwid_Admin_Developers_Page::ADMIN_SLUG,
					'Ecwid_Admin_Developers_Page::do_page'
				);
			}
		}//end if

		if ( ! $is_newbie || ( isset( $_GET['page'] ) && $_GET['page'] == 'ec-store-advanced' ) ) {
			add_submenu_page(
				self::ADMIN_SLUG,
				__( 'Advanced settings', 'ecwid-shopping-cart' ),
				__( 'Advanced', 'ecwid-shopping-cart' ),
				self::get_capability(),
				self::ADMIN_SLUG . '-advanced',
				'ecwid_advanced_settings_do_page'
			);
		}

		add_submenu_page( 'ec-admin', 'Ecwid debug', '', 'manage_options', 'ec_debug', 'ecwid_debug_do_page' );
		add_submenu_page( 'ec-admin', 'Ecwid get mobile app', '', 'manage_options', 'ec-admin-mobile', 'ecwid_admin_mobile_do_page' );
		add_submenu_page( 'ec-admin', 'Ecwid params', '', 'manage_options', 'ec-params', 'ecwid_params_do_page' );

		if ( ! Ecwid_Config::is_wl() ) {
			add_submenu_page(
				self::ADMIN_SLUG,
				__( 'Help', 'ecwid-shopping-cart' ),
				__( 'Help', 'ecwid-shopping-cart' ),
				self::get_capability(),
				self::ADMIN_SLUG . '-help',
				'ecwid_help_do_page'
			);
		}

		add_submenu_page( '', 'Install ecwid theme', '', 'manage_options', 'ecwid-install-theme', 'ecwid_install_theme' );

		add_submenu_page( '', 'Ecwid sync', '', 'manage_options', 'ec-sync', 'ecwid_sync_do_page' );

		$pages = array(
			'ecwid',
			'ecwid-admin-orders',
			'ecwid-admin-products',
			'ecwid-appearance',
			'ecwid-advanced',
			'ecwid-help',
			'ecwid-debug',
			'ecwid-sync',
		);

		foreach ( $pages as $page ) {
			add_submenu_page( '', 'Legacy', '', 'manage_options', $page, array( $this, 'do_ec_redirect' ) );
		}

		if ( self::are_auto_menus_enabled() && Ecwid_Api_V3::is_available() && ! ecwid_is_demo_store() ) {
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

			if ( ! Ecwid_Admin_Storefront_Page::is_gutenberg_active() ) {
				add_theme_page(
					__( 'Store', 'ecwid-shopping-cart' ),
					__( 'Store', 'ecwid-shopping-cart' ),
					self::get_capability(),
					'admin.php?page=' . self::ADMIN_SLUG . '-admin-design'
				);
			}
		}//end if
	}

	public function do_admin_page() {
		$menus = $this->_get_menus();

		$admin_prefix = self::ADMIN_SLUG . '-admin-';
		$wp_slug      = get_current_screen()->base;
		$slug         = substr( get_current_screen()->base, strpos( $wp_slug, $admin_prefix ) );

		$menu = $this->_get_menus();

		$hash = '';

		foreach ( $menu as $item ) {
			if ( isset( $item['slug'] ) && $item['slug'] == $slug ) {
				$hash = $item['hash'];
				break;
			}
			if ( isset( $item['children'] ) && $item['children'] ) {
				foreach ( $item['children'] as $child ) {
					if ( $child['slug'] == $slug ) {
						$hash = $child['hash'];
						break;
					}
				}
			}
		}

		// Yeah, in some case there might be a collision between the wp slug and ecwid hash if some hashes collide into the same slug
		Ecwid_Admin_Main_Page::do_integrated_admin_page( $hash );
	}

	public function ajax_update_menu() {
		if ( ! current_user_can( self::get_capability() ) ) {
			die();
		}

		check_ajax_referer( 'ec_admin', '_ajax_nonce' );

		if ( ! isset( $_POST['menu'] ) ) {
			die();
		}

		$menu = map_deep( wp_unslash( $_POST['menu'] ), 'sanitize_text_field' );

		EcwidPlatform::set( 'admin_menu', $menu );

		echo json_encode( $this->_get_menus() );
		die();
	}

	public function maybe_hide_menu_item( $item ) {
		if ( ! isset( $item['path'] ) ) {
			return false;
		}

		$hidden_items_path = array(
			'dashboard',
			'starter-site',
			'website',
			'website-overview',
			'website-design',
		);

		if ( class_exists( 'Ecwid_Admin_Storefront_Page' ) && Ecwid_Admin_Storefront_Page::is_gutenberg_active() ) {
			$hidden_items_path[] = 'design';
		}

		return in_array( $item['path'], $hidden_items_path );
	}

	protected function _get_menus() {
		$menu = EcwidPlatform::get( 'admin_menu' );

		if ( is_null( $menu ) ) {
			$menu = $this->_get_default_menu();
		}

		$slugs            = array();
		$result           = array();
		$additional_menus = array();

		if ( current_user_can( 'edit_pages' ) ) {
			$additional_menus[ Ecwid_Admin_Storefront_Page::ADMIN_SLUG ] = array(
				'title'    => __( 'Storefront', 'ecwid-shopping-cart' ),
				'slug'     => Ecwid_Admin_Storefront_Page::ADMIN_SLUG,
				'url'      => 'admin.php?page=' . Ecwid_Admin_Storefront_Page::ADMIN_SLUG,
				'is_added' => false,
			);
		}

		if ( ! Ecwid_Config::is_wl() ) {
			$additional_menus[ Ecwid_Admin_Developers_Page::ADMIN_SLUG ] = array(
				'title'    => __( 'Developers', 'ecwid-shopping-cart' ),
				'slug'     => Ecwid_Admin_Developers_Page::ADMIN_SLUG,
				'url'      => 'admin.php?page=' . Ecwid_Admin_Developers_Page::ADMIN_SLUG,
				'is_added' => false,
			);
		}

		foreach ( $menu as $item ) {
			$menu_item = array();

			if ( $item['type'] == 'menuItem' && $item['path'] == 'payments' ) {
				$page_slug = Ecwid_Admin_Storefront_Page::ADMIN_SLUG;

				if ( empty( $additional_menus[ $page_slug ] ) ) {
					continue;
				}

				$result[]                                   = $additional_menus[ $page_slug ];
				$additional_menus[ $page_slug ]['is_added'] = true;
			}

			if ( $this->maybe_hide_menu_item( $item ) ) {
				unset( $menu[ $item['path'] ] );
				continue;
			}

			$menu_item['title'] = stripslashes( $item['title'] );

			if ( @$item['type'] != 'separator' ) {
				$slug              = $this->_slugify_ecwid_cp_hash( $item['path'], $slugs );
				$menu_item['url']  = 'admin.php?page=' . $slug;
				$menu_item['slug'] = $slug;
				$menu_item['hash'] = $item['path'];
				$slugs[]           = $slug;
			} else {
				$menu_item['type'] = 'separator';
			}

			if ( isset( $item['items'] ) ) {
				foreach ( $item['items'] as $item2 ) {
					if ( $item2['title'] == 'Website' ) {
						$item2['title'] = __( 'Instant site', 'ecwid-shopping-cart' );
					}

					$slug2          = $this->_slugify_ecwid_cp_hash( $item2['path'], $slugs );
					$slugs[]        = $slug2;
					$item2['url']   = 'admin.php?page=' . $slug2;
					$item2['slug']  = $slug2;
					$item2['hash']  = $item2['path'];
					$item2['title'] = stripslashes( $item2['title'] );

					$menu_item['children'][] = $item2;
				}
			}

			$result[] = $menu_item;

			$dev_page_slug = Ecwid_Admin_Developers_Page::ADMIN_SLUG;
			if ( isset( $additional_menus[ $dev_page_slug ] ) && $item['type'] == 'menuItem' && $item['path'] == 'billing' ) {
				$result[]                                       = $additional_menus[ $dev_page_slug ];
				$additional_menus[ $dev_page_slug ]['is_added'] = true;
			}
		}//end foreach

		foreach ( $additional_menus as $menu ) {
			if ( ! $menu['is_added'] ) {
				$result[] = $menu;
			}
		}

		return $result;
	}

	protected function _slugify_ecwid_cp_hash( $hash, $slugs ) {

		if ( strpos( $hash, ':' ) === false && ! in_array( self::ADMIN_SLUG . '-admin-' . $hash, $slugs ) ) {
			$slug = $hash;
		} else {
			$match = array();

			$slug = $hash;

			$result = preg_match_all( '#[\p{L}0-9\-_]+#u', $slug, $match );

			if ( $result && count( @$match[0] ) > 0 ) {
				$slug = implode( '-', $match[0] );
			}

			$prefix = '';
			while ( in_array( $slug . $prefix, $slugs ) ) {
				$prefix = intval( $prefix ) + 1;
			}

			if ( $prefix ) {
				$slug .= $prefix;
			}
		}//end if

		$slug = self::ADMIN_SLUG . '-admin-' . $slug;

		return $slug;
	}

	public function do_ec_redirect() {

		$screen = get_current_screen();

		$base = $screen->base;
		if ( strpos( $base, 'admin_page_ecwid' ) === false ) {
			return;
		}

		$page = str_replace( 'admin_page_ecwid', self::ADMIN_SLUG, $base );

		wp_safe_redirect( admin_url( 'admin.php?page=' . $page ), 301 );
		exit();
	}

	public static function get_capability() {
		return apply_filters( 'ec_store_admin_get_capability', 'manage_options' );
	}

	public static function get_dashboard_url() {
		return admin_url( self::get_relative_dashboard_url() );
	}

	public static function get_relative_dashboard_url() {
		return 'admin.php?page=' . self::ADMIN_SLUG;
	}

	public static function are_auto_menus_enabled() {
		if ( self::disable_dashboard() ) {
			return false;
		}

		if ( get_option( self::OPTION_ENABLE_AUTO_MENUS ) == self::OPTION_ENABLE_AUTO_MENUS_OFF ) {
			return false;
		}

		if ( get_option( self::OPTION_ENABLE_AUTO_MENUS ) == self::OPTION_ENABLE_AUTO_MENUS_ON ) {
			return true;
		}

		return true;
	}

	public static function disable_dashboard() {

		if ( ! isset( $_GET['reconnect'] ) ) {
			if ( get_option( 'ecwid_disable_dashboard' ) == 'on' ) {
				return true;
			} elseif ( get_option( 'ecwid_disable_dashboard' ) != 'off' && isset( $_COOKIE['ecwid_is_safari'] ) && $_COOKIE['ecwid_is_safari'] == 'true' ) {
				return true;
			}
		}

		return false;
	}

	protected function _get_default_menu() {
		static $default_menu = array();

		if ( ! empty( $default_menu ) ) {
			return $default_menu;
		}

		$default_menu = array(
			array(
				'title' => __( 'Store management', 'ecwid-shopping-cart' ),
				'type'  => 'separator',
			),
			array(
				'title' => __( 'Dashboard', 'ecwid-shopping-cart' ),
				'type'  => 'menuItem',
				'path'  => 'dashboard',
			),
			array(
				'title' => __( 'My Sales', 'ecwid-shopping-cart' ),
				'type'  => 'menuItem',
				'path'  => 'orders',
			),
			array(
				'title' => __( 'Catalog', 'ecwid-shopping-cart' ),
				'type'  => 'menuItem',
				'path'  => 'products',
			),
			array(
				'title' => __( 'Marketing', 'ecwid-shopping-cart' ),
				'type'  => 'menuItem',
				'path'  => 'marketing',
			),
			array(
				'title' => __( 'Reports', 'ecwid-shopping-cart' ),
				'type'  => 'menuItem',
				'path'  => 'reports',
			),
			array(
				'title' => __( 'Sales channels', 'ecwid-shopping-cart' ),
				'type'  => 'separator',
			),
			array(
				'title' => __( 'Sell on Facebook', 'ecwid-shopping-cart' ),
				'type'  => 'menuItem',
				'path'  => 'fb-shops',
			),
			array(
				'title' => __( 'Mobile', 'ecwid-shopping-cart' ),
				'type'  => 'menuItem',
				'path'  => 'mobile',
			),
			array(
				'title' => __( 'Website', 'ecwid-shopping-cart' ),
				'type'  => 'menuItem',
				'path'  => 'website',
			),
			array(
				'title' => __( 'All Sales Channels', 'ecwid-shopping-cart' ),
				'type'  => 'menuItem',
				'path'  => 'sales-channel',
			),
			array(
				'title' => __( 'Configuration', 'ecwid-shopping-cart' ),
				'type'  => 'separator',
			),
			array(
				'title' => __( 'Design', 'ecwid-shopping-cart' ),
				'type'  => 'menuItem',
				'path'  => 'design',
			),
			array(
				'title' => __( 'Payment', 'ecwid-shopping-cart' ),
				'type'  => 'menuItem',
				'path'  => 'payments',
			),
			array(
				'title' => __( 'Shipping & Pickup', 'ecwid-shopping-cart' ),
				'type'  => 'menuItem',
				'path'  => 'shippings',
			),
			array(
				'title' => __( 'Settings', 'ecwid-shopping-cart' ),
				'type'  => 'menuItem',
				'path'  => 'store-profile',
			),
			array(
				'title' => __( 'Apps', 'ecwid-shopping-cart' ),
				'type'  => 'menuItem',
				'path'  => 'appmarket',
			),
			array(
				'title' => __( 'My Profile', 'ecwid-shopping-cart' ),
				'type'  => 'menuItem',
				'path'  => 'billing',
			),
		);

		return $default_menu;
	}
}

$ecwid_admin = new Ecwid_Admin();
