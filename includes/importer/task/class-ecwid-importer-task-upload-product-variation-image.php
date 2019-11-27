<?php

class Ecwid_Importer_Task_Upload_Product_Variation_Image extends Ecwid_Importer_Task_Product_Base
{
	public static $type = 'upload_product_variation_image';

	public function execute( Ecwid_Importer $exporter, array $data ) {
		$api = new Ecwid_Api_V3();

		$this->_woo_product_id = $data['product_id'];
		$this->_ecwid_product_id = get_post_meta( $this->_woo_product_id, '_ecwid_product_id', true );

		if ( !$this->_ecwid_product_id ) {
			return array(
				'status' => 'error',
				'data'   => 'skipped',
				'message' => 'parent product was not imported. data:' .  var_export($data, true)
			);
		}


		$variation_id = get_post_meta( $data['variation_id'], '_ecwid_variation_id', true );

		$file = get_attached_file ( get_post_thumbnail_id( $data['variation_id'] ) );
		
		if ( !$variation_id ) {
			return array(
				'status' => 'error',
				'data'   => 'skipped',
				'message' => 'parent variation was not imported. data:' . var_export($data, true)
			);
		}

		if ( Ecwid_Importer::is_localhost() ) {

			$data = array(
				'productId' => $this->_ecwid_product_id,
				'variationId' => $variation_id,
				'data' => file_get_contents( $file )
			);

			$result = $api->upload_product_variation_image( $data );

			return self::_process_api_result( $result, $data );
		} else {

			$batch_item_id = self::$type . '|' . $this->_ecwid_product_id;

			$file_url = wp_get_attachment_url( get_post_thumbnail_id( $data['variation_id'] ) );
			$data = array(
				'externalUrl' => $file_url
			);

			$batch_item = $api->batch_upload_product_variation_image( $data, $this->_ecwid_product_id, $data['variation_id'], $batch_item_id );
			$exporter->append_batch( $batch_item );

			return $this->_result_success();

		}
	}

	public static function build( array $data ) {
		return array(
			'type' => self::$type,
			'product_id' => $data['product_id'],
			'variation_id' => $data['variation_id']
		);
	}
}