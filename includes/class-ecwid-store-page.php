<?php

class Ecwid_Store_Page {
	public static function get_product_url( $id )
	{
		if ( Ecwid_Products::is_enabled() ) {
			global $ecwid_products;

			$url = $ecwid_products->get_product_link( $id );

			if ( $url ) {
				return $url;
			}
		}

		$url = self::get_product_url_from_api( $id );
		if ( $url ) {
			return $url;
		}

		return self::get_product_url_default_fallback( $id );
	}

	public static function get_product_url_from_api( $id ) {
		$api = new Ecwid_Api_V3();

		if ( $api->is_available() ) {

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

		$api = new Ecwid_Api_V3();
		if ( $api->is_available() ) {

			$category = $api->get_category( $id );

			if ( $category ) {
				$url = $category->url;
			}

			return $url;
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
			foreach( array( 'ecwid_store_page_id', 'ecwid_store_page_id_auto' ) as $option ) {
				$id = get_option( $option );
				if ( $id ) {
					$status = get_post_status( $id );

					if ( $status == 'publish' || $status == 'private' ) {
						$page_id = $id;
						break;
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

		return get_option( 'ecwid_store_page_id' ) == $page_id || get_option( 'ecwid_store_page_id_auto' ) == $page_id;
	}

}