<?php

require_once dirname(__FILE__) . '/ecwid_platform.php';

class Ecwid_Api_V3
{
	const CLIENT_ID = 'RD4o2KQimiGUrFZc';
	const CLIENT_SECRET = 'jEPVdcA3KbzKVrG8FZDgNnsY3wKHDTF8';

	const TOKEN_OPTION_NAME = 'ecwid_oauth_token';
	
	const PROFILE_CACHE_NAME = 'apiv3_store_profile';
	
	const FEATURE_NEW_PRODUCT_LIST = 'NEW_PRODUCT_LIST';

	public $store_id = null;
	
	protected static $profile = null;

	public function __construct() {

		$this->store_id = EcwidPlatform::get_store_id();
		$this->_api_url = 'https://' . Ecwid_Config::get_api_domain() . '/api/v3/';
		$this->_stores_api_url = $this->_api_url . 'stores';

		$this->_categories_api_url = $this->_api_url . $this->store_id . '/categories';
		$this->_products_api_url = $this->_api_url . $this->store_id . '/products';
	}

	public static function is_available()
	{
		$token = self::_load_token();
		
		if ( $token && $token != get_option( self::TOKEN_OPTION_NAME ) ) {
			return true;
		}

		return false;
	}

	public static function save_token($token)
	{
		EcwidPlatform::init_crypt(true);

		$value = base64_encode(EcwidPlatform::encrypt($token));

		update_option(self::TOKEN_OPTION_NAME, $value);
	}


	public function get_categories($input_params)
	{
		$params = array('token');
		if (array_key_exists('parent', $input_params)) {
			$params['parent'] = $input_params['parent'];
		}
		$passthru = array( 'offset', 'limit', 'parent', 'baseUrl', 'cleanUrls', 'hidden_categories' );
		foreach ($passthru as $name) {
			if ( array_key_exists( $name, $input_params ) ) {
				$params[$name] = $input_params[$name];
			}
		}
		
		if ( !isset( $params['baseUrl'] ) ) {
			$params['baseUrl'] = Ecwid_Store_Page::get_store_url();
		}

		if ( Ecwid_Seo_Links::is_enabled() ) {
			$params['cleanUrls'] = 'true';
		}

		$url = $this->build_request_url(
				$this->_categories_api_url,
				$params
		);
		
		$result = EcwidPlatform::get_from_categories_cache($url);
		
		if ( !$result ) {
			$result = EcwidPlatform::fetch_url( $url );
		}

		if ($result['code'] != '200') {
			return false;
		}

		EcwidPlatform::store_in_categories_cache( $url, $result );

		$result = json_decode( $result['data'] );

		if ( !empty( $result->items ) ) {
			foreach ( $result->items as $item ) {
				if (Ecwid_Seo_Links::is_enabled()) {
					$item->seo_link = $item->url;
				}
				
				Ecwid_Category::from_stdclass( $item );
			}
		}
		
		return $result;
	}

	public function get_category($categoryId)
	{
		if (!isset($categoryId) || $categoryId == 0 ) {
			return false;
		}

		$params = array('token');


		if ( !isset( $params['baseUrl'] ) ) {
			$params['baseUrl'] = Ecwid_Store_Page::get_store_url();
		}

		if ( Ecwid_Seo_Links::is_enabled() ) {
			$params['cleanUrls'] = 'true';
		}

		$url = $this->build_request_url(
				$this->_categories_api_url . '/' . $categoryId,
				$params
		);
		$result = EcwidPlatform::get_from_categories_cache( $url );

		if ( !$result ) {
			$result = EcwidPlatform::fetch_url( $url );
		}

		if ($result['code'] != '200') {
			return false;
		}

		EcwidPlatform::store_in_categories_cache( $url, $result );

		$result = json_decode( $result['data'] );

		return $result;
	}

	public function get_product( $product_id ) {
		$params = array('token');

		if ( !isset( $params['baseUrl'] ) ) {
			$params['baseUrl'] = Ecwid_Store_Page::get_store_url();
		}

		if ( Ecwid_Seo_Links::is_enabled() ) {
			$params['cleanUrls'] = 'true';
		}

		$url = $this->build_request_url(
				$this->_products_api_url . '/' . $product_id,
				$params
		);

		$result = EcwidPlatform::get_from_products_cache( $url );

		if (!$result) {
			
			$result = EcwidPlatform::fetch_url( $url );

			if ($result['code'] != '200') {
				return false;
			}

			EcwidPlatform::store_in_products_cache( $url, $result );
		}

		$result = json_decode($result['data']);

		return $result;
	}

