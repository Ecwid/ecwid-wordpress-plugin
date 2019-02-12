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
	const TOKEN = 'config_token';
	const STORE_ID = 'config_store_id';
	const API_DOMAIN = 'config_api_domain';
	const FRONTEND_DOMAIN = 'config_frontend_domain';
	const CPANEL_DOMAIN = 'config_cpanel_domain';
	const DEMO_STORE_ID = 'config_demo_store_id';

	public static function is_wl() {
		return EcwidPlatform::get( self::IS_WL, false );
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
		return EcwidPlatform::get( self::REGISTRATION_URL );
	}
	
	// Whether it is in WL mode with no registration
	public static function is_no_reg_wl() {
		return self::is_wl() && !self::get_registration_url();
	}

	public static function get_channel_id() {
		return EcwidPlatform::get( self::CHANNEL_ID, 'wporg' );
	}

	public static function get_oauth_token_url() {
		return EcwidPlatform::get( self::OAUTH_TOKEN_URL, 'https://' . self::get_cpanel_domain() . '/api/oauth/token' );
	}

	public static function get_oauth_auth_url() {
		return EcwidPlatform::get( self::OAUTH_AUTH_URL, 'https://' . self::get_cpanel_domain() . '/api/oauth/authorize' );
	}

	public static function should_show_reconnect_in_footer() {
		return !self::is_wl() || EcwidPlatform::get( self::OAUTH_AUTH_URL, false );
	}
	
	public static function get_oauth_appid() {
		return EcwidPlatform::get( self::OAUTH_APPID, Ecwid_Api_V3::CLIENT_ID );
	}

	public static function get_oauth_appsecret() {

		return EcwidPlatform::get( self::OAUTH_APPSECRET, Ecwid_Api_V3::CLIENT_SECRET );
	}
	
	public static function get_store_id() {
		return EcwidPlatform::get( self::STORE_ID, null ); 
	}
	
	public static function get_token() {
		return EcwidPlatform::get( self::TOKEN, null );
	}
	
	public static function overrides_token() {
		return (bool)self::get_token();
	}
	
	public static function get_api_domain() {
		return EcwidPlatform::get( self::API_DOMAIN, 'app.ecwid.com' );
	}
	
	public static function get_scriptjs_domain() {
		return EcwidPlatform::get( self::FRONTEND_DOMAIN, 'app.ecwid.com' );
	}

	public static function get_cpanel_domain() {
		return EcwidPlatform::get( self::CPANEL_DOMAIN, 'my.ecwid.com' );
	}

	// ALWAYS use ecwid_get_demo_store_id or ecwid_is_demo_store instead of this function
	public static function get_demo_store_id() {
		return EcwidPlatform::get( self::DEMO_STORE_ID, null );
	}
	
	public static function load_from_ini() {

		$filename = apply_filters('ecwid_config_ini_path', ECWID_PLUGIN_DIR . 'config.ini');

		$result = false;
		if ( file_exists( $filename ) ) {
			$result = @parse_ini_file($filename);
		}
		
		if ( !$result ) {
			return;
		}
		
		self::_apply_config( $result );
	}
	
	public static function enqueue_styles() {
		if ( !self::is_wl() ) {
			return;
		}

		wp_enqueue_style( 'ecwid-wl', ECWID_PLUGIN_URL . 'css/wl.css', array( 'ecwid-admin-css' ), get_option( 'ecwid_plugin_version' ) );
	}

	/**
	 * @return array
	 */
	protected static function _get_wl_config() {
		$wl_config = array(
			self::IS_WL            => 'wl_mode',
			self::BRAND            => 'brand',
			self::CONTACT_US_URL   => 'contact_us_url',
			self::KB_URL           => 'kb_url',
			self::REGISTRATION_URL => 'registration_url',
			self::OAUTH_TOKEN_URL  => 'oauth_token_url',
			self::OAUTH_AUTH_URL   => 'oauth_authorize_url',
		);

		return $wl_config;
	}

	/**
	 * @return array
	 */
	protected static function _get_common_config() {
		$common_config = array(
			self::OAUTH_APPID     => 'oauth_appid',
			self::OAUTH_APPSECRET => 'oauth_appsecret',
			self::TOKEN           => 'oauth_token',
			self::STORE_ID        => 'store_id',
			self::CHANNEL_ID      => 'channel_id',
			self::API_DOMAIN      => 'api_domain',
			self::FRONTEND_DOMAIN => 'scriptjs_domain',
			self::CPANEL_DOMAIN   => 'cp_domain',
			self::DEMO_STORE_ID   => 'demo_store_id'
		);

		return $common_config;
	}

	/**
	 * @param $values
	 */
	protected static function _apply_config( $values ) {
		$wl_config = self::_get_wl_config();

		$common_config = self::_get_common_config();

		$empty_is_allowed = array(
			self::REGISTRATION_URL
		);

		$is_wl_enabled = @$values['wl_mode'];

		foreach ( $wl_config as $name => $ini_name ) {

			$value = @$values[ $ini_name ];
			if ( $is_wl_enabled && ( $value || in_array( $value, $empty_is_allowed ) ) ) {
				EcwidPlatform::set( $name, @$values[ $ini_name ] );
			} else {
				EcwidPlatform::reset( $name );
			}
		}


		if (
			isset( $values[ self::TOKEN ] ) && ! isset( $values[ self::STORE_ID ] )
			||
			! isset( $values[ self::TOKEN ] ) && isset( $values[ self::STORE_ID ] )
		) {
			unset( $values[ self::TOKEN ] );
			unset( $values[ self::STORE_ID ] );
		}

		foreach ( $common_config as $name => $ini_name ) {
			$value = @$values[ $ini_name ];
			if ( $value ) {
				EcwidPlatform::set( $name, $value );
			} else {
				EcwidPlatform::reset( $name );
			}
		}

		ecwid_invalidate_cache( true );
	}
}

add_action( 'admin_enqueue_scripts', array( 'Ecwid_Config', 'enqueue_styles' ) );