<?php

define( 'ECWID_ADMIN_TEMPLATES_DIR', ECWID_PLUGIN_DIR . '/templates/admin' );

class Ecwid_Admin_Main_Page
{
	const PAGE_HASH_DASHBOARD = 'dashboard';
	const PAGE_HASH_PRODUCTS = 'products';
	const PAGE_HASH_ORDERS = 'orders';
	const PAGE_HASH_MOBILE = 'mobile';
	const PAGE_HASH_UPGRADE = 'billing:feature=sso&plan=ecwid_venture';
	
	public function do_page()
	{
		if ( self::is_forced_reconnect() ) {
			ecwid_update_store_id( ecwid_get_demo_store_id() );
		}

		$is_demo = ecwid_is_demo_store();
		$is_api_connection_ok = !Ecwid_Api_V3::connection_fails();
		
		if ( $is_demo && $is_api_connection_ok ) {

			if (
				$this->_is_whitelabel_mode_with_no_registration()
				|| $this->_is_oauth_error()
				|| $this->_is_current_user_email_registered_at_ecwid()
				|| self::is_forced_reconnect()
			) {

				$this->_do_simple_connect_page();
				return;

			} else {

				$this->_do_fancy_connect_page();
				return;
			}
		}
		
		
		if ( $is_demo && !$is_api_connection_ok ) {
		
			$this->_do_legacy_connect_page();
			return;
		}

		if ( !$is_demo ) {
			
			if ( $this->_is_connect_error() ) {

				$this->_do_simple_reconnect_page();
				return;

			} else if ( 
				!$is_api_connection_ok 
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
	
	public static function uses_integrated_admin()
	{
		$page = new Ecwid_Admin_Main_Page();
		
		return 
			!ecwid_is_demo_store()
			&& !$page->_is_connect_error()
			&& !Ecwid_Api_V3::connection_fails()
			&& !Ecwid_Admin::disable_dashboard();
	}
	
	public static function do_integrated_admin_page( $page = self::PAGE_HASH_DASHBOARD )
	{
		$this_obj = new Ecwid_Admin_Main_Page();
		$this_obj->_do_integrated_admin_page( $page );
	}
	
	public function _do_integrated_admin_page( $page = self::PAGE_HASH_DASHBOARD )
	{
		if (isset($_GET['show_timeout']) && $_GET['show_timeout'] == '1') {
			require_once ECWID_PLUGIN_DIR . 'templates/admin-timeout.php';
			die();
		}

		if (Ecwid_Api_V3::get_token() == false) {
			require_once ECWID_PLUGIN_DIR . 'templates/reconnect-sso.php';
			die();
		}

		global $ecwid_oauth;

		if (isset($_GET['ec-page']) && $_GET['ec-page']) {
			$page = $_GET['ec-page'];
		}

		if (isset($_GET['ec-store-page']) && $_GET['ec-store-page']) {
			$page = $_GET['ec-store-page'];
		}

		if ( $page == self::PAGE_HASH_UPGRADE ) {
			update_option('ecwid_api_check_time', time() - ECWID_API_AVAILABILITY_CHECK_TIME + 10 * 60);
		}

		if ( $page == self::PAGE_HASH_DASHBOARD ) {
			$show_reconnect = true;
		}

		$time = time() - get_option('ecwid_time_correction', 0);

		$iframe_src = ecwid_get_iframe_src($time, $page);

		$request = Ecwid_Http::create_get('embedded_admin_iframe', $iframe_src, array(Ecwid_Http::POLICY_RETURN_VERBOSE));

		if (!$request) {
			Ecwid_Message_Manager::show_message('no_oauth');
			return;
		}

		$result = $request->do_request();

		if ( @$result['code'] == 403 && (
				strpos($result['data'], 'Token too old') !== false
				|| strpos($result['data'], 'window.top.location = \'https://my.ecwid.com/api/v3/' . get_ecwid_store_id() . '/sso?') !== false
			)
		) {

			if (isset($result['headers']['date'])) {
				$time = strtotime($result['headers']['date']);

				$iframe_src = ecwid_get_iframe_src($time, $page);

				$request = Ecwid_Http::create_get('embedded_admin_iframe', $iframe_src, array(Ecwid_Http::POLICY_RETURN_VERBOSE));
				if (!$request) {
					Ecwid_Message_Manager::show_message('no_oauth');
					return;
				}
				$result = $request->do_request();

				if ($result['code'] == 200) {
					update_option('ecwid_time_correction', time() - $time);
				}
			}

			$iframe_src = ecwid_get_iframe_src($time, $page);

			$request = Ecwid_Http::create_get('embedded_admin_iframe', $iframe_src, array(Ecwid_Http::POLICY_RETURN_VERBOSE));
			$result = $request->do_request();
		}
		
		if ( $result['code'] == 403 ) {
			Ecwid_Api_V3::save_token('');
		}

		if ( empty( $result['code'] ) && empty( $result['data'] ) || $result['code'] == 500 ) {
			require_once ECWID_PLUGIN_DIR . 'templates/admin-timeout.php';
		} else if ($result['code'] != 200) {
			if (ecwid_test_oauth(true)) {
				require_once ECWID_PLUGIN_DIR . 'templates/reconnect-sso.php';
			} else {
				require_once ECWID_PLUGIN_DIR . 'templates/dashboard.php';
			}
		} else {
			require_once ECWID_PLUGIN_DIR . 'templates/ecwid-admin.php';
		}		
	}

	public static function is_forced_reconnect()
	{
		return isset( $_GET['reconnect'] );
	}

	protected static function _get_upgrade_page_hash()
	{
		return 'billing:feature=sso&plan=ecwid_venture';
	}
	
	protected function _do_simple_dashboard_page()
	{
		require_once ECWID_ADMIN_TEMPLATES_DIR . '/simple-dashboard.php';	
	}
	
	protected function _do_simple_connect_page()
	{
		require_once ECWID_ADMIN_TEMPLATES_DIR . '/simple-connect.tpl.php';
	}

	protected function _do_simple_reconnect_page()
	{
		
		require_once ECWID_ADMIN_TEMPLATES_DIR . '/simple-reconnect.tpl.php';
	}

	protected function _do_fancy_connect_page()
	{
		require_once ECWID_ADMIN_TEMPLATES_DIR . '/landing.tpl.php';
	}
	
	protected function _do_legacy_connect_page()
	{
		wp_enqueue_style('legacy-connect', ECWID_PLUGIN_URL . '/css/legacy-connect.css');

		require_once ECWID_ADMIN_TEMPLATES_DIR . '/legacy-connect.tpl.php';
	}
	
	protected function _is_whitelabel_mode_with_no_registration()
	{
		return Ecwid_Config::is_no_reg_wl();
	}
	
	protected function _is_oauth_error() 
	{
		$connection_error = isset( $_GET['connection_error'] );
		$no_oauth = @$_GET['oauth'] == 'no';
		
		return isset( $connection_error ) && $no_oauth;		
	}
	
	protected function _is_current_user_email_registered_at_ecwid()
	{	
		$api = new Ecwid_Api_V3();
		$current_user = wp_get_current_user();
		
		return $api->does_store_exist( $current_user->user_email );
	}
	
	protected function _is_connect_error()
	{
		return isset( $_GET['connection_error'] );
	}
}

$_ecwid_admin_main_page = new Ecwid_Admin_Main_Page();