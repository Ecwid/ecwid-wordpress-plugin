<?php

class Ecwid_Importer_Task_Delete_Products extends Ecwid_Importer_Task
{
	public static $type = 'delete_products';

	public function execute( Ecwid_Importer $exporter, array $data ) {
		
		$ids = $data['ids'];
		$exporter->clear_batch();

		foreach ( $ids as $id ) {
			$api = new Ecwid_Api_V3();
			$batch_item = null;

			$batch_item_id = self::$type . '|' . $id;
			$batch_item = $api->batch_delete_product( $id, $batch_item_id );

			$exporter->append_batch( $batch_item );
		}

		$batch = $exporter->get_batch();
		if( count( $batch ) ) {

			$api = new Ecwid_Api_V3();
			$result = $api->create_batch( $batch );

			if( $result['response']['code'] == '200' ) {

				$data = json_decode( $result['body'] );
				$ticket = $data->ticket;

				$exporter->append_child(
					Ecwid_Importer_Task_Batch_Status::build(
						array( 'ticket' => $ticket )
					)
				);

				$exporter->clear_batch();
			}
		}

		return $this->_result_success();
	}

	public static function build( array $ids ) {
		return array(
			'type' => self::$type,
			'ids' => $ids
		);
	}
}