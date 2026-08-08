<?php

define( 'ECWID_ADMIN_TEMPLATES_DIR', ECWID_PLUGIN_DIR . '/templates/admin' );

class Ecwid_Admin_Main_Page {

	const PAGE_HASH_DASHBOARD             = 'dashboard';
	const PAGE_HASH_PRODUCTS              = 'products';
	const PAGE_HASH_ORDERS                = 'orders';
	const PAGE_HASH_MOBILE                = 'mobile';
	const PAGE_HASH_UPGRADE               = 'billing:feature=sso&plan=ecwid_venture';
	const PAGE_HASH_COMPLETE_REGISTRATION = 'complete-registration';

	const NONCE_RECONNECT = 'ec_forced_reconnect';

	public function do_page() {
		if ( self::is_forced_reconnect() && self::is_verified_reconnect_request() ) {
			ecwid_update_store_id( ecwid_get_demo_store_id() );
		}

		$is_demo              = ecwid_is_demo_store();
		$is_api_connection_ok = ! Ecwid_Api_V3::connection_fails();

		if ( $is_demo && $is_api_connection_ok ) {
			if (
			$this->_is_whitelabel_mode_with_no_registration()
			|| $this->_is_oauth_error()
			|| self::is_forced_reconnect()
			) {
				$this->_do_simple_connect_page();
				return;
			} else {
				$this->_do_fancy_connect_page();
				return;
			}
		}

		if ( $is_demo && ! $is_api_connection_ok ) {
			$this->_do_legacy_connect_page();
			return;
		}

		if ( ! $is_demo ) {
			if ( self::is_connection_error() ) {
				$this->_do_simple_reconnect_page();
				return;
			} elseif ( ! $is_api_connection_ok
			|| Ecwid_Admin::disable_dashboard()
			) {
				$this->_do_simple_dashboard_page();
				return;
			} else {
				$this->_do_integrated_admin_page();
				return;
			}
		}
	}

	public static function uses_integrated_admin() {
		$page = new Ecwid_Admin_Main_Page();

		return ! ecwid_is_demo_store()
			&& ! self::is_connection_error()
			&& ! Ecwid_Api_V3::connection_fails()
			&& ! Ecwid_Admin::disable_dashboard();
	}

	public static function do_integrated_admin_page( $page = self::PAGE_HASH_DASHBOARD ) {
		$this_obj = new Ecwid_Admin_Main_Page();
		$this_obj->_do_integrated_admin_page( $page );
	}

	public function _do_integrated_admin_page( $page = self::PAGE_HASH_DASHBOARD ) {
		global $ecwid_oauth;

		if ( isset( $_GET['show_timeout'] ) && $_GET['show_timeout'] == '1' ) {
			require_once ECWID_PLUGIN_DIR . 'templates/admin-timeout.php';
			die();
		}

		if ( Ecwid_Api_V3::get_token() == false ) {
			if ( ! $ecwid_oauth->has_scope( 'allow_sso' ) ) {
				require_once ECWID_PLUGIN_DIR . 'templates/reconnect-sso.php';
			} else {
				require_once ECWID_PLUGIN_DIR . 'templates/admin/simple-dashboard.php';
			}

			die();
		}

		if ( isset( $_GET['ec-page'] ) ) {
			$page = sanitize_text_field( wp_unslash( $_GET['ec-page'] ) );
		}

		if ( isset( $_GET['ec-store-page'] ) ) {
			$page = sanitize_text_field( wp_unslash( $_GET['ec-store-page'] ) );
		}

		$show_reconnect = false;
		if ( $page == self::PAGE_HASH_DASHBOARD || $page == self::PAGE_HASH_COMPLETE_REGISTRATION ) {
			$show_reconnect = true;
		}

		$time = time() - get_option( 'ecwid_time_correction', 0 );

		$iframe_src = ecwid_get_iframe_src( $time, $page );

		if ( ! $iframe_src ) {
			$this->_do_simple_connect_page();
			return;
		}

		$request = Ecwid_Http::create_get( 'embedded_admin_iframe', $iframe_src, array( Ecwid_Http::POLICY_RETURN_VERBOSE ) );

		if ( ! $request ) {
			Ecwid_Message_Manager::show_message( 'no_oauth' );
			return;
		}

		$result = $request->do_request( array( 'timeout' => 20 ) );

		if ( @$result['code'] == 403 && (
				strpos( $result['data'], 'Token too old' ) !== false
				|| strpos( $result['data'], 'window.top.location = \'https://my.ecwid.com/api/v3/' . get_ecwid_store_id() . '/sso?' ) !== false
				|| strpos( $result['data'], 'window.top.location = \'https://app.ecwid.com/api/v3/' . get_ecwid_store_id() . '/sso?' ) !== false
			)
		) {
			if ( isset( $result['headers']['date'] ) ) {
				$time = strtotime( $result['headers']['date'] );

				$iframe_src = ecwid_get_iframe_src( $time, $page );

				$request = Ecwid_Http::create_get( 'embedded_admin_iframe', $iframe_src, array( Ecwid_Http::POLICY_RETURN_VERBOSE ) );
				if ( ! $request ) {
					Ecwid_Message_Manager::show_message( 'no_oauth' );
					return;
				}
				$result = $request->do_request( array( 'timeout' => 20 ) );

				if ( $result['code'] == 200 ) {
					update_option( 'ecwid_time_correction', time() - $time );
				}
			}

			$iframe_src = ecwid_get_iframe_src( $time, $page );

			$request = Ecwid_Http::create_get( 'embedded_admin_iframe', $iframe_src, array( Ecwid_Http::POLICY_RETURN_VERBOSE ) );
			$result  = $request->do_request( array( 'timeout' => 20 ) );
		}//end if

		$need_to_force_show_dashboard = false;

		if ( ! empty( $result ) && $result['code'] == 403 ) {
			if ( get_option( EcwidPlatform::OPTION_ECWID_CHECK_API_RETRY_AFTER, 0 ) == 0 ) {
				Ecwid_Api_V3::set_api_status( Ecwid_Api_V3::API_STATUS_ERROR_TOKEN );
				Ecwid_Api_V3::save_token( '' );
			} else {
				update_option( EcwidPlatform::OPTION_ECWID_CHECK_API_RETRY_AFTER, time() + 5 * MINUTE_IN_SECONDS );
				$need_to_force_show_dashboard = true;
			}
		}

		if ( empty( $result['code'] ) && empty( $result['data'] ) || $result['code'] == 500 ) {
			require_once ECWID_PLUGIN_DIR . 'templates/admin-timeout.php';
		} elseif ( $result['code'] != 200 && ! $need_to_force_show_dashboard ) {
			if ( ecwid_test_oauth( true ) ) {
				require_once ECWID_PLUGIN_DIR . 'templates/reconnect-sso.php';
			} else {
				require_once ECWID_PLUGIN_DIR . 'templates/admin/simple-dashboard.php';
			}
		} else {
			require_once ECWID_PLUGIN_DIR . 'templates/ecwid-admin.php';
		}
	}

