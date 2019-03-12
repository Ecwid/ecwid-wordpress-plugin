<?php

abstract class Ecwid_Importer_Task
{
	
	const WC_POST_TYPE_PRODUCT = 'product';
	
	const STATUS_SUCCESS = 'success';
	const STATUS_ERROR = 'error';
 	
	public static $type;
	
	public static function build( array $data ) {
		return array_merge(
			array(
				'type' => static::$type
			),
			$data
		);
	}
	
	abstract public function execute( Ecwid_Importer $importer, array $data );
	
	public static function load_task( $task_type ) {
		$tasks = self::_get_tasks();
		
		$task = $tasks[$task_type];
		
		return new $task();
	}
	
	protected static function _get_tasks()
	{
		static $tasks = array();
	
		if ( !empty( $tasks ) ) {
			return $tasks;
		}
		
		$names = array( 
			'Main',
			'Create_Category', 
			'Create_Product', 
			'Upload_Product_Image', 
			'Upload_Category_Image', 
			'Delete_Products', 
			'Create_Product_Variation', 
			'Upload_Product_Variation_Image',
			'Upload_Product_Gallery_Image',
			'Import_Woo_Products',
			'Import_Woo_Products_Batch',
			'Import_Woo_Categories',
			'Import_Woo_Product'
		);
		
		foreach ( $names as $name ) {
			$class_name = 'Ecwid_Importer_Task_' . $name;
			
			$tasks[$class_name::$type] = $class_name;
		}

		return $tasks;
	}
	
	protected function _result_success()
	{
		return array(
			'status' => self::STATUS_SUCCESS
		);
		
	}
	
	protected function _result_nothing()
	{
		return array(
			
		);
	}
	
	protected function _is_api_result_error( $result ) {
		return is_wp_error( $result )
           || !is_array( $result )
           || !isset( $result['body'] )
           || !isset( $result['response'] )
           || !isset( $result['response']['code'] )
           || $result['response']['code'] != '200';
		;
	}
	protected function _process_api_result( $result, $sent_data ) {
		if ( $this->_is_api_result_error( $result )
		) {
			$result = array(
				'type' => static::$type,
				'status' => self::STATUS_ERROR,
				'data' => $result,
				'sent_data' => $sent_data
			);
			
			return $result;
		}
		
		return array(
			'type' => static::$type,
			'status' => self::STATUS_SUCCESS,
			'data' => json_decode( $result['body'] )
		);
	}
}