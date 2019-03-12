<?php

class Ecwid_Importer_Task_Delete_Products extends Ecwid_Importer_Task
{
	public static $type = 'delete_products';

	public function execute( Ecwid_Importer $exporter, array $data ) {
		$api = new Ecwid_Api_V3();

		$ids = $data['ids'];

		$result = $api->delete_products( $ids );

		return self::_process_api_result( $result, $data );
	}

	public static function build( array $ids ) {
		return array(
			'type' => self::$type,
			'ids' => $ids
		);
	}
}