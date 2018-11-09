<?php

require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-product-browser.php';

class Ecwid_Static_Home_Page {
	
	const OPTION_IS_ENABLED = 'ecwid_static_home_page_enabled';
	
	const OPTION_VALUE_ENABLED = 'Y';
	const OPTION_VALUE_DISABLED = 'N';
	const OPTION_VALUE_AUTO = '';
	
	const CACHE_DATA = 'static_home_page_data';
	const PARAM_VALID_FROM = 'static_home_page_valid_from';
	
	public function __construct() {
		
		add_option( self::OPTION_IS_ENABLED );
		
		if ( !is_admin() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}
	
	public function enqueue_scripts()
	{
		if ( !self::is_enabled() ) {
			return null;
		}
		
		$data = $this->get_data_for_current_page();

		if ( !$data || !is_array( $data->cssFiles ) || empty ( $data->cssFiles ) ) return;

		EcwidPlatform::enqueue_script( 'static-home-page' );

		foreach ( $data->cssFiles as $ind => $item ) {
			wp_enqueue_style( 'ecwid-static-home-page-' . $ind, $item );
		}
		
		if ( @$data->scripts ) {
			foreach ($data->scripts as $item) {
				wp_add_inline_script( 'ecwid-static-home-page', $item );
			}
		}
	}
	
	public static function get_data_for_current_page()
	{
		if ( !self::is_enabled() ) {
			return null;
		}
		
		if ( Ecwid_Seo_Links::is_enabled() && Ecwid_Seo_Links::is_product_browser_url() ) {
			return null;
		}

		$data = self::_maybe_fetch_data();
		
		if ( $data ) {
			return $data;
		}
		
		return null;
	}
	
	protected static function _maybe_fetch_data()
	{
		$store_page_data = Ecwid_Store_Page::get_store_page_data( );
		
		$possible_params = array(
			'lang',
			'default_category_id'
		);
		
		$params = array();
		foreach ( $possible_params as $name ) {
			$data = Ecwid_Store_Page::get_store_page_data( $name );
			if ( isset( $store_page_data[$name] ) ) {
				$params[$name] = $store_page_data[$name];
			}
		}
		
		if ( !@$params['lang'] ) {
			$lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
			$lang = substr( $lang, 0, strpos( $lang, ';' ) );
			$params['lang'] = $lang;
		}
		
		if ( Ecwid_Seo_Links::is_enabled() ) {
			$params['clean_links'] = 'true';
			$params['base_url'] = get_permalink();
		}
		
		foreach ( Ecwid_Product_Browser::get_attributes() as $attribute ) {
			$name = $attribute['name'];
			if ( @$attribute['is_storefront_api'] && isset( $store_page_data[$name] ) ) {
				if ( @$attribute['type'] == 'boolean' ) {
					$value = $store_page_data[$name] ? 'true' : 'false';
				} else {
					$value = $store_page_data[$name];
				}

				$params['tplvar_ec.storefront.' . $name] = $value;
			}
		}
		
		$url = 'https://storefront.ecwid.com/home-page/' . get_ecwid_store_id() . '/static-code?';
		foreach ( $params as $name => $value ) {
			$url .= $name . '=' . urlencode( $value ) . '&'; 
		}
		
		$cached_data = EcwidPlatform::get_from_catalog_cache( $url );
		
		if ( $cached_data ) {
			return $cached_data;
		}
		
		$fetched_data = null;
		
		$fetched_data = EcwidPlatform::fetch_url( $url, array( 'timeout' => 3 ) );
		
		
		if ( $fetched_data && @$fetched_data['data'] ) {

			$fetched_data = @json_decode( $fetched_data['data'] );
			EcwidPlatform::store_in_catalog_cache( $url, $fetched_data );
			
			return $fetched_data;
		}

		return null;
	}
	
	public static function is_enabled()
	{
		if ( !EcwidPlatform::is_catalog_cache_trusted() ) {
			return false;
		}
		
		if ( get_option( self::OPTION_IS_ENABLED ) == self::OPTION_VALUE_ENABLED ) {
			return true;
		}
		
		if ( !self::is_feature_available() ) {
			return false;
		}		
		
		if ( get_option( self::OPTION_IS_ENABLED ) == self::OPTION_VALUE_DISABLED ) {
			return false;
		}
		
		return true;
	}

	public static function is_feature_available()
	{
		$api = new Ecwid_Api_V3();

		return $api->is_store_feature_enabled( Ecwid_Api_V3::FEATURE_STATIC_HOME_PAGE )
		       && $api->is_store_feature_enabled( Ecwid_Api_V3::FEATURE_NEW_PRODUCT_LIST );
	}
}

$__ecwid_static_home_page = new Ecwid_Static_Home_Page();