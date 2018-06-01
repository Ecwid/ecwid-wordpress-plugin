<?php

class Ecwid_Store_Page {

	const OPTION_STORE_PAGES = 'ecwid_store_pages';
	const OPTION_MAIN_STORE_PAGE_ID = 'ecwid_store_page_id';
	const OPTION_LAST_STORE_PAGE_ID = 'ecwid_last_store_page_id';
	const OPTION_FLUSH_REWRITES = 'ecwid_flush_rewrites';
	const WARMUP_ACTION = 'ecwid_warmup_store';

	protected static $_store_pages = false;

	public static function get_product_url( $id )
	{
		$product = Ecwid_Product::get_by_id( $id );
		
		$url = $product->link;
		
		if ( $url ) {
			return $url;
		}

		return self::get_product_url_default_fallback( $id );
	}

	public static function get_product_url_from_api( $id ) {
		
		if ( Ecwid_Api_V3::is_available() ) {

			$api = new Ecwid_Api_V3();

			$product = $api->get_product( $id );

			if ( $product ) {

				return $product->url;
			}
		}

		return '';
	}

	public static function get_product_url_default_fallback( $id ) {
		return self::get_store_url() . '#!/p/' . $id;
	}

	public static function get_category_url( $id )
	{
		$category = Ecwid_Category::get_by_id( $id );

		$url = $category->link;
		
		if ( $url ) {
			return $url;
		}
		
		
		if ( $id == 0 ) {
			return self::get_store_url();
		}

		if ( Ecwid_Api_V3::is_available() ) {
			$api = new Ecwid_Api_V3();

			$category = $api->get_category( $id );

			if ( $category ) {
				$url = $category->url;

				return $url;
			}
		}

		return self::get_store_url() . '#!/c/' . $id;
	}

	public static function get_menu_item_url( $menu_item )
	{
		$suffix = '';
		if ( Ecwid_Seo_Links::is_enabled() ) {
			$suffix = $menu_item['ecwid-page'];
			if ( $suffix == '/' ) {
				$suffix = '';
			}
		} else {
			$suffix = '#!' . $menu_item['url'];
		}
		return self::get_store_url() . $suffix;
	}

	public static function get_cart_url()
	{
		if ( Ecwid_Seo_Links::is_enabled() ) {
			return untrailingslashit( self::get_store_url() ) . '/cart';
		} else {
			return self::get_store_url() . '#!/~/cart';
		}
	}

	public static function get_store_url()
	{
		static $link = null;

		if ( is_null( $link ) ) {
			$id = self::get_current_store_page_id();
			
			if ( !$id ) {
				return get_bloginfo( 'url' );
			}
			
			$link = get_permalink( $id );
		}
		
		return $link;
	}

	public static function get_page_base_url( $page = 0 ) {
		return urldecode( get_page_uri( $page ) );
	} 
	
	public static function get_current_store_page_id()
	{
		static $page_id = null;

		if ( is_null( $page_id ) ) {
			$page_id = false;

			$id = get_option( self::OPTION_MAIN_STORE_PAGE_ID );
			if ( $id ) {

				$post = get_post( $id );
				$changed = false;
				
				while ( is_null( $post ) ) {

					$changed = true;

					$pages = self::get_store_pages_array();
					$ind = array_search( $id, $pages );

					if ( $ind !== false ) {
						unset($pages[$ind]);
						$pages = self::_set_store_pages($pages);
					}

					if ( count( $pages ) == 0 ) {
						return false;
					}

					$id = $pages[0];
					$post = get_post($id);
				}
				$status = get_post_status( $id );

				if (in_array($status, self::_get_allowed_post_statuses())) {
					$page_id = $id;
					if ( $changed ) {
						update_option( self::OPTION_MAIN_STORE_PAGE_ID, $id );
					}
				}
			}
		}

		return $page_id;
	}

	public static function is_store_page( $page_id = 0 ) {

		if (!$page_id) {
			$page_id = get_the_ID();
		}

		$pages = self::get_store_pages_array();

		return in_array( $page_id, $pages );
	}

	public static function add_store_page( $page_id = 0 ) {

		$pages = self::get_store_pages_array();
		if ( in_array( $page_id, $pages ) ) {
			return;
		}

		$pages[] = $page_id;

		if ( count( $pages ) == 1 || !get_option( self::OPTION_MAIN_STORE_PAGE_ID ) ) {
			update_option( self::OPTION_MAIN_STORE_PAGE_ID, $page_id );
		}

		self::_set_store_pages( $pages );
		self::schedule_flush_rewrites();
	}

