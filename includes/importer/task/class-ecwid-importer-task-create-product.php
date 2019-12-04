<?php

class Ecwid_Importer_Task_Create_Product extends Ecwid_Importer_Task_Product_Base
{
	public static $type = 'create_product';

	const WC_PRODUCT_TYPE_VARIABLE = 'variable';

	public function get_batch_data( Ecwid_Importer $exporter, $woo_product_id ) {
		
		$this->_woo_product_id = $woo_product_id;

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


		$sale_price = $product->get_sale_price();
		if( !empty( $sale_price ) ) {
			$data['compareToPrice'] = $data['price'];
			$data['price'] = floatval( $sale_price );
		}
		
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

		return $data;
	}

	public function execute( Ecwid_Importer $exporter, array $product_data ) {

		return $this->_result_success();
	}

	public function _get_variable_product_data( $id = false )
	{
		if( !$id ) {
			$id = $this->get_woo_id();
		}
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