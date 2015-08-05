<?php

class Ecwid_OAuth {

	public function __construct()
	{
		add_action('admin_post_ecwid_oauth', array($this, 'process_authorization'));
		add_action('admin_post_ecwid_disconnect', array($this, 'disconnect_store'));
		add_action('admin_post_ecwid_show_reconnect', array($this, 'show_reconnect'));
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

	public function get_auth_dialog_url( $scopes = array( 'read_store_profile', 'read_catalog' ) )
	{
		if ( !is_array( $scopes ) ) {
			return false;
		}

		$url = 'https://my.ecwid.com/api/oauth/authorize';

		$params['source']        = 'wporg';
		$params['client_id'] 		 = get_option( 'ecwid_oauth_client_id' );
		$params['redirect_uri']  = admin_url( 'admin-post.php?action=ecwid_oauth' );
		$params['response_type'] = 'code';
		$params['scope']         = implode( ',', $scopes );

		return $url . '?' . build_query( $params );
	}

	public function process_authorization()
	{
		if ( isset( $_REQUEST['error'] ) || !isset( $_REQUEST['code'] ) ) {
			return $this->trigger_auth_error();
		}

		$params['code'] = $_REQUEST['code'];
		$params['client_id'] = get_option( 'ecwid_oauth_client_id' );
		$params['client_secret'] = get_option( 'ecwid_oauth_client_secret' );
		$params['redirect_uri'] = admin_url( 'admin-post.php?action=ecwid_oauth' );
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
			return $this->trigger_auth_error();
		}

		update_option( 'ecwid_store_id', $result->store_id );
		update_option( 'ecwid_oauth_token', $result->access_token );

		setcookie('ecwid_create_store_clicked', null, strtotime('-1 day'), ADMIN_COOKIE_PATH, COOKIE_DOMAIN);

		wp_redirect('admin.php?page=ecwid&settings-updated=true');
	}

	public function disconnect_store()
	{
		update_option( 'ecwid_store_id', '' );
		update_option( 'ecwid_oauth_token', '' );
		update_option('ecwid_is_api_enabled', 'off');
		update_option('ecwid_api_check_time', 0);

		wp_redirect('admin.php?page=ecwid');
	}

	protected function trigger_auth_error()
	{
		update_option('ecwid_last_oauth_fail_time', time());

		$logs = get_option('ecwid_error_log');

		if ($logs) {
			$logs = json_decode($logs);
		}

		if (count($logs) > 0) {
			$entry = $logs[count($logs) - 1];
			if (isset($entry->message)) {
				$last_error = $entry->message;
			}
		}
		if (!$last_error) {
			return;
		}

		$url = 'http://' . APP_ECWID_COM . '/script.js?805056&data_platform=wporg&data_wporg_error=' . urlencode($last_error) . '&url=' . urlencode(get_bloginfo('url'));

		wp_remote_get($url);

		wp_redirect('admin.php?page=ecwid&connection_error=true');
	}
}

$ecwid_oauth = new Ecwid_OAuth();
