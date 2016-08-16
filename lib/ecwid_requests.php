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

	const TRANSPORT_CHECK_EXPIRATION = 60 * 60 * 24;

	const POLICY_IGNORE_ERRORS = 'ignore_errors';
	const POLICY_EXPECT_JSONP  = 'expect_jsonp';

	abstract protected function _do_request($url, $args);

	public function __construct($name, $url, $policies) {
		$this->name = $name;
		$this->url = $url;
		$this->policies = $policies;
	}

	public function do_request($args) {
		$url = $this->_preprocess_url($this->url);

		$data = $this->_do_request($url, $args);

		if ( is_null( $data ) ) return null;

		$this->_process_data($data);

		return $this->processed_data;
	}

	public static function create_get($name, $url, $params) {
		$transport_class = self::_get_transport($name, $url, $params);

		if (!$transport_class) {
			$transport_class = self::_detect_get_transport($name, $url, $params);
		}

		if (empty($transport_class)) {
			return null;
		}

		$transport = new $transport_class($name, $url, $params);

		return $transport;
	}

	protected static function _get_transport($name) {
		$data = EcwidPlatform::get('get_transport_' . $name);

		if (!empty($data) && $data['use_default']) {
			return self::_get_default_transport();
		}

		if (!empty(@$data['preferred']) && ( time() - @$data['last_check'] ) < self::TRANSPORT_CHECK_EXPIRATION ) {
			return $data['preferred'];
		}

		return null;
	}

	protected static function _detect_get_transport($name, $url, $params) {

		foreach (self::_get_transports() as $transport_class) {
			$transport = new $transport_class($name, $url, $params);

			$result = $transport->do_request();

			if (!$transport->is_error) {
				self::_set_transport_for_request(
					$name,
					array(
						'preferred' => $transport_class,
						'last_check' => time()
					)
				);

				return $transport_class;
			}
		}

		return null;
	}

	protected static function _set_transport_for_request($name, $transport) {
		EcwidPlatform::set('get_transport_' . $name, $transport);
	}

	protected static function _get_transport_for_request($name) {
		return EcwidPlatform::get('get_transport_' . $name);
	}

	protected static function _get_default_transport() {
		return 'Ecwid_HTTP_Get_WpRemoteGet';
	}

	protected static function _get_transports() {
		return array('Ecwid_HTTP_Get_WpRemoteGet', 'Ecwid_HTTP_Get_Fopen');
	}

	protected function _trigger_error() {
		$this->is_error = true;
		$this->error = $this->raw_result;

		if ( $this->has_policy(self::IGNORE_ERRORS) ) {
			return;
		}
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
}

class Ecwid_HTTP_Get_WpRemoteGet extends Ecwid_HTTP_Get {

	protected function _do_request($url, $args) {

		$this->raw_result = wp_remote_get(
			$url,
			$args
		);

		if (is_wp_error($this->raw_result)) {
			return $this->_handle_error();

			return $this->raw_result;
		}

		$this->code = $this->raw_result['response']['code'];
		$this->message = $this->raw_result['response']['message'];

		return $this->raw_result['body'];
	}
}

class Ecwid_HTTP_Get_Fopen extends Ecwid_HTTP_Get {

	protected function _do_request($url, $args) {

		$ctx = stream_context_create($this->_build_stream_context_args($args));
		$handle = @fopen($url, 'r', $ctx);

		if (!$handle) {
			$this->_handle_error();

			return null;
		}

		$this->result = stream_get_contents($handle);

		$stream_meta = stream_get_meta_data($handle);
		$status = explode(' ', $stream_meta['wrapper_data'][0]);
		$this->code = $status[1];
		$this->message = $status[2];

		return $this->raw_result;
	}

	protected function _build_stream_context_args($args) {
		$result = array();

		if (@$args['timeout']) {
			$result['timeout'] = $args['timeout'];
		}

		return $result;
	}

}