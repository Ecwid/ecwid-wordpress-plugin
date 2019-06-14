<?php

require_once ECWID_PLUGIN_DIR . 'lib/ecwid_api_v3.php';

class Ecwid_OAuth {

	const MODE_CONNECT = 'connect';
	const MODE_RECONNECT = 'reconnect';
	
	const OPTION_JUST_CONNECTED = 'ecwid_just_connected';
	
	const SCOPE_READ_CATALOG = 'read_catalog';
	const SCOPE_READ_STORE_PROFILE = 'read_store_profile';
	const SCOPE_UPDATE_STORE_PROFILE = 'update_store_profile';

	protected $state;

	protected $api;

	public function __construct()
	{
		add_action('admin_post_ec_oauth', array($this, 'process_authorization'));
		add_action('admin_post_ec_oauth_reconnect', array($this, 'process_authorization'));
		add_action('admin_post_ec_disconnect', array($this, 'disconnect_store'));
		add_action('admin_post_ec_show_reconnect', array($this, 'show_reconnect'));

		if ( get_option( self::OPTION_JUST_CONNECTED ) ) {
			add_action( 'shutdown', array( $this, 'reset_just_connected' ) );
		}
		
		$this->_load_state();

		$this->api = new Ecwid_Api_V3();
	}

	public function test_post()
	{
		$return = EcwidPlatform::http_post_request($this->get_test_post_url());

		return is_array($return);
	}

	public function get_test_post_url()
	{
		return Ecwid_Config::get_oauth_auth_url();
	}


	public function get_auth_dialog_url( )
	{
		$action = 'ec_oauth';
		if ( $this->_is_reconnect()  ) {
			$action = 'ec_oauth_reconnect';
		}

		$redirect_uri = 'admin-post.php?action=' . $action;

		return $this->api->get_oauth_dialog_url(
			admin_url( $redirect_uri ),
			implode(' ', $this->_get_scope() )
		);
	}

	public function get_sso_reconnect_dialog_url()
	{
		$redirect_uri = 'admin-post.php?action=ec_oauth_reconnect';

		$scope = $this->_get_scope();

		if (!in_array( 'create_customers', $scope ) ) {
			$scope[] = 'create_customers';
		}

		return $this->api->get_oauth_dialog_url(
			admin_url( $redirect_uri ),
			implode(' ', $scope )
		);
	}

	public function process_authorization()
	{
		$reconnect = $_REQUEST['action'] == 'ec_oauth_reconnect';

		if ( isset( $_REQUEST['error'] ) || !isset( $_REQUEST['code'] ) ) {
			if ($reconnect) {
				$this->update_state(array('mode' => self::MODE_RECONNECT, 'error' => 'cancelled'));
			} else {
				$this->update_state(array('mode' => self::MODE_CONNECT, 'error' => 'cancelled'));
			}

			wp_redirect( Ecwid_Admin::get_dashboard_url() . '&connection_error' . ($reconnect ? '&reconnect' : ''));
			exit;
		}

		$base_admin_url = 'admin-post.php?action=ec_oauth' . ($reconnect ? '_reconnect' : '');

		$params['code'] = $_REQUEST['code'];
		$params['client_id'] = Ecwid_Config::get_oauth_appid();
		$params['client_secret'] = Ecwid_Config::get_oauth_appsecret();
		$params['redirect_uri'] = admin_url( $base_admin_url );

		$params['grant_type'] = 'authorization_code';
		
		$request = Ecwid_HTTP::create_post( 'oauth_authorize', Ecwid_Config::get_oauth_token_url(), array(
			Ecwid_HTTP::POLICY_RETURN_VERBOSE
		));

		$return = $request->do_request(array('body' => $params));

		$result = new stdClass();
		if ( is_array( $return ) && isset( $return['data'] ) ) {
			$result = json_decode( $return['data'] );
		}

		if (
			!is_array( $return )
			|| !isset( $result->store_id )
			|| !isset( $result->scope )
			|| !isset( $result->access_token )
			|| ( $result->token_type != 'Bearer' )
		) {
			return $this->trigger_auth_error($reconnect ? 'reconnect' : 'default');
		}

		ecwid_update_store_id( $result->store_id );

		update_option( 'ecwid_oauth_scope', $result->scope );
		update_option( 'ecwid_api_check_time', 0 );
		update_option( 'ecwid_public_token', $result->public_token );
		update_option( self::OPTION_JUST_CONNECTED, true );
		EcwidPlatform::cache_reset( 'all_categories' );
		ecwid_invalidate_cache( true );
		Ecwid_Api_V3::reset_api_status();
		
		$this->api->save_token($result->access_token);
		
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
		update_option( 'ecwid_store_id', ecwid_get_demo_store_id() );
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
		
		if (Ecwid_Config::overrides_token()) {
			$stored_scope = implode(' ', $this->_get_default_scopes_array());
		} else {
			$stored_scope = get_option( 'ecwid_oauth_scope' );
			if (empty($stored_scope)) {
				$stored_scope = implode(
					' ',
					array(
						Ecwid_OAuth::SCOPE_READ_STORE_PROFILE,
						Ecwid_OAuth::SCOPE_UPDATE_STORE_PROFILE,
						Ecwid_OAuth::SCOPE_READ_CATALOG
					)
				);
			}
		}

		return in_array( $scope, explode(' ', $stored_scope) );
	}

	protected function _get_default_scopes_array() {
		$defaults = array(
			Ecwid_OAuth::SCOPE_READ_STORE_PROFILE,
			Ecwid_OAuth::SCOPE_UPDATE_STORE_PROFILE,
			Ecwid_OAuth::SCOPE_READ_CATALOG, 
			'allow_sso', 
			'create_customers', 
			'public_storefront'
		);
	
		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			$defaults[] = 'create_catalog';
			$defaults[] = 'update_catalog';
		}
		
		return $defaults;
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
		$url = 'https://' . Ecwid_Config::get_cpanel_domain() . '/api/v3/%s/sso?token=%s&timestamp=%s&signature=%s&inline=true';

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
	}

	public function get_state() {
		return $this->state;
	}

	protected function _save_state() {
		if ( !headers_sent( ) ) {
			setcookie('ecwid_oauth_state', json_encode($this->state), strtotime('+1 day'), ADMIN_COOKIE_PATH, COOKIE_DOMAIN);
		}
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

	public static function just_connected()
	{
		return get_option( self::OPTION_JUST_CONNECTED );
	}
	
	public function reset_just_connected()
	{
		update_option( self::OPTION_JUST_CONNECTED, false );
	}
	
	protected function _is_reconnect() {
		return @$this->state->mode == self::MODE_RECONNECT;
	}
}

$ecwid_oauth = new Ecwid_OAuth();
