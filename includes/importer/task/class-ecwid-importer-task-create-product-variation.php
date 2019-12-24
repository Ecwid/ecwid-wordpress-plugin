<?php

class Ecwid_Importer_Task_Create_Product_Variation extends Ecwid_Importer_Task_Product_Base
{
	public static $type = 'create_variation';

	public function execute( Ecwid_Importer $exporter, array $data ) {
		$api = new Ecwid_Api_V3();
	
		$this->_woo_product_id = $data['woo_id'];
		$this->_ecwid_product_id = $exporter->get_ecwid_product_id( $this->get_woo_id() );
		
		$p = wc_get_product( $this->get_woo_id() );
		$attributes = $p->get_attributes();
		$vars = $p->get_available_variations();

		$variation_data = array(
			'productId' => $this->get_ecwid_id(),
			'options' => array()
		);

		foreach ( $vars as $var ) {
			if ( $var['variation_id'] != $data['variation_id'] ) {
				continue;
			}

			foreach ($attributes as $internal_name => $attribute) {
				$tax_attribute = $attribute->get_taxonomy_object();

				$name = '';
				if ( $tax_attribute ) {
					$name = $tax_attribute->attribute_label;
				} else {
					$name = $attribute->get_name();
				}

				$value = $var['attributes']['attribute_' . strtolower($internal_name)];

				if ( $value ) {
					$variation_data['options'][] = array(
						'name' => $name,
						'value' => $value
					);
				}
			}

			$variation_data['price'] = $var['display_price'];
			if ($var['weight']) {
				$variation_data['weight'] = (float)$var['weight'];
			}
			if ($var['max_qty']) {
				$variation_data['quantity'] = $var['max_qty'];
			}

			if ( $var['sku'] != $p->get_sku() ) {
				$variation_data['sku'] = $var['sku'];
			}

			if ( !isset( $variation_data['sku']) ) {
			  unset( $variation_data['quantity'] );
      		}
			
			break;
		}

		$batch_item_id = self::$type . '|' . $this->_woo_product_id . '|' . $data['variation_id'];

		$batch_item = $api->batch_create_product_variation( $variation_data, $this->_ecwid_product_id, $batch_item_id );
		$exporter->append_batch( $batch_item );
		
		return $this->_result_success();
	}

	public static function build( array $data ) {
		return array(
			'type' => self::$type,
			'woo_id' => $data['woo_id'],
			'variation_id' => $data['variation_id']
		);
	}
}