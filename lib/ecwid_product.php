<?php

require_once dirname(__FILE__) . '/ecwid_catalog_entry.php';

class Ecwid_Product extends Ecwid_Catalog_Entry
{
	protected static $products = array();
	protected $_cache_name_prefix = 'ecwid-product-';
	protected $_link_prefix = 'p';

	protected static function _get_from_local_object_cache( $id ) {
		if ( isset( self::$products[$id] ) ) {
			return self::$products[$id];
		}
		
		return null;
	}
	
	protected static function _put_into_local_object_cache( $obj ) {
		if ( !isset( $obj->id ) ) {
			return false;
		}
		
		self::$products[$obj->id] = $obj;
	}
	
	protected static function _new_this() {
		return new Ecwid_Category();
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
			$p->_data = $product_data;
		}
		
		self::_put_into_local_object_cache($p);
		
		$p->_persist();
		
		return $p;
	}
	
	public static function get_without_loading($id, $fallback_object = null)
	{
		
		if ( $product = self::_get_from_local_object_cache($id) ) {
			return $product;
		}

		$p = new Ecwid_Product();

		$product_data = $p->_get_from_cache( $id );
		if (!$product_data) {
			if ($fallback_object) {
				$product_data = $fallback_object;
			} else {
				$product_data = new stdClass();
			}

			$product_data->id = $id;
		}
		
		$p->_data = $product_data;
		
		return $p;
	}
	
	public static function init_from_stdclass( $data )
	{
		$p = new Ecwid_Product();
		$p->_data = $data;
		
		$p->_persist();
		
		return $p;
	}
	
	protected function _get_from_cache( $id ) {
		return EcwidPlatform::get_from_products_cache( $this->_get_cache_key_by_id( $id ) );
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
			$this->_data = $data;
		}
		
		return $data;
	}
	
	protected function _persist() {
		
		EcwidPlatform::store_in_products_cache(
			$this->_get_cache_key_by_id( $this->_data->id ),
			$this->_data
		);		
	}
}