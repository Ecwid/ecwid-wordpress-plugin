<?php

class Ecwid_Importer_Task_Upload_Product_Image extends Ecwid_Importer_Task_Product_Base
{
	public static $type = 'upload_product_image';

	public function execute( Ecwid_Importer $exporter, array $product_data ) {
		
		$this->_woo_product_id = $product_data['woo_id'];
		$this->_ecwid_product_id = $exporter->get_ecwid_product_id( $product_data['woo_id'] );

		if ( !$this->_ecwid_product_id ) {
			return array(
				'status' => 'error',
				'data'   => 'skipped',
				'message' => 'Parent product was not imported for product #' . $product_data['woo_id']
			);
		}
		
		$api = new Ecwid_Api_V3();

		$file = get_attached_file ( get_post_thumbnail_id( $product_data['woo_id'] ) );

		$data = array(
			'productId' => $this->_ecwid_product_id,
			'data' => file_get_contents( $file )
		);

		$result = $api->upload_product_image( $data );

		return self::_process_api_result( $result, $data );
	}

	public static function build( array $data ) {
		return array(
			'type' => self::$type,
			'woo_id' => $data['woo_id']
		);
	}
}