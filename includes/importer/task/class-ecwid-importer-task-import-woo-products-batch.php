<?php

class Ecwid_Importer_Task_Import_Woo_Products_Batch extends Ecwid_Importer_Task {

	public static $type = 'import-woo-products-batch';

	public function execute( Ecwid_Importer $importer, array $data ) {

		$products = get_posts( 
			array( 
				'post_type' => self::WC_POST_TYPE_PRODUCT, 
				'posts_per_page' => $data['length'],
				'offset' => $data['start'],
				'fields' => 'ids' 
			) 
		);
		
		if ( $products ) {
			foreach ( $products as $id ) {
				$importer->append_child(
					Ecwid_Importer_Task_Import_Woo_Product::build(
						array( 'id' => $id )
					)
				);
			}
		}

		return $this->_result_success();
	}
}