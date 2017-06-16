<?php

abstract class Ecwid_Catalog_Entry {

	protected $_data;
	protected static $_cache_name_prefix = null;
	protected static $_link_prefix = null;
	
	abstract protected function _get_from_local_object_cache( $id );
	abstract protected function _put_into_local_object_cache( $id );
	
	protected function __construct()
	{
		$this->_data = new stdClass();
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

	public static function from_stdclass( $data ) {
		trigger_error('from_stdclass should never be called from Ecwid_catalog_Entry');
		
		return false;
	}

	public static function get_by_id( $id ) {
		trigger_error('get_by_id should never be called from Ecwid_catalog_Entry');

		return false;		
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

				$url .= '#!/' . urlencode( $this->_data->name ) . '/' . self::$_link_prefix . '/' . $this->_data->id;

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

			$url .= '/' . urlencode( $this->_data->name ) . '-' . self::$_link_prefix . $this->_data->id;

			return $url;
		}

		return false;
	}
	
	protected static function _get_cache_key_by_id( $id ) {
		return self::$_cache_name_prefix . $id;
	}
}