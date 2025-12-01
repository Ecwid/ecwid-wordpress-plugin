<?php

require_once dirname( __FILE__ ) . '/ecwid_platform.php'; //phpcs:ignore

class Ecwid_Api_V3 {

	const APP_ID         = 'RD4o2KQimiGUrFZc';
	const APP_PUBLIC_KEY = 'jEPVdcA3KbzKVrG8FZDgNnsY3wKHDTF8';

	const TOKEN_OPTION_NAME = 'ecwid_oauth_token';

	const PROFILE_CACHE_NAME      = 'apiv3_store_profile';
	const UPDATE_STATS_CACHE_NAME = 'apiv3_store_latest_stats';

	const OPTION_API_STATUS      = 'ecwid_api_status';
	const API_STATUS_OK          = 'ok';
	const API_STATUS_UNDEFINED   = null;
	const API_STATUS_ERROR_TLS   = 'fail_old_tls';
	const API_STATUS_ERROR_OTHER = 'fail_other';
	const API_STATUS_ERROR_TOKEN = 'fail_token';

	const FEATURE_NEW_PRODUCT_LIST  = 'NEW_PRODUCT_LIST';
	const FEATURE_PRODUCT_FILTERS   = 'PRODUCT_FILTERS';
	const FEATURE_PRODUCT_SUBTITLES = 'PRODUCT_SUBTITLES_FEATURE';

	public static function get_api_status_list() {
		return array(
			self::API_STATUS_UNDEFINED,
			self::API_STATUS_OK,
			self::API_STATUS_ERROR_TOKEN,
			self::API_STATUS_ERROR_TLS,
			self::API_STATUS_ERROR_OTHER,
		);
	}

	const FEATURE_VARIATIONS       = 'COMBINATIONS';
	const FEATURE_NEW_DETAILS_PAGE = 'NEW_DETAILS_PAGE';

	public $store_id = null;

	protected static $profile = null;

	protected $api_url;
	protected $stores_api_url;
	protected $categories_api_url;
	protected $products_api_url;
	protected $profile_api_url;
	protected $starter_site_api_url;
	protected $batch_requests_api_url;
	protected $storefront_widget_pages_api_url;
    protected $static_pages_api_url;

	public function __construct() {

		$this->store_id       = EcwidPlatform::get_store_id();
		$this->api_url        = 'https://' . Ecwid_Config::get_api_domain() . '/api/v3/';
		$this->stores_api_url = $this->api_url . 'stores';

		$this->categories_api_url              = $this->api_url . $this->store_id . '/categories';
		$this->products_api_url                = $this->api_url . $this->store_id . '/products';
		$this->profile_api_url                 = $this->api_url . $this->store_id . '/profile';
		$this->starter_site_api_url            = $this->api_url . $this->store_id . '/startersite';
		$this->batch_requests_api_url          = $this->api_url . $this->store_id . '/batch';
		$this->storefront_widget_pages_api_url = $this->api_url . $this->store_id . '/storefront-widget-pages';

        $this->static_pages_api_url = 'https://' . Ecwid_Config::get_static_pages_api_domain();

		add_option( self::OPTION_API_STATUS, self::API_STATUS_UNDEFINED );
	}

	public static function is_available() {
		$status = self::get_api_status();

		return self::check_api_status();
	}

	public static function connection_fails() {
		$status = self::get_api_status();

		return in_array( $status, array( self::API_STATUS_ERROR_OTHER, self::API_STATUS_ERROR_TLS ) );
	}

	public static function reset_api_status() {
		update_option( self::OPTION_API_STATUS, self::API_STATUS_UNDEFINED );
	}

	public static function set_api_status( $new_status ) {
		if ( in_array( $new_status, self::get_api_status_list() ) ) {
			update_option( self::OPTION_API_STATUS, $new_status );
		}

		return $new_status == self::API_STATUS_OK;
	}

	public static function get_api_status() {
		return get_option( self::OPTION_API_STATUS );
	}


	public static function check_api_status() {
		if ( ecwid_is_demo_store() ) {
			return self::set_api_status( self::API_STATUS_OK );
		}

		$api = new Ecwid_Api_V3();

		$token = self::_load_token();
		if ( ! $token ) {
			return self::set_api_status( self::API_STATUS_ERROR_TOKEN );
		}

		$update_stats = EcwidPlatform::cache_get( self::UPDATE_STATS_CACHE_NAME );

		if ( ! $update_stats ) {
			$update_stats = $api->get_store_update_stats();
			EcwidPlatform::cache_set( self::UPDATE_STATS_CACHE_NAME, $update_stats, 60 * 5 );
		}

		if ( $update_stats ) {
			return self::set_api_status( self::API_STATUS_OK );
		}

		$transports = stream_get_transports();

		$tls_fails = true;

		foreach ( $transports as $transport ) {
			$matches = array();

			$is_tls = preg_match( '!tlsv(.*)!', $transport, $matches );

			if ( $is_tls ) {
				if ( version_compare( $matches[1], '1.1', '>=' ) ) {
					$tls_fails = false;
					break;
				}
			}
		}

		if ( -$tls_fails ) {
			return self::set_api_status( self::API_STATUS_ERROR_TLS );
		}

		return self::set_api_status( self::API_STATUS_ERROR_OTHER );
	}

