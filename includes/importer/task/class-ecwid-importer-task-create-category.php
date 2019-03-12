<?php

class Ecwid_Importer_Task_Create_Category extends Ecwid_Importer_Task
{
	public static $type = 'create_category';

	public function execute( Ecwid_Importer $exporter, array $category_data ) {
		$api = new Ecwid_Api_V3();

		$category = get_term_by( 'id', $category_data['woo_id'], 'product_cat' );
		$data = array(
			'name' => html_entity_decode( $category->name ),
			'parentId' => $exporter->get_ecwid_category_id( $category->parent ),
			'description' => $category->description
		);

		$ecwid_category_id = get_term_meta( $category_data['woo_id'], 'ecwid_category_id', true );

		$result = false;

		if ( $ecwid_category_id ) {
			$result = $api->update_category( $data, $ecwid_category_id );

			if ( $this->_is_api_result_error( $result ) ) {
				$ecwid_category_id = null;
			};
		}

		if ( !$ecwid_category_id ) {
			$result = $api->create_category(
				$data
			);

			if ( $result['response']['code'] == 200 ) {
				$result_object = json_decode( $result['body'] );
				$ecwid_category_id = $result_object->id;
			}
		}

		$return = self::_process_api_result( $result, $data );

		if ( $return['status'] == self::STATUS_SUCCESS ) {
			$exporter->save_ecwid_category( $category_data['woo_id'], $ecwid_category_id );
			update_term_meta( $category_data['woo_id'], 'ecwid_category_id', $ecwid_category_id );
		}

		return $return;
	}

	public static function build( array $data ) {
		return array(
			'type' => self::$type,
			'woo_id' => $data['woo_id']
		);
	}
}