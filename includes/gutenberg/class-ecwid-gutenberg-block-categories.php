<?php

require_once dirname( __FILE__ ) . '/class-ecwid-gutenberg-block-base.php';

class Ecwid_Gutenberg_Block_Categories extends Ecwid_Gutenberg_Block_Base {

	protected $_name = 'categories';
	
	public function render_callback( $params ) {

		$params = wp_parse_args(
			$params,
			array(
				'is_ecwid_shortcode' => true
			)
		);

		$shortcode = new Ecwid_Shortcode_Categories( $params );

		return $shortcode->render();
	}
}