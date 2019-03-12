<?php

class Ecwid_Importer_Task_Create_Product_Variation extends Ecwid_Importer_Task
{
	public static $type = 'create_variation';

	public function execute( Ecwid_Importer $exporter, array $data ) {
		$api = new Ecwid_Api_V3();

		$p = wc_get_product( $data['woo_id'] );
		$attributes = $p->get_attributes();
		$vars = $p->get_available_variations();

		$variation_data = array(
			'productId' => $exporter->get_ecwid_product_id( $data['woo_id'] ),
			'options' => array()
		);

		foreach ( $vars as $var ) {
			if ( $var['variation_id'] != $data['var_id'] ) {
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

				$variation_data['options'][] = array(
					'name' => $name,
					'value' => $value
				);
			}

			$variation_data['price'] = $var['display_price'];
			if ($var['weight']) {
				$variation_data['weight'] = $var['weight'];
			}
			if ($var['max_qty']) {
				$variation_data['quantity'] = $var['max_qty'];
			}

			if ( $var['sku'] != $p->get_sku() ) {
				$variation_data['sku'] = $var['sku'];
			}

			break;
		}

		$result = $api->create_product_variation(
			$variation_data
		);

		$return = self::_process_api_result( $result, $data );

		if ( $return['status'] == self::STATUS_SUCCESS ) {
			$result_object = json_decode( $result['body'] );

			update_post_meta( $data['var_id'], '_ecwid_variation_id', $result_object->id );
		}

		return $return;
	}

	public static function build( array $data ) {
		return array(
			'type' => self::$type,
			'woo_id' => $data['woo_id'],
			'var_id' => $data['var_id']
		);
	}
}