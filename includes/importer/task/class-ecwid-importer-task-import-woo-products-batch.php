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
		
		$importer->clear_batch();

		if ( $products ) {

			foreach ( $products as $id ) {

				$api = new Ecwid_Api_V3();
				$batch_item = null;
				
				$task_create_product = new Ecwid_Importer_Task_Create_Product();
				$data = $task_create_product->get_batch_data( $importer, $id );

				$batch_item_id = Ecwid_Importer_Task_Create_Product::$type . '|' . $id;

				if ( $importer->get_setting( Ecwid_Importer::SETTING_UPDATE_BY_SKU ) && isset( $data['sku'] ) ) {

					$filter = array( 'sku' => $data['sku'] );
					$ecwid_products = $api->get_products( $filter );

					if ( $ecwid_products->total > 0 ) {
						$ecwid_id = $ecwid_products->items[0]->id;
						$batch_item = $api->batch_update_product( $data, $ecwid_id, $batch_item_id );
					}
				}

				if ( !$batch_item ) {
					$batch_item = $api->batch_create_product( $data, $batch_item_id );
				}

				$importer->append_batch( $batch_item );
			}

			$batch = $importer->get_batch();
			if( count( $batch ) ) {

				$api = new Ecwid_Api_V3();
				$result = $api->create_batch( $batch );

				if( $result['response']['code'] == '200' ) {

					$data = json_decode( $result['body'] );
					$ticket = $data->ticket;

					$importer->append_child(
						Ecwid_Importer_Task_Batch_Status::build(
							array( 'ticket' => $ticket )
						)
					);

					$importer->clear_batch();
				}
			}
		}

		return $this->_result_success();
	}
}