<?php

class Ecwid_Product
{
	protected $_data;

	static protected $products = array();
	
	protected function __construct()
	{
		$this->_data = new stdClass();
	}
	
	public static function from_stdclass( $data ) {
		$product = new Ecwid_Product();
		
		$product->_init_from_stdclass( $data ); 
	}
		
	public function __get( $name ) {
		
		if ( $name == 'link' ) {
			return $this->get_link();
		}
		
		return $this->_data->$name;
	}
	
	public function __isset( $name ) {
		
		if ($name == 'link') {
			$link = $this->get_link();
			return (bool) $link;
		}
		
		return isset( $this->_data->$name );
	}
	
	public static function get_by_id( $id )
	{
		if ( isset( self::$products[$id] ) ) {
			return self::$products[$id];
		}
		
		$p = new Ecwid_Product();
		
		$product_data = $p->_get_product_data_from_cache( $id );
		
		if ( !$product_data ) {
			$p->_load($id);
		} else {
			$p->_init_from_stdclass($product_data);
		}
		
		self::$products[$id] = $p;
		
		return $p;
	}
	
	protected function _get_product_data_from_cache( $id ) {
		return EcwidPlatform::get_from_products_cache( self::_get_cache_key_by_id( $id ) );
	}
	
	protected static function _get_cache_key_by_id( $id ) {
		return 'ecwid-product-' . $id;
	} 
	
	protected function _init_from_stdclass( $data )
	{
		$this->_data = $data;

		EcwidPlatform::store_in_products_cache(
			self::_get_cache_key_by_id( $data->id ),
			$data
		);
	}

	public function get_link( $baseUrl = false )
	{
		if ( Ecwid_Seo_Links::is_enabled() ) {
			return $this->get_seo_link( $baseUrl );
		} else {
			if ( $this->_data->name && $this->_data->id ) {
				if ( !$baseUrl ) {
					$baseUrl = Ecwid_Store_Page::get_store_url();
				}
				$url = $baseUrl;

				$url .= '#!/' . urlencode( $this->_data->name ) . '/p/' . $this->_data->id;

				return $url;
			}
		}
		
		return false;
	}
	
	public function get_seo_link( $baseUrl = false )
	{
		if ( isset( $this->_data->seo_link ) ) {
			return $this->_data->seo_link;
		} else if ( $this->_data->id && $this->_data->name ) {
			
			if ( !$baseUrl ) {
				$baseUrl = Ecwid_Store_Page::get_store_url();
			}
			$url = $baseUrl;

			$url .= '/' . urlencode( $this->_data->name ) . '-p' . $this->_data->id;

			return $url;
		}
		
		return false;
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