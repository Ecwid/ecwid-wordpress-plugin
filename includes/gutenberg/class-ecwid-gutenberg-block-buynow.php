<?php

require_once dirname( __FILE__ ) . '/class-ecwid-gutenberg-block-base.php';

class Ecwid_Gutenberg_Block_Buynow extends Ecwid_Gutenberg_Block_Product {

	protected $_name = 'buynow';

	public function get_block_name() {
		return Ecwid_Gutenberg_Block_Base::get_block_name();
	}

	public function render_callback( $params ) {
		
		if ( !@$params['id'] ) return '';

		$params = wp_parse_args(
			$params,
			array(
				'show_price_on_button' => true,
				'center_align' => true,
				'show_border' => false,
				'id' => 0,
				'show_picture' => false,
				'show_title' => false,
				'show_price' => true,
				'show_options' => false,
				'show_qty' => false,
				'show_addtobag' => true
			)
		);
		
		$params['show_price'] = $params['show_price_on_button'];

		$result = Ecwid_Gutenberg_Block_Product::render_callback( $params );

		return $result;
	}
}