<?php

class Ecwid_Config {
	const IS_WL = 'whitelabel_is_enabled';
	const BRAND = 'whitelabel_brand';
	const KB_URL = 'whitelabel_kb_url';
	const CONTACT_US_URL = 'whitelabel_contact_us_url';
	const REGISTRATION_URL = 'whitelabel_registration_url';
	const CHANNEL_ID = 'whitelabel_channel_id';
	const OAUTH_APPID = 'whitelabel_oauth_appid';
	const OAUTH_APPSECRET = 'whitelabel_oauth_appsecret';
	const OAUTH_TOKEN_URL = 'whitelabel_oauth_token_url';
	const OAUTH_AUTH_URL = 'whitelabel_oauth_auth_url';

	public static function is_wl() {
		return EcwidPlatform::get( self::IS_WL );
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

	public static function get_registration_url() {
		return EcwidPlatform::get( self::REGISTRATION_URL, 'https://my.ecwid.com/cp/?source=wporg' );
	}

	public static function get_channel_id() {
		return EcwidPlatform::get( self::CHANNEL_ID, 'wporg' );
	}

	public static function get_oauth_token_url() {
		return EcwidPlatform::get( self::OAUTH_TOKEN_URL, Ecwid_Api_V3::OAUTH_URL );
	}

	public static function get_oauth_auth_url() {
		return EcwidPlatform::get( self::OAUTH_AUTH_URL, 'https://my.ecwid.com/api/oauth/authorize' );
	}

	public static function get_oauth_appid() {
		return EcwidPlatform::get( self::OAUTH_APPID, Ecwid_Api_V3::CLIENT_ID );
	}

	public static function get_oauth_appsecret() {
		return EcwidPlatform::get( self::OAUTH_APPSECRET, Ecwid_Api_V3::CLIENT_SECRET );
	}

	public static function load_from_ini() {
		$result = parse_ini_file(ECWID_PLUGIN_DIR . 'config.ini');

		$config = array(
			self::IS_WL => 'wl_mode',
			self::BRAND => 'brand',
			self::CONTACT_US_URL => 'contact_us_url',
			self::KB_URL => 'kb_url',
			self::REGISTRATION_URL => 'registration_url',
			self::CHANNEL_ID => 'channel_id',
			self::OAUTH_APPID => 'oauth_appid',
			self::OAUTH_APPSECRET => 'oauth_appsecret',
			self::OAUTH_TOKEN_URL => 'oauth_token_url',
			self::OAUTH_AUTH_URL => 'oauth_authorize_url'
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
	public static function enqueue_styles() {
		if ( !self::is_wl() ) {
			return;
		}

		wp_enqueue_style( 'ecwid-wl', ECWID_PLUGIN_URL . 'css/wl.css', array( 'ecwid-admin-css' ), get_option( 'ecwid_plugin_version' ) );
	}
}
add_action( 'admin_enqueue_scripts', array( Ecwid_Config, 'enqueue_styles' ) );