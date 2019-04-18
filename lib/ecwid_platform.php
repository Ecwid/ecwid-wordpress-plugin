<?php

require_once 'ecwid_requests.php';
require_once ECWID_PLUGIN_DIR . 'lib/phpseclib/AES.php';

class EcwidPlatform {

	static protected $http_use_streams = false;

	static protected $crypt = null;

	static protected $ecwid_plugin_data = null;

	const FORCES_CATALOG_CACHE_RESET_VALID_FROM = 'forced_catalog_cache_reset_valid_from';
	const CATEGORIES_CACHE_VALID_FROM = 'categories_cache_valid_from';
	const PRODUCTS_CACHE_VALID_FROM = 'products_cache_valid_from';
	const PROFILE_CACHE_VALID_FROM = 'profile_cache_valid_from';
	
	const OPTION_LOG_CACHE = 'ecwid_log_cache';
	const OPTION_ECWID_PLUGIN_DATA = 'ecwid_plugin_data';
	
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
		
		$handle = self::make_handle( $file );
		
		$filename .= '.js';
		
		if ( defined( 'WP_DEBUG' ) ) {
			$path = ECWID_PLUGIN_DIR . 'js/' . $filename;
			
			$ver = filemtime( $path );
		} else {
			$ver = get_option( 'ecwid_plugin_version' );
		}
		
