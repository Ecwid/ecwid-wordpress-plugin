<?php

require_once __DIR__ . '/ecwid_catalog_entry.php';

class Ecwid_Product extends Ecwid_Catalog_Entry
{
	protected static $products = array();
	protected static $_cache_name_prefix = 'ecwid-product-';
	protected static $_link_prefix = 'p';
	protected static $_classname = 'Ecwid_Product';

	protected function _get_from_local_object_cache( $id ) {
		if ( isset( self::$products[$id] ) ) {
			return self::$products[$id];
		}
		
		return null;
	}
	
	protected function _put_into_local_object_cache( $obj ) {
		if ( !isset( $obj->id ) ) {
			return false;
		}
		
		self::$products[$obj->id] = $obj;
	}
	
	protected static function _new_this() {
		return new Ecwid_Category();
	}

	public static function from_stdclass( $data ) {

		$entry = new Ecwid_Product();

		$entry->_init_from_stdclass( $data );

		$entry->_put_into_local_object_cache( $entry );
	}

	public static function get_by_id( $id )
	{
		if ( $product = self::_get_from_local_object_cache($id) ) {
			return $product;
		}
		
		$p = new Ecwid_Product();
		
		$product_data = $p->_get_from_cache( $id );
		
		if ( !$product_data ) {
			$p->_load($id);
		} else {
			$p->_init_from_stdclass($product_data);
		}
		
		self::_put_into_local_object_cache($p);
		
		return $p;
	}
	
	protected function _get_from_cache( $id ) {
		return EcwidPlatform::get_from_products_cache( self::_get_cache_key_by_id( $id ) );
	}

	protected function _init_from_stdclass( $data )
	{
		$this->_data = $data;

		EcwidPlatform::store_in_products_cache(
			self::_get_cache_key_by_id( $data->id ),
			$data
		);
	}
	
	
	protected function _load($id) {
		
		$data = null;
		if ( Ecwid_Api_V3::is_available() ) {
			$api = new Ecwid_Api_V3();
			$data = $api->get_product($id);
			
			if ( $data && Ecwid_Seo_Links::is_enabled() ) {
				$data->seo_link = $data->url;
			}
		} else {
			$api = ecwid_new_product_api();
			$data = $api->get_product_https($id);
		}
		
		if ($data) {
			$this->_init_from_stdclass( $data );
		}
		
		return $data;
	}
}