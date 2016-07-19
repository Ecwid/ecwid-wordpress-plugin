<?php
abstract class Ecwid_Shortcode_Base {

	protected $_params;
	protected $_lang;
	protected $_should_render = true;
	protected $_index;

	static protected $shortcodes = array();

	abstract public function get_shortcode_name();
	abstract protected function _process_params( $params );
	abstract public function get_ecwid_widget_function_name();

	public function __construct( $params ) {

		if ($params['lang']) {
			$this->_lang = $params['lang'];
		}
		$this->_process_params( $params );

		if (!isset(self::$shortcodes[$this->get_shortcode_name()])) {
			self::$shortcodes[$this->get_shortcode_name()] = array();
		}
		$this->_index = count(self::$shortcodes[$this->get_shortcode_name()]);
		self::$shortcodes[$this->get_shortcode_name()][] = $this;
	}

	public function wrap_code($code) {

		return "<!-- Ecwid shopping cart plugin v 4.4 --><!-- noptimize -->"
	       . ecwid_get_scriptjs_code($this->_lang)
	       . $code
	       . "<!-- /noptimize --><!-- END Ecwid Shopping Cart v 4.4 -->";
	}

	public function render() {
		if (!$this->_should_render) return '';

		$custom_renderer = apply_filters('ecwid_shortcode_custom_renderer', null, $this);
		if (is_callable($custom_renderer)) {
			return call_user_func( $custom_renderer, $this );
		}

		return self::_default_render();
	}

	public function render_script() {
		$params_string = $this->build_params_string();
		$function = $this->get_ecwid_widget_function_name();
		$id = $this->get_html_id();
		return <<<HTML
<script data-cfasync="false" type="text/javascript"> $function($params_string);</script>
HTML;
	}

	public function render_placeholder() {

		$classname = $this->_get_html_class_name();
		$id = $this->get_html_id();
		return <<<HTML
<div class="ecwid-shopping-cart-$classname" id="$id"></div>
HTML;
	}

	protected function _get_html_class_name() {
		return $this->get_shortcode_name();
	}

	public function get_html_id() {
		return 'ecwid-shopping-cart-' . $this->get_shortcode_name() . '-' . ( $this->_index + 1);
	}

	protected function _default_render() {
		$result = '';

		$result .= $this->render_placeholder();
		$result .= $this->render_script();

		$result = apply_filters('ecwid_' . $this->get_shortcode_name() . '_shortcode_content', $result);

		if ($result) {
			return $this->wrap_code( $result );
		}

		return '';
	}

	public function build_params_string() {


		$pieces = array();
		if ( !empty ( $this->_params ) ) {
			foreach ( $this->_params as $key => $value ) {
				$pieces[] = "$key=$value";
			}
		}

		$id = $this->get_html_id();
		$pieces[] = "id=$id";

		return '"' . implode('","', $pieces) . '"';
	}
}