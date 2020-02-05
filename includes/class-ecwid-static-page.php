<?php

class Ecwid_Static_Page {
	
	const OPTION_IS_ENABLED = 'ecwid_static_home_page_enabled';
	
	const OPTION_VALUE_ENABLED = 'Y';
	const OPTION_VALUE_DISABLED = 'N';
	const OPTION_VALUE_AUTO = '';
	
	const HANDLE_STATIC_PAGE = 'static-page';
	const API_URL = 'https://storefront.ecwid.com/';

	protected $_has_theme_adjustments = false;
	
	public function __construct() {
		add_option( self::OPTION_IS_ENABLED );
		
		if ( !is_admin() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( Ecwid_Theme_Base::ACTION_APPLY_THEME, array( $this, 'apply_theme' ) );
		}
	}
	
	public function enqueue_scripts() {
		if ( !self::is_enabled_static_home_page() ) {
			return null;
		}

		if ( !Ecwid_Store_page::is_store_page() ) {
			return null;
		}
		
		if( !self::is_data_available() ) {
			return null;
		}

		EcwidPlatform::enqueue_script( self::HANDLE_STATIC_PAGE, array() );
		
		$css_files = self::get_css_files();

		if( $css_files && is_array( $css_files ) ) {
			foreach ( $css_files as $index => $item ) {
				wp_enqueue_style( 'ecwid-' . self::HANDLE_STATIC_PAGE . '-' . $index, $item, array(), null );
			}
		}
	}
	
	public function apply_theme( $theme ) {
		if ( $theme ) {
			$this->_has_theme_adjustments = true;
		}
	}

	public static function get_data_for_current_page() {
		if ( current_user_can( Ecwid_Admin::get_capability() ) ) {
			add_action( 'wp_enqueue_scripts', 'ecwid_enqueue_cache_control', 100 );
		}
		
		$data = self::_maybe_fetch_data();
		
		return $data;
	}

	protected static function _get_endpoint_url( $params = false ){

		if( !$params ) {
			if ( ecwid_is_applicable_escaped_fragment() ) {
				$params = ecwid_parse_escaped_fragment( $_GET['_escaped_fragment_'] );
			} else {
				$params = Ecwid_Seo_Links::maybe_extract_html_catalog_params();
			}
		}

		if( !isset( $params['mode'] ) ) {
			$params['mode'] = 'home';
		}

		$url = self::API_URL;
		$url .= sprintf( '%s-page/', $params['mode'] );
		$url .= sprintf( '%s/', get_ecwid_store_id() );

		if( isset( $params['id'] ) ) {
			$url .= sprintf( '%s/', $params['id'] );
		}

		$url .= 'static-code?';

		return $url;
	}

	protected static function _maybe_fetch_data() {
		$version = get_bloginfo('version');
		$pb_attribures = array();
		if ( strpos( $version, '5.0' )  === 0 || version_compare( $version, '5.0' ) > 0 ) {
			$pb_attribures = Ecwid_Product_Browser::get_attributes();
		}

		$store_page_params = Ecwid_Store_Page::get_store_page_params();
		$endpoint_params = false;

		$params = array();
		
		if ( Ecwid_Seo_Links::is_enabled() || ecwid_is_demo_store() ) {
			$params['clean_links'] = 'true';
			$params['base_url'] = get_permalink();
		}

		if ( array_key_exists( 'offset', $_GET ) ) {
			$params['offset'] = intval( $_GET['offset'] );
		}

		if( !array_key_exists( 'category', $_GET) && isset( $store_page_params['default_category_id'] ) && $store_page_params['default_category_id'] > 0 ) {
			$params['default_category_id'] = $store_page_params['default_category_id'];
		}

		$accept_language = apply_filters( 'ecwid_lang', @$_SERVER['HTTP_ACCEPT_LANGUAGE'] );

		$params['lang'] = $accept_language;

		foreach ( $pb_attribures as $attribute ) {
			$name = $attribute['name'];
			if ( @$attribute['is_storefront_api'] && isset( $store_page_params[$name] ) ) {
				if ( @$attribute['type'] == 'boolean' ) {
					$value = $store_page_params[$name] ? 'true' : 'false';
				} else {
					$value = $store_page_params[$name];
				}

				if( strpos($name, 'chameleon') !== false ) {
					$name = str_replace('chameleon_', '', $name);
					$params['tplvar_ec.chameleon.' . $name] = $value;
				} else {
					$params['tplvar_ec.storefront.' . $name] = $value;
				}
			}
		}


		if( !empty( $_COOKIE['ec_store_chameleon_font'] ) ) {
			$params['tplvar_ec.chameleon.font_family'] = stripslashes( $_COOKIE['ec_store_chameleon_font'] );
		}


		$hreflang_items = apply_filters( 'ecwid_hreflangs', null );

		if( !empty( $hreflang_items ) ) {
			foreach ($hreflang_items as $lang => $link) {
				$params['international_pages[' . $lang . ']'] = $link;
			}
		}

		$url = self::_get_endpoint_url( $endpoint_params );

		foreach ( $params as $name => $value ) {
			$url .= $name . '=' . urlencode( $value ) . '&'; 
		}

		$url = substr( $url, 0, -1 );

		$cache_key = $url;
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

			if( isset( $fetched_data->lastUpdated ) ) {
				$last_update = substr( $fetched_data->lastUpdated, 0, -3 );
			} else {
				$last_update = time();
			}

			EcwidPlatform::invalidate_catalog_cache_from( $last_update );

			EcwidPlatform::store_in_catalog_cache( $cache_key, $fetched_data );
			
			return $fetched_data;
		}

