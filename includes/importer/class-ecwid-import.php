<?php

if ( !defined( 'ECWID_IMPORTER_TEMPLATES_DIR' ) ) {
	define ( 'ECWID_IMPORTER_TEMPLATES_DIR', ECWID_TEMPLATES_DIR . '/importer' );
}

require_once __DIR__ . '/class-ecwid-importer.php';
require_once __DIR__ . '/class-ecwid-import-page.php';

class Ecwid_Import
{
	const PAGE_SLUG = 'ec-store-import';
	const IMPORTER_IDENTIFIER = 'ec-store-import';
	
	protected $_view = null;
	
	public function __construct()
	{
		if ( !Ecwid_Api_V3::is_available() || ecwid_is_demo_store() ) {
			return;
		}
		
		$this->_view = new Ecwid_Import_Page();
		$this->_view->init_actions();
		
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}
	
	public function admin_init() 
	{
		if ( 1 || !Ecwid_Config::is_wl() ) {
			if ( !function_exists( 'register_importer' ) ) {
				require_once ABSPATH . 'wp-admin/includes/import.php';
			}
	
			register_importer(
				self::IMPORTER_IDENTIFIER,
				sprintf( __( '%s products and categories', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ),
				sprintf( __( 'Bulk import products and categories to your %s store', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ),
				array( $this->_view, 'do_page' )
			);
		}
	}
	
	
	public static function gather_import_data()
	{
		$result = array();

		$api = new Ecwid_Api_V3();

		$ecwid_products = $api->get_products( array( 'limit' => '1' ) );
		$result['ecwid_total_products'] = $ecwid_products->total;
		
		$ecwid_categories = $api->get_categories(array('limit' => 1));
		$result['ecwid_total_categories'] = $ecwid_categories->total;

		$result['allow_delete_demo'] = count( Ecwid_Importer::get_ecwid_demo_products() > 0 );
		
		$count = wp_count_posts( 'product' );

		$result['woo_total_products'] = $count->publish;
		
		$args = array(
			'taxonomy' => 'product_cat',
			'count' => true,
			'hierarchical' => true,
			'get' => 'all'
		);
		$all_categories = get_categories( $args );
		$result['woo_total_categories'] = count( $all_categories );
		
		return $result;
	}
}