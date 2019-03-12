<?php

class Ecwid_Importer_Task_Upload_Category_Image extends Ecwid_Importer_Task
{
	public static $type = 'upload_category_image';

	public function execute( Ecwid_Importer $exporter, array $category_data ) {
		$api = new Ecwid_Api_V3();

		$woo_id = $category_data['woo_id'];

		// get the thumbnail id using the queried category term_id
		$thumbnail_id = get_term_meta( $woo_id, 'thumbnail_id', true );
		$file = get_attached_file ( $thumbnail_id );

		$category_id = $exporter->get_ecwid_category_id( $woo_id );
		if ( !$category_id ) {
			return array(
				'status' => 'error',
				'data'   => 'skipped',
				'message' => 'parent category was not imported'
			);
		}

		$data = array(
			'categoryId' => $category_id,
			'data' => file_get_contents( $file )
		);

		$result = $api->upload_category_image( $data );

		return self::_process_api_result( $result, $data );
	}

	public static function build( array $data ) {
		return array(
			'type' => self::$type,
			'woo_id' => $data['woo_id']
		);
	}
}
