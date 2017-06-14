<?php

class Ecwid_Store_Page {

	const OPTION_STORE_PAGES = 'ecwid_store_pages';
	const OPTION_MAIN_STORE_PAGE_ID = 'ecwid_store_page_id';
	const OPTION_FLUSH_REWRITES = 'ecwid_flush_rewrites';

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
			$suffix = $menu_item['clean-url'];
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
			$link = get_page_link( self::get_current_store_page_id() );
		}

		return $link;
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
		self::_schedule_flush_rewrite();
	}

	public static function reset_store_page( $page_id ) {

		$pages = self::get_store_pages_array();

		$index = array_search( $page_id, $pages );
		if ( $index === false ) {
			return;
		}

		unset( $pages[$index] );

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

	protected static function _schedule_flush_rewrite() {
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

		if ( $has_pb && in_array( get_post_status( $post_id ), self::_get_allowed_post_statuses() ) ) {
			self::add_store_page( $post_id );
		} else if ( get_option( self::OPTION_MAIN_STORE_PAGE_ID ) == $post_id ) {
			update_option( self::OPTION_MAIN_STORE_PAGE_ID, '' );
		}
	}

	protected static function _get_allowed_post_statuses()
	{
		return array('publish', 'private', 'draft');
	}

	public static function on_frontend_rendered() {}
}

add_action( 'init', array( 'Ecwid_Store_Page', 'flush_rewrites' ) );
add_action( 'shutdown', array( 'Ecwid_Store_Page', 'on_frontend_rendered' ) );
add_action( 'save_post', array( 'Ecwid_Store_Page', 'on_save_post' ) );