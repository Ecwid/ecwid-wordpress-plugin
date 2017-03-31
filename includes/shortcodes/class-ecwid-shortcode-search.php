<?php

require_once ECWID_SHORTCODES_DIR . '/class-ecwid-shortcode-base.php';

class Ecwid_Shortcode_Search extends Ecwid_Shortcode_Base {

	protected function _process_params( $params = array() ) {
		$this->_should_render = (isset($params['is_ecwid_shortcode']) && $params['is_ecwid_shortcode']) ? true : get_option('ecwid_show_search_box');
	}

	public static function get_shortcode_name() {
		return 'search';
	}

	public function get_ecwid_widget_function_name() {
		if (get_option('ecwid_use_new_search', false)) {
			return 'xSearch';
		} else {
			return 'xSearchPanel';
		}
	}
}