	public static function save_token( $token ) {
		if ( ! $token ) {
			update_option( self::TOKEN_OPTION_NAME, '' );
		} else {
			EcwidPlatform::init_crypt( true );

			$value = base64_encode( EcwidPlatform::encrypt( $token ) );

			update_option( self::TOKEN_OPTION_NAME, $value );
		}
		self::reset_api_status();
	}

	public function get_categories( $input_params ) {
		if ( ecwid_is_demo_store() && ! ecwid_get_demo_store_public_key() ) {
			return false;
		}

		$params = array();

		if ( array_key_exists( 'parent', $input_params ) ) {
			$params['parent'] = $input_params['parent'];
		}

		$passthru = array( 'offset', 'limit', 'parent', 'baseUrl', 'cleanUrls', 'hidden_categories', 'responseFields', 'slugsWithoutIds', 'slugsWithoutIds' );
		foreach ( $passthru as $name ) {
			if ( array_key_exists( $name, $input_params ) ) {
				$params[ $name ] = $input_params[ $name ];
			}
		}

		if ( ! isset( $params['baseUrl'] ) ) {
			$params['baseUrl'] = Ecwid_Store_Page::get_store_url();
		}

		if ( Ecwid_Seo_Links::is_enabled() ) {
			$params['cleanUrls'] = 'true';
		}

		if ( Ecwid_Seo_Links::is_slugs_without_ids_enabled() ) {
			$params['slugsWithoutIds'] = 'true';
		}

		$options = $this->build_request_headers();

		$url = $this->build_request_url(
			$this->categories_api_url,
			$params
		);

		$result = EcwidPlatform::get_from_categories_cache( $url );

		if ( ! $result ) {
			if ( empty( $options ) ) {
				return false;
			}

			$result = EcwidPlatform::fetch_url( $url, $options );

			if ( $result['code'] != '200' ) {
				return false;
			}

			// PLUGINS-6870 there's some cases when data are not cached because emojis, need to encode
			$data = json_decode( $result['data'] );

			if ( ! empty( $data->items ) ) {
				foreach ( $data->items as &$item ) {
					$item = EcwidPlatform::encode_fields_with_emoji(
						$item,
						array( 'name', 'nameTranslated', 'description', 'descriptionTranslated', 'seoTitle', 'seoTitleTranslated', 'seoDescription', 'seoDescriptionTranslated', 'alt' )
					);
				}
			}

			$result['data'] = wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );
			// end PLUGINS-6870 there's some cases when data are not cached because emojis, need to encode

			EcwidPlatform::store_in_categories_cache( $url, $result );
		}//end if

		$result = json_decode( $result['data'] );

		if ( ! empty( $result->items ) ) {
			foreach ( $result->items as $item ) {
				if ( Ecwid_Seo_Links::is_enabled() && ! empty( $item->url ) ) {
					$item->seo_link = $item->url;
				}

				Ecwid_Category::from_stdclass( $item );
			}
		}

