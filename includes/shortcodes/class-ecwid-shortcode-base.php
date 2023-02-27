<?php
abstract class Ecwid_Shortcode_Base {

	protected $_params = array();
	protected $_lang;
	protected $_should_render = true;
	protected $_index;

	protected static $shortcodes = array();

	public static function get_shortcode_name() {
		return 'ec_store';
	}

	abstract protected function _process_params( $shortcode_params = array() );
	abstract public function get_ecwid_widget_function_name();

	public function __construct( $params ) {

		if ( isset( $params['lang'] ) && $params['lang'] ) {
			$this->_lang = $params['lang'];
		}

		$this->_process_params( $params );

		if ( ! isset( self::$shortcodes[ $this->get_shortcode_name() ] ) ) {
			self::$shortcodes[ $this->get_shortcode_name() ] = array();
		}
		$this->_index = count( self::$shortcodes[ $this->get_shortcode_name() ] );

		self::$shortcodes[ $this->get_shortcode_name() ][] = $this;
	}

	public static function get_store_shortcode_names() {
		return array( 'ecwid', 'ec_store' );
	}

	public static function get_current_store_shortcode_name() {
		if ( Ecwid_Config::is_wl() ) {
			return 'ec_store';
		}

		return 'ecwid';
	}

	public static function get_shortcode_object( $name, $params ) {
		$names = array( 'productbrowser', 'minicart', 'search', 'categories', 'product' );

		$expected_prefix = 'ecwid_';
		if ( Ecwid_Config::is_wl() ) {
			$expected_prefix = 'ec_';
		}

		$prefix = substr( $name, 0, strlen( $expected_prefix ) );

		if ( $prefix != $expected_prefix ) {
			return '';
		}

		$base = substr( $name, strlen( $expected_prefix ) );

		if ( in_array( $base, $names ) ) {
			$class = 'Ecwid_Shortcode_' . $base;

			$class     = apply_filters( 'ecwid_get_shortcode_class', $class, $name );
			$shortcode = new $class( $params );

			return $shortcode;
		}

		return null;
	}

	public function wrap_code( $code ) {

		$version = get_option( 'ecwid_plugin_version' );

		$shortcode_content = ecwid_get_scriptjs_code( $this->_lang ) . $code;

		$shortcode_content = apply_filters( 'ecwid_shortcode_content', $shortcode_content );

		$brand = Ecwid_Config::get_brand();

		$shortcode_content = "<!-- $brand shopping cart plugin v $version -->"
		. $shortcode_content
		. "<!-- END $brand Shopping Cart v $version -->";

		return $shortcode_content;
	}

	public function render() {
		if ( ! $this->_should_render ) {
			return '';
		}

		$custom_renderer = apply_filters( 'ecwid_shortcode_custom_renderer', null, $this );
		if ( is_callable( $custom_renderer ) ) {
			return call_user_func( $custom_renderer, $this );
		}

		return self::_default_render();
	}

	public function render_script() {
		$params_string = $this->build_params_string(
			array_merge(
				$this->_params,
				array( 'id' => $this->get_html_id() )
			)
		);

		$function = $this->get_ecwid_widget_function_name();

		if ( ! Ec_Store_Defer_Init::is_enabled() ) {
			return sprintf( '<script data-cfasync="false" data-no-optimize="1" type="text/javascript">%s(%s);</script>', $function, $params_string );
		} else {
			return Ec_Store_Defer_Init::print_js_widget( $function, $this->get_html_id(), $params_string );
		}
	}

	public function render_placeholder() {
		$classname = $this->_get_html_class_name();
		$id        = $this->get_html_id();

		return '<div class="ecwid-shopping-cart-' . esc_attr( $classname ) . '" id="' . esc_attr( $id ) . '"></div>';
	}

	protected function _get_html_class_name() {
		return $this->get_shortcode_name();
	}

	public function get_html_id() {
		return 'ecwid-shopping-cart-' . $this->get_shortcode_name() . '-' . ( $this->_index + 1 );
	}

	protected function _default_render() {
		$result = '';

		$result .= $this->render_placeholder();
		$result .= $this->render_script();

		$result = apply_filters( 'ecwid_' . $this->get_shortcode_name() . '_shortcode_content', $result );

		if ( $result ) {
			return $this->wrap_code( $result );
		}

		return '';
	}

	public function build_params_string( $params = null ) {

		if ( is_null( $params ) ) {
			$params = $this->_params;
		}

		unset( $params['noHTMLCatalog'] );

		$pieces = array();
		if ( ! empty( $params ) ) {
			foreach ( $params as $key => $value ) {
				$pieces[] = sprintf( '%s=%s', esc_attr( $key ), esc_attr( $value ) );
			}
		}

		return '"' . implode( '","', $pieces ) . '"';
	}

	public function get_params() {
		return $this->_params;
	}
}
