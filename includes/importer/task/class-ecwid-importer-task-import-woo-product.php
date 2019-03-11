<?php

class Ecwid_Importer_Task_Import_Woo_Product extends Ecwid_Importer_Task {

	public static $type = 'import-woo-product';
	
	public static function build( array $data ) {
		return array(
			'woo_id' => $data['id'],
			'type' => self::$type
		);
	}

	public function execute( Ecwid_Importer $importer, array $product ) {

		$importer->append_task(
			Ecwid_Importer_Task_Create_Product::build(
				array(
					'woo_id' => $product['woo_id']
				)
			)
		);
		
		if ( get_post_thumbnail_id( $product['woo_id'] ) ) {
			$importer->append_task(
				Ecwid_Importer_Task_Upload_Product_Image::build(
					array(
						'woo_id' => $product['woo_id']
					)
				)
			);
		}
		
		$p = wc_get_product( $product['woo_id'] );
		
		if ( $p instanceof WC_Product_Variable ) {

			$vars = $p->get_available_variations();

			foreach ( $vars as $var ) {

				$importer->append_task(
					Ecwid_Importer_Task_Create_Product_Variation::build(
						array(
							'woo_id' => $product['woo_id'],
							'var_id' => $var['variation_id']
						)
					)
				);

				if ( $var['image_id'] && $var['image_id'] != $p->get_image_id() ) {
					$importer->append_task(
						Ecwid_Importer_Task_Upload_Product_Variation_Image::build(
							array(
								'product_id' => $product['woo_id'],
								'variation_id' => $var['variation_id']
							)
						)
					);
				}
			}
		}

		if ( $p->get_gallery_image_ids() ) {
			foreach ( $p->get_gallery_image_ids() as $image ) {
				$importer->append_task(
					Ecwid_Importer_Task_Upload_Product_Gallery_Image::build(
						array(
							'product_id' => $product['woo_id'],
							'image_id' => $image
						)
					)
				);
			}
		}
		
		return $this->_result_success();
	}
}