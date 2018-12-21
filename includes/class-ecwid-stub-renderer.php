<?php

abstract class Ecwid_Stub_Renderer {
	public function __construct()
	{
		if ( $this->_should_apply() ) {
			add_filter( 'ecwid_shortcode_custom_renderer', array( $this, 'get_custom_renderer' ), 10, 2 );
			add_filter( 'ecwid_get_custom_widget_renderer', array( $this, 'get_custom_widget_renderer' ), 10, 3 );
			add_filter( 'ecwid_inline_js_config', array( $this, 'filter_inline_js_config' ), 10000 );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}
	}

	public function get_custom_renderer() {
		return array( $this, 'render_shortcode' );
	}

	public function get_custom_widget_renderer() {
		return array( $this, 'render_widget' );
	}

	public function render_shortcode( $args ) {

		if ( $args instanceof Ecwid_Shortcode_Product ) {
			ob_start();

			$message = __( 'Product', 'ecwid-shopping-cart' );

			require ECWID_TEMPLATES_DIR . '/shortcode-stub.tpl.php';

			$contents = ob_get_contents();
			ob_end_clean();

			return $contents;
		} else if ( is_array( $args ) ) {
			ob_start();

			$message = __( 'Your store will be shown here', 'ecwid-shopping-cart' );

			require ECWID_TEMPLATES_DIR . '/shortcode-stub.tpl.php';

			$contents = ob_get_contents();
			ob_end_clean();

			return $contents;
		}

		return false;
	}

	public function render_widget( $widget, $args, $instance ) {

		if ( is_array( $args ) ) {
			ob_start();

			$message = $widget->name;
			require ECWID_TEMPLATES_DIR . '/widget-stub.tpl.php';

			$contents = ob_get_contents();
			ob_end_clean();

			return $contents;
		}

		return false;
	}

	abstract protected function _should_apply();

	public function enqueue_scripts() {
		EcwidPlatform::enqueue_style( 'shortcode-stub' );
		EcwidPlatform::enqueue_style( 'widget-stub' );
	}

	public function filter_inline_js_config( $js ) {
		if ( $this->_should_apply() ) {
			return "";
		}

		return $js;
	}
}