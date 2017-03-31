<?php

require_once 'ecwid_requests.php';

class EcwidPlatform {

	static protected $http_use_streams = false;

	static protected $crypt = null;

	static public function get_store_id()
	{
		return get_ecwid_store_id();
	}

	static public function init_crypt($force = false)
	{
		if ( $force || is_null(self::$crypt) ) {
			self::$crypt = new Ecwid_Crypt_AES();
			self::_init_crypt();
		}
	}

	static protected function _init_crypt()
	{
		self::$crypt->setIV( substr( md5( SECURE_AUTH_SALT . get_option('ecwid_store_id') ), 0, 16 ) );
		self::$crypt->setKey( SECURE_AUTH_KEY );
	}

	static public function encrypt($what)
	{
		self::init_crypt();

		return self::$crypt->encrypt($what);
	}

	static public function decrypt($what)
	{
		self::init_crypt();

		return self::$crypt->decrypt($what);
	}

	static public function esc_attr($value)
	{
		return esc_attr($value);
	}

	static public function esc_html($value)
	{
		return esc_html($value);
	}

	static public function get_price_label()
	{
		return __('Price', 'ecwid-shopping-cart');
	}

	static public function cache_get($name, $default = false)
	{
		$result = get_transient('ecwid_' . $name);
		if ($default !== false && $result === false) {
			return $default;
		}

		return $result;
	}

	static public function cache_set($name, $value, $expires_after)
	{
		set_transient('ecwid_' . $name, $value, $expires_after);
	}

	static public function cache_reset($name) {
		delete_transient('ecwid_' . $name);
	}

	static public function parse_args($args, $defaults)
	{
		return wp_parse_args($args, $defaults);
	}

	static public function report_error($error) {
		ecwid_log_error(json_encode($error));
	}

	static public function fetch_url($url, $options = array())
	{
		$default_timeout = 10;

		$ctx = stream_context_create(
			array(
				'http'=> array(
					'timeout' => $default_timeout
				)
			)
		);

		$use_file_get_contents = get_option('ecwid_fetch_url_use_file_get_contents', false);

		if ($use_file_get_contents) {
				$result = @file_get_contents($url, null, $ctx);
		} else {
				if (get_option('ecwid_http_use_stream', false)) {
					self::$http_use_streams = true;
				}
				$result = wp_remote_get( $url, array_merge(
						$options,
						array(
							'timeout' => get_option( 'ecwid_remote_get_timeout', $default_timeout )
						)
					)
				);

				if (get_option('ecwid_http_use_stream', false)) {
					self::$http_use_streams = false;
				}
				if (!is_array($result)) {
						$result = @file_get_contents($url, null, $ctx);
						if (!empty($result)) {
								update_option('ecwid_fetch_url_use_file_get_contents', true);
						}
				}
		}

		$return = array(
			'code' => '',
			'data' => '',
			'message' => ''
		);

		if (is_string($result)) {
			$return['code'] = 200;
			$return['data'] = $result;
		}

		if (is_array($result)) {
			$return = array(
				'code' => $result['response']['code'],
				'data' => $result['body']
			);
		} elseif (is_object($result)) {

			$return = array(
				'code' => $result->get_error_code(),
				'data' => $result->get_error_data(),
				'message' => $result->get_error_message()
			);

			$get_contents = @file_get_contents($url, null, $ctx);
			if ($get_contents !== false) {
				$return = array(
					'code' => 200,
					'data' => $get_contents,
					'is_file_get_contents' => true
				);
			}
		}

		if ( ( empty($return['data']) || $return['code'] != 200 ) && !isset($options['self_call']) ) {

			$log_url = 'http://' . APP_ECWID_COM . '/script.js?805056&data_platform=wporg&data_wporg_error=remote_get_fails';
			$log_url .= '&data_url=' . urlencode(get_bloginfo('url'));
			$log_url .= '&data_target_url=' . urlencode($url);
			if (get_option('ecwid_http_use_stream', false)) {
				$log_url .= '&data_method=stream';
			} elseif (get_option('ecwid_fetch_url_use_file_get_contents')) {
				$log_url .= '&data_method=filegetcontents';
			}

			$log_url .= '&data_code=' . $return['code'];
			$log_url .= '&data_message=' . urlencode(@$return['message']);

			self::fetch_url($log_url, array('self_call' => 1));
			update_option('ecwid_remote_get_fails', 1);
		} 

		return $return;
	}

	static public function http_get_request($url) {
		return self::fetch_url($url);
	}

	static public function http_post_request($url, $data = array(), $params = array())
	{
		$result = null;

		$args =array();

		if (!empty($params)) {
			$args = $params;
		}

		$args['body'] = $data;

		if (get_option('ecwid_http_use_stream', false) !== true) {

			$result = wp_remote_post( $url, $args );
		}

		if ( !is_array($result) ) {
			self::$http_use_streams = true;
			$result = wp_remote_post( $url, $args );
			self::$http_use_streams = false;

			if ( is_array($result) ) {
				update_option('ecwid_http_use_stream', true);
			}
		}

		return $result;
	}

	static public function get( $name, $default = null )
	{
		$options = get_option( 'ecwid_plugin_data' );

		if ( is_array( $options ) && array_key_exists( $name, $options ) ) {
			return $options[$name];
		}

		return $default;
	}

	static public function set( $name, $value ) {
		$options = get_option( 'ecwid_plugin_data' );

		if ( !is_array( $options ) ) {
			$options = array();
		}

		$options[$name] = $value;

		update_option( 'ecwid_plugin_data', $options );
	}

	static public function reset( $name ) {
		$options = get_option( 'ecwid_plugin_data' );

		if ( !is_array( $options ) || !array_key_exists($name, $options)) {
			return;
		}

		unset($options[$name]);

		update_option( 'ecwid_plugin_data', $options );

	}

	static public function http_api_transports($transports)
	{
		if (self::$http_use_streams) {
			return array('streams');
		}

		return $transports;
	}

	static public function is_set_time_limit_available() {
		return function_exists('set_time_limit' )
		       && strpos( ini_get( 'disable_functions' ), 'set_time_limit' ) == false
		       && ! ini_get( 'safe_mode' );
	}
}

add_filter('http_api_transports', array('EcwidPlatform', 'http_api_transports'));
