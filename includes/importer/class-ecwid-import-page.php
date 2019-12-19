<?php

require_once __DIR__ . '/class-ecwid-importer.php';

class Ecwid_Import_Page
{
	const PAGE_SLUG = 'ec-store-import';
	const PAGE_SLUG_WOO = 'ec-store-import-woocommerce';
	
	const AJAX_ACTION_CHECK_IMPORT = 'ec-store-check-import';
	const AJAX_ACTION_DO_WOO_IMPORT = 'ec-store-do-woo-import';
	const ACTION_DO_RECONNECT = 'ec-store-do-reconnect';
	
	const PARAM_FROM_IMPORT_ONBOARDING = 'from-woo-import-message';
	
	public function init_actions()
	{
		add_action( 'admin_menu', array( $this, 'build_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'current_screen', array( $this, 'process_woo_onboarding_redirect' ) );
		add_action( 'wp_ajax_' . self::AJAX_ACTION_CHECK_IMPORT, array( $this, 'check_import') );
		add_action( 'wp_ajax_' . self::AJAX_ACTION_DO_WOO_IMPORT, array( $this, 'do_woo_import') );
		add_action( 'current_screen', array( $this, 'do_reconnect') );
	}
	
	public function process_woo_onboarding_redirect() 
	{
		if ( strrpos( strrev( get_current_screen()->base), strrev( self::PAGE_SLUG_WOO) ) !== 0 ) {
			return;
		}

		if ( @$_GET[self::PARAM_FROM_IMPORT_ONBOARDING] ) {
			Ecwid_Message_Manager::disable_message( Ecwid_Message_Manager::MSG_WOO_IMPORT_ONBOARDING );
		}
	}
	
	public function build_menu()
	{
		add_submenu_page(
			'',
			__( 'Import', 'ecwid-shopping-cart' ),
			__( 'Import', 'ecwid-shopping-cart' ),
			Ecwid_Admin::get_capability(),
			self::PAGE_SLUG,
			array( $this, 'do_page' )
		);
		
		if ( $this->_need_to_show_woo() ) { 
			add_submenu_page(
				self::PAGE_SLUG,
				sprintf( __( 'Import your products from WooCommerce to %s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ),
				sprintf( __( 'Import your products from WooCommerce to %s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ),
				Ecwid_Admin::get_capability(),
				self::PAGE_SLUG_WOO,
				array( $this, 'do_woo_page' )
			);
		}
	}
	
	public function enqueue_scripts()
	{
		wp_enqueue_style( 'ecwid-importer', ECWID_PLUGIN_URL . '/css/importer.css' );
		wp_enqueue_script( 'ecwid-importer', ECWID_PLUGIN_URL . '/js/importer.js' );
		wp_localize_script( 'ecwid-importer', 'ecwid_importer', array(
			'check_token_action' => self::AJAX_ACTION_CHECK_IMPORT,
			'do_woo_import_action' => self::AJAX_ACTION_DO_WOO_IMPORT
		) );
	}
	
	public function check_import()
	{
		if ( !current_user_can( Ecwid_Admin::get_capability() ) ) {
			return;
		}
		
		$data = array();
		$token_ok = $this->_is_token_ok();
		if ( !$token_ok ){
			$data['has_good_token'] = false;
		} else {
			$data['has_good_token'] = true;
			$data = Ecwid_Import::gather_import_data();
		}
		
		echo json_encode( $data );
		
		die();
	}
	
	// Returns url for the page that should be displayed on clicking the "Import from woo" button in woo import onboarding message
	public static function get_woo_page_url_from_message() {
		return 'admin.php?page=' . self::PAGE_SLUG_WOO . '&' . self::PARAM_FROM_IMPORT_ONBOARDING . '=1';
	}
	
	protected function _is_token_ok()
	{
		$oauth = new Ecwid_OAuth();
		
		return $oauth->has_scope( 'create_catalog' ) && $oauth->has_scope( 'update_catalog' );
	}
	
	public function do_woo_import()
	{
		$importer = new Ecwid_Importer();
		
		if ( !$importer->has_begun() || isset( $_REQUEST['reset'] ) ) {
			$importer->initiate( @$_REQUEST['settings'] );
		}
		
		$result = $importer->proceed();
		
		echo json_encode( $result );
		
		die();
	}
	
	protected function _get_billing_page_url() {
		return 'admin.php?page=' . Ecwid_Admin::ADMIN_SLUG . '&ec-page=billing';
	}
	
	public function do_reconnect()
	{
		if ( strrpos( strrev( get_current_screen()->base), strrev( self::PAGE_SLUG_WOO) ) !== 0 ) {
			return;
		}
		
		if ( !isset( $_GET['action'] ) || $_GET['action'] != 'reconnect' ) {
			return;
		}
		
		$url = $this->_get_woo_url() . '#start';
		
		$params = array(
			'delete-demo',
			'update-by-sku'
		);
		
		foreach ( $params as $param ) {
			if ( isset( $_GET[$param] ) ) {
				$url .= '&' . $param . '=true';
			}
		}

		wp_safe_redirect(
			'admin.php?page=' .  Ecwid_Admin::ADMIN_SLUG 
			. '&reconnect&return-url=' . urlencode( $url )
			. '&scope=create_catalog+update_catalog&do_reconnect=1'
		);
	}
	
	protected function _get_woo_url()
	{
		return 'admin.php?page=' . self::PAGE_SLUG_WOO;
	}

	protected function _need_to_show_woo()
	{
		return is_plugin_active( 'woocommerce/woocommerce.php' ) && wp_count_posts( 'product' )->publish > 0;
	}
	
	public function do_page()
	{
		require_once ECWID_IMPORTER_TEMPLATES_DIR . '/landing.tpl.php';
	}
	
	public function do_woo_page()
	{
		$import_data = Ecwid_Import::gather_import_data();

		require_once ECWID_IMPORTER_TEMPLATES_DIR . '/woo-main.tpl.php';
	}
	
	protected function _get_products_categories_message( $products, $categories ) {
		if ( ecwid_is_paid_account() ) {
			return sprintf( 
				__( '%s products and %s categories', 'ecwid-shopping-cart' ),
				$products,
				$categories
			);
		} else {
			return sprintf(
				__( '%s products', 'ecwid-shopping-cart' ),
				$products
			);
		}
	}
}