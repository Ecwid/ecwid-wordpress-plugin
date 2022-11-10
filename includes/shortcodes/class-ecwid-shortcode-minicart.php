<?php

require_once ECWID_SHORTCODES_DIR . '/class-ecwid-shortcode-base.php';

class Ecwid_Shortcode_Minicart extends Ecwid_Shortcode_Base {

	protected function _process_params( $shortcode_attributes = array() ) {

		$params = shortcode_atts(
			array(
				'layout'             => null,
				'is_ecwid_shortcode' => false,
			),
			$shortcode_attributes
		);

		$layout = $params['layout'];
		if ( ! in_array(
			$layout,
			array(
				'',
				'attachToCategories',
				'floating',
				'Mini',
				'MiniAttachToProductBrowser',
			),
			true
		)
		) {
			$layout = 'MiniAttachToProductBrowser';
		}

		$this->_params = array(
			'layout' => $layout,
		);

		if ( $params['is_ecwid_shortcode'] ) {
			// it is a part of the ecwid shortcode, we need to show it anyways
			$ecwid_enable_minicart = $ecwid_show_categories = true;
		} else {
			// it is a ecwid_minicart widget that works based on appearance settings
			$ecwid_enable_minicart = get_option( 'ecwid_enable_minicart' );
			$ecwid_show_categories = get_option( 'ecwid_show_categories' );
		}

		$this->_should_render = ! empty( $ecwid_enable_minicart ) && ! empty( $ecwid_show_categories );
	}

	public static function get_shortcode_name() {
		return 'minicart';
	}

	public function get_ecwid_widget_function_name() {
		return 'xMinicart';
	}

	public function render() {

		$default_render = parent::render();

		if ( ! isset( $this->_params['layout'] ) || $this->_params['layout'] != 'MiniAttachToProductBrowser' ) {
			return $default_render;
		}

		$has_minicart = ( strpos( $default_render, 'xMinicart' ) !== false );
		if ( $has_minicart && Ecwid_Static_Page::is_enabled_static_home_page() ) {
			$default_render .= '<style>#static-ec-store { padding-top: 40px; }</style>';
		}

		return $default_render;
	}

	public function build_params_string( $params = null ) {
		if ( ! is_null( $params ) && array_key_exists( 'id', $params ) && isset( $params['layout'] ) && $params['layout'] == 'MiniAttachToProductBrowser' ) {
			unset( $params['id'] );
		}

		return parent::build_params_string( $params );
	}
}
