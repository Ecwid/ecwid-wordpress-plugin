<?php

class Ecwid_Static_Page {

	const OPTION_IS_ENABLED = 'ecwid_static_home_page_enabled';

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

	protected static function get_endpoint_params() {

        if ( ecwid_is_applicable_escaped_fragment() ) {
            $params = ecwid_parse_escaped_fragment();
        } else {
            $params = Ecwid_Seo_Links::maybe_extract_html_catalog_params();
        }

		if ( ! isset( $params['mode'] ) ) {
			$params['mode'] = 'home';
		}

		return $params;
	}

	protected static function maybe_fetch_data() {
		$version       = get_bloginfo( 'version' );
		$pb_attribures = array();
		if ( strpos( $version, '5.0' ) === 0 || version_compare( $version, '5.0' ) > 0 ) {
			$pb_attribures = Ecwid_Product_Browser::get_attributes();
		}

		$store_page_params = Ecwid_Store_Page::get_store_page_params();
		$endpoint_params = array();
        $query_params = array();

		// for cases of early access to the page if the cache is empty and need to get store block params
		if ( empty( $store_page_params ) ) {
			if ( strpos( $version, '5.0' ) === 0 || version_compare( $version, '5.0' ) > 0 ) {
				do_blocks( get_the_content() );
				$store_page_params = Ecwid_Store_Page::get_store_page_params();
			}
		}

		if ( Ecwid_Seo_Links::is_enabled() || ecwid_is_demo_store() ) {
			$query_params['clean_urls'] = 'true';
		} else {
			$query_params['clean_urls'] = 'false';
		}

		if ( array_key_exists( 'offset', $_GET ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$query_params['offset'] = intval( $_GET['offset'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		if ( ! array_key_exists( 'category', $_GET ) && isset( $store_page_params['default_category_id'] ) && intval( $store_page_params['default_category_id'] ) > 0 ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$query_params['default_category_id'] = $store_page_params['default_category_id'];
		}

		$query_params['lang'] = self::get_accept_language();

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
					$name = str_replace( 'chameleon_', '', $name );
					$query_params[ 'tplvar_ec.chameleon.' . $name ] = $value;
				} else {
					$query_params[ 'tplvar_ec.storefront.' . $name ] = $value;
				}
			}
		}//end foreach

		$hreflang_items = apply_filters( 'ecwid_hreflangs', null );

		if ( ! empty( $hreflang_items ) ) {
			foreach ( $hreflang_items as $lang => $link ) {
				$query_params[ 'international_pages[' . $lang . ']' ] = $link;
			}
		}

		if ( self::is_need_use_new_endpoint() ) {
            $query_params['baseUrl'] = get_permalink();

			$query_params['getStaticContent'] = 'true';
			$query_params['slug']             = self::get_current_storefront_page_slug();

            if( Ecwid_Seo_Links::is_slugs_without_ids_enabled() ) {
                $query_params['slugsWithoutIds'] = 'true';
            } else {
                $query_params['slugsWithoutIds'] = 'false';
            }

			if ( empty( $query_params['slug'] ) ) {
				$query_params['storeRootPage'] = 'true';
			} else {
				$query_params['storeRootPage'] = 'false';
			}
		} else {
            $query_params['base_url'] = get_permalink();

            if ( ! empty( $query_params['default_category_id'] ) ) {
                $endpoint_params = array(
                    'mode' => 'category',
                    'id'   => $query_params['default_category_id'],
                );
            }

			if( empty( $endpoint_params ) ) {
                $endpoint_params = self::get_endpoint_params();
            }
		}

		$dynamic_css = '';
		if ( ! empty( $_COOKIE['ec_store_dynamic_css'] ) ) {
			$dynamic_css = wp_strip_all_tags( $_COOKIE['ec_store_dynamic_css'] ); //phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash
		}

        $cache_key = self::get_cache_key( $query_params, $endpoint_params );

		$cached_data = EcwidPlatform::get_from_static_pages_cache( $cache_key );

		if ( $cached_data ) {
            
            self::process_page_status( $cached_data, $cache_key );

            if ( isset( $cached_data->staticContent ) ) {
                $static_content = $cached_data->staticContent;
            } else {
                $static_content = $cached_data;
            }

			$is_css_defined     = ! empty( $dynamic_css );
			$is_css_already_set = in_array( $dynamic_css, $static_content->cssFiles, true ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			$is_home_page       = Ecwid_Store_Page::is_store_home_page();

			if ( $is_home_page && $is_css_defined && ! $is_css_already_set ) {
				$static_content->cssFiles = array( $dynamic_css ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

                if ( isset( $cached_data->staticContent ) ) {
                    $cached_data->staticContent = $static_content;
                } else {
                    $cached_data = $static_content;
                }

				EcwidPlatform::save_in_static_pages_cache( $cache_key, $cached_data );
			}

			return $static_content;
		}

		$fetched_data = self::get_static_snapshot( $endpoint_params, $query_params, $dynamic_css );

		return $fetched_data;
	}

	public static function get_current_storefront_page_slug( $page_id = null ) {
        $slug = '';
        
        if ( ! $page_id ) {
            $page_id = get_queried_object_id();
        }

		$page_link      = get_permalink( $page_id );
		$page_permalink = wp_make_link_relative( $page_link );

		$current_url = add_query_arg( null, null );

		$url = str_replace( $page_permalink, '/', $current_url );

		if ( preg_match( '/\/([^\/\?]+)/', $url, $matches ) ) {
			$slug = $matches[1];
		}

		return $slug;
	}

	protected static function process_page_status( $data, $cache_key = null ) {

        if( ! Ecwid_Seo_Links::is_enabled() || ! Ecwid_Seo_Links::is_slugs_without_ids_enabled() ) {
            return;
        }

        if ( ! empty( $data->status ) && in_array( $data->status, array( 'NONCANONICAL', 'NOT_FOUND' ), true ) ) {

            if ( ! empty( $cache_key ) ) {
                unset( $data->staticContent );
                EcwidPlatform::save_in_static_pages_cache( $cache_key, $data );
            }

            if( $data->status === 'NONCANONICAL' ) {
                $permalink = get_permalink();
                $permalink = trailingslashit( $permalink );
                wp_redirect( $permalink . $data->canonicalSlug, 301 );
                exit;
            }
            
            // if( $data->status === 'NOT_FOUND' ) {
            //     global $wp_query;

            //     $wp_query->set_404();
            //     status_header( 404 );

            //     exit();
            // }
        }
    }

	protected static function get_static_snapshot( $endpoint_params, $query_params, $dynamic_css = '' ) {

		if ( self::is_need_use_new_endpoint() ) {
			$api          = new Ecwid_Api_V3();
			$data = $api->get_storefront_widget_page( $query_params );

            $cache_key = self::get_cache_key( $query_params, $endpoint_params );
            self::process_page_status( $data, $cache_key );

			if ( empty( $data->staticContent ) || ! is_object( $data->staticContent ) ) { //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				return null;
			}
		} else {
            $api          = new Ecwid_Api_V3();
			$data = $api->get_static_page( $endpoint_params, $query_params );

            if ( empty( $data ) || ! is_object( $data ) ) {
                return null;
            }
		}//end if

		if ( ! empty( $data ) ) {
            //phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
            
            if ( ! empty( $data->staticContent ) ) {
                $static_content = $data->staticContent;
            } else {
                $static_content = $data;
            }

			if ( ! empty( $dynamic_css ) ) {
				$static_content->cssFiles = array( $dynamic_css );
			}

			if ( ! empty( $static_content->htmlCode ) ) {
				$pattern = '/<img(.*?)>/is';

				$static_content->htmlCode = preg_replace( $pattern, '<img $1 decoding="async">', $static_content->htmlCode );
			}

			EcwidPlatform::encode_fields_with_emoji(
				$static_content,
				array( 'htmlCode', 'metaDescriptionHtml', 'ogTagsHtml', 'jsonLDHtml' )
			);

			if ( isset( $static_content->lastUpdated ) ) {
				$last_update = substr( $static_content->lastUpdated, 0, -3 );
			} else {
				$last_update = time();
			}
            //phpcs:enable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

            if ( ! empty( $data->staticContent ) ) {
                $data->staticContent = $static_content;
            }

            $cache_key = self::get_cache_key( $query_params, $endpoint_params );

			EcwidPlatform::invalidate_static_pages_cache_from( $last_update );
			EcwidPlatform::save_in_static_pages_cache( $cache_key, $data );

			return $static_content;
		}//end if

		return null;
	}

    protected static function is_need_use_new_endpoint() {
        $is_token_valid = Ecwid_Api_V3::get_api_status() === Ecwid_Api_V3::API_STATUS_OK;

		if ( ! ecwid_is_demo_store() && Ecwid_Seo_Links::is_slugs_without_ids_enabled() && $is_token_valid ) {
            return true;
        }

        return false;
    }

    protected static function get_cache_key( $query_params, $endpoint_params ) {
        return serialize( array_merge( $query_params, $endpoint_params ) );
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
		$title = self::get_data_field( 'title' );

        if( empty( $title ) ) {
            $meta_description = self::get_data_field( 'metaDescriptionHtml' );

            if( ! empty( $meta_description ) ) {
                $title = preg_replace( '/<title>(.*?)<\/title>(.*)/is', '$1', $meta_description );
                $title = trim( $title );
            }            
        }

        return $title;
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

		if ( isset( $store_page_params['show_root_categories'] ) && $store_page_params['show_root_categories'] === false ) {
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
}

$__ecwid_static_page = new Ecwid_Static_Page();