	public function search_products($input_params) {
		$params = array('token');
		$passthru = array( 'updatedFrom', 'offset', 'limit', 'sortBy', 'keyword', 'baseUrl', 'cleanUrls', 'category', 'productId' );
		foreach ($passthru as $name) {
			if ( array_key_exists( $name, $input_params ) ) {
				$params[$name] = (string)$input_params[$name];
			}
		}

		if ( !isset( $params['baseUrl'] ) ) {
			$params['baseUrl'] = Ecwid_Store_Page::get_store_url();
		}

		if ( Ecwid_Seo_Links::is_enabled() ) {
			$params['cleanUrls'] = 'true';
		}

		$params['enabled'] = 'true';
		
		if (EcwidPlatform::get('hide_out_of_stock')) {
			$params['inStock'] = 'true';
		}
		
		$url = $this->build_request_url(
				$this->_products_api_url,
				$params
		);
		
		$result = EcwidPlatform::get_from_products_cache( $url );
		
		if (!$result ) {
			$result = EcwidPlatform::fetch_url( $url );
			if ($result['code'] != '200') {
				return false;
			}

			EcwidPlatform::store_in_products_cache( $url, $result );
		}

		$result = json_decode($result['data']);
		
		if ( !empty( $result->items ) ) {
			foreach ( $result->items as $item ) {
				if (Ecwid_Seo_Links::is_enabled()) {
					$item->seo_link = $item->url;
				}
				Ecwid_Product::init_from_stdclass( $item );
			}
		}

		$this->_maybe_remember_all_products($params, $result, $url);
		
		return $result;
	}

	public function get_deleted_products($input_params) {
		$params = array('token');

		if (array_key_exists('from_date', $input_params)) {
			$params['from_date'] = $input_params['from_date'];
		}

		if (array_key_exists('offset', $input_params)) {
			$params['offset'] = $input_params['offset'];
		}

		if (array_key_exists('limit', $input_params)) {
			$params['limit'] = $input_params['limit'];
		}

		$result = EcwidPlatform::fetch_url(
			$this->build_request_url(
				$this->_products_api_url . '/deleted',
				$params
			)
		);

		if ($result['code'] != '200') {
			return false;
		}

		$result = json_decode($result['data']);

		return $result;
	}

	public function get_products($input_params)
	{
		$params = array('token');

		$passthru = array( 'updatedFrom', 'offset', 'limit', 'sortBy', 'keyword', 'createdFrom', 'createdTo', 'sku' );

		foreach ($passthru as $name) {
		    if ( array_key_exists( $name, $input_params ) ) {
		        $params[$name] = $input_params[$name];
            }
        }
        
        if ( isset( $params['createdTo'] ) ) {
			// For some reason createdTo does not include the exact timestamp while createdFrom does
			$params['createdTo']++;
		}

		$result = EcwidPlatform::fetch_url(
			$this->build_request_url(
				$this->_products_api_url,
				$params
			)
		);

		if ($result['code'] != '200') {
			return false;
		}
		$result = json_decode($result['data']);

		return $result;
	}

	protected static function _load_token()
	{
		$db_value = get_option(self::TOKEN_OPTION_NAME);
		if (empty($db_value)) return false;

		if (strlen($db_value) == 64) {
			
			$encrypted = base64_decode($db_value);
			if (empty($encrypted)) return false;

			$token = EcwidPlatform::decrypt($encrypted);
			
			if ($token == $db_value) {
				return false;
			}
		} else {
			$token = $db_value;
		}
		
		return $token;
	}

	public static function get_token()
	{
		$config_value = Ecwid_Config::get_token();

		if ($config_value) return $config_value;
		
		return self::_load_token();
	}

	public function get_oauth_dialog_url($redirect_uri, $scope)
	{
		if ( !$scope || !$redirect_uri ) {
			return null;
		}

		$url = Ecwid_Config::get_oauth_auth_url();

		$query = array();

		$query['source']        = 'wporg';
		$query['client_id']     = Ecwid_Config::get_oauth_appid();
		$query['redirect_uri']  = $redirect_uri;
		$query['response_type'] = 'code';
		$query['scope']         = $scope;

		foreach ($query as $key => $value) {
			$query[$key] = urlencode($value);
		}

		return $url . '?' . build_query( $query );
	}

