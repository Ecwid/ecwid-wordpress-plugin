<?php

class Ecwid_Gutenberg_Block_Filters_Page extends Ecwid_Gutenberg_Block_Store {
	protected $_name = 'filters-page';

	public function get_block_name() {
		return Ecwid_Gutenberg_Block_Base::get_block_name();
	}

	public function get_params() {
		$params = parent::get_params();
		
		$api = new Ecwid_Api_V3();
		$settings = $api->get_store_profile();
		
		
		$params['filtersEnabled'] = true;
		if ( isset( $settings->productFiltersSettings ) && isset( $settings->productFiltersSettings->enabledInStorefront ) ) {
			$params['filtersEnabled'] = $settings->productFiltersSettings->enabledInStorefront;
		}
		return $params;
	}
	
	public function get_attributes_for_editor() {
		$attributes = parent::get_attributes_for_editor();

		$overrides = array(
			'show_breadcrumbs' => false
		);

		foreach ( $overrides as $name => $editor_default ) {
			$attributes[$name]['profile_default'] = $attributes[$name]['default'];
			$attributes[$name]['default'] = $editor_default;
		}

		return $attributes;
	}
}