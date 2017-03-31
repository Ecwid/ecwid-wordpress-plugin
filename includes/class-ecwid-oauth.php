<?php

include ECWID_PLUGIN_DIR . "lib/phpseclib/AES.php";
require_once ECWID_PLUGIN_DIR . 'lib/ecwid_api_v3.php';

class Ecwid_OAuth {

	const MODE_CONNECT = 'connect';
	const MODE_RECONNECT = 'reconnect';

	protected $state;

	protected $api;

	public function __construct()
	{
		add_action('admin_post_ecwid_oauth', array($this, 'process_authorization'));
		add_action('admin_post_ecwid_oauth_reconnect', array($this, 'process_authorization'));
		add_action('admin_post_ecwid_disconnect', array($this, 'disconnect_store'));
		add_action('admin_post_ecwid_show_reconnect', array($this, 'show_reconnect'));

		$this->_load_state();

		$this->api = new Ecwid_Api_V3();
	}

	public function show_reconnect()
	{
		$ecwid_oauth = $this;
		require_once(ECWID_PLUGIN_DIR . 'templates/reconnect.php');
	}

	public function test_post()
	{
		$return = EcwidPlatform::http_post_request($this->get_test_post_url());

		return is_array($return);
	}

	public function get_test_post_url()
	{
		return Ecwid_Config::get_oauth_url();
	}


	public function get_auth_dialog_url( )
	{
		$action = 'ecwid_oauth';
		if ( $this->_is_reconnect()  ) {
			$action = 'ecwid_oauth_reconnect';
		}

		$redirect_uri = 'admin-post.php?action=' . $action;

		return $this->api->get_oauth_dialog_url(
			admin_url( $redirect_uri ),
			implode(' ', $this->_get_scope() )
		);
	}

	public function get_sso_reconnect_dialog_url()
	{
		$redirect_uri = 'admin-post.php?action=ecwid_oauth_reconnect';

		$scope = $this->_get_scope();

		if (!in_array('create_customers', $scope)) {
			$scope[] = 'create_customers';
		}

		return $this->api->get_oauth_dialog_url(
			admin_url( $redirect_uri ),
			implode(' ', $scope )
		);
	}

	public function process_authorization()
	{
		$reconnect = $_REQUEST['action'] == 'ecwid_oauth_reconnect';

		if ( isset( $_REQUEST['error'] ) || !isset( $_REQUEST['code'] ) ) {
			if ($reconnect) {
				$this->update_state(array('mode' => self::MODE_RECONNECT, 'error' => 'cancelled'));
			} else {
				$this->update_state(array('mode' => self::MODE_CONNECT, 'error' => 'cancelled'));
			}

			wp_redirect( Ecwid_Admin::get_dashboard_url() . '&connection_error' . ($reconnect ? '&reconnect' : ''));
			exit;
		}

		$base_admin_url = 'admin-post.php?action=ecwid_oauth' . ($reconnect ? '_reconnect' : '');

		$params['code'] = $_REQUEST['code'];
		$params['client_id'] = Ecwid_Config::get_oauth_appid();
		$params['client_secret'] = Ecwid_Config::get_oauth_appsecret();
		$params['redirect_uri'] = admin_url( $base_admin_url );

		$params['grant_type'] = 'authorization_code';

		$request = Ecwid_HTTP::create_post( 'oauth_authorize', Ecwid_Config::get_oauth_url(), array(
			Ecwid_HTTP::POLICY_RETURN_VERBOSE
		));

		$return = $request->do_request(array('body' => $params));

		if (is_array($return) && isset($return['data'])) {
			$result = json_decode($return['data']);
		}

		if (
			!is_array($return)
			|| !isset( $result->store_id )
			|| !isset( $result->scope )
			|| !isset( $result->access_token )
			|| ( $result->token_type != 'Bearer' )
		) {
			ecwid_log_error(var_export($return, true));
			return $this->trigger_auth_error($reconnect ? 'reconnect' : 'default');
		}

		ecwid_update_store_id( $result->store_id );

		update_option( 'ecwid_oauth_scope', $result->scope );
		update_option( 'ecwid_api_check_time', 0 );
		EcwidPlatform::cache_reset( 'all_categories' );
		$this->api->save_token($result->access_token);

		// Reset "Create store cookie" set previously to display the landing page
		//in "Connect" mode rather than "Create" mode
		setcookie('ecwid_create_store_clicked', null, strtotime('-1 day'), ADMIN_COOKIE_PATH, COOKIE_DOMAIN);

		if ( isset( $this->state->return_url ) && !empty( $this->state->return_url ) ) {
			wp_redirect( admin_url( $this->state->return_url ) );
		} else {
			$url = '';
			if ($reconnect) {
				$url = Ecwid_Admin::get_dashboard_url() . '&setting-updated=true';
			} else {
				$url = Ecwid_Admin::get_dashboard_url();
			}
			wp_redirect( $url );
		}
		exit;
	}

	public function disconnect_store()
	{
		update_option( 'ecwid_store_id', ECWID_DEMO_STORE_ID );
		$this->api->save_token( '' );
		update_option( 'ecwid_is_api_enabled', 'off' );
		update_option( 'ecwid_api_check_time', 0 );

		wp_redirect( Ecwid_Admin::get_dashboard_url() );
		exit;
	}

