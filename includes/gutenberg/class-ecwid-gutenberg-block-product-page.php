<?php

class Ecwid_Gutenberg_Block_Product_Page extends Ecwid_Gutenberg_Block_Store {
	protected $_name = 'product-page';

	public function get_block_name() {
		return Ecwid_Gutenberg_Block_Base::get_block_name();
	}
	
	public function render_callback( $params ) {
		if ( ! @$params['default_product_id'] ) {
			return '';
		}
		
		return parent::render_callback( $params );
	}
	
	public function get_attributes_for_editor() {
		$attributes = parent::get_attributes_for_editor();
		
		$overrides = array(
			'show_footer_menu' => false,
			'product_details_show_breadcrumbs' => false
		);
		
		foreach ( $overrides as $name => $editor_default ) {
			$attributes[$name]['profile_default'] = $attributes[$name]['default'];
			$attributes[$name]['default'] = $editor_default;
		}

		return $attributes;
	}
}