	public static function reset_store_page( $page_id ) {

		$pages = self::get_store_pages_array();

		$index = array_search( $page_id, $pages );
		if ( $index === false ) {
			return;
		}

		unset( $pages[$index] );
		ecwid_reset_categories_cache();
		
		$pages = self::_set_store_pages( $pages );

		if ( $page_id == get_option( self::OPTION_MAIN_STORE_PAGE_ID ) ) {

			if ( isset( $pages[0] ) ) {
				update_option( self::OPTION_MAIN_STORE_PAGE_ID, $pages[0] );
			} else {
				update_option( self::OPTION_MAIN_STORE_PAGE_ID, '' );
			}
		}
	}

	public static function get_store_pages_array() {

		if ( self::$_store_pages ) {
			return self::$_store_pages;
		}

		$pages = get_option( self::OPTION_STORE_PAGES );
		if ( !$pages || !is_string( $pages ) ) {
			$pages = '';
		}

		self::$_store_pages = explode( ',',  $pages );
		self::$_store_pages[] = get_option( self::OPTION_MAIN_STORE_PAGE_ID );
		self::$_store_pages = array_values(
			array_filter(
				array_unique(
				self::$_store_pages
				)
			)
		);


		return self::$_store_pages;
	}

	public static function schedule_flush_rewrites() {
		update_option( self::OPTION_FLUSH_REWRITES, 1 );
	}

	public static function flush_rewrites() {
		if ( get_option( self::OPTION_FLUSH_REWRITES ) == 1) {
			flush_rewrite_rules();
		}

		update_option( self::OPTION_FLUSH_REWRITES, 0 );
	}

	protected static function _set_store_pages( $pages ) {

		self::$_store_pages = array_values(
			array_filter(
				$pages
			)
		);

		$option_value = implode( ',', $pages );

		update_option( self::OPTION_STORE_PAGES, $option_value );

		return self::$_store_pages;
	}

	public static function post_content_has_productbrowser( $post_id = null ) {

		if ( is_null( $post_id ) ) {
			if ( is_admin() ) return false;
			$post_id = get_the_ID();
		}

		$post = get_post($post_id);

		if ( $post ) {
			$post_content = get_post( $post_id )->post_content;

			$result = ecwid_content_has_productbrowser( $post_content );
			$result = apply_filters( 'ecwid_page_has_product_browser', $result );
		}


		return $result;
	}

	public static function on_save_post( $post_id ) {

		if ( wp_is_post_revision( $post_id ) )
			return;

		$has_pb = self::post_content_has_productbrowser( $post_id );

		if ( self::is_store_page( $post_id ) ) {
			$is_disabled = !in_array( get_post_status( $post_id ), self::_get_allowed_post_statuses() );

			if ( $is_disabled || !$has_pb ) {
				self::reset_store_page( $post_id );
			}
		}
		
		if ($has_pb) {
			ecwid_reset_categories_cache();
		}

		if ( $has_pb && in_array( get_post_status( $post_id ), self::_get_allowed_post_statuses() ) ) {
			self::add_store_page( $post_id );
		} else if ( get_option( self::OPTION_MAIN_STORE_PAGE_ID ) == $post_id ) {
			update_option( self::OPTION_LAST_STORE_PAGE_ID, $post_id );
			update_option( self::OPTION_MAIN_STORE_PAGE_ID, '' );
		}
	}

	protected static function _get_allowed_post_statuses()
	{
		return array('publish', 'private');
	}

	public static function warmup_store() 
	{
		$store_page = get_post( self::get_current_store_page_id() );
		
		if ( !$store_page ) {
			return;
		}
		
		$shortcodes = array();
		foreach ( Ecwid_Shortcode_Base::get_store_shortcode_names() as $shortcode_name ) {
			$shortcodes[] = ecwid_find_shortcodes( $store_page->post_content, $shortcode_name );
		}
		
		if ( sizeof( $shortcodes ) == 0 ) {
			return;
		}
		
		$shortcode_data = $shortcodes[0];
		
		$category = 0;
		
		if ( isset( $shortcode_data[3] ) ) {
			$attributes = shortcode_parse_atts($shortcode_data[3]);

			if ( !$attributes ) {
				return;
			}
			
			$category = $attributes['default_category_id'];
		}
		
		$page_url = get_permalink( $store_page );

		include_once ECWID_PLUGIN_DIR . 'lib/ecwid_catalog.php';

		$catalog = new EcwidCatalog(get_ecwid_store_id(), $page_url);
		
		$catalog->warmup_store_page(intval($category));
	}
}

add_action( 'init', array( 'Ecwid_Store_Page', 'flush_rewrites' ) );
add_action( 'save_post', array( 'Ecwid_Store_Page', 'on_save_post' ) );
add_action( 'wp_ajax_' . Ecwid_Store_Page::WARMUP_ACTION, array( 'Ecwid_Store_Page', 'warmup_store' ) );
add_action( 'update_option_page_on_front', array( 'Ecwid_Store_Page', 'schedule_flush_rewrites' ) );