<?php

class Ecwid_Importer_Task_Upload_Product_Gallery_Image extends Ecwid_Importer_Task
{
	public static $type = 'upload_product_gallery_image';

	public function execute( Ecwid_Importer $exporter, array $product_data ) {
		$api = new Ecwid_Api_V3();

		$file = get_attached_file( $product_data['image_id'] );

		$product_id = get_post_meta( $product_data['product_id'], '_ecwid_product_id', true );

		if ( !$product_id ) {
			return array(
				'status' => 'error',
				'data'   => 'skipped',
				'message' => 'Parent product was not imported'
			);
		}

		if ( !$file || !file_exists( $file ) || !is_readable( $file ) ) {
			return array(
				'status' => 'error',
				'data' => 'skipped',
				'message' => 'File not found for product#' . $product_data['product_id'] . ' image ' . $product_data['image_id']
			);
		}

		$data = array(
			'productId' => $product_id,
			'data' => file_get_contents( $file )
		);

		$result = $api->upload_product_gallery_image( $data );

		return self::_process_api_result( $result, $data );
	}

	public static function build( array $data ) {
		return array(
			'type' => self::$type,
			'product_id' => $data['product_id'],
			'image_id' => $data['image_id']
		);
	}
}