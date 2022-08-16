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
		$id        = $this->get_html_id();
		return '<div class="ecwid-shopping-cart-' . esc_attr( $classname ) . '"><div id="' . esc_attr( $id ) . '"></div></div>';
	}
}
