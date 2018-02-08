<?php

abstract class Ecwid_Http {

	protected $name = '';
	protected $url = '';
	protected $policies;
	protected $is_error = false;
	protected $error_message = '';
	protected $raw_result;
	protected $processed_data;
	protected $timeout;
	protected $jsonp_callback = null;
	protected $code;
	protected $message;
	protected $headers;
	protected $error;

	const TRANSPORT_CHECK_EXPIRATION = 86400;

	/**
	 * No error handling whatsoever
	 */
	const POLICY_IGNORE_ERRORS = 'ignore_errors';

	/**
	 * Data sent and received will be treated like jsonp
	 */
	const POLICY_RETURN_JSON  = 'return_json';

	/**
	 * Data received will be interpreted as json array
	 */
	const POLICY_RETURN_JSON_ARRAY  = 'expect_json_array';

	/**
	 * Data sent and received will be treated like jsonp
	 */
	const POLICY_EXPECT_JSONP  = 'expect_jsonp';

	/**
	 * Returns all response data with headers and such instead of data only
	 */
	const POLICY_RETURN_VERBOSE = 'return_verbose';

	abstract protected function _do_request($url, $args);

	public function __construct($name, $url, $policies) {
		$this->name = $name;
		$this->url = $url;
		$this->policies = $policies;
	}

	public function get_response_meta() {
		return array(
			'data' => $this->raw_result,
			'code' => $this->code,
			'message' => $this->message,
			'headers' => $this->headers
		);
	}

	public function do_request($args = array()) {
		$url = $this->_preprocess_url($this->url);

		$data = $this->_do_request($url, $args);

		if ( is_null( $data ) || $this->is_error ) {
			return null;
		}

		$this->_process_data($data);

		return $this->processed_data;
	}

	public static function create_get($name, $url, $params) {

		$transport_class = self::_get_transport();
		
		$transport = new $transport_class($name, $url, $params);

		return $transport;
	}

	public static function create_post($name, $url, $params) {
		$transport_class = self::_post_transport();
		
		$transport = new $transport_class($name, $url, $params);

		return $transport;
	}

	protected static function _set_transport_for_request($name, $transport) {
		EcwidPlatform::set('get_transport_' . $name, $transport);
	}

	protected static function _get_transport_for_request($name) {
		return EcwidPlatform::get('get_transport_' . $name);
	}

	protected static function _get_transport() {
		return 'Ecwid_HTTP_Get_WpRemoteGet';
	}

	protected static function _post_transport() {
		return 'Ecwid_HTTP_Post_WpRemotePost';
	}
	
	protected function _trigger_error() {
		$this->is_error = true;
		$this->error = $this->raw_result;

		self::_set_transport_for_request($this->name, null);

		if ( $this->_has_policy(self::POLICY_IGNORE_ERRORS) ) {
			return false;
		}

		return true;
	}

	protected function _has_policy( $policy ) {
		return in_array( $policy, $this->policies );
	}

	protected function _process_data($raw_data) {
		$result = $raw_data;

		if ( in_array( self::POLICY_EXPECT_JSONP, $this->policies ) ) {
			$prefix_length = strlen($this->jsonp_callback . '(');
			$suffix_length = strlen(');');
			$result = substr($raw_data, $prefix_length, strlen($result) - $suffix_length - $prefix_length - 1);

			$result = json_decode($result);
		}

		if ( in_array( self::POLICY_RETURN_JSON_ARRAY, $this->policies ) ) {
			$result = json_decode($raw_data, true);
		}

		if ( in_array( self::POLICY_RETURN_JSON, $this->policies ) ) {
			$result = json_decode($raw_data);
		}

		if ( $this->_has_policy( self::POLICY_RETURN_VERBOSE ) ) {
			$result = $this->get_response_meta();
			$result['data'] = $raw_data;
		}

		$this->processed_data = $result;
	}

	protected function _preprocess_url($url) {

		if ( in_array( 'expect_jsonp', $this->policies ) ) {
			$this->jsonp_callback = 'jsoncallback' . time();
			$url .= '&callback=' . $this->jsonp_callback;
		}

		return $url;
	}
}

abstract class Ecwid_HTTP_Get extends Ecwid_Http {
	protected function _trigger_error() {
		$continue = parent::_trigger_error();

		if (!$continue) {
			return false;
		}
		update_option('ecwid_remote_get_fails', 1);

		ecwid_log_error($this->message);
	}
}

class Ecwid_HTTP_Get_WpRemoteGet extends Ecwid_HTTP_Get {

	protected function _do_request($url, $args) {

		$this->raw_result = wp_remote_get(
			$url,
			$args
		);

		if (is_wp_error($this->raw_result)) {
			$this->error = $this->raw_result;
			
			$this->_trigger_error();

			return $this->raw_result;
		}

		$this->code = $this->raw_result['response']['code'];
		$this->message = $this->raw_result['response']['message'];
		$this->headers = $this->raw_result['headers'];

		return $this->raw_result['body'];
	}

	protected function _trigger_error() {

		if (is_wp_error($this->error)) {
			$a = new WP_Error;

			$this->error_message = $this->error->get_error_message();
		}

		return parent::_trigger_error();
	}
}

abstract class Ecwid_HTTP_Post extends Ecwid_Http {

}

class Ecwid_HTTP_Post_WpRemotePost extends Ecwid_Http_Post {

	protected function _do_request($url, $args) {

		$this->raw_result = wp_remote_post(
			$url,
			$args
		);

		if (is_wp_error($this->raw_result)) {
			$this->_trigger_error();

			return $this->raw_result;
		}

		$this->code = $this->raw_result['response']['code'];
		$this->message = $this->raw_result['response']['message'];
		$this->headers = $this->raw_result['headers'];

		return $this->raw_result['body'];

	}
}