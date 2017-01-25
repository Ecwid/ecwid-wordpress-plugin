<?php

class Ecwid_WL {
	const IS_ENABLED = 'whitelabel_is_enabled';
	const BRAND = 'whitelabel_brand';
	const KB_URL = 'whitelabel_kb_url';
	const CONTACT_US_URL = 'whitelabel_contact_us_url';

	public static function is_wl() {
		return true;
	}

	public static function get_brand() {
		return EcwidPlatform::get( self::BRAND, 'Ecwid' );
	}

	public static function get_kb_link() {
		return EcwidPlatform::get( self::KB_URL );
	}

	public static function get_contact_us_url() {
		return EcwidPlatform::get( self::CONTACT_US_URL, 'https://support.ecwid.com/hc/en-us/requests/new' );
	}

	public static function load_from_ini() {
		$result = parse_ini_file(ECWID_PLUGIN_DIR . 'config.ini');

		$config = array(
			self::IS_ENABLED => 'wl_mode',
			self::BRAND => 'brand',
			self::CONTACT_US_URL => 'contact_us_url',
			self::KB_URL => 'kb_url'
		);

		$is_enabled = @$result['wl_mode'];

		foreach ( $config as $name => $ini_name ) {

			$value = @$result[$ini_name];
			if ( $is_enabled && $value ) {
				EcwidPlatform::set($name, @$result[$ini_name]);
			} else {
				EcwidPlatform::reset($name);
			}
		}
	}
}