	public function does_store_exist($email)
	{
		$params = array(
			'appClientId',
			'appSecretKey',
			'email' => $email
		);

		$url = $this->build_request_url($this->_stores_api_url, $params);

		$request = Ecwid_Http::create_get('does_store_exist', $url, array(
			Ecwid_Http::POLICY_RETURN_VERBOSE
		));

		if (!$request) {
			return false;
		}

		$result = $request->do_request();

		return @$result['code'] == 200;
	}

	public function get_store_update_stats() {

		$url = $this->_api_url . $this->store_id . '/latest-stats';

		$params = array(
			'token' => $this->get_token()
		);

		$url = $this->build_request_url($url, $params);
		$result = EcwidPlatform::fetch_url($url);

		if ( !isset( $result['data'] ) ) {
			return null;
		}
		
		return json_decode($result['data']);
	}

	public function get_store_profile() {
		
		$profile = EcwidPlatform::cache_get( self::PROFILE_CACHE_NAME );
		
		if ( $profile ) {
			return $profile;
		}
		
		$url = $this->_api_url . $this->store_id . '/profile';

		$params = array(
			'token' => $this->get_token()
		);
		
		$url = $this->build_request_url($url, $params);
		$result = EcwidPlatform::fetch_url($url);
		
		if (@$result['code'] != '200' || empty($result['data'])) {
			return false;
		}

		$profile = json_decode($result['data']);

		EcwidPlatform::cache_set( self::PROFILE_CACHE_NAME, $profile, 60 * 5 );
		
		if ($profile && isset($profile->settings) && isset($profile->settings->hideOutOfStockProductsInStorefront)) {
			EcwidPlatform::set('hide_out_of_stock', $profile->settings->hideOutOfStockProductsInStorefront);
		}

		return $profile;
	}
	
	public function is_store_feature_enabled( $feature_name ) {
		
		static $features = array();
	
		if ( array_key_exists( $feature_name, $features ) ) {
			return $features[$feature_name]['enabled'];
		}
		
		$profile = $this->get_store_profile();
	
		if (!$profile) {
			return false;
		}
		
		foreach ( $profile->featureToggles as $feature ) {
			if ( $feature->name == $feature_name ) {
				$features[$feature_name]['enabled'] = $feature->enabled;
				
				return $feature->enabled;
			}
		}
	
		return false;
	}
	
	public function create_store()
	{
		global $current_user;
		$admin_email = $current_user->user_email;

		$admin_first = get_user_meta($current_user->ID, 'first_name', true);
		if (!$admin_first) {
			$admin_first = get_user_meta($current_user->ID, 'nickname', true);
		}
		$admin_last = get_user_meta($current_user->ID, 'last_name', true);
		if (!$admin_last) {
			$admin_last = get_user_meta($current_user->ID, 'nickname', true);
		}
		$admin_name = "$admin_first $admin_last";
		$admin_nickname = $current_user->display_name;
		$store_url = Ecwid_Store_Page::get_store_url();
		$site_name = get_bloginfo('name');
		$site_email = get_option('admin_email');
		$timezone = get_option('timezone_string', 'UTC+0');

		$params = array(
			'merchant' => array(
				'email' => $admin_email,
				'name' => $admin_name,
				'password' => wp_generate_password(8),
				'ip' => in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1', '::1')) ? '35.197.29.131' : $_SERVER['REMOTE_ADDR']
			),
			'affiliatePartner' => array(
				'source' => 'wporg'
			),
			'profile' => array(
				'generalInfo' => array(
					'storeUrl' => $store_url
				),
				'account' => array(
					'accountName' => $admin_name,
					'accountNickName' => $admin_nickname,
					'accountEmail' => $admin_email
				),
				  'settings' => array(
					'storeName' => $site_name
				),
				  'mailNotifications' => array(
					'adminNotificationEmails' => array($site_email),
					'customerNotificationFromEmail' => $site_email
				), 'formatsAndUnits' => array(
					'timezone' => $timezone
				)
			),
		);

		$ref = apply_filters( 'ecwid_get_new_store_ref_id', '' );

		if ($ref) {
			$params['affiliatePartner']['ambassador'] = array(
				'ref' => $ref
			);
		}

		$request_params =  array(
			'appClientId',
			'appSecretKey',
			'returnApiToken' => 'true'
		);
		$url = $this->build_request_url($this->_stores_api_url, $request_params);

		$result = EcwidPlatform::http_post_request(
			$url, json_encode($params),
			array(
				'timeout' => 20,
				'headers' => array(
					'Content-Type' => 'application/json;charset="utf-8"')
			)
		);

		return $result;
	}

