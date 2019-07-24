<?php

class Ecwid_Importer_Task_Create_Product extends Ecwid_Importer_Task_Product_Base
{
	public static $type = 'create_product';

	const WC_PRODUCT_TYPE_VARIABLE = 'variable';

	public function execute( Ecwid_Importer $exporter, array $product_data ) {
		
		$this->_woo_product_id = $product_data['woo_id'];

		$product = wc_get_product( $this->_woo_product_id );

		$data = array(
			'name' => $product->get_title(),
			'price' => $product->get_regular_price(),
			'description' => $product->get_description(),
			'isShippingRequired' => !$product->get_virtual(),
			'categoryIds' => array(),
			'showOnFrontpage' => (int) $product->get_featured()
		);
		
		$sku = $product->get_sku();
		if ( $sku ) {
			$data['sku'] = $sku;
		}
		
		if ( $product->get_manage_stock() ) {
			$data['unlimited'] = false;
			$data['quantity'] = $product->get_stock_quantity();
		} else {
			$data['unlimited'] = true;
		}

		if ($product->get_type() == self::WC_PRODUCT_TYPE_VARIABLE ) {
			$data = array_merge( $data, $this->_get_variable_product_data( $this->_woo_product_id ) );
		}

		$data['price'] = floatval( $data['price'] );
		
		$categories = get_the_terms( $this->_woo_product_id, 'product_cat' );

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

		$api = new Ecwid_Api_V3();

		if ( $exporter->get_setting( Ecwid_Importer::SETTING_UPDATE_BY_SKU ) ) {
			
			if( isset( $data['sku'] ) ) {
				$filter = array( 'sku' => $data['sku'] );
			} else {
				$filter = array( 'id' => $this->_woo_product_id );
			}
			$products = $api->get_products( $filter );

			if ( $products->total > 0 ) {
				$ecwid_id = $products->items[0]->id;
				$result = $api->update_product( $data, $ecwid_id );
				$exporter->save_ecwid_product_id( $this->get_woo_id(), $ecwid_id );
			}
		}

		if ( !$result ) {
			$result = $api->create_product( $data );
			
			if ( !$this->_is_api_result_error($result) ) {
				$result_object = json_decode( $result['body'] );
				$ecwid_id = $result_object->id;
			}
		}

		$return = $this->_process_api_result( $result, $data );

		if ( $return['status'] == self::STATUS_SUCCESS ) {
			$result_object = json_decode( $result['body'] );
			
			$this->_ecwid_product_id = $ecwid_id;

			update_post_meta( $this->get_woo_id(), '_ecwid_product_id', $ecwid_id );
			$exporter->save_ecwid_product_id( $this->get_woo_id(), $ecwid_id ? $ecwid_id : $result_object->id );
		}

		return $return;
	}

	public function _get_variable_product_data( )
	{
		$id = $this->get_woo_id();
		$result = array();

		$product = new WC_Product_Variable( $id );
		$result['price'] = $product->get_variation_price();

		$attributes = $product->get_variation_attributes();
		if ( $attributes && is_array( $attributes ) && count( $attributes ) > 0 ) {

			$default_attributes = $product->get_default_attributes();
			$result['options'] = array();
			foreach ( $attributes as $name => $attribute ) {

				$att = $this->_get_attribute_by_name( $product, $name );
				
				if ( !$att ) {
					continue;
				}
				
				$tax_attribute = $att->get_taxonomy_object();

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
	
	protected function _get_attribute_by_name( $product, $name ) {
		
		$atts = $product->get_attributes();
		$found = null;
		foreach ( $atts as $att ) {
			if ( $att['name'] == $name ) {
				$found = $att;
				break;
			}
		}
		
		return $found;
	}

	public static function build( array $data ) {
		return array(
			'type' => self::$type,
			'woo_id' => $data['woo_id']
		);
	}
}