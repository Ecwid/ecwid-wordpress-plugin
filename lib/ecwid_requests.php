<?php

class Ecwid_Request {
	protected $params = array();
	protected $url = '';

	public function add_params($params) {
		$this->params = $params;
	}

	public function set_url($url) {
		$this->url = $url;
	}
}


class Ecwid_Request_ApiV3 {
	protected $api_url;

	public function __construct() {
		$this->api_url = 'https://app.ecwid.com/api/v3/';
	}

	public function init() {

	}
}

class Ecwid_Request_CreateStore {
	public function execute () {

	}
}

class Ecwid_Http {
	public $code;
	public $message;
}

class Ecwid_Http_Fopen {
	public function execute($url, $params) {

	}
}

class Ecwid_Http_WpRemoteGet {
	public function execute($url, $params) {

		$args = $this->_process_args($params);
		$result = wp_remote_get($url, $args);

		if (is_wp_error($result)) {
			return array(
				'error' => $result
			);
		}

		$this->code = $result['response']['code'];
		$this->message = $result['response']['message'];

		return $result;
	}

	protected function _process_args($args) {
		$return = EcwidPlatform::parse_args(
			array('timeout' => 5),
			$args
		);

		return $return;
	}
}

class Ecwid_Http_WpRemoteGetStream extends Ecwid_Http_WpRemoteGet {

	public function __construct() {
		parent::__construct();

		add_filter( 'http_api_transports', array( $this, 'http_api_transports' ) );
	}

	public function http_api_transports() {

		return array('streams');

	}

}