    public function get_safe_scopes_array($scopes)
    {
        if (!isset($scopes) || empty($scopes)) {
            return $this->_get_default_scopes_array();
        }

        if (!empty($scopes)) {
            $scopes_array = explode(' ', $scopes);

            foreach ($scopes_array as $key => $scope) {
                if (!preg_match('/^[a-z_]+$/', $scope)) {
                    unset($scopes_array[$key]);
                }
            }
        }

        return $scopes_array;
    }

	public function has_scope( $scope ) {
		$stored_scope = get_option( 'ecwid_oauth_scope' );
		if (empty($stored_scope)) {
			$stored_scope = 'read_store_profile read_catalog';
		}

		return in_array( $scope, explode(' ', $stored_scope) );
	}

	protected function _get_default_scopes_array() {
		return array( 'read_store_profile', 'read_catalog', 'allow_sso', 'create_customers', 'public_storefront' );
	}

	protected function trigger_auth_error($mode = 'default')
	{
		update_option('ecwid_last_oauth_fail_time', time());

		$logs = get_option('ecwid_error_log');

		if ($logs) {
			$logs = json_decode($logs);
		}

		if (is_array($logs) && count($logs) > 0) {
			$entry = $logs[count($logs) - 1];
			if (isset($entry->message)) {
				$last_error = $entry->message;
			}
		}

		if ( $mode == self::MODE_RECONNECT ) {
			$this->update_state(array(
				'mode' => 'reconnect',
				'error' => 'other'
			));
		}

		if (isset($last_error)) {
			EcwidPlatform::report_error($last_error);
		}

		wp_redirect( Ecwid_Admin::get_dashboard_url() . '&connection_error' . ( $mode == self::MODE_RECONNECT ? '&reconnect' : '' ) );
		exit;
	}

	protected function _get_scope() {
		$default = $this->_get_default_scopes_array();

		$scopes = array();
		if ( $this->_is_reconnect() ) {
			$scopes = isset($this->state->reconnect_scopes) && is_array($this->state->reconnect_scopes)
				? $this->state->reconnect_scopes
				: array();
		}

		$scopes = array_merge($scopes, $default);

		return $scopes;
 	}

	public function get_sso_admin_link() {
		$url = 'https://my.ecwid.com/api/v3/%s/sso?token=%s&timestamp=%s&signature=%s&inline=true';

		$store_id = get_ecwid_store_id();

		$token = $this->api->get_token();

		$timestamp = time();
		$signature = hash('sha256', $store_id . $token . $timestamp . Ecwid_Config::get_oauth_appsecret());

		$url = sprintf(
			$url,
			$store_id,
			$token,
			$timestamp,
			$signature
		);

		return $url;
	}

	protected function _load_state() {
		if (isset($_COOKIE['ecwid_oauth_state'])) {
			$this->state = @json_decode( $_COOKIE['ecwid_oauth_state'] );

		}

		if (!is_object($this->state)) {
			$this->state = new stdClass();
			$this->state->reconnect_scopes = array();
			$this->state->reconnect_error = '';
			$this->state->return_url = '';
			$this->state->reason = '';
			$this->state->mode = self::MODE_CONNECT;
		}

		if (isset($_COOKIE['ecwid_create_store_clicked'])) {
			$this->state->create_store_clicked = $_COOKIE['ecwid_create_store_clicked'];
		}
	}

	public function get_state() {
		return $this->state;
	}

	public function was_create_store_clicked() {
		return $this->state->create_store_clicked;
	}

	protected function _save_state() {
		setcookie('ecwid_oauth_state', json_encode($this->state), strtotime('+1 day'), ADMIN_COOKIE_PATH, COOKIE_DOMAIN);
	}

	public function get_reconnect_error() {
		return $this->state->reconnect_error;
	}

	public function update_state($params) {

		if (isset($params['mode'])) {
			$this->state->mode = $params['mode'] == self::MODE_RECONNECT ? self::MODE_RECONNECT : self::MODE_CONNECT;
		}

		if ( $this->_is_reconnect() ) {
			if ( isset( $params['scope'] ) ) {
				$this->state->reconnect_scopes = $this->get_safe_scopes_array( @$params['scope'] );
			}
			if ( isset( $params['return_url'] ) ) {
				$this->state->return_url = $params['return_url'];
			}

			if ( isset( $params['error'] ) ) {
				$this->state->reconnect_error = $params['error'];
			}

			if ( isset( $params['reason'] ) ) {
				$this->state->reason = $params['reason'];
			}
		}

		$this->_save_state();
	}

	public function get_error() {

		if ($this->_is_reconnect()) {
			return $this->state->reconnect_error;
		} else {
			return $this->state->error;
		}
	}

	public function get_reconnect_message() {
		$reconnect_message = '';

		if (isset($this->state->reason)) {
			switch ( $this->state->reason ) {
				case 'spw':
					$reconnect_message = sprintf( __( 'To be able to choose a product to insert to your posts and pages, you will need to re-connect your site to your %s store. This will only require you to accept permissions request – so that the plugin will be able to list your products in the "Add product" dialog.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() );
					break;
				case '2':
					$reconnect_message = "Message 2";
					break;
			}
		}

		return $reconnect_message;
	}

	protected function _is_reconnect() {
		return @$this->state->mode == self::MODE_RECONNECT;
	}
}

$ecwid_oauth = new Ecwid_OAuth();
