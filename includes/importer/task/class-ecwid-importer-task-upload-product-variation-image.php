<?php

class Ecwid_Importer_Task_Upload_Product_Variation_Image extends Ecwid_Importer_Task
{
	public static $type = 'upload_product_variation_image';

	public function execute( Ecwid_Importer $exporter, array $data ) {
		$api = new Ecwid_Api_V3();

		$product_id = get_post_meta( $data['product_id'], '_ecwid_product_id', true );
		$variation_id = get_post_meta( $data['variation_id'], '_ecwid_variation_id', true );

		$file = get_attached_file ( get_post_thumbnail_id( $data['variation_id'] ) );

		if ( !$product_id ) {
			return array(
				'status' => 'error',
				'data'   => 'skipped',
				'message' => 'parent product was not imported. data:' .  var_export($data, true)
			);
		}

		if ( !$variation_id ) {
			return array(
				'status' => 'error',
				'data'   => 'skipped',
				'message' => 'parent variation was not imported. data:' . var_export($data, true)
			);
		}

		$data = array(
			'productId' => $product_id,
			'variationId' => $variation_id,
			'data' => file_get_contents( $file )
		);

		$result = $api->upload_product_variation_image( $data );

		return self::_process_api_result( $result, $data );
	}

	public static function build( array $data ) {
		return array(
			'type' => self::$type,
			'product_id' => $data['product_id'],
			'variation_id' => $data['variation_id']
		);
	}
}