		return $result;
	}

	public function has_public_categories() {
		$cats = $this->get_categories( array( 'limit' => 1 ) );

		if ( ! isset( $cats->total ) ) {
			return false;
		}

		return $cats->total > 0;
	}

	public function get_category( $category_id ) {
		if ( ! isset( $category_id ) || $category_id == 0 ) {
			return false;
		}

		$params = array();

		if ( ! isset( $params['baseUrl'] ) ) {
			$params['baseUrl'] = Ecwid_Store_Page::get_store_url();
		}

		if ( Ecwid_Seo_Links::is_enabled() ) {
			$params['cleanUrls'] = 'true';
		}

        if ( Ecwid_Seo_Links::is_slugs_without_ids_enabled() ) {
			$params['slugsWithoutIds'] = 'true';
		}

		$options = $this->build_request_headers();

		$url = $this->build_request_url(
			$this->categories_api_url . '/' . $category_id,
			$params
		);

		$result = EcwidPlatform::get_from_categories_cache( $url );

		if ( ! $result ) {
			if ( empty( $options ) ) {
				return false;
			}

			$result = EcwidPlatform::fetch_url( $url, $options );

			if ( $result['code'] != '200' ) {
				return false;
			}

			// PLUGINS-6870 there's some cases when data are not cached because emojis, need to encode
			$data = json_decode( $result['data'] );
			if ( ! empty( $data ) ) {
				$data = EcwidPlatform::encode_fields_with_emoji(
					$data,
					array( 'name', 'nameTranslated', 'description', 'descriptionTranslated', 'seoTitle', 'seoTitleTranslated', 'seoDescription', 'seoDescriptionTranslated', 'alt' )
				);
			}
			// end PLUGINS-6870 there's some cases when data are not cached because emojis, need to encode

			$result['data'] = wp_json_encode( $data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE );

			EcwidPlatform::store_in_categories_cache( $url, $result );
		}//end if

		$result = json_decode( $result['data'] );

		return $result;
	}

	public function get_product( $product_id ) {

		if ( ! $product_id ) {
			return false;
		}

		$params = array();

		if ( ! isset( $params['baseUrl'] ) ) {
			$params['baseUrl'] = Ecwid_Store_Page::get_store_url();
		}

		if ( Ecwid_Seo_Links::is_enabled() ) {
			$params['cleanUrls'] = 'true';
		} else {
			$params['cleanUrls'] = 'false';
		}

        if ( Ecwid_Seo_Links::is_slugs_without_ids_enabled() ) {
			$params['slugsWithoutIds'] = 'true';
		}

		$options = $this->build_request_headers();

		$url = $this->build_request_url(
			$this->products_api_url . '/' . $product_id,
			$params
		);

		$result = EcwidPlatform::get_from_products_cache( $url );

		if ( ! $result ) {
			if ( empty( $options ) ) {
				return false;
			}

			$result = EcwidPlatform::fetch_url( $url, $options );

			if ( $result['code'] != '200' ) {
				return false;
			}

			EcwidPlatform::store_in_products_cache( $url, $result );
		}

		$result = json_decode( $result['data'] );

		return $result;
	}

	public function search_products( $input_params ) {
		$params = array();

		$passthru = array( 'updatedFrom', 'offset', 'limit', 'sortBy', 'keyword', 'baseUrl', 'cleanUrls', 'category', 'productId', 'slugsWithoutIds' );
		foreach ( $passthru as $name ) {
			if ( array_key_exists( $name, $input_params ) ) {
				$params[ $name ] = (string) $input_params[ $name ];
			}
		}

		if ( ! isset( $params['baseUrl'] ) ) {
			$params['baseUrl'] = Ecwid_Store_Page::get_store_url();
		}

		if ( Ecwid_Seo_Links::is_enabled() ) {
			$params['cleanUrls'] = 'true';
		}

        if ( Ecwid_Seo_Links::is_slugs_without_ids_enabled() ) {
			$params['slugsWithoutIds'] = 'true';
		}

		$params['enabled'] = 'true';

		if ( EcwidPlatform::get( 'hide_out_of_stock' ) ) {
			$params['inStock'] = 'true';
		}

		$options = $this->build_request_headers();

		$url = $this->build_request_url(
			$this->products_api_url,
			$params
		);

		$result = EcwidPlatform::get_from_products_cache( $url );

		if ( ! $result ) {
			if ( empty( $options ) ) {
				return false;
			}

			$result = EcwidPlatform::fetch_url( $url, $options );

			if ( $result['code'] != '200' ) {
				return false;
			}

			EcwidPlatform::store_in_products_cache( $url, $result );
		}

		$result = json_decode( $result['data'] );

		if ( ! empty( $result->items ) ) {
			foreach ( $result->items as $item ) {
				if ( Ecwid_Seo_Links::is_enabled() ) {
					$item->seo_link = $item->url;
				}
				Ecwid_Product::init_from_stdclass( $item );
			}
		}

		$this->_maybe_remember_all_products( $params, $result, $url );

		return $result;
	}

	public function get_deleted_products( $input_params ) {
		$params = array();

		if ( array_key_exists( 'from_date', $input_params ) ) {
			$params['from_date'] = $input_params['from_date'];
		}

		if ( array_key_exists( 'offset', $input_params ) ) {
			$params['offset'] = $input_params['offset'];
		}

		if ( array_key_exists( 'limit', $input_params ) ) {
			$params['limit'] = $input_params['limit'];
		}

		$options = $this->build_request_headers();

		$url = $this->build_request_url(
			$this->products_api_url . '/deleted',
			$params
		);

		if ( empty( $options ) ) {
			return false;
		}

		$result = EcwidPlatform::fetch_url( $url, $options );

		if ( $result['code'] != '200' ) {
			return false;
		}

		$result = json_decode( $result['data'] );

		return $result;
	}

	public function get_products( $input_params ) {
		$params = array();

		$passthru = array( 'updatedFrom', 'offset', 'limit', 'sortBy', 'keyword', 'createdFrom', 'createdTo', 'sku', 'enabled', 'responseFields', 'slugsWithoutIds' );

		foreach ( $passthru as $name ) {
			if ( array_key_exists( $name, $input_params ) ) {
				$params[ $name ] = $input_params[ $name ];
			}
		}

		if ( isset( $params['createdTo'] ) ) {
			// For some reason createdTo does not include the exact timestamp while createdFrom does
			++$params['createdTo'];
		}

		$options = $this->build_request_headers();

		$url = $this->build_request_url(
			$this->products_api_url,
			$params
		);

		if ( empty( $options ) ) {
			return false;
		}

		$result = EcwidPlatform::fetch_url( $url, $options );

		if ( $result['code'] != '200' ) {
			return false;
		}
		$result = json_decode( $result['data'] );

		return $result;
	}

	protected static function _load_token() {
		$db_value = get_option( self::TOKEN_OPTION_NAME );
		if ( empty( $db_value ) ) {
			return false;
		}

		if ( strlen( $db_value ) == 64 ) {
			$encrypted = base64_decode( $db_value );
			if ( empty( $encrypted ) ) {
				return false;
			}

			$token = EcwidPlatform::decrypt( $encrypted );

			if ( $token == $db_value ) {
				return false;
			}
		} else {
			$token = $db_value;
		}

		return $token;
	}

	public static function get_token() {
		if ( ecwid_is_demo_store() ) {
			return ecwid_get_demo_store_public_key();
		}

		$config_value = Ecwid_Config::get_token();

		if ( $config_value ) {
			return $config_value;
		}

		return self::_load_token();
	}

	public function get_oauth_dialog_url( $redirect_uri, $scope ) {
		if ( ! $scope || ! $redirect_uri ) {
			return null;
		}

		$url = Ecwid_Config::get_oauth_auth_url();

		$query = array();

		$query['source']        = 'wporg';
		$query['client_id']     = Ecwid_Config::get_oauth_appid();
		$query['redirect_uri']  = $redirect_uri;
		$query['response_type'] = 'code';
		$query['scope']         = $scope;

		if ( Ecwid_Config::get_channel_id() && ! Ecwid_Config::is_wl() ) {
			$query['partner'] = Ecwid_Config::get_channel_id();
		}

		$is_default_wl_domain = strpos( $url, 'shopsettings.com' ) !== false || strpos( $url, 'business.shop' ) !== false;
		if ( ! isset( $query['partner'] ) && Ecwid_Config::is_wl() && $is_default_wl_domain ) {
			$query['partner'] = Ecwid_Config::get_channel_id();
		}

		foreach ( $query as $key => $value ) {
			$query[ $key ] = rawurlencode( $value );
		}

		return $url . '?' . build_query( $query );
	}

	public function does_store_exist( $email ) {
		$params = array(
			'appClientId',
			'appSecretKey',
			'email' => $email,
		);

		$url = $this->build_request_url( $this->stores_api_url, $params );

		$request = Ecwid_Http::create_get(
			'does_store_exist',
			$url,
			array(
				Ecwid_Http::POLICY_RETURN_VERBOSE,
			)
		);

		if ( ! $request ) {
			return false;
		}

		$result = $request->do_request();

		return @$result['code'] == 200;
	}

	public function get_store_update_stats( $additional_params = false ) {

		static $stats = null;

		if ( $stats ) {
			return $stats;
		}

		$url = $this->api_url . $this->store_id . '/latest-stats';

		$params = array();

		if ( is_array( $additional_params ) ) {
			$params = array_merge( $additional_params, $params );
		}

		$options = $this->build_request_headers();
		$url     = $this->build_request_url( $url, $params );

		if ( empty( $options ) ) {
			return false;
		}

		$result = EcwidPlatform::fetch_url( $url, $options );

		if ( ! isset( $result['data'] ) ) {
			if ( isset( $result['message'] ) ) {
				ecwid_log_error( $result['message'] );
			}

			return null;
		}

		$stats = json_decode( $result['data'] );

		return $stats;
	}

	public function get_store_profile( $disable_cache = false ) {

		if ( ecwid_is_demo_store() ) {
			return false;
		}

		$profile = EcwidPlatform::cache_get( self::PROFILE_CACHE_NAME );

		if ( ! empty( $profile ) && ! $disable_cache ) {
			return $profile;
		}

		if ( ! self::get_token() ) {
			self::set_api_status( self::API_STATUS_ERROR_TOKEN );
			return false;
		}

		$params = array(
			'responseFields' => 'generalInfo,account,settings,payment(applePay),featureToggles,formatsAndUnits(currency,currencyPrefix,currencySuffix),designSettings',
		);

		$options = $this->build_request_headers();
		$url     = $this->build_request_url( $this->profile_api_url, $params );

		if ( empty( $options ) ) {
			return false;
		}

		$result = EcwidPlatform::fetch_url( $url, $options );

		if ( @$result['code'] == '403' ) {
			if ( get_option( EcwidPlatform::OPTION_ECWID_CHECK_API_RETRY_AFTER, 0 ) == 0 ) {
				self::set_api_status( self::API_STATUS_ERROR_TOKEN );
				self::save_token( '' );
			} else {
				update_option( EcwidPlatform::OPTION_ECWID_CHECK_API_RETRY_AFTER, time() + 5 * MINUTE_IN_SECONDS );
			}

			return false;
		}

		if ( self::get_api_status() == self::API_STATUS_OK && ( @$result['code'] != '200' || empty( $result['data'] ) ) ) {
			ecwid_log_error( var_export( $result, true ) );
			self::set_api_status( self::API_STATUS_UNDEFINED );
			return false;
		}

		$profile = json_decode( $result['data'] );

		// PLUGINS-6870 there's some cases when data are not cached because emojis, need to encode
		if ( ! empty( $profile ) ) {
			$profile->settings = EcwidPlatform::encode_fields_with_emoji(
				$profile->settings,
				array( 'storeName', 'storeDescription', 'storeDescriptionTranslated', 'rootCategorySeoTitleTranslated', 'rootCategorySeoDescription', 'rootCategorySeoDescriptionTranslated' )
			);
		}
		// end PLUGINS-6870 there's some cases when data are not cached because emojis, need to encode

		EcwidPlatform::cache_set( self::PROFILE_CACHE_NAME, $profile, 10 * MINUTE_IN_SECONDS );

		if ( $profile && isset( $profile->settings ) && isset( $profile->settings->hideOutOfStockProductsInStorefront ) ) {
			EcwidPlatform::set( 'hide_out_of_stock', $profile->settings->hideOutOfStockProductsInStorefront );
		}

		add_action( 'wp', array( 'Ecwid_Store_Page', 'set_store_url' ) );

		return $profile;
	}

	public function update_store_profile( $params ) {
		$request_params = array();

		$url = $this->build_request_url( $this->profile_api_url, $request_params );

		$result = $this->_do_put( $url, $params );

		if ( ! is_wp_error( $result ) && @$result['response']['code'] == '200' ) {
			return $result;
		}

		return false;
	}

	public function is_store_feature_enabled( $feature_name ) {

		static $features = array();

		if ( ! empty( $features ) && array_key_exists( $feature_name, $features ) ) {
			return $features[ $feature_name ]['enabled'];
		}

		$profile = $this->get_store_profile();

		if ( ! $profile ) {
			return false;
		}

		$toggles = @$profile->featureToggles;

		if ( ! $toggles ) {
			return false;
		}

		foreach ( $toggles as $feature ) {
			if ( $feature->name == $feature_name ) {
				$features[ $feature_name ]            = array();
				$features[ $feature_name ]['enabled'] = $feature->enabled;

				return $feature->enabled;
			}
		}

		return false;
	}

	public function is_store_feature_available( $feature_name ) {
		$profile = $this->get_store_profile();

		if ( $profile
			&& property_exists( $profile, 'account' )
			&& property_exists( $profile->account, 'availableFeatures' )
			&& is_array( $profile->account->availableFeatures )
			&& in_array( $feature_name, $profile->account->availableFeatures )
		) {
			return true;
		}

		return false;
	}

	public function create_store( $params = array() ) {
		global $current_user;
		$admin_email = $current_user->user_email;

		$admin_first = get_user_meta( $current_user->ID, 'first_name', true );
		if ( ! $admin_first ) {
			$admin_first = get_user_meta( $current_user->ID, 'nickname', true );
		}

		$admin_last = get_user_meta( $current_user->ID, 'last_name', true );
		if ( ! $admin_last ) {
			$admin_last = get_user_meta( $current_user->ID, 'nickname', true );
		}

		$admin_name = implode( ' ', array( $admin_first, $admin_last ) );
		$store_url  = Ecwid_Store_Page::get_store_url();
		$site_name  = get_bloginfo( 'name' );
		$site_email = get_option( 'admin_email' );
		$timezone   = get_option( 'timezone_string', 'UTC+0' );

		if ( ! empty( $params['email'] ) ) {
			$admin_email = $params['email'];
		}

		if ( ! empty( $params['name'] ) ) {
			$admin_name = $params['name'];
		}

		if ( ! empty( $params['password'] ) ) {
			$password = $params['password'];
		} else {
			$password = '';
		}

		$data = array(
			'merchant'         => array(
				'email'    => $admin_email,
				'name'     => $admin_name,
				'password' => $password,
			),
			'affiliatePartner' => array(
				'source' => 'wporg',
			),
			'profile'          => array(
				'generalInfo'       => array(
					'storeUrl' => $store_url,
				),
				'account'           => array(
					'accountName'  => $admin_name,
					'accountEmail' => $admin_email,
				),
				'settings'          => array(
					'storeName' => $site_name,
				),
				'mailNotifications' => array(
					'adminNotificationEmails'       => array( $site_email ),
					'customerNotificationFromEmail' => $site_email,
				),
				'formatsAndUnits'   => array(
					'timezone' => $timezone,
				),
			),
		);

		if ( ! empty( $params['channel_id'] ) ) {
			$data['merchant']['channelId'] = $params['channel_id'];
		}

		if ( ! empty( $params['goods'] ) ) {
			$data['profile']['registrationAnswers']['goods'] = $params['goods'];
		}

		if ( isset( $_SERVER['REMOTE_ADDR'] ) && ! in_array( $_SERVER['REMOTE_ADDR'], array( '127.0.0.1', '::1' ) ) ) {
			$data['merchant']['ip'] = sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) );
		}

		$ref = apply_filters( 'ecwid_get_new_store_ref_id', '' );

		if ( $ref ) {
			$data['affiliatePartner']['ambassador'] = array(
				'ref' => $ref,
			);
		}

		$request_params = array(
			'returnApiToken' => 'true',
		);
		$url            = $this->build_request_url( $this->stores_api_url, $request_params );

		$result = EcwidPlatform::http_post_request(
			$url,
			json_encode( $data ),
			array(
				'timeout' => 20,
				'headers' => array(
					'Content-Type'           => 'application/json;charset="utf-8"',
					'X-Ecwid-App-Client-Id'  => Ecwid_Config::get_oauth_appid(),
					'X-Ecwid-App-Secret-Key' => Ecwid_Config::get_oauth_appsecret(),
				),
			)
		);

		return $result;
	}

	public static function format_time( $time ) {
		return date_i18n( 'Y-m-d H:i:s', $time );
	}

	protected function build_request_url( $url, $input_params ) {
		$params = array();
		foreach ( $input_params as $key => $param ) {
			if ( ! is_string( $key ) ) {
				if ( $param == 'appClientId' ) {
					$params['appClientId'] = Ecwid_Config::get_oauth_appid();
				} elseif ( $param == 'appSecretKey' ) {
					$params['appSecretKey'] = Ecwid_Config::get_oauth_appsecret();
				} elseif ( $param == 'token' ) {
					$params['token'] = self::get_token();
				}
			} else {
				$params[ $key ] = rawurlencode( $param );
			}
		}

		$lang = apply_filters( 'ecwid_lang', null );
		if ( ! empty( $lang ) ) {
			$params['lang'] = $lang;
		}

		return $url . '?' . build_query( $params );
	}

	protected function build_request_headers() {
		$headers = array();
		$token   = self::get_token();

		if ( ! empty( $token ) ) {
			$headers['headers'] = array(
				'Authorization' => 'Bearer ' . $token,
			);
		}

		return $headers;
	}

	public function create_product( $params ) {
		$request_params = array();
		$url            = $this->build_request_url( $this->products_api_url, $request_params );

		$params = $this->_sanitize_product_data( $params );

		$result = $this->_do_post( $url, $params );

		return $result;
	}

	public function create_product_variation( $params ) {
		$request_params = array();

		$url = $this->build_request_url( $this->products_api_url . '/' . $params['productId'] . '/combinations', $request_params );

		$result = $this->_do_post( $url, $params );

		return $result;
	}

	public function update_product( $params, $product_id ) {
		$request_params = array();

		$url = $this->build_request_url( $this->products_api_url . '/' . $product_id, $request_params );

		$params = $this->_sanitize_product_data( $params );

		$result = $this->_do_put( $url, $params );

		return $result;
	}

	protected function _sanitize_product_data( $data ) {

		$int_fields = array( 'quantity', 'defaultCategoryId', 'showOnFrontPage' );
		foreach ( $int_fields as $field ) {
			if ( array_key_exists( $field, $data ) ) {
				$data[ $field ] = intval( $data[ $field ] );
			}
		}

		$float_fields = array( 'price' );
		foreach ( $float_fields as $field ) {
			if ( array_key_exists( $field, $data ) ) {
				$data[ $field ] = floatval( $data[ $field ] );
			}
		}

		if ( array_key_exists( 'categoryIds', $data ) ) {
			foreach ( $data['categoryIds'] as $key => $id ) {
				$data['categoryIds'][ $key ] = intval( $id );
			}
		}

		return $data;
	}

	public function create_category( $params ) {

		$request_params = array();

		$url = $this->build_request_url( $this->categories_api_url, $request_params );

		$params = $this->_sanitize_category_data( $params );

		$result = $this->_do_post( $url, $params );

		return $result;
	}

	public function update_category( $params, $category_id ) {

		$request_params = array();

		$url = $this->build_request_url( $this->categories_api_url . '/' . $category_id, $request_params );

		$params = $this->_sanitize_category_data( $params );

		$result = $this->_do_put( $url, $params );

		return $result;
	}

	protected function _sanitize_category_data( $data ) {
		$result = array();

		$int_fields = array( 'parentId', 'orderBy' );
		foreach ( $int_fields as $field ) {
			if ( array_key_exists( $field, $data ) ) {
				$data[ $field ] = intval( $data[ $field ] );
			}
		}

		return $data;
	}

	public function delete_products( $ids ) {
		$request_params = array();
		$requests       = array();
		foreach ( $ids as $id ) {
			$requests[] = array(
				'type'    => Requests::DELETE,
				'headers' => array(
					'Authorization' => 'Bearer ' . self::get_token(),
				),
				'url'     => $this->build_request_url( $this->products_api_url . '/' . $id, $request_params ),
			);
		}

		$result = Requests::request_multiple( $requests );

		return $result;
	}

	public function upload_category_image( $params ) {
		$request_params = array();
		$url            = $this->build_request_url( $this->categories_api_url . '/' . $params['categoryId'] . '/image', $request_params );

		$result = $this->_do_post( $url, $params['data'], true );

		return $result;
	}

	public function upload_product_image( $params ) {
		$request_params = array();
		$url            = $this->build_request_url( $this->products_api_url . '/' . $params['productId'] . '/image', $request_params );

		$result = $this->_do_post( $url, $params['data'], true );

		return $result;
	}

	public function upload_product_gallery_image( $params ) {
		$request_params = array();
		$url            = $this->build_request_url( $this->products_api_url . '/' . $params['productId'] . '/gallery', $request_params );

		$result = $this->_do_post( $url, $params['data'], true );

		return $result;
	}


	public function upload_product_variation_image( $params ) {
		$request_params = array();
		$url            = $this->build_request_url( $this->products_api_url . '/' . $params['productId'] . '/combinations/' . $params['variationId'] . '/image', $request_params );

		$result = $this->_do_post( $url, $params['data'], true );

		return $result;
	}

	public function get_starter_site_info() {
		$request_params = array();

		$options = $this->build_request_headers();
		$url     = $this->build_request_url( $this->starter_site_api_url, $request_params );

		if ( empty( $options ) ) {
			return false;
		}

		$result = EcwidPlatform::fetch_url( $url, $options );

		if ( ! isset( $result['data'] ) ) {
			return null;
		}

		$data = json_decode( $result['data'] );

		return $data;
	}

	protected function _do_post( $url, $data, $raw = false ) {
		$result = wp_remote_post(
			$url,
			array(
				'body'    => $raw ? $data : json_encode( $data ),
				'timeout' => 20,
				'headers' => array(
					'Content-Type'  => 'application/json;charset="utf-8"',
					'Authorization' => 'Bearer ' . self::get_token(),
				),
			)
		);

		if ( is_array( $result ) ) {
			$result['http_message'] = $this->_get_response_message_from_wp_remote_results( $result );
			$json_result            = $result['body'];
			$api_error              = json_decode( $json_result );
			if ( is_object( $api_error ) ) {
				$result['api_code']    = @$api_error->errorCode; //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$result['api_message'] = @$api_error->errorMessage; //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			}
		}

		return $result;
	}

	protected function _do_put( $url, $data, $raw = false ) {
		$result = wp_remote_post(
			$url,
			array(
				'body'    => $raw ? $data : json_encode( $data ),
				'timeout' => 20,
				'headers' => array(
					'Content-Type'  => 'application/json;charset="utf-8"',
					'Authorization' => 'Bearer ' . self::get_token(),
				),
				'method'  => 'PUT',
			)
		);

		if ( is_array( $result ) ) {
			$result['api_message'] = $this->_get_response_message_from_wp_remote_results( $result );
		}

		return $result;
	}

	protected function _get_response_message_from_wp_remote_results( $result ) {
		$raw     = $result['http_response']->get_response_object()->raw;
		$pattern = '!HTTP/1.1 [0-9][0-9][0-9] (.*)!';
		if ( preg_match( $pattern, $raw, $matches ) ) {
			return substr( $matches[1], 0, strlen( $matches[1] ) - 1 );
		}

		return null;
	}

	protected function _maybe_remember_all_products( $params, $result, $url ) {
		$limiting_params = array(
			'updatedFrom',
			'keyword',
			'category',
			'productId',
		);

		$all = true;
		foreach ( $limiting_params as $param ) {
			if ( array_key_exists( $param, $params ) ) {
				$all = false;
				break;
			}
		}

		if ( $all ) {
			EcwidPlatform::store_in_products_cache( 'ecwid_total_products', $result->total );

			if ( $result->total < 100 && $result->count == $result->total ) {
				EcwidPlatform::store_in_products_cache( 'ecwid_all_products_request', $url );
			} else {
				EcwidPlatform::store_in_products_cache( 'ecwid_all_products_request', '' );
			}
		}
	}

	public function create_batch( $params ) {
		$request_params = array(
			'stopOnFirstFailure' => 'false',
		);
		$url            = $this->build_request_url( $this->batch_requests_api_url, $request_params );

		$result = $this->_do_post( $url, $params );

		return $result;
	}

	public function get_batch_status( $ticket ) {
		$params = array(
			'ticket' => $ticket,
		);

		$options = $this->build_request_headers();
		$url     = $this->build_request_url( $this->batch_requests_api_url, $params );

		if ( empty( $options ) ) {
			return false;
		}

		$result = EcwidPlatform::fetch_url( $url, $options );

		if ( @$result['code'] != '200' ) {
			return false;
		}

		return $result;
	}

	public function compose_batch_item( $path, $method = 'GET', $body = false, $batch_id = false ) {
		$result = array(
			'path'   => $path,
			'method' => $method,
		);

		if ( ! empty( $body ) ) {
			$result['body'] = $body;
		}

		if ( ! empty( $batch_id ) ) {
			$result['id'] = $batch_id;
		}

		return $result;
	}

	public function batch_create_product( $params, $batch_id = false ) {
		return $this->compose_batch_item(
			'/products',
			'POST',
			$this->_sanitize_product_data( $params ),
			$batch_id
		);
	}

	public function batch_update_product( $params, $product_id, $batch_id = false ) {
		return $this->compose_batch_item(
			'/products/' . $product_id,
			'PUT',
			$this->_sanitize_product_data( $params ),
			$batch_id
		);
	}

	public function batch_delete_product( $product_id, $batch_id = false ) {
		return $this->compose_batch_item(
			'/products/' . $product_id,
			'DELETE',
			false,
			$batch_id
		);
	}

	public function batch_upload_category_image_async( $params, $category_id, $batch_id = false ) {
		$url = $this->build_request_url( '/categories/' . $category_id . '/image/async', array() );

		return $this->compose_batch_item(
			$url,
			'POST',
			$params,
			$batch_id
		);
	}

	public function batch_upload_product_image_async( $params, $product_id, $batch_id = false ) {
		$url = $this->build_request_url( '/products/' . $product_id . '/image/async', array() );

		return $this->compose_batch_item(
			$url,
			'POST',
			$params,
			$batch_id
		);
	}

	public function batch_upload_product_gallery_image_async( $params, $product_id, $batch_id = false ) {
		$url = $this->build_request_url( '/products/' . $product_id . '/gallery/async', array() );

		return $this->compose_batch_item(
			$url,
			'POST',
			$params,
			$batch_id
		);
	}

	public function batch_delete_all_gallery_image( $product_id, $batch_id = false ) {

		return $this->compose_batch_item(
			'/products/' . $product_id . '/gallery',
			'DELETE',
			false,
			$batch_id
		);
	}

	public function batch_upload_product_variation_image( $params, $product_id, $variation_id, $batch_id = false ) {
		$url = $this->build_request_url( '/products/' . $product_id . '/combinations/' . $variation_id . '/image', $params );

		return $this->compose_batch_item(
			$url,
			'POST',
			false,
			$batch_id
		);
	}

	public function batch_create_product_variation( $params, $product_id, $batch_id = false ) {
		return $this->compose_batch_item(
			'/products/' . $product_id . '/combinations',
			'POST',
			$params,
			$batch_id
		);
	}

	public function build_static_pages_request_url( $params ) {
        $allowed_modes = array(
            'home',
            'product',
            'category'
        );
        $default_mode = 'home';

        if( empty( $params['mode'] ) || ! in_array( $params['mode'], $allowed_modes ) ) {
            $mode = $default_mode;
        } else {
            $mode = $params['mode'];
        }

        if( empty( $params['id'] ) || $mode === $default_mode ) {
            $id = false;
        } else {
            $id = intval($params['id']);
        }

        $url = $this->static_pages_api_url . '/' . $mode . '-page/' . $this->store_id;
        
        if( ! empty( $id ) ) {
            $url .= '/' . $id;
        }
        
        $url .= '/static-code';

        return $url;
    }

	public function get_static_page( $endpoint_params, $query_params ) {

        $url = $this->build_static_pages_request_url( $endpoint_params );

        $options = array(
            'timeout' => 3
        );

		$url = $this->build_request_url(
			$url,
			$query_params
		);

		$result = EcwidPlatform::fetch_url( $url, $options );

        if ( $result['code'] != '200' ) {
			return false;
		}

        $data = json_decode( $result['data'] );

		return $data;
    }

	public function get_storefront_widget_page( $params ) {

		if ( empty( $params['slug'] ) && empty( $params['storeRootPage'] ) ) {
			return false;
		}

		$options = $this->build_request_headers();

		$options['timeout'] = 3;

		$url = $this->build_request_url(
			$this->storefront_widget_pages_api_url,
			$params
		);

		$result = EcwidPlatform::fetch_url( $url, $options );

		if ( $result['code'] != '200' ) {
			return false;
		}

		$data = json_decode( $result['data'] );

		return $data;
	}
}
