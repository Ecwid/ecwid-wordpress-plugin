<?php

abstract class Ecwid_Catalog_Entry {

	protected $_data;
	protected $_cache_name_prefix = null;
	protected $_link_prefix = null;
	
	protected function __construct()
	{
		$this->_data = new stdClass();
	}

	public function __get( $name ) {
		
		if ( $name == 'link' ) {
			return $this->get_link();
		}

		if ( isset($this->_data->$name) ) {
			return $this->_data->$name;	
		}
		
		return null;
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
		if ( !isset( $this->_data->id ) ) {
			return false;
		}
		
		if ( Ecwid_Seo_Links::is_enabled() ) {
			return $this->get_seo_link( $baseUrl );
		} else {
			if ( !$baseUrl ) {
				$baseUrl = Ecwid_Store_Page::get_store_url();
			}
			$url = $baseUrl . '#!/';
			
			if ( isset( $this->_data->name ) ) {
				$url .= $this->_linkify( $this->_data->name ) . '/';
			}

			$url .=  $this->_link_prefix . '/' . $this->_data->id;

			return $url;
		}

		return false;
	}


	public function get_seo_link( $baseUrl = '' )
	{
		if ( $this->_data->id && isset($this->_data->name) ) {

			if ( !$baseUrl ) {
				if ( Ecwid_Store_Page::is_store_page() ) {
					$baseUrl = get_permalink();
				} else {
					$baseUrl = Ecwid_Store_Page::get_store_url();
				}
			}
			$url = $baseUrl;
			
			if ($url && strlen($url) > 0 && strrpos($url, '/') != strlen($url) - 1) {
				$url .= '/';
			}
			
			$url .= $this->_linkify( $this->_data->name ) . '-' . $this->_link_prefix . $this->_data->id;

			return $url;
		} else if ( isset( $this->_data->seo_link ) ) {
			return $this->_data->seo_link;
		}

		return false;
	}
	
	protected function _get_cache_key_by_id( $id ) {
		return $this->_cache_name_prefix . $id;
	}

	protected function _linkify( $str ) {
		$match = array();
		$result = preg_match_all('#[\p{L}0-9\-_]+#u', $str, $match);
		
		if ( $result && count( @$match[0] ) > 0 )
			return implode('-', $match[0] );
		
		return urlencode($str);
	}
}