		return null;
	}

	public static function _get_data_field( $field ) {
		$data = self::get_data_for_current_page();

		if( isset( $data->$field ) ) {
			$data->$field = apply_filters( 'ecwid_static_page_field_' . strtolower($field), $data->$field );
			return $data->$field;
		}

		return false;
	}

	public static function get_css_files() {
		return self::_get_data_field( 'cssFiles' );
	}

	public static function get_html_code() {
		return self::_get_data_field( 'htmlCode' );
	}

	public static function get_js_code() {
		return self::_get_data_field( 'jsCode' );
	}

	public static function get_title() {
		$title = self::_get_data_field( 'metaDescriptionHtml' );

		if( $title ) {
			$title = preg_replace( '/<title>(.*?)<\/title>(.*)/is', '$1', $title );
			$title = trim( $title );
		}

		return $title;
	}

	public static function get_meta_description_html() {
		$description = self::_get_data_field( 'metaDescriptionHtml' );

		if( $description ) {
			$description = preg_replace( '/<title>.*?<\/title>/i', '', $description );
		}

		return $description;
	}

	public static function get_canonical_url() {
		return self::_get_data_field( 'canonicalUrl' );
	}

	public static function get_og_tags_html() {
		return self::_get_data_field( 'ogTagsHtml' );
	}

	public static function get_json_ld_html() {
		return self::_get_data_field( 'jsonLDHtml' );
	}

	public static function get_href_lang_html() {
		return self::_get_data_field( 'hrefLangHtml' );
	}

	public static function get_last_update() {
		return self::_get_data_field( 'lastUpdated' );
	}

	public static function is_data_available() {
		if( self::get_last_update() ){
			return true;
		}

		return false;
	}
	
	public static function is_enabled_static_home_page() {

		if( is_preview() ) {
			return false;
		}

		$html_catalog_params = Ecwid_Seo_Links::maybe_extract_html_catalog_params();
		$is_home_page = empty( $html_catalog_params );
		if( !$is_home_page ) {
			return false;
		}
		
		if( Ecwid_Seo_Links::is_noindex_page() ) {
			return false;	
		}

		$store_page_params = Ecwid_Store_Page::get_store_page_params();
		if ( @$store_page_params['default_product_id'] ) {
			return false;
		}

		if ( array_key_exists( 'ec-enable-static-page', $_GET ) ) {
			return true;
		}

		if ( !EcwidPlatform::is_catalog_cache_trusted() ) {
			return false;
		}
		
		if ( get_option( self::OPTION_IS_ENABLED ) == self::OPTION_VALUE_ENABLED ) {
			return true;
		}

		if( ecwid_is_demo_store() ) {
			return true;
		}

		if ( !self::is_feature_available() ) {
			return false;
		}
		
		if ( get_option( self::OPTION_IS_ENABLED ) == self::OPTION_VALUE_DISABLED ) {
			return false;
		}

		if ( get_option( self::OPTION_IS_ENABLED ) == '' ) {
			return true;
		}
		
		return false;
	}

	public static function is_feature_available() {
		if( ecwid_is_demo_store() ) {
			return true;
		}

		$api = new Ecwid_Api_V3();
		
		return $api->is_store_feature_enabled( Ecwid_Api_V3::FEATURE_NEW_PRODUCT_LIST );
	}

	public static function clear_all_cache() {
	    global $wpdb;

	    $sql = "
	        DELETE 
	        FROM {$wpdb->options}
	        WHERE option_name like '\_transient\_ecwid\_catalog\_%'
	        OR option_name like '\_transient\_timeout\_ecwid\_catalog\_%'
	    ";

	    $wpdb->query($sql);
	}
}

$__ecwid_static_page = new Ecwid_Static_Page();