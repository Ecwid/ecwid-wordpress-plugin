<?php

class Ecwid_Import_Page
{
	const PAGE_SLUG = 'ec-store-import';
	const AJAX_ACTION_CHECK_IMPORT = 'ec-store-check-import';
	const AJAX_ACTION_DO_WOO_IMPORT = 'ec-store-do-woo-import';
	
	public function init_actions()
	{
		add_action( 'admin_menu', array( $this, 'build_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_ajax_' . self::AJAX_ACTION_CHECK_IMPORT, array( $this, 'check_import') );
		add_action( 'wp_ajax_' . self::AJAX_ACTION_DO_WOO_IMPORT, array( $this, 'do_woo_import') );
	}
	
	
	public function build_menu()
	{
		add_submenu_page(
			'',
			'Import',
			'Import',
			Ecwid_Admin::get_capability(),
			self::PAGE_SLUG,
			array( $this, 'do_page' ),
			'',
			'2.562347345'
		);
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
		
		$oauth = new Ecwid_OAuth();
		
		$data = array();
		$token_ok = $oauth->has_scope( 'create_catalog' ) && $oauth->has_scope( 'update_catalog' );
		if ( !$token_ok ){
			$data['has_good_token'] = false;
		} else {
			$data['has_good_token'] = true;
			$data = Ecwid_Import::gather_import_data();
		}
		
		echo json_encode( $data );
		
		die();
	}
	
	public function do_woo_import()
	{
		require_once __DIR__ . '/class-ecwid-importer.php';
		$importer = new Ecwid_Importer();

		if ( !$importer->has_begun() ) {
			$importer->initiate();
		}
		
		$result = $importer->proceed();
		
		echo json_encode( $result );
		
		die();
	}
	
	protected function _get_woo_url()
	{
		return 'admin.php?page=' . self::PAGE_SLUG . '#woo';
	}
	
	
	public function do_page()
	{
		require_once __DIR__ . '/class-ecwid-importer.php';
		$importer = new Ecwid_Importer();
		
		require_once ECWID_IMPORTER_TEMPLATES_DIR . '/landing.php';
	}
}