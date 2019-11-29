<?php

class Ecwid_Importer_Task_Batch_Status extends Ecwid_Importer_Task_Product_Base
{
	public static $type = 'batch_status';

	const STATUS_QUEUED = 'QUEUED';
	const STATUS_IN_PROGRESS = 'IN_PROGRESS';
	const STATUS_COMPLETED = 'COMPLETED';

	public function execute( Ecwid_Importer $exporter, array $task_data ) {

		$ticket = $task_data['ticket'];

		$api = new Ecwid_Api_V3();

		$result = $api->get_batch_status( $ticket );

		$data = json_decode( $result['data'] );

		if ( $data->status != self::STATUS_COMPLETED ) {
			$exporter->append_task(
				$this->build(
					array(
						'ticket' => $ticket,
						'timeout' => '1'
					)
				)
			);
		}

		if ( $data->status == self::STATUS_COMPLETED ) {

			foreach($data->responses as $response) {
				
				if( $response->status != self::STATUS_COMPLETED ) {
					continue;
				}

				$params = explode( '|', $response->id );

				$type = $params[0];

				if ($type == 'create_product' ) {
					$woo_id = $params[1];
					$ecwid_id = $response->httpBody->id;

					update_post_meta( $woo_id, '_ecwid_product_id', $ecwid_id );
					$exporter->save_ecwid_product_id( $woo_id, $ecwid_id );

					$exporter->append_task( 
						Ecwid_Importer_Task_Import_Woo_Product::build(
							array('id' => $woo_id)
						)
					);
				}

				if ($type == 'create_variation' ) {
					$woo_variation_id = $params[1];
					$ecwid_id = $response->httpBody->id;

					update_post_meta( $woo_variation_id, '_ecwid_variation_id', $ecwid_id );
				}

			}

			// TO-DO добавить обработку ошибок и подсчет статистики
		}

		if( isset( $task_data['timeout'] ) ) {
			sleep( intval($task_data['timeout']) );
		}

		return $this->_result_success();
	}

	public static function build( array $data ) {
		return array(
			'type' => self::$type,
			'ticket' => $data['ticket']
		);
	}
}