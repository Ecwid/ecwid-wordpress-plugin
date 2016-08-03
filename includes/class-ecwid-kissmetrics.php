<?php

class Ecwid_Kissmetrics {

	const API_KEY = '12a19b058a28c5db7b722584d59e60e4f080e142';
	const STORAGE_OPTION_NAME = 'ecwid_kissmetrics';
	const EVENT_PREFIX = 'wp-plugin ';

	static $instance;

	public static function init() {
		self::$instance = new Ecwid_Kissmetrics();
	}

	public static function record( $event ) {
		$fire_in_background = array( 'wpPluginDeactivated', 'wpPluginUninstalled' );
		$raw_names = array( 'Signed Up' );

		$name = in_array( $event, $raw_names ) ? $event : self::EVENT_PREFIX . $event;

		if ( in_array( $event, $fire_in_background ) ) {
			self::$instance->_record( $name );
		} else {
			self::$instance->_enqueue_record( $name );
		}
	}

	public static function set( $name, $value ) {
		self::$instance->_enqueue_property( $name, $value );
	}

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ) );
	}

	public function enqueue_script() {
		wp_enqueue_script('ecwid-kissmetrics', 'https://scripts.kissmetrics.com/' . self::API_KEY . '.2.js');
		wp_enqueue_script('ecwid-kissmetrics-events', ECWID_PLUGIN_URL . 'js/kissmetrics.js', array( 'ecwid-kissmetrics' ), get_option('ecwid_plugin_version') );


		$this->_enqueue_property('Storefront URL', ecwid_get_store_page_url());
		$this->_enqueue_property('WP Theme', ecwid_get_theme_name());
		$this->_enqueue_property('WP Chameleon Enabled', get_option('ecwid_use_chameleon') ? 'true' : 'false');

		$woo = 'none';
		$all_plugins = get_plugins();
		if (array_key_exists('woocommerce/woocommerce.php', $all_plugins)) {
			$active_plugins = get_option('active_plugins');
			if (in_array('woocommerce/woocommerce.php', $active_plugins)) {
				$woo = 'active';
			} else {
				$woo = 'inactive';
			}
		}
		$kissmetrics['woo'] = $woo;
		$this->_enqueue_property('Woocommerce installed', $woo);

		$kissmetrics = array(
			'items' => $this->_get_pending(),
			'key' => self::API_KEY
		);

		$this->_flush_pending();

		$store_id = get_ecwid_store_id();
		if ($store_id != ECWID_DEMO_STORE_ID) {
			$kissmetrics['store_id'] = $store_id;
		}

		wp_localize_script('ecwid-kissmetrics-events', 'ecwid_kissmetrics', $kissmetrics);
	}

	protected function _record($event) {

		$params = array(
			'_k' => self::API_KEY,
			'_p' => $_COOKIE['km_ai'],
			'_n' => $event
		);
		$query = http_build_query($params);
		//die(var_dump($_COOKIE));

		$result = wp_remote_get('http://trk.kissmetrics.com/e?' . $query);
	}

	protected function _enqueue_record( $event ) {
		$items = $this->_get_pending();

		array_push( $items, array( 'event' => $event ) );

		update_option ( self::STORAGE_OPTION_NAME, $items );
	}

	protected function _enqueue_property( $name, $value ) {
		$items = $this->_get_pending();

		array_push( $items, array(
				'property' => array(
					$name => $value
				)
			)
		);

		update_option ( self::STORAGE_OPTION_NAME, $items );
	}

	protected function _get_pending() {
		$value = get_option( self::STORAGE_OPTION_NAME );

		if ( !is_array($value) ) {
			$value = array();
		}

		return $value;
	}

	protected function _flush_pending() {
		update_option( self::STORAGE_OPTION_NAME, null );
	}
}

Ecwid_Kissmetrics::init();
