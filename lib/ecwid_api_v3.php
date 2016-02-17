<?php

require_once dirname(__FILE__) . '/ecwid_platform.php';

class Ecwid_Api_V3
{
	const CLIENT_ID = 'RD4o2KQimiGUrFZc';
	const CLIENT_SECRET = 'jEPVdcA3KbzKVrG8FZDgNnsY3wKHDTF8';

	const TOKEN_OPTION_NAME = 'ecwid_oauth_token';

	public function __construct() {
		$this->_api_url = ' https://app.ecwid.com/api/v3/';
		$this->_stores_api_url = $this->_api_url . 'stores';
	}

	public function is_api_available()
	{
	}

	public static function save_token($token)
	{
		EcwidPlatform::init_crypt(true);

		$value = base64_encode(EcwidPlatform::encrypt($token));

		update_option(self::TOKEN_OPTION_NAME, $value);
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

		$result = $this->api_get($url);

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

		$result = EcwidPlatform::http_post_request($url, json_encode($params));

		return $result;
	}

	protected function api_get($url)
	{
		$result = EcwidPlatform::http_get_request($url);

		if (@$result['code'] == '403') {
			EcwidPlatform::set('api_v3_enabled', false);
			EcwidPlatform::set('api_v3_last_error', $result['data']);
		}
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
			} else {
				$params[$key] = urlencode($param);
			}
		}

		return $url . '?' . build_query($params);
	}
}