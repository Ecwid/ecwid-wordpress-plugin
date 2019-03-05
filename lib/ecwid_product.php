<?php

require_once dirname(__FILE__) . '/ecwid_catalog_entry.php';

class Ecwid_Product extends Ecwid_Catalog_Entry
{
	protected static $products = array();
	protected $_cache_name_prefix = 'ecwid-product-';
	protected $_link_prefix = 'p';

	public static function get_by_id( $id )
	{
		$p = new Ecwid_Product();

		$product_data = $p->_get_from_cache( $id );

		if ( !$product_data ) {
			$p->_load($id);
			if ( !$p->_data ) {
				return null;
			}
			$p->_persist();
		} else {
			$p->_data = $product_data;
		}		
		
		return $p;
	}
	
	public static function get_random_product()
	{
		$total = EcwidPlatform::get_from_products_cache( 'ecwid_total_products' );
		
		$all_products = false;
		
		if ( $total < 100 && $total > 0 && EcwidPlatform::get_from_products_cache( 'ecwid_all_products_request' ) ) {
			$all_products = EcwidPlatform::get_from_products_cache(
				EcwidPlatform::get_from_products_cache( 'ecwid_all_products_request' )	
			);
		}
		
		if ( $all_products ) {
			$index = rand( 0, $total - 1 );
			
			$result = json_decode( $all_products['data'] );
			
			$random_product_id = $result->items[$index]->id;
		} else {
			$index = rand( 0, $total );
			$offset = floor($index / 100) * 100;
			
			$api = new Ecwid_Api_V3();
			$result = $api->search_products(
				array(
					'offset' => $offset
				)
			);
			
			if ( !@$result->items ) {
				return null;
			}
			
			if( count($result->items) < ($index - $offset) ) {
				$random_product = current($result->items);
				$random_product_id = $random_product->id;
			} else {
				$random_product_id = $result->items[$index - $offset]->id;
			}
		}
		
		return Ecwid_Product::get_by_id( $random_product_id );
	}
	
	public static function get_without_loading($id, $fallback_object = null)
	{
		$p = new Ecwid_Product();
		
		$product_data = $p->_get_from_cache( $id );
		if ( !$product_data ) {
			if ( $fallback_object ) {
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
	
	public static function preload_by_ids($ids) 
	{
		if ( !is_array( $ids ) || empty( $ids ) || !Ecwid_Api_V3::is_available() ) {
			return;
		}
		
		$ids_string = implode( ',', $ids );
		
		$api = new Ecwid_Api_V3();
		
		$data = $api->search_products( array( 'productId' => $ids_string ) );

		if ($data && $data->count > 0) {
			foreach($data->items as $product_data){
				$p = new Ecwid_Product();
				$p->_data = $product_data;
				$p->_persist();
			}
		}
	}
	
	protected function _get_from_cache( $id ) {
		return EcwidPlatform::get_from_products_cache( $this->_get_cache_key_by_id( $id ) );
	}
	
	protected function _load( $id ) {
		
		$data = null;
		if ( Ecwid_Api_V3::is_available() ) {
			$api = new Ecwid_Api_V3();
			$data = $api->get_product($id);
			
			if ( $data && Ecwid_Seo_Links::is_enabled() ) {
				$data->seo_link = $data->url;
			}
		}
		
		if ($data) {
			$this->_data = $data;
		}
		
		return $data;
	}
	
	protected function _persist() {
		
		if ( !property_exists( $this->_data, 'id' ) ) {
			return;
		}
		EcwidPlatform::store_in_products_cache(
			$this->_get_cache_key_by_id( $this->_data->id ),
			$this->_data
		);		
	}
}