		wp_enqueue_script( $handle, ECWID_PLUGIN_URL . 'js/' . $filename, $deps, $ver, $in_footer );
	}
	
	static public function make_handle( $file )
	{
		$filename = $file;

		if ( strpos( $file, '.js' ) == strlen( $file ) - 3 ) {
			$filename = substr( $filename, 0, strlen( $file ) - 3 );
		}

		$prefix = 'ecwid-';
		
		if ( strpos( $file, $prefix ) === 0 ) {
			$filename = substr( $filename, strlen( $prefix ) );
		}
		
		$handle = self::slugify( $filename );
		
		$handle = $prefix . $handle;
		
		return $handle;
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
			throw new InvalidArgumentException( 'Can\'t make slug from ' . $string );
		}		
		return $handle;
	}
	
	static protected function _init_crypt()
	{
		$salt = '';
		
		// It turns out sometimes there is no salt is wp-config. And since it is already seeded
		// with the SECURE_AUTH_KEY, and to avoid breaking someones encryption
		// we use 'SECURE_AUTH_SALT' as string
		if ( defined( 'SECURE_AUTH_SALT' ) ) {
			$salt = SECURE_AUTH_SALT;
		} else {
			$salt = 'SECURE_AUTH_SALT';
		}
		
		self::$crypt->setIV( substr( md5( $salt . get_option('ecwid_store_id') ), 0, 16 ) );
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

	static public function cache_log_record( $operation, $params ) {
		
		if ( !get_option( self::OPTION_LOG_CACHE, false ) )  { return; }

		if (!$params) $params = array();
		$backtrace = debug_backtrace(false);
		
		$file = $line = '';
		foreach ( $backtrace as $entry ) {
			if ( strpos( @$entry['file'], 'ecwid_platform.php' ) !== false ) {
				continue;
			}
			
			@$file = $entry['file'];
			@$line = $entry['line'];
		}
		
		$log_entry = array(
			'operation' => $operation,
			'file' => $file,
			'line' => $line,
			'timestamp' => time()
		);
		
		$log_entry = array_merge(
			$log_entry,
			$params
		);

		$cache = get_option( 'ecwid_cache_log' );
		if (!$cache) {
			$cache = array();
		}
		$cache[] = $log_entry;
		
		update_option('ecwid_cache_log', $cache );
	}
	
	static public function get_price_label()
	{
		return __('Price', 'ecwid-shopping-cart');
	}

	static public function cache_get($name, $default = false)
	{
		$result = get_transient('ecwid_' . $name);

		self::cache_log_record( 'get', array( 'name' => $name, 'default' => $default, 'result' => $result ) );
		
		if ($default !== false && $result === false) {
			return $default;
		}

		return $result;
	}

	static public function cache_set($name, $value, $expires_after = 0)
	{
		self::cache_log_record( 'set', array( 'name' => $name, 'value' => $value, 'expires' => $expires_after ) );
		set_transient('ecwid_' . $name, $value, $expires_after);
	}

	static public function cache_reset($name) {
		self::cache_log_record( 'reset', array( 'name' => $name ) );
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
		if ( !self::$ecwid_plugin_data ) {
			self::$ecwid_plugin_data = get_option( self::OPTION_ECWID_PLUGIN_DATA );
		}
		
		if ( is_array( self::$ecwid_plugin_data ) && array_key_exists( $name, self::$ecwid_plugin_data ) ) {
			return self::$ecwid_plugin_data[$name];
		}

		return $default;
	}
	
	static public function set( $name, $value ) {
		
		if ( is_null( self::$ecwid_plugin_data ) ) {
			self::$ecwid_plugin_data = get_option( self::OPTION_ECWID_PLUGIN_DATA );
		}
		
		if ( !is_array( self::$ecwid_plugin_data ) ) {
			self::$ecwid_plugin_data = array();
		}

		self::$ecwid_plugin_data[$name] = $value;

		update_option( self::OPTION_ECWID_PLUGIN_DATA, self::$ecwid_plugin_data );
	}

	static public function reset( $name ) {
		if ( !self::$ecwid_plugin_data ) {
			self::$ecwid_plugin_data = get_option( self::OPTION_ECWID_PLUGIN_DATA );
		}

		$options = get_option( self::OPTION_ECWID_PLUGIN_DATA );

		if ( !is_array( self::$ecwid_plugin_data ) || !array_key_exists( $name, self::$ecwid_plugin_data ) ) {
			return;
		}

		unset( self::$ecwid_plugin_data[$name] );
		
		update_option( self::OPTION_ECWID_PLUGIN_DATA, self::$ecwid_plugin_data );

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

	static public  function store_in_catalog_cache( $url, $data ) {
		self::_store_in_cache($url, 'catalog', $data);
	}

	static protected function _store_in_cache( $url, $type, $data ) {
		$name = self::_build_cache_name( $url, $type );

		$to_store = array(
			'time' => time(),
			'data' => $data
		);

		self::cache_set( $name, $to_store, MONTH_IN_SECONDS );
		
		self::cache_log_record( 'store_in_entity_cache', array( 'name' => $url, 'type' => $type, 'data' => $data ), 'set' );
	}

	static public function get_from_categories_cache( $key )
	{
		$cache_name = self::_build_cache_name( $key, 'categories' );

		$result = self::cache_get( $cache_name );

		self::cache_log_record( 
			'get_from_categories_cache', 
			array( 
				'name' => $key,
				'result' => $result, 
				'valid_from' => EcwidPlatform::get( self::CATEGORIES_CACHE_VALID_FROM )
			) 
		);
		
		if ( $result['time'] > EcwidPlatform::get( self::CATEGORIES_CACHE_VALID_FROM ) ) {
			return $result['data'];
		}

		return false;
	}

	static public function get_from_products_cache( $key )
	{
		$cache_name = self::_build_cache_name( $key, 'products' );

		$result = self::cache_get( $cache_name );

		self::cache_log_record(
			'get_from_products_cache',
			array(
				'name' => $key,
				'result' => $result,
				'valid_from' => EcwidPlatform::get( self::CATEGORIES_CACHE_VALID_FROM )
			)
		);
		
		if ( $result['time'] > EcwidPlatform::get( self::PRODUCTS_CACHE_VALID_FROM ) ) {
			return $result['data'];
		}

		return false;
	}
	
	static public function get_from_catalog_cache( $key )
	{
		$cache_name = self::_build_cache_name( $key, 'catalog' );

		$result = self::cache_get( $cache_name );
		
		$valid_from = max( 
			EcwidPlatform::get( self::CATEGORIES_CACHE_VALID_FROM ), 
			EcwidPlatform::get( self::PRODUCTS_CACHE_VALID_FROM ),
			EcwidPlatform::get( self::PROFILE_CACHE_VALID_FROM ),
			EcwidPlatform::get( self::FORCES_CATALOG_CACHE_RESET_VALID_FROM )
		);

		self::cache_log_record(
			'get_from_catalog_cache',
			array(
				'name' => $key,
				'result' => $result,
				'valid_from' => $valid_from
			)
		);
		
		if ( $result['time'] > $valid_from ) {
			return $result['data'];
		}

		return false;
	}
	
	static public function is_catalog_cache_trusted() {

		$valid_from = max(
			EcwidPlatform::get( self::CATEGORIES_CACHE_VALID_FROM ),
			EcwidPlatform::get( self::PRODUCTS_CACHE_VALID_FROM ),
			EcwidPlatform::get( self::PROFILE_CACHE_VALID_FROM )
		);
		
		self::cache_log_record( 'is_trusted', array( 'result' => time() - $valid_from > 10, 'time' => time(), 'cats' => EcwidPlatform::get( self::CATEGORIES_CACHE_VALID_FROM ), 'prods' => EcwidPlatform::get( self::PRODUCTS_CACHE_VALID_FROM )));
		
		return time() - $valid_from > 10;
	}

	static protected function _build_cache_name( $url, $type ) {
		return $type . '_' . md5($url);
	}

	static protected function _invalidate_cache_from( $name, $time ) {
		$time = is_null( $time ) ? time() : $time;
		
		$old = EcwidPlatform::get( $name );
		if ( $old > $time) return;

		EcwidPlatform::set( $name, $time );
		self::cache_log_record(
			'invalidate_cache_' . $name,
			array(
				'time' => $time
			)
		);
	}
	
	static public function invalidate_products_cache_from( $time = null )
	{
		self::_invalidate_cache_from( self::PRODUCTS_CACHE_VALID_FROM, $time );
	}

	static public function invalidate_categories_cache_from( $time = null )
	{
		self::_invalidate_cache_from( self::CATEGORIES_CACHE_VALID_FROM, $time );
	}

	static public function invalidate_profile_cache_from( $time = null )
	{
		self::_invalidate_cache_from( self::PROFILE_CACHE_VALID_FROM, $time );
	}
	
	static public function force_catalog_cache_reset( $time = null )
	{
		$time = is_null( $time ) ? time() : $time;
		EcwidPlatform::set( self::FORCES_CATALOG_CACHE_RESET_VALID_FROM, $time );
	}
}

add_filter('http_api_transports', array('EcwidPlatform', 'http_api_transports'));
