<?php

require_once dirname(__FILE__) . '/ecwid_catalog_entry.php';

class Ecwid_Category extends Ecwid_Catalog_Entry
{
	protected static $categories = array();
	protected $_cache_name_prefix = 'ecwid-category-';
	protected $_link_prefix = 'c';

	public static function usort_callback() {
		return array( 'Ecwid_Category', 'usort_categories_orderby' );
	}
	
	public static function usort_categories_orderby( $a, $b ) {
		return @$a->orderBy > @$b->orderBy;
	}
 	
	protected function _get_from_local_object_cache( $id ) {
		if ( isset( self::$categories[$id] ) ) {
			return self::$categories[$id];
		}

		return null;
	}

	protected function _put_into_local_object_cache( $obj ) {
		if ( !isset( $obj->id ) ) {
			return false;
		}

		self::$categories[$obj->id] = $obj;
	}

	public static function from_stdclass( $data ) {

		$entry = new Ecwid_Category();

		$entry->_init_from_stdclass( $data );

		$entry->_put_into_local_object_cache( $entry );
	}


	public static function get_by_id( $id )
	{
		$e = new Ecwid_Category();
		
		if ( $cat = $e->_get_from_local_object_cache($id) ) {
			return $cat;
		}
		
		$entry_data = $e->_get_from_cache( $id );

		if ( !$entry_data ) {
			$e->_load($id);
		} else {
			$e->_init_from_stdclass( $entry_data );
		}
		
		if ( !$e->_data ) {
			return null;
		}

		$e->_put_into_local_object_cache($e);

		return $e;
	}

	protected function _get_from_cache( $id ) {
		return EcwidPlatform::get_from_categories_cache( $this->_get_cache_key_by_id( $id ) );
	}
	
	protected function _init_from_stdclass( $data )
	{
		$this->_data = $data;

		EcwidPlatform::store_in_categories_cache(
			$this->_get_cache_key_by_id( $data->id ),
			$data
		);
	}

	protected function _load($id) {

		$data = null;
		if ( Ecwid_Api_V3::is_available() ) {
			$api = new Ecwid_Api_V3();
			$data = $api->get_category($id);

			if ( $data && Ecwid_Seo_Links::is_enabled() ) {
				$data->seo_link = $data->url;
			}
		} 

		if ($data) {
			$this->_init_from_stdclass($data);
		}

		return $data;
	}
}