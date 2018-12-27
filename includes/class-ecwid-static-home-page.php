<?php

require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-product-browser.php';

class Ecwid_Static_Home_Page {
	
	const OPTION_IS_ENABLED = 'ecwid_static_home_page_enabled';
	
	const OPTION_VALUE_ENABLED = 'Y';
	const OPTION_VALUE_DISABLED = 'N';
	const OPTION_VALUE_AUTO = '';
	
	const CACHE_DATA = 'static_home_page_data';
	const PARAM_VALID_FROM = 'static_home_page_valid_from';
	
	const HANDLE_STATIC_PAGE = 'static-home-page';
	
	protected $_has_theme_adjustments = false;
	
	public function __construct() {
		
		add_option( self::OPTION_IS_ENABLED );
		
		if ( !is_admin() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( Ecwid_Theme_Base::ACTION_APPLY_THEME, array( $this, 'apply_theme' ) );
		}
	}
	
	public function enqueue_scripts()
	{
		if ( !self::is_enabled() ) {
			return null;
		}
		
		$data = $this->get_data_for_current_page();

		if ( !$data || !is_array( $data->cssFiles ) || empty ( $data->cssFiles ) ) return;

		EcwidPlatform::enqueue_script( self::HANDLE_STATIC_PAGE );
		
		foreach ( $data->cssFiles as $ind => $item ) {
			wp_enqueue_style( 'ecwid-' . self::HANDLE_STATIC_PAGE . '-' . $ind, $item );
		}
		
		wp_add_inline_script( 'ecwid-' . self::HANDLE_STATIC_PAGE, "window.ec = window.ec || {}; window.ec.config = window.ec.config || {}; window.ec.config.interactive = false;" );
	}
	
	public function apply_theme( $theme ) {
		if ( $theme ) {
			$this->_has_theme_adjustments = true;
		}
	}
	
	public static function get_data_for_current_page()
	{
		if ( !self::is_enabled() ) {
			return null;
		}

		if ( current_user_can( Ecwid_Admin::get_capability() ) ) {
			EcwidPlatform::force_catalog_cache_reset();
		}
		
		
		if ( Ecwid_Seo_Links::is_enabled() && Ecwid_Seo_Links::is_seo_link() ) {
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
		$store_page_params = Ecwid_Store_Page::get_store_page_params();
		
		if ( isset( $store_page_params['default_category_id'] ) && $store_page_params['default_category_id'] ) {
			return null;
		}
		$params = array();
		
		if ( Ecwid_Seo_Links::is_enabled() ) {
			$params['clean_links'] = 'true';
			$params['base_url'] = get_permalink();
		}
		
		foreach ( Ecwid_Product_Browser::get_attributes() as $attribute ) {
			$name = $attribute['name'];
			if ( @$attribute['is_storefront_api'] && isset( $store_page_params[$name] ) ) {
				if ( @$attribute['type'] == 'boolean' ) {
					$value = $store_page_params[$name] ? 'true' : 'false';
				} else {
					$value = $store_page_params[$name];
				}

				$params['tplvar_ec.storefront.' . $name] = $value;
			}
		}
		
		$url = 'https://storefront.ecwid.com/home-page/' . get_ecwid_store_id() . '/static-code?';
		foreach ( $params as $name => $value ) {
			$url .= $name . '=' . urlencode( $value ) . '&'; 
		}
		
		$accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		
		$cache_key = $accept_language . "\n" . $url;
		
		$cached_data = EcwidPlatform::get_from_catalog_cache( $cache_key );
		
		if ( $cached_data ) {
			return $cached_data;
		}
		
		$fetched_data = null;
		
		$fetched_data = EcwidPlatform::fetch_url( 
			$url, 
			array( 
				'timeout' => 3,
				'headers' => array(
					'ACCEPT-LANGUAGE' => $accept_language
				)
			)
		);
		
		if ( $fetched_data && @$fetched_data['data'] ) {

			$fetched_data = @json_decode( $fetched_data['data'] );
			EcwidPlatform::store_in_catalog_cache( $cache_key, $fetched_data );
			
			return $fetched_data;
		}

		return null;
	}
	
	protected static function _get_store_params()
	{
		$store_id = get_ecwid_store_id();

		$post = get_post();
		if ( !$post ) {
			return null;
		}
		$post_modified = strtotime( $post->post_modified_gmt );

		$lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$lang = substr( $lang, 0, strpos( $lang, ';' ) );

		$cache_key = "static_post_content $store_id $post->ID $post_modified $lang";

		$store_params = EcwidPlatform::get_from_catalog_cache( $cache_key );

		if ( !$store_params ) {
			$store_params = Ecwid_Store_Page::get_store_page_params();
		}

		$non_tplvar_params = array(
			'default_category_id',
			'lang'
		);

		$result = array();
		
		foreach ( $store_params as $name => $value ) {
			if ( in_array( $name, $non_tplvar_params ) ) {
				$result[$name] = $value;
			} else {
				$result['tplvar_ec.storefront.' . $name] = $value;
			}
		}
		
		return $result;
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

		if ( 0 && get_ecwid_store_id() > 15182050 && get_ecwid_store_id() % 2 == 0 ) {
			return true;
		}
		
		return false;
	}

	public static function is_feature_available()
	{
		$api = new Ecwid_Api_V3();
		
		return $api->is_store_feature_enabled( Ecwid_Api_V3::FEATURE_STATIC_HOME_PAGE )
		       && $api->is_store_feature_enabled( Ecwid_Api_V3::FEATURE_NEW_PRODUCT_LIST );
	}
	
	protected static function _get_page_content_data_key( $url ) {
		$lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];	
	}

}

$__ecwid_static_home_page = new Ecwid_Static_Home_Page();