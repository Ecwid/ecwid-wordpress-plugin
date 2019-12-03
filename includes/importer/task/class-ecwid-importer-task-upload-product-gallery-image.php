<?php

class Ecwid_Importer_Task_Upload_Product_Gallery_Image extends Ecwid_Importer_Task_Product_Base
{
	public static $type = 'upload_product_gallery_image';

	public function execute( Ecwid_Importer $exporter, array $product_data ) {
		$api = new Ecwid_Api_V3();
		
		$this->_woo_product_id = $product_data['product_id'];
		$this->_ecwid_product_id = get_post_meta( $this->_woo_product_id, '_ecwid_product_id', true );
		
		if ( !$this->_ecwid_product_id ) {
			return array(
				'status' => 'error',
				'data'   => 'skipped',
				'message' => 'Parent product was not imported'
			);
		}

		$file = get_attached_file( $product_data['image_id'] );

		if ( !$file || !file_exists( $file ) || !is_readable( $file ) ) {
			return array(
				'status' => 'error',
				'data' => 'skipped',
				'message' => 'File not found for product#' . $this->_woo_product_id . ' image ' . $product_data['image_id']
			);
		}


		if ( Ecwid_Importer::is_localhost() ) {

			$data = array(
				'productId' => $this->_ecwid_product_id,
				'data' => file_get_contents( $file )
			);

			$result = $api->upload_product_gallery_image( $data );

			return self::_process_api_result( $result, $data );
		} else {

			$batch_item_id = self::$type . '|' . $this->_ecwid_product_id;

			$file_url = wp_get_attachment_url( $product_data['image_id'] );
			$data = array(
				'externalUrl' => $file_url
			);

			$batch_item = $api->batch_upload_product_gallery_image( $data, $this->_ecwid_product_id, $batch_item_id );
			$exporter->append_batch( $batch_item );

			return $this->_result_success();

		}
	}

	public static function build( array $data ) {
		return array(
			'type' => self::$type,
			'product_id' => $data['product_id'],
			'image_id' => $data['image_id']
		);
	}
}