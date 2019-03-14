<?php

class Ecwid_Importer_Task_Create_Product extends Ecwid_Importer_Task
{
	public static $type = 'create_product';

	const WC_PRODUCT_TYPE_VARIABLE = 'variable';

	public function execute( Ecwid_Importer $exporter, array $product_data ) {

		$api = new Ecwid_Api_V3( );

		$woo_id = $product_data['woo_id'];

		$post = get_post( $woo_id );
		$product = wc_get_product( $woo_id );

		$data = array(
			'name' => $post->post_title,
			'price' => get_post_meta( $woo_id, '_regular_price', true ),
			'description' => $product->get_description(),
			'isShippingRequired' => get_post_meta( $woo_id, '_virtual', true ) != 'yes',
			'categoryIds' => array(),
			'showOnFrontpage' => (int) $product->get_featured()
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

		if ($product->get_type() == self::WC_PRODUCT_TYPE_VARIABLE ) {
			$data = array_merge( $data, $this->_get_variable_product_data( $woo_id ) );
		}

		$data['price'] = floatval( $data['price'] );
		
		$categories = get_the_terms( $woo_id, 'product_cat' );

		if ( $categories ) foreach ( $categories as $category ) {
			$category_id = $exporter->get_ecwid_category_id( $category->term_id );

			if ( $category_id ) {
				$data['categoryIds'][] = $category_id;
			}
		}
		if ( empty( $data['categoryIds'] ) ) {
			unset( $data['categoryIds'] );
		}
		
		$ecwid_product_id = null;
		$result = null;
		$ecwid_id = null;

		if ( $exporter->get_setting( Ecwid_Importer::SETTING_UPDATE_BY_SKU ) ) {
			$products = $api->get_products( array( 'sku' => $data['sku'] ) );

			if ( $products->total > 0 ) {
				$ecwid_id = $products->items[0]->id;
				$result = $api->update_product( $data, $ecwid_id );
				$exporter->save_ecwid_product_id( $woo_id, $ecwid_id );
			}
		}

		if ( !$result ) {
			$result = $api->create_product( $data );
			
			if ( !$this->_is_api_result_error($result) ) {
				$result_object = json_decode( $result['body'] );
				$ecwid_product_id = $result_object->id;
			}
		}

		$return = $this->_process_api_result( $result, $data );

		if ( $return['status'] == self::STATUS_SUCCESS ) {
			$result_object = json_decode( $result['body'] );

			update_post_meta( $woo_id, '_ecwid_product_id', $ecwid_id ? $ecwid_id : $result_object->id );
			$exporter->save_ecwid_product_id( $woo_id, $ecwid_id ? $ecwid_id : $result_object->id );
		}

		return $return;
	}

	public function _get_variable_product_data( $id )
	{
		$result = array();

		$product = new WC_Product_Variable( $id );
		$result['price'] = $product->get_variation_price();

		$attributes = $product->get_variation_attributes();
		if ( $attributes && is_array( $attributes ) && count( $attributes ) > 0 ) {

			$default_attributes = $product->get_default_attributes();
			$result['options'] = array();
			foreach ( $attributes as $name => $attribute ) {

				$atts = $product->get_attributes();
				$tax_attribute = $atts[strtolower($name)]->get_taxonomy_object();

				if ($tax_attribute) {
					$name = $tax_attribute->attribute_label;
				}

				$option = array( 'type' => 'SELECT', 'name' => $name, 'required' => true, 'choices' => array() );
				foreach ( $attribute as $option_name ) {
					$choice = array( 'text' => $option_name, 'priceModifier' => 0, 'priceModifierType' => 'ABSOLUTE' );
					$option['choices'][] = $choice;
				}
				if ( @$default_attributes[$name] ) {
					$ind = array_search( $default_attributes[$name], $attribute );

					if ( $ind !== false ) {
						$option['defaultChoice'] = $ind;
					}
				}

				$result['options'][] = $option;
			}
		}

		return $result;
	}

	public static function build( array $data ) {
		return array(
			'type' => self::$type,
			'woo_id' => $data['woo_id']
		);
	}
}