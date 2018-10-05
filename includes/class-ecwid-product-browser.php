<?php

class Ecwid_Product_Browser
{
	public static function get_design_attributes_for_gutenberg_inspector()
	{
		return array(   
			'product_list_image_size' => array(
				'values' => array(
					'SMALL' => 'S', 
					'MEDIUM' => 'M', 
					'LARGE' => 'L'
				)
			),
			'product_list_image_aspect_ratio' => array(
				'values' => array(
					//'PORTRAIT_0667' => //'PORTRAIT_075', 'SQUARE_1', 'LANDSCAPE_1333', 'LANDSCAPE_15'
				)
			),
		);
	}
}