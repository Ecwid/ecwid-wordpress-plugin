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
			'appSecretKey',
			'email' => $email
		);

		$url = $this->build_request_url($this->_stores_api_url, $params);

		$result = EcwidPlatform::http_get_request($url);

		return @$result['code'] == 200;
	}

	public function create_store()
	{
		global $current_user;

		$admin_email = time() . '+' . $current_user->user_email;

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

		$site_url = get_bloginfo('url');
		$site_name = get_bloginfo('name');
		$site_email = get_option('admin_email');

		$params = array(
			'merchant' => array(
				'email' => $admin_email,
				'name' => $admin_name,
				'password' => wp_generate_password(8),
				'ip' => $_SERVER['REMOTE_ADDR']
			),
			'affiliatePartner' => array(
				'source' => 'wporg'
			),
			'profile' => array(
				'generalInfo' => array(
					'storeUrl' => $site_url
				)
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
			)
		);

		$request_params =  array(
			'appClientId',
			'appSecretKey',
			'returnApiToken' => 'true'
		);
		$url = $this->build_request_url($this->_stores_api_url, $request_params);

		$result = EcwidPlatform::http_post_request($url, json_encode($params));

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
			} else {
				$params[$key] = urlencode($param);
			}
		}

		return $url . '?' . build_query($params);
	}
}