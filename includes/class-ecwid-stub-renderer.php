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
			$template_file = 'shortcode-stub.tpl.php';

			// detect Buy Now
			$params = $args->get_params();
			if( isset($params['display']) ) {
				
				$display_items = explode( ' ', $params['display'] );
				$display_buynow = array('addtobag', 'price');

				if( !array_diff( $display_items, $display_buynow ) ) {
					$message = __( 'Buy Now', 'ecwid-shopping-cart' );
					$template_file = 'shortcode-stub-buynow.tpl.php';
				}
			}

			require ECWID_TEMPLATES_DIR . '/' . $template_file;

			$contents = ob_get_contents();
			ob_end_clean();

			return $contents;
		} else if ( is_array( $args ) ) {
			ob_start();

			require ECWID_TEMPLATES_DIR . '/shortcode-stub-store.tpl.php';

			$contents = ob_get_contents();
			ob_end_clean();

			return $contents;
		}

		return false;
	}

	public function render_widget( $widget, $args, $instance ) {

		if ( is_array( $args ) ) {
			ob_start();

			if ( $widget instanceof Ecwid_Widget_Product_Browser ) {
				$message = $widget->widget_options['description'];
			} else {
				$message = $widget->name;
			}

			$classname = $widget->widget_options['classname'];

			require ECWID_TEMPLATES_DIR . '/widget-stub.tpl.php';

			$contents = ob_get_contents();
			ob_end_clean();

			return $contents;
		}

		return false;
	}

	abstract protected function _should_apply();

	public function enqueue_scripts() {
		wp_enqueue_style( 'ec-shortcode-stub', ECWID_PLUGIN_URL . 'css/gutenberg/blocks.editor.build.css' );
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