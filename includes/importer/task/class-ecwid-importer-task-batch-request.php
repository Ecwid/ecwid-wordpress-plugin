<?php

class Ecwid_Importer_Task_Batch_Request extends Ecwid_Importer_Task_Product_Base
{
	public static $type = 'batch_request';

	const STATUS_QUEUED = 'QUEUED';
	const STATUS_IN_PROGRESS = 'IN_PROGRESS';
	const STATUS_COMPLETED = 'COMPLETED';

	public function execute( Ecwid_Importer $exporter, array $data ) {

		$ticket = $data['ticket'];

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
			// обработать все id привязать товары
		}

		if( isset( $data['timeout'] ) ) {
			sleep( intval($data['timeout']) );
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