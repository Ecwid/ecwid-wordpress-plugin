<?php
abstract class Ecwid_Shortcode_Base {

	protected $shortcodes = array();

	protected static $instance = null;

	abstract protected function _get_name();
	abstract protected function _get_html_class_name();

	public function wrap_code($code, $name, $attrs) {
		return "<!-- Ecwid shopping cart plugin v 4.4 --><!-- noptimize -->"
	       . ecwid_get_scriptjs_code(@$attrs['lang'])
	       . "<div class=\"ecwid-shopping-cart-$name\">$code</div>"
	       . "<!-- /noptimize --><!-- END Ecwid Shopping Cart v 4.4 -->";
	}

	public static function render($params) {
		return static::_get_instance()->_render($params);
	}

	public function get_shortcode_tag() {
		return 'ecwid_' . $this->_get_name();
	}

	protected function _render($params) {
		$custom_renderer = apply_filters('ecwid_shortcode_custom_renderer', null, $this);
		if (is_callable($custom_renderer)) {
			return call_user_func_array( $custom_renderer, $params );
		}

		self::_default_render( $params, $this->_get_tag_class_name() );
	}

	protected function _default_render($params, $name) {
		$result = '';

		$result .= $this->render_placeholder();
		$result .= $this->render_script();

		return $this->wrap_code( $result, $name, $params );
	}

	public function render_script($params) {

		$params_string = $this->_build_params($params);
		return <<<HTML
<script data-cfasync="false" type="text/javascript"> xProductBrowser("$params_string");</script>
HTML;
	}

	protected function _build_params($params = array()) {
		return "";
	}
}