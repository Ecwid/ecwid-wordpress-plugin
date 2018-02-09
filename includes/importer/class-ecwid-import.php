<?php

if ( !defined( 'ECWID_IMPORTER_TEMPLATES_DIR' ) ) {
	define ( 'ECWID_IMPORTER_TEMPLATES_DIR', ECWID_TEMPLATES_DIR . '/importer' );
}

require_once __DIR__ . '/class-ecwid-import-page.php';
require_once __DIR__ . '/class-ecwid-importer.php';

class Ecwid_Import
{
	const PAGE_SLUG = 'ec-store-import';
	
	protected $_view = null; 
	
	public function __construct()
	{
		$this->_view = new Ecwid_Import_Page();
		$this->_view->init_actions();
	}
	
	public static function gather_import_data()
	{
		$result = array();
		
		$api = new Ecwid_Api_V3();
		
		$ecwid_products = $api->get_products(array('limit' => 1));
		$result['ecwid_total_products'] = $ecwid_products->total;

		$ecwid_categories = $api->get_categories(array('limit' => 1));
		$result['ecwid_total_categories'] = $ecwid_categories->total;

		$count = wp_count_posts( 'product' );

		$result['woo_total_products'] = $count->publish;
		
		$args = array(
			'taxonomy' => 'product_cat',
			'count' => true,
			'hierarchical' => true,
			'get' => 'all'
		);
		$all_categories = get_categories( $args );
		$result['woo_total_categories'] = count($all_categories);
		
		return $result;
	}
}