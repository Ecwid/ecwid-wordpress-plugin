<?php

class Ecwid_Static_Page {

	const OPTION_IS_ENABLED     = 'ecwid_static_home_page_enabled';
	const OPTION_NEW_IS_ENABLED = 'ecwid_new_static_home_page_enabled';

	const OPTION_VALUE_ENABLED  = 'Y';
	const OPTION_VALUE_DISABLED = 'N';
	const OPTION_VALUE_AUTO     = '';

	const HANDLE_STATIC_PAGE = 'static-page';

	protected static $cache_key;

	public function __construct() {
		add_option( self::OPTION_IS_ENABLED );

		if ( ! is_admin() ) {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}

	public function enqueue_scripts() {
		if ( ! self::is_enabled_static_home_page() ) {
			return null;
		}

		if ( ! Ecwid_Store_page::is_store_page() ) {
			return null;
		}

		if ( ! self::is_data_available() ) {
			return null;
		}

		EcwidPlatform::enqueue_script( self::HANDLE_STATIC_PAGE, array(), true );

		$css_files = self::get_css_files();

		if ( $css_files && is_array( $css_files ) ) {
			foreach ( $css_files as $index => $item ) {
				wp_enqueue_style( 'ecwid-' . self::HANDLE_STATIC_PAGE . '-' . $index, $item, array(), null ); //phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
			}
		}
	}

	public static function get_data_for_current_page() {
		if ( current_user_can( Ecwid_Admin::get_capability() ) ) {
			add_action( 'wp_enqueue_scripts', 'ecwid_enqueue_cache_control', 100 );
		}

		$data = self::maybe_fetch_data();
		return $data;
	}

	protected static function get_endpoint_url( $params = false ) {

		if ( ! $params ) {
			if ( ecwid_is_applicable_escaped_fragment() ) {
				$params = ecwid_parse_escaped_fragment();
			} else {
				$params = Ecwid_Seo_Links::maybe_extract_html_catalog_params();
			}
		}

		if ( ! isset( $params['mode'] ) ) {
			$params['mode'] = 'home';
		}

		$url  = 'https://' . Ecwid_Config::get_static_pages_api_domain() . '/';
		$url .= sprintf( '%s-page/', $params['mode'] );
		$url .= sprintf( '%s/', get_ecwid_store_id() );

		if ( isset( $params['id'] ) ) {
			$url .= sprintf( '%s/', $params['id'] );
		}

		$url .= 'static-code?';

		return $url;
	}

	protected static function maybe_fetch_data() {
		$version       = get_bloginfo( 'version' );
		$pb_attribures = array();
		if ( strpos( $version, '5.0' ) === 0 || version_compare( $version, '5.0' ) > 0 ) {
			$pb_attribures = Ecwid_Product_Browser::get_attributes();
		}

		$store_page_params = Ecwid_Store_Page::get_store_page_params();
		$endpoint_params   = false;

		// for cases of early access to the page if the cache is empty and need to get store block params
		if ( empty( $store_page_params ) ) {
			if ( strpos( $version, '5.0' ) === 0 || version_compare( $version, '5.0' ) > 0 ) {
				do_blocks( get_the_content() );
				$store_page_params = Ecwid_Store_Page::get_store_page_params();
			}
		}

		$params = array();

		if ( Ecwid_Seo_Links::is_enabled() || ecwid_is_demo_store() ) {
			$params['clean_urls'] = 'true';
		} else {
			$params['clean_urls'] = 'false';
		}

		$params['base_url'] = get_permalink();

		if ( array_key_exists( 'offset', $_GET ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$params['offset'] = intval( $_GET['offset'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		if ( ! array_key_exists( 'category', $_GET ) && isset( $store_page_params['default_category_id'] ) && intval( $store_page_params['default_category_id'] ) > 0 ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$params['default_category_id'] = $store_page_params['default_category_id'];
		}

		$params['lang'] = self::get_accept_language();

		$storefront_view_params = array( 'show_root_categories', 'enable_catalog_on_one_page' );
		foreach ( $storefront_view_params as $param ) {
			if ( isset( $store_page_params[ $param ] ) ) {
				$pb_attribures[ $param ] = array(
					'name'              => $param,
					'is_storefront_api' => true,
					'type'              => 'boolean',
				);
			}
		}
		unset( $pb_attribures['storefront_view'] );

		foreach ( $pb_attribures as $attribute ) {
			$name = $attribute['name'];

			if ( ! empty( $attribute['is_storefront_api'] ) && isset( $store_page_params[ $name ] ) ) {
				if ( ! empty( $attribute['type'] ) && $attribute['type'] === 'boolean' ) {
					$value = $store_page_params[ $name ] ? 'true' : 'false';
				} else {
					$value = $store_page_params[ $name ];
				}

				if ( strpos( $name, 'chameleon' ) !== false ) {
					$name                                     = str_replace( 'chameleon_', '', $name );
					$params[ 'tplvar_ec.chameleon.' . $name ] = $value;
				} else {
					$params[ 'tplvar_ec.storefront.' . $name ] = $value;
				}
			}
		}//end foreach

		$hreflang_items = apply_filters( 'ecwid_hreflangs', null );

		if ( ! empty( $hreflang_items ) ) {
			foreach ( $hreflang_items as $lang => $link ) {
				$params[ 'international_pages[' . $lang . ']' ] = $link;
			}
		}

		if ( ! empty( $params['default_category_id'] ) ) {
			$endpoint_params = array(
				'mode' => 'category',
				'id'   => $params['default_category_id'],
			);
		}

		if ( self::is_need_to_use_new_endpoint() ) {
			// $api  = new Ecwid_Api_V3();
			// $url  = $api->get_storefront_widget_pages_endpoint();
			$url = '?';

			$params['getStaticContent'] = 'true';
			$params['slug']             = self::get_current_storefront_page_slug();

			if ( empty( $params['slug'] ) ) {
				$params['storeRootPage'] = 'true';
			} else {
				$params['storeRootPage'] = 'false';
			}
		} else {
			$url = self::get_endpoint_url( $endpoint_params );
		}

		foreach ( $params as $name => $value ) {
			$url .= $name . '=' . rawurlencode( $value ) . '&';
		}

		$url = substr( $url, 0, -1 );

		$dynamic_css = '';
		if ( ! empty( $_COOKIE['ec_store_dynamic_css'] ) ) {
			$dynamic_css = wp_strip_all_tags( $_COOKIE['ec_store_dynamic_css'] ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		}

		$cached_data = EcwidPlatform::get_from_static_pages_cache( $url );
		if ( $cached_data ) {
			$is_css_defined     = ! empty( $dynamic_css );
			$is_css_already_set = in_array( $dynamic_css, $cached_data->cssFiles, true ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$is_home_page       = Ecwid_Store_Page::is_store_home_page();

			if ( $is_home_page && $is_css_defined && ! $is_css_already_set ) {
				$cached_data->cssFiles = array( $dynamic_css ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

				EcwidPlatform::save_in_static_pages_cache( $url, $cached_data );
			}

			return $cached_data;
		}

		$fetched_data = self::get_static_snapshot( $url, $params, $dynamic_css );

		return $fetched_data;
	}

	protected static function get_current_storefront_page_slug() {
		$slug = '';

		$page_id        = get_queried_object_id();
		$page_link      = get_permalink( $page_id );
		$page_permalink = wp_make_link_relative( $page_link );

		$current_url = add_query_arg( null, null );

		$url = str_replace( $page_permalink, '/', $current_url );

		if ( preg_match( '/\/([^\/\?]+)/', $url, $matches ) ) {
			$slug = $matches[1];
		}

		return $slug;
	}

	protected static function get_static_snapshot( $url, $params, $dynamic_css = '' ) {

		if ( self::is_need_to_use_new_endpoint() ) {
			$api          = new Ecwid_Api_V3();
			$fetched_data = $api->get_storefront_widget_page( $params );

			if ( empty( $fetched_data->staticContent ) || ! is_object( $fetched_data->staticContent ) ) { //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				return null;
			}

			$data = $fetched_data->staticContent; //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		} else {
			$fetched_data = EcwidPlatform::fetch_url(
				$url,
				array(
					'timeout' => 3,
					'headers' => array(
						'ACCEPT-LANGUAGE' => self::get_accept_language(),
					),
				)
			);

			if ( ! empty( $fetched_data['data'] ) ) {
				$data = json_decode( $fetched_data['data'] );

				if ( empty( $data ) || ! is_object( $data ) ) {
					return null;
				}
			}
		}//end if

		if ( ! empty( $data ) ) {
            //phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			if ( ! empty( $dynamic_css ) ) {
				$data->cssFiles = array( $dynamic_css );
			}

			if ( ! empty( $data->htmlCode ) ) {
				$pattern = '/<img(.*?)>/is';

				$data->htmlCode = preg_replace( $pattern, '<img $1 decoding="async" loading="lazy">', $data->htmlCode );
			}

			EcwidPlatform::encode_fields_with_emoji(
				$data,
				array( 'htmlCode', 'metaDescriptionHtml', 'ogTagsHtml', 'jsonLDHtml' )
			);

			if ( isset( $data->lastUpdated ) ) {
				$last_update = substr( $data->lastUpdated, 0, -3 );
			} else {
				$last_update = time();
			}
            //phpcs:enable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

			EcwidPlatform::invalidate_static_pages_cache_from( $last_update );
			EcwidPlatform::save_in_static_pages_cache( $url, $data );

			return $data;
		}//end if

		return null;
	}

	public static function get_accept_language() {
		$http_accept_language = isset( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) ? : ''; //phpcs:ignore Universal.Operators.DisallowShortTernary.Found
		return apply_filters( 'ecwid_lang', $http_accept_language );
	}

	public static function get_data_field( $field ) {
		$data = self::get_data_for_current_page();

		if ( isset( $data->$field ) ) {
			$data->$field = apply_filters( 'ecwid_static_page_field_' . strtolower( $field ), $data->$field );
			return $data->$field;
		}

		return false;
	}

	public static function preparing_css_url( $url ) {
		$replace_pairs = array(
			'#'  => '%23',
			','  => '%2C',
			' '  => '%20',
			'"'  => '%22',
			'\\' => '',
		);

		return strtr( $url, $replace_pairs );
	}

	public static function get_css_files() {
		$css_files = self::get_data_field( 'cssFiles' );

		if ( ! empty( $css_files ) ) {
			$css_files = array_map( 'Ecwid_Static_Page::preparing_css_url', $css_files );
		}

		return $css_files;
	}

	public static function get_html_code() {
		return self::get_data_field( 'htmlCode' );
	}

	public static function get_js_code() {
		return self::get_data_field( 'jsCode' );
	}

	public static function get_title() {
		return self::get_data_field( 'title' );
	}

	public static function get_meta_description_html() {
		$description = self::get_data_field( 'metaDescriptionHtml' );

		if ( $description ) {
			$description = preg_replace( '/<title>.*?<\/title>/i', '', $description );
		}

		return $description;
	}

	public static function get_canonical_url() {
		return self::get_data_field( 'canonicalUrl' );
	}

	public static function get_og_tags_html() {
		$og_tags_html = self::get_data_field( 'ogTagsHtml' );

		$ec_title = self::get_title();
		$wp_title = wp_get_document_title();

		if ( $og_tags_html && $wp_title && $ec_title ) {
			$og_tags_html = str_replace( "content=\"$ec_title\"", "content=\"$wp_title\"", $og_tags_html );
		}

		return $og_tags_html;
	}

	public static function get_json_ld_html() {
		return self::get_data_field( 'jsonLDHtml' );
	}

	public static function get_href_lang_html() {
		return self::get_data_field( 'hrefLangHtml' );
	}

	public static function get_last_update() {
		return self::get_data_field( 'lastUpdated' );
	}

	public static function is_data_available() {
		if ( self::get_last_update() ) {
			return true;
		}

		return false;
	}

	public static function is_enabled() {
		return self::is_enabled_static_home_page();
	}

	public static function is_enabled_static_home_page() {

		$api     = new Ecwid_Api_V3();
		$profile = $api->get_store_profile();

		if ( isset( $profile->settings->closed ) && $profile->settings->closed ) {
			return false;
		}

		if ( is_preview() ) {
			return false;
		}

		$is_home_page = Ecwid_Store_Page::is_store_home_page();
		if ( ! $is_home_page ) {
			return false;
		}

		if ( Ecwid_Seo_Links::is_noindex_page() ) {
			return false;
		}

		$store_page_params = Ecwid_Store_Page::get_store_page_params();
		if ( isset( $store_page_params['default_product_id'] ) && $store_page_params['default_product_id'] > 0 ) {
			return false;
		}

		if ( isset( $store_page_params['enable_catalog_on_one_page'] ) && $store_page_params['enable_catalog_on_one_page'] ) {
			return false;
		}

		if ( array_key_exists( 'ec-enable-static-page', $_GET ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return true;
		}

		if ( ! Ecwid_Seo_Links::is_enabled() ) {
			return false;
		}

		if ( ! EcwidPlatform::is_static_pages_cache_trusted() ) {
			return false;
		}

		if ( Ecwid_Ajax_Defer_Renderer::is_ajax_request() ) {
			return false;
		}

		if ( get_option( self::OPTION_IS_ENABLED ) === self::OPTION_VALUE_ENABLED ) {
			return true;
		}

		if ( ecwid_is_demo_store() ) {
			return true;
		}

		if ( get_option( self::OPTION_IS_ENABLED ) === self::OPTION_VALUE_DISABLED ) {
			return false;
		}

		if ( get_option( self::OPTION_IS_ENABLED, self::OPTION_VALUE_AUTO ) === self::OPTION_VALUE_AUTO ) {
			return true;
		}

		return false;
	}

	public static function is_need_to_use_new_endpoint() {
		if ( get_option( self::OPTION_NEW_IS_ENABLED ) === self::OPTION_VALUE_ENABLED ) {
			return true;
		}

		if ( get_option( self::OPTION_NEW_IS_ENABLED ) === self::OPTION_VALUE_DISABLED ) {
			return false;
		}

		// to-do enable just for 25% of users
		// if ( get_option( self::OPTION_NEW_IS_ENABLED ) === '' && get_ecwid_store_id() % 4 === 0 ) {
		if ( get_option( self::OPTION_NEW_IS_ENABLED, self::OPTION_VALUE_AUTO ) === '' ) {
			return true;
		}

		return false;
	}
}

$__ecwid_static_page = new Ecwid_Static_Page();
