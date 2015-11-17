<?php

include ECWID_PLUGIN_DIR . "lib/phpseclib/AES.php";

class Ecwid_OAuth {

	const OAUTH_CLIENT_ID = 'RD4o2KQimiGUrFZc';
	const OAUTH_CLIENT_SECRET = 'jEPVdcA3KbzKVrG8FZDgNnsY3wKHDTF8';

	const TOKEN_OPTION_NAME = 'ecwid_oauth_token';

	const MODE_CONNECT = 'connect';
	const MODE_RECONNECT = 'reconnect';

    protected $crypt = null;

	protected $state;

	public function __construct()
	{
		add_action('admin_post_ecwid_oauth', array($this, 'process_authorization'));
		add_action('admin_post_ecwid_oauth_reconnect', array($this, 'process_authorization'));
		add_action('admin_post_ecwid_disconnect', array($this, 'disconnect_store'));
		add_action('admin_post_ecwid_show_reconnect', array($this, 'show_reconnect'));

        $this->crypt = new Ecwid_Crypt_AES();
		$this->_init_crypt();

		$this->_load_state();
	}

	public function show_reconnect()
	{
		$ecwid_oauth = $this;
		require_once(ECWID_PLUGIN_DIR . '/templates/reconnect.php');
	}

	public function test_post()
	{
		$return = wp_remote_post('https://my.ecwid.com/api/oauth/token');

		return is_array($return);
	}

	public function get_auth_dialog_url()
	{
		$action = 'ecwid_oauth';
		if ( $this->_is_reconnect()  ) {
			$action = 'ecwid_oauth_reconnect';
		}

		$redirect_uri = 'admin-post.php?action=' . $action;

		$params = array(
			'scopes' => implode(' ', $this->_get_scope()),
			'redirect_uri' => admin_url( $redirect_uri )
		);

		if ( !is_array( $params )
            || empty( $params['scopes'] )
        ) {
			return false;
		}

		$url = 'https://my.ecwid.com/api/oauth/authorize';

        $query = array();

		$query['source']        = 'wporg';
		$query['client_id']     = self::OAUTH_CLIENT_ID;
		$query['redirect_uri']  = $params['redirect_uri'];
		$query['response_type'] = 'code';
		$query['scope']         = $params['scopes'];
		foreach ($query as $key => $value) {
			$query[$key] = urlencode($value);
		}

		return $url . '?' . build_query( $query );
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

			wp_redirect('admin.php?page=ecwid&connection_error' . ($reconnect ? '&reconnect' : ''));
			exit;
		}

		$base_admin_url = 'admin-post.php?action=ecwid_oauth' . ($reconnect ? '_reconnect' : '');

		$params['code'] = $_REQUEST['code'];
		$params['client_id'] = self::OAUTH_CLIENT_ID;
		$params['client_secret'] = self::OAUTH_CLIENT_SECRET;
		$params['redirect_uri'] = admin_url( $base_admin_url );

		$params['grant_type'] = 'authorization_code';

		$return = wp_remote_post('https://my.ecwid.com/api/oauth/token', array('body' => $params));

		if (is_array($return) && isset($return['body'])) {
			$result = json_decode($return['body']);
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

		update_option( 'ecwid_store_id', $result->store_id );
		$this->_init_crypt();
		$this->_save_token($result->access_token);

		// Reset "Create store cookie" set previously to display the landing page
		//in "Connect" mode rather than "Create" mode
		setcookie('ecwid_create_store_clicked', null, strtotime('-1 day'), ADMIN_COOKIE_PATH, COOKIE_DOMAIN);

		if ( isset( $this->state->return_url ) && !empty( $this->state->return_url ) ) {
			wp_redirect( admin_url( $this->state->return_url ) );
		} else {
			wp_redirect( 'admin.php?page=ecwid&settings-updated=true' );
		}
		exit;
	}

	public function disconnect_store()
	{
		update_option( 'ecwid_store_id', '' );
		update_option( 'ecwid_oauth_token', '' );
		update_option( 'ecwid_is_api_enabled', 'off' );
		update_option( 'ecwid_api_check_time', 0 );

		wp_redirect('admin.php?page=ecwid');
		exit;
	}

    public function get_safe_scopes_array($scopes)
    {
        if (!isset($scopes) || empty($scopes)) {
            return array( 'read_store_profile', 'read_catalog' );
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
			$url = 'http://' . APP_ECWID_COM . '/script.js?805056&data_platform=wporg&data_wporg_error=' . urlencode($last_error) . '&url=' . urlencode(get_bloginfo('url'));
			wp_remote_get($url);
		}

		wp_redirect('admin.php?page=ecwid&connection_error' . ($mode == self::MODE_RECONNECT ? '&reconnect' : ''));
		exit;
	}

	public function get_oauth_token()
	{
		if ($this->is_initialized()) {
			return $this->_load_token();
		}

		return null;
	}

	protected function _get_scope() {
		$default = array( 'read_store_profile', 'read_catalog' );

		$scopes = array();
		if ( $this->_is_reconnect() ) {
			$scopes = isset($this->state->reconnect_scopes) && is_array($this->state->reconnect_scopes)
				? $this->state->reconnect_scopes
				: array();
		}

		$scopes = array_merge($scopes, $default);

		return $scopes;
 	}

	public function is_initialized()
	{
		return get_option( self::TOKEN_OPTION_NAME );
	}

	protected function _save_token($token)
	{
		$value = base64_encode($this->crypt->encrypt($token));

		update_option(self::TOKEN_OPTION_NAME, $value);
	}

	protected function _load_token()
	{

		$db_value = get_option(self::TOKEN_OPTION_NAME);
        if (empty($db_value)) return false;

		if (strlen($db_value) == 64) {
			$encrypted = base64_decode($db_value);
			if (empty($encrypted)) return false;

			$token = $this->crypt->decrypt($encrypted);
		} else {
			$token = $db_value;
		}

		return $token;
	}

	public function _init_crypt() {
		$this->crypt->setIV( substr( md5( SECURE_AUTH_SALT . get_option('ecwid_store_id') ), 0, 16 ) );
		$this->crypt->setKey( SECURE_AUTH_KEY );
	}

	protected function _load_state() {
		if (isset($_COOKIE['ecwid_oauth_state'])) {
			$this->state = @unserialize( $_COOKIE['ecwid_oauth_state'] );
		} else {
			$this->state = new stdClass();
			$this->state->reconnect_scopes = array();
			$this->state->reconnect_error = '';
			$this->state->return_url = '';
			$this->state->reason = '';
			$this->state->mode = self::MODE_CONNECT;
		}

		$this->state->create_store_clicked = @$_COOKIE['ecwid_create_store_clicked'];
	}

	public function get_state() {
		return $this->state;
	}

	public function was_create_store_clicked() {
		return $this->state->create_store_clicked;
	}

	protected function _save_state() {
		setcookie('ecwid_oauth_state', serialize($this->state), strtotime('+1 day'), ADMIN_COOKIE_PATH, COOKIE_DOMAIN);
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
				case '1':
					$reconnect_message = "Message 1";
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
