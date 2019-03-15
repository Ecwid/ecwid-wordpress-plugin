<?php

require_once dirname( __FILE__ ) . '/class-ecwid-gutenberg-block-base.php';

class Ecwid_Gutenberg_Block_Buynow extends Ecwid_Gutenberg_Block_Product {

	protected $_name = 'buynow';

	protected function _get_block_name() {
		return Ecwid_Gutenberg_Block_Base::_get_block_name();
	}

	public function render_callback( $params ) {
		
		if ( !@$params['id'] ) return '';

		$params = wp_parse_args(
			$params,
			array(
				'show_price_on_button' => true,
				'center_align' => true,
				'show_border' => false,
				'display' => 'addtobag',
				'id' => 0,
				'show_picture' => false,
				'show_title' => false,
				'show_price' => false,
				'show_options' => false,
				'show_qty' => false,
				'show_addtobag' => false,
			)
		);

		$result = Ecwid_Gutenberg_Block_Product::render_callback( $params );

		return $result;
	}
}