<?php

require_once dirname(__FILE__) . '/ecwid_platform.php';

class Ecwid_Api_V3
{
	const CLIENT_ID = 'RD4o2KQimiGUrFZc';
	const CLIENT_SECRET = 'jEPVdcA3KbzKVrG8FZDgNnsY3wKHDTF8';

	public function __construct() {
		$this->_api_url = ' https://app.ecwid.com/api/v3/';
		$this->_stores_api_url = $this->_api_url . 'stores';
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
			'appClientSecret',
			'email' => $email
		);

		$url = $this->build_request_url($this->_stores_api_url, $params);

		$result = EcwidPlatform::http_get_request($url);

		die(var_dump($result, $url));
		return @$result['code'] == 200;
	}

	protected function build_request_url($url, $params)
	{
		foreach ($params as $key => $param) {
			if ( $param == 'appClientId' ) {
				unset($params[$key]);
				$params['appClientId'] = self::CLIENT_ID;
			} elseif ( $param == 'appClientSecret' ) {
				unset($params[$key]);
				$params['appClientSecret'] = self::CLIENT_SECRET;
			} else {
				$params[$key] = urlencode($param);
			}
		}

		return $url . '?' . build_query($params);
	}
}