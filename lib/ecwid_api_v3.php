<?php

require_once dirname(__FILE__) . '/ecwid_platform.php';

class Ecwid_Api_V3
{
	const CLIENT_ID = 'RD4o2KQimiGUrFZc';
	const CLIENT_SECRET = 'jEPVdcA3KbzKVrG8FZDgNnsY3wKHDTF8';

	const TOKEN_OPTION_NAME = 'ecwid_oauth_token';

	public $store_id = null;

	public function __construct($store_id) {

		$this->store_id = $store_id;
		$this->_api_url = ' https://app.ecwid.com/api/v3/';
		$this->_stores_api_url = $this->_api_url . 'stores';

		$this->_categories_api_url = $this->_api_url . $this->store_id . '/categories';
		$this->_products_api_url = $this->_api_url . $this->store_id . '/products';
	}

	public function is_api_available()
	{
		$token = $this->_load_token();
		if ( $token ) {
			return true;
		}
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

		$result = EcwidPlatform::fetch_url(
			$this->build_request_url(
				$this->_categories_api_url,
				$params
			)
		);

		if ($result['code'] != '200') {
			return false;
		}

		$result = json_decode($result['data']);

		return $result->items;
	}

	public function get_category($categoryId)
	{
		if (!isset($categoryId)) {
			return false;
		}

		$params = array('token');

		$result = EcwidPlatform::fetch_url(
			$this->build_request_url(
				$this->_categories_api_url . '/' . $categoryId,
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
		if (array_key_exists('category', $input_params)) {
			$params['category'] = $input_params['category'];
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

		return $result->items;
	}

	protected static function _load_token()
	{
		$db_value = get_option(self::TOKEN_OPTION_NAME);
		if (empty($db_value)) return false;

		if (strlen($db_value) == 64) {
			$encrypted = base64_decode($db_value);
			if (empty($encrypted)) return false;

			$token = EcwidPlatform::decrypt($encrypted);
		} else {
			$token = $db_value;
		}

		return $token;
	}

	public static function get_token()
	{
		return self::_load_token();
	}

	public function get_oauth_dialog_url($redirect_uri, $scope)
	{
		if ( !$scope || !$redirect_uri ) {
			return null;
		}

		$url = 'https://my.ecwid.com/api/oauth/authorize';

		$query = array();

		$query['source']        = 'wporg';
		$query['client_id']     = self::CLIENT_ID;
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

		$result = EcwidPlatform::fetch_url($url);

		return @$result['code'] == 200;
	}

	public function create_store($params)
	{
		$request_params =  array(
			'appClientId',
			'appSecretKey',
			'returnApiToken' => 'true'
		);
		$url = $this->build_request_url($this->_stores_api_url, $request_params);

		return $url;

		$result = EcwidPlatform::http_post_request($url, $params);

		return $result;
	}

	protected function build_request_url($url, $params)
	{
		foreach ($params as $key => $param) {
			if ( $param == 'appClientId' ) {
				unset($params[$key]);
				$params['appClientId'] = self::CLIENT_ID;
			} elseif ( $param == 'appSecretKey' ) {
				unset($params[$key]);
				$params['appSecretKey'] = self::CLIENT_SECRET;
			} elseif ($param == 'token') {
				unset($params[$key]);
				$params['token'] = self::get_token();
			} else {
				$params[$key] = urlencode($param);
			}
		}

		return $url . '?' . build_query($params);
	}
}