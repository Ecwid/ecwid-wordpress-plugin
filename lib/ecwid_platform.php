<?php

require_once 'ecwid_requests.php';

class EcwidPlatform {

	static protected $http_use_streams = false;

	static protected $crypt = null;

	const CATEGORIES_CACHE_VALID_FROM = 'categories_cache_valid_from';
	const PRODUCTS_CACHE_VALID_FROM = 'products_cache_valid_from';

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

	/*
	 * @throws InvalidArgumentException if $file can't be slugified at all
	 */
	static public function enqueue_script( $file, $deps = array(), $in_footer = false, $handle = false ) {
		
		$filename = $file;
		
		if ( strpos( $file, '.js' ) == strlen( $file ) - 3 ) {
			$filename = substr( $filename, 0, strlen( $file ) - 3 );
		}
		
		if ( !$handle ) {
			$handle = self::slugify( $filename );
		}
		
		$handle = 'ecwid-' . $handle;
		
		$file .= '.js';
		
		if ( defined( 'WP_DEBUG' ) ) {
			$path = ECWID_PLUGIN_DIR . 'js/' . $file;
			
			$ver = filemtime( $path );
		} else {
			$ver = get_option( 'ecwid_plugin_version' );
		}
		
		wp_enqueue_script( $handle, ECWID_PLUGIN_URL . 'js/' . $file, $deps, $ver, $in_footer );
	}

	/*
 * @throws InvalidArgumentException if $file can't be slugified at all
 */
	static public function enqueue_style( $file, $deps = array(), $handle = false ) {

		$filename = $file;

		if ( strpos( $file, '.css' ) == strlen( $file ) - 4 ) {
			$filename = substr( $filename, 0, strlen( $file ) - 4 );
		}

		if ( !$handle ) {
			$handle = self::slugify( $filename );
		}
		
		$handle = 'ecwid-' . $handle;
		
		$file = $filename . '.css';

		if ( defined( 'WP_DEBUG' ) ) {
			$path = ECWID_PLUGIN_DIR . 'css/' . $file;

			$ver = filemtime( $path );
		} else {
			$ver = get_option( 'ecwid_plugin_version' );
		}

		wp_enqueue_style( $handle, ECWID_PLUGIN_URL . 'css/' . $file, $deps, $ver );
	}

	
	static public function slugify( $string ) {
		$match = array();
		$result = preg_match_all( '#[\p{L}0-9\-_]+#u', strtolower( $string ), $match );

		if ( $result && count( @$match[0] ) > 0 ) {
			$handle = implode('-', $match[0] );
		} else {
			throw new InvalidArgumentException( 'Can\'t make slug from ' . $file );
		}		
		return $handle;
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
	
	static public function store_in_products_cache( $url, $data ) {
		
		self::_store_in_cache($url, 'products', $data);
	}


	static public  function store_in_categories_cache( $url, $data ) {
		self::_store_in_cache($url, 'categories', $data);
	}

	static protected function _store_in_cache( $url, $type, $data ) {
		$name = self::_build_cache_name( $url, $type );

		$to_store = array(
			'time' => time(),
			'data' => $data
		);

		self::cache_set( $name, $to_store, MONTH_IN_SECONDS );
	}

	static public function get_from_categories_cache( $key )
	{
		$cache_name = self::_build_cache_name( $key, 'categories' );

		$result = self::cache_get( $cache_name );
		if ( $result['time'] > EcwidPlatform::get( self::CATEGORIES_CACHE_VALID_FROM ) ) {
			return $result['data'];
		}

		return false;
	}

	static public function get_from_products_cache( $key )
	{
		$cache_name = self::_build_cache_name( $key, 'products' );

		$result = self::cache_get( $cache_name );

		if ( $result['time'] > EcwidPlatform::get( self::PRODUCTS_CACHE_VALID_FROM ) ) {
			return $result['data'];
		}

		return false;
	}

	static protected function _build_cache_name( $url, $type ) {
		return $type . '_' . md5($url);
	}

	static public function invalidate_products_cache_from( $time = null )
	{
		$time = is_null( $time ) ? time() : $time; 
		EcwidPlatform::set( self::PRODUCTS_CACHE_VALID_FROM, $time );
	}

	static public function invalidate_categories_cache_from( $time = null )
	{
		$time = is_null( $time ) ? time() : $time;
		EcwidPlatform::set( self::CATEGORIES_CACHE_VALID_FROM, $time );
	}
}

add_filter('http_api_transports', array('EcwidPlatform', 'http_api_transports'));
