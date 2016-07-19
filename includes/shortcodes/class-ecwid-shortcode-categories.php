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
		return 'xCategories';
	}

	public function render_script() {
		if ( get_option('ecwid_use_new_horizontal_categories') ) {
			$store_id = get_ecwid_store_id();
			$ver = get_option('ecwid_plugin_version');
			$result = <<<HTML
<div id="horizontal-menu" data-storeid="$store_id"></div>
<script src="https://djqizrxa6f10j.cloudfront.net/horizontal-category-widget/v1.2/horizontal-widget.js?ver=$ver"></script>
HTML;
		} else {
			$result = <<<HTML
<script data-cfasync="false" type="text/javascript"> xCategories("style="); </script>
HTML;
		}

		return $result;
	}
}