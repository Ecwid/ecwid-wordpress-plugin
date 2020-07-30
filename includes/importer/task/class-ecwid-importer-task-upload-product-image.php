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

		if ( Ecwid_Importer::is_localhost() ) {

			$file = get_attached_file ( get_post_thumbnail_id( $product_data['woo_id'] ) );

			$data = array(
				'productId' => $this->_ecwid_product_id,
				'data' => file_get_contents( $file )
			);

			$result = $api->upload_product_image( $data );
			return self::_process_api_result( $result, $data );

		} else {

			$image_id = get_post_thumbnail_id( $this->_woo_product_id );

			$file_url = wp_get_attachment_url( $image_id );
			$file_meta = wp_get_attachment_metadata( $image_id );

			$data = array(
				'url' => $file_url,
				'width' => $file_meta['width'],
				'height' => $file_meta['height']
			);

			$batch_item_id = self::$type . '|' . $this->_ecwid_product_id;

			$batch_item = $api->batch_upload_product_image_async( $data, $this->_ecwid_product_id, $batch_item_id );
			$exporter->append_batch( $batch_item );

			return $this->_result_nothing();
		}
	}

	public static function build( array $data ) {
		return array(
			'type' => self::$type,
			'woo_id' => $data['woo_id']
		);
	}
}