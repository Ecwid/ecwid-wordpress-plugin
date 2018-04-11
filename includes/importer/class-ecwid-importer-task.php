<?php

abstract class Ecwid_Importer_Task
{
	public static $type;
	
	abstract public function execute( Ecwid_Importer $exporter, $data );
	
	public static function load_task( $task_type ) {
		$tasks = self::get_tasks();
		
		$task = $tasks[$task_type];
		
		return new $task();
	}
	
	protected static function get_tasks()
	{
		static $tasks = array();
	
		if ( !empty( $tasks ) ) {
			return $tasks;
		}
		
		$names = array( 'Create_Category', 'Create_Product', 'Upload_Product_Image', 'Upload_Category_Image', 'Delete_Products' );
		
		foreach ( $names as $name ) {
			$class_name = 'Ecwid_Importer_Task_' . $name;
			
			$tasks[$class_name::$type] = $class_name;
		}

		return $tasks;
	}
	
}

class Ecwid_Importer_Task_Create_Product extends Ecwid_Importer_Task
{
	public static $type = 'create_product';

	public function execute( Ecwid_Importer $exporter, $product_data ) {
		$api = new Ecwid_Api_V3( );

		$woo_id = $product_data['woo_id'];
		$product = get_post( $woo_id );
		
		$data = array(
			'name' => $product->post_title,
			'price' => floatval( get_post_meta( $woo_id, '_regular_price', true ) ),
			'description' => $product->post_content,
			'isShippingRequired' => get_post_meta( $woo_id, '_virtual', true ) != 'yes',
			'categoryIds' => array(),
			'showOnFrontpage' => (int) ( get_post_meta( $woo_id, '_featured', true ) == 'yes' )
		);
		
		$meta = get_post_meta( $woo_id, '_sku', true );
		if ( !empty( $meta ) ) {
			$data['sku'] = get_post_meta( $woo_id, '_sku', true );
		}

		if ( get_post_meta( $woo_id, '_manage_stock', true ) == 'yes' ) {
			$data['unlimited'] = false;
			$data['quantity'] = intval( get_post_meta( $woo_id, '_stock', true ) );
		} else {
			$data['unlimited'] = true;
		}
		
		$categories = get_the_terms( $woo_id, 'product_cat' );

		if ( $categories ) foreach ( $categories as $category ) {
			$category_id = $exporter->get_ecwid_category_id( $category->term_id );
			
			if ( $category_id ) {
				$data['categoryIds'][] = $category_id;
			}
		}
		if ( empty( $data['categoryIds'] ) ) {
			unset($data['categoryIds']);
		}
		
		$result = null;
		if ( $exporter->get_setting( Ecwid_Importer::SETTING_UPDATE_BY_SKU ) ) {
			$products = $api->get_products( array( 'sku' => $data['sku'] ) );
			
			if ( $products->total > 0 ) {
				$data['id'] = $products->items[0]->id;
				$result = $api->update_product( $data );
			}
		}
		
		if ( !$result ) {
			$result = $api->create_product( $data );
		}
		
		$return = array(
			'type' => self::$type
		);
		
		if ( $result['response']['code'] == '200' ) {
			$result_object = json_decode( $result['body'] );

			$exporter->save_ecwid_product_id( $woo_id, $result_object->id );
			
			$return['status'] = 'success';
			$return['data'] = $result_object;
		} else {
			$return['status'] = 'error';
			$return['data'] = $result;
			$return['sent_data'] = $data;
		}

		return $return;
	}

	public static function build( $data ) {
		return array(
			'type' => self::$type,
			'woo_id' => $data['woo_id']
		);
	}
}

class Ecwid_Importer_Task_Delete_Products extends Ecwid_Importer_Task
{
	public static $type = 'delete_products';

	public function execute( Ecwid_Importer $exporter, $data ) {
		$api = new Ecwid_Api_V3();

		$ids = $data['ids'];
		
		$result = $api->delete_products( $ids );

		$return = array(
			'type' => self::$type
		);
		
		$return['status'] = 'success';
		$return['data'] = $result;

		return $return;
	}

	public static function build( $ids ) {
		return array(
			'type' => self::$type,
			'ids' => $ids
		);
	}
}

class Ecwid_Importer_Task_Upload_Category_Image extends Ecwid_Importer_Task
{
	public static $type = 'upload_category_image';

	public function execute( Ecwid_Importer $exporter, $category_data ) {
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

		$return = array(
			'type' => self::$type
		);
		if ( $result['response']['code'] == '200' ) {
			$result_object = json_decode( $result['body'] );

			$return['status'] = 'success';
			$return['data'] = $result_object;
		} else {
			$return['status'] = 'error';
			$return['data'] = $result;
		}

		return $return;
	}

	public static function build($data) {
		return array(
			'type' => self::$type,
			'woo_id' => $data['woo_id']
		);
	}
}

class Ecwid_Importer_Task_Upload_Product_Image extends Ecwid_Importer_Task
{
	public static $type = 'upload_product_image';

	public function execute( Ecwid_Importer $exporter, $product_data ) {
		$api = new Ecwid_Api_V3();
		
		$file = get_attached_file ( get_post_thumbnail_id( $product_data['woo_id'] ) );

		$product_id = $exporter->get_ecwid_product_id( $product_data['woo_id'] );
		if ( !$product_id ) {
			return array(
				'status' => 'error',
				'data'   => 'skipped',
				'message' => 'Parent product was not imported'
			);
		}
		
		$data = array(
			'productId' => $product_id,
			'data' => file_get_contents( $file )
		);

		$result = $api->upload_product_image( $data );

		$return = array(
			'type' => self::$type
		);
		if ( $result['response']['code'] == '200' ) {
			$result_object = json_decode( $result['body'] );
			
			$return['status'] = 'success';
			$return['data'] = $result_object;
		} else {
			$return['status'] = 'error';
			$return['data'] = $result;
		}

		return $return;
	}

	public static function build($data) {
		return array(
			'type' => self::$type,
			'woo_id' => $data['woo_id']
		);
	}
}

class Ecwid_Importer_Task_Create_Category extends Ecwid_Importer_Task
{
	public static $type = 'create_category';

	public function execute( Ecwid_Importer $exporter, $category_data ) {
		$api = new Ecwid_Api_V3();
		
		$category = get_term_by( 'id', $category_data['woo_id'], 'product_cat' );
		$data = array(
			'name' => $category->name,
			'parentId' => $exporter->get_ecwid_category_id( $category->parent ),
			'description' => $category->description
		);

		$result = $api->create_category(
			$data	
		);
		
		$return = array(
			'type' => self::$type
		);
		if ( $result['response']['code'] == '200' ) {
			$result_object = json_decode( $result['body'] );
			
			$exporter->save_ecwid_category( $category_data['woo_id'], $result_object->id );
			
			$return['status'] = 'success';
			$return['data'] = $result_object;
		} else {
			$return['status'] = 'error';
			$return['data'] = $result;
		}
		
		return $return;
	}
	
	public static function build($data) {
		return array(
			'type' => self::$type,
			'woo_id' => $data['woo_id']
		);
	}
}