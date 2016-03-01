<?php

class Ecwid_Kissmetrics {

	const API_KEY = '12a19b058a28c5db7b722584d59e60e4f080e142';
	const STORAGE_OPTION_NAME = 'ecwid_kissmetrics';
	const EVENT_PREFIX = 'wp-plugin ';

	static $instance;

	public static function init() {
		self::$instance = new Ecwid_Kissmetrics();
	}

	public static function record($event) {
		$fire_in_background = array('wpPluginDeactivated');

		if (in_array($event, $fire_in_background)) {
			self::$instance->_record(self::EVENT_PREFIX . $event);
		} else {
			self::$instance->_enqueue_record(self::EVENT_PREFIX . $event);
		}
	}

	private function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_script' ) );
	}

	public function enqueue_script() {
		wp_enqueue_script('ecwid-kissmetrics', 'https://scripts.kissmetrics.com/' . self::API_KEY . '.2.js');
		wp_enqueue_script('ecwid-kissmetrics-events', ECWID_PLUGIN_URL . 'js/kissmetrics.js', array( 'ecwid-kissmetrics' ) );

		$kissmetrics = array(
			'events' => $this->_get_pending_events(),
			'key' => self::API_KEY
		);

		$this->_flush_events();

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
		$events = $this->_get_pending_events();

		array_push( $events, array( 'event' => $event ) );

		update_option ( self::STORAGE_OPTION_NAME, $events );
	}

	protected function _get_pending_events() {
		$value = get_option( self::STORAGE_OPTION_NAME );

		if ( !is_array($value) ) {
			$value = array();
		}

		return $value;
	}

	protected function _flush_events() {
		update_option( self::STORAGE_OPTION_NAME, null );
	}
}

Ecwid_Kissmetrics::init();
