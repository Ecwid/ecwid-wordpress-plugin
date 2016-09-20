<?php

require_once ECWID_SHORTCODES_DIR . '/class-ecwid-shortcode-base.php';

class Ecwid_Shortcode_Categories extends Ecwid_Shortcode_Base {

	protected function _process_params( $params ) {
		$this->_should_render = $params['is_ecwid_shortcode'] ? true : get_option('ecwid_show_categories');
	}

	public function get_shortcode_name() {
		return 'categories';
	}

	public function get_ecwid_widget_function_name() {
		if ( get_option('ecwid_use_new_horizontal_categories') ) {
			return 'xCategoriesV2';
		} else {
			return 'xCategories';
		}
	}

	public function render_placeholder() {
		$classname = $this->_get_html_class_name();
		$id = $this->get_html_id();
		return <<<HTML
<div class="ecwid-shopping-cart-$classname"><div id="$id"></div></div>
HTML;
	}
}