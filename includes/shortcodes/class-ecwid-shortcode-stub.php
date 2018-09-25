<?php

require_once ECWID_SHORTCODES_DIR . '/class-ecwid-shortcode-base.php';

class Ecwid_Shortcode_Stub extends Ecwid_Shortcode_Base {

	protected function _process_params( $params = array() ) {
	}

	public static function get_shortcode_name() {
		return 'categories';
	}

	public function get_ecwid_widget_function_name() {
		return '';
	}
	
	public function render_widget() {
		
	}

	public function render_placeholder() {
		$classname = $this->_get_html_class_name();
		$id = $this->get_html_id();
		return <<<HTML
<div class="ecwid-shopping-cart-$classname"><div id="$id"></div></div>
HTML;
	}
}