	public static function is_forced_reconnect() {
		return isset( $_GET['reconnect'] );
	}

	/**
	 * Tells whether the current forced reconnect request is allowed to reset
	 * the store to the demo one. Guards the destructive part of the flow
	 * against CSRF: displaying the connect page stays nonce-free, resetting
	 * the store id does not.
	 */
	public static function is_verified_reconnect_request() {
		if ( ! current_user_can( Ecwid_Admin::get_capability() ) ) {
			return false;
		}

		if ( ! isset( $_GET['_wpnonce'] ) ) {
			return false;
		}

		return (bool) wp_verify_nonce(
			sanitize_text_field( wp_unslash( $_GET['_wpnonce'] ) ),
			self::NONCE_RECONNECT
		);
	}

	/**
	 * Builds a nonce-signed url that forces the reconnect flow.
	 *
	 * Returns a raw (unescaped) url, so it is safe to pass to wp_safe_redirect()
	 * and to javascript. Escape it with esc_url() when printing into markup.
	 *
	 * @param string $extra_args optional query string appended to the url, e.g. '&connection_error'.
	 */
	public static function get_forced_reconnect_url( $extra_args = '' ) {
		return Ecwid_Admin::get_dashboard_url()
			. '&reconnect' . $extra_args
			. '&_wpnonce=' . wp_create_nonce( self::NONCE_RECONNECT );
	}

	protected static function _get_upgrade_page_hash() {
		return 'billing:feature=sso&plan=ecwid_venture';
	}

	protected function _do_welcome_page( $state ) {
		global $ecwid_oauth;

		if ( isset( $_GET['oauth'] ) && $_GET['oauth'] == 'no' ) {
			$state = 'no_oauth';
		}

		$connection_error = self::is_connection_error();
		$connect_url      = 'admin-post.php?action=ec_connect';

		require_once ECWID_ADMIN_TEMPLATES_DIR . '/welcome-page.php';
	}

	protected function _is_registration_blocked_locale() {
		$locale = ecwid_get_current_user_locale();
		if ( strpos( $locale, '_RU' ) != false ) {
			return true;
		}
		return false;
	}

	public function get_welcome_page_note( $text, $additional_classes = '' ) {
		return sprintf( '<div class="ec-note %s">%s</div>', $additional_classes, $text );
	}

	protected function _do_simple_connect_page() {
		$this->_do_welcome_page( 'connect' );
	}

	protected function _do_simple_reconnect_page() {
		$this->_do_welcome_page( 'connect' );
	}

	protected function _do_fancy_connect_page() {
		$this->_do_welcome_page( 'create' );
	}

	protected function _do_simple_dashboard_page() {
		require_once ECWID_ADMIN_TEMPLATES_DIR . '/simple-dashboard.php';
	}

	protected function _do_legacy_connect_page() {
		wp_enqueue_style( 'legacy-connect', ECWID_PLUGIN_URL . '/css/legacy-connect.css' );

		require_once ECWID_ADMIN_TEMPLATES_DIR . '/legacy-connect.tpl.php';
	}

	protected function _is_whitelabel_mode_with_no_registration() {
		return Ecwid_Config::is_no_reg_wl();
	}

	protected function _is_oauth_error() {
		$connection_error = isset( $_GET['connection_error'] );
		$no_oauth         = isset( $_GET['oauth'] ) && $_GET['oauth'] == 'no';

		return isset( $connection_error ) && $no_oauth;
	}

	protected function _is_current_user_email_registered_at_ecwid() {
		$api          = new Ecwid_Api_V3();
		$current_user = wp_get_current_user();

		return $api->does_store_exist( $current_user->user_email );
	}

	public static function is_connection_error() {
		return isset( $_GET['connection_error'] );
	}
}

$_ecwid_admin_main_page = new Ecwid_Admin_Main_Page();