	public static function format_time($time) {
		return strftime('%F %T', $time);
	}

	protected function build_request_url($url, $input_params)
	{
		$params = array();
		foreach ($input_params as $key => $param) {
			
			if ( !is_string( $key ) ) {
				if ($param == 'appClientId') {
					$params['appClientId'] = Ecwid_Config::get_oauth_appid();
				} elseif ($param == 'appSecretKey') {
					$params['appSecretKey'] = Ecwid_Config::get_oauth_appsecret();
				} elseif ($param == 'token') {
					$params['token'] = self::get_token();
				}
			}else {
				$params[$key] = urlencode($param);
			}
		}

		return $url . '?' . build_query($params);
	}

	public function create_product( $params ) {
		$request_params =  array(
			'token'
		);
		$url = $this->build_request_url( $this->_products_api_url, $request_params );
		
		$result = $this->_do_post( $url, $params );

		return $result;
	}

	public function update_product( $params ) {
		$request_params =  array(
			'token'
		);
		
		$id = $params['id'];
		unset( $params['id'] );
		
		$url = $this->build_request_url( $this->_products_api_url . '/' . $id, $request_params );

		$result = $this->_do_put( $url, $params );

		return $result;
	}
	
	public function create_category( $params ) {
		$request_params =  array(
			'token'
		);
		$url = $this->build_request_url( $this->_categories_api_url, $request_params );

		$result = $this->_do_post( $url, $params );

		return $result;
	}

	public function delete_products( $ids )
	{
		$request_params = array( 'token' );
		$requests = array();
		foreach ( $ids as $id ) {
			$requests[] = array(
				'type' => Requests::DELETE,
				'url' => $this->build_request_url( $this->_products_api_url . '/' . $id, $request_params )
			);
		}

		$result = Requests::request_multiple( $requests );
		
		return $result;
	}

	public function upload_category_image( $params )
	{
		$request_params =  array(
			'token'
		);
		$url = $this->build_request_url( $this->_categories_api_url . '/' . $params['categoryId'] . '/image', $request_params );

		$result = $this->_do_post( $url, $params['data'], true );

		return $result;
	}

	public function upload_product_image( $params )
	{
		$request_params =  array(
			'token'
		);
		$url = $this->build_request_url( $this->_products_api_url . '/' . $params['productId'] . '/image', $request_params );

		$result = $this->_do_post( $url, $params['data'], true );

		return $result;
	}
	
	protected function _do_post( $url, $data, $raw = false ) {
		$result = wp_remote_post( $url,
			array(
				'body' => $raw ? $data : json_encode( $data ),
				'timeout' => 20,
				'headers' => array(
					'Content-Type' => 'application/json;charset="utf-8"'
				)
			)
		);
		
		if ( is_array( $result ) ) {
			$result['api_message'] = $this->_get_response_message_from_wp_remote_results( $result );
		}
		
		return $result;
	}

	protected function _do_put( $url, $data, $raw = false ) {
		$result = wp_remote_post( $url,
			array(
				'body' => $raw ? $data : json_encode( $data ),
				'timeout' => 20,
				'headers' => array(
					'Content-Type' => 'application/json;charset="utf-8"'
				),
				'method' => 'PUT'
			)
		);

		if ( is_array( $result ) ) {
			$result['api_message'] = $this->_get_response_message_from_wp_remote_results( $result );
		}

		return $result;
	}

	protected function _get_response_message_from_wp_remote_results( $result ) {
		$raw = $result['http_response']->get_response_object()->raw;
		$pattern = '!HTTP/1.1 [0-9][0-9][0-9] (.*)!';
		if ( preg_match( $pattern, $raw, $matches ) ) {
			return substr( $matches[1], 0, strlen($matches[1] ) - 1 );
		}

		return null;
	}
	
	protected function _maybe_remember_all_products($params, $result, $url)
	{
		$limiting_params = array(
			'updatedFrom', 'keyword', 'category', 'productId'
		);

		$all = true;
		foreach ($limiting_params as $param) {
			if (array_key_exists($param, $params)) {
				$all = false;
				break;
			}
		}

		if ($all) {

			EcwidPlatform::store_in_products_cache('ecwid_total_products', $result->total);

			if ($result->total < 100 && $result->count == $result->total) {
				EcwidPlatform::store_in_products_cache('ecwid_all_products_request', $url);
			} else {
				EcwidPlatform::store_in_products_cache('ecwid_all_products_request', '');
			}
		}
	}
}