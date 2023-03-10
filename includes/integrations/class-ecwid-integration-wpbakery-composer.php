<?php

require_once ECWID_PLUGIN_DIR . '/includes/class-ecwid-stub-renderer.php';

class Ec_Integration_WPBakery_Composer {

	public function __construct() {
		add_action( 'vc_before_init', array( $this, 'before_init_actions' ) );
	}

	public function before_init_actions() {
		// .. Code from other Tutorials ..//
		// Require new custom Element
	}
}

class Ec_Integration_WPBakery_Stub_Renderer extends Ecwid_Stub_Renderer {
	protected function _should_apply() {
		return isset( $_GET['vc_editable'] ) && sanitize_text_field( wp_unslash( $_GET['vc_editable'] ) );
	}
}

$__ec_wpbakery_composer                  = new Ec_Integration_WPBakery_Composer();
$__ec_integration_wpbakery_stub_renderer = new Ec_Integration_WPBakery_Stub_Renderer();


// Element Class
class Ec_WPBakeryShortCode_Store extends WPBakeryShortCode {

	// Element Init
	function __construct() {
		add_action( 'init', array( $this, 'vc_infobox_mapping' ) );
		add_shortcode( 'vc_ecwid_store', array( $this, 'vc_ecwid_store' ) );

		add_filter( 'ecwid_page_has_product_browser', array( $this, 'vc_page_has_product_browser' ), 10, 3 );
	}

	public function vc_page_has_product_browser( $result, $content, $post_id ) {

		if ( has_shortcode( $content, 'vc_ecwid_store' ) ) {
			return true;
		}

		return $result;
	}

	// Element Mapping
	public function vc_infobox_mapping() {

		// Stop all if VC is not enabled
		if ( ! defined( 'WPB_VC_VERSION' ) ) {
			return;
		}

		$categories = ecwid_get_categories_for_selector();

		$cats = array(
			__( 'Store root category', 'ecwid-shopping-cart' ) => 0,
		);
		foreach ( $categories as $category ) {
			$cats[ $category->path ] = $category->id;
		}

		// Map the block with vc_map()
		vc_map(
			array(
				'name'        => sprintf( _x( 'Online store', 'vc-tab', 'ecwid-shopping-cart' ) ),
				'base'        => 'vc_ecwid_store',
				'description' => __( 'Displays storefront: product listing and checkout', 'ecwid-shopping-cart' ),
				'category'    => _x( 'Online store', 'vc-tab', 'ecwid-shopping-cart' ),
				'icon'        => ECWID_PLUGIN_URL . 'images/wordpress_20x20.svg',
				'params'      => array(
					array(
						'type'       => 'checkbox',
						'param_name' => 'show_search',
						'group'      => __( 'Settings', 'ecwid-shopping-cart' ),
						'value'      => array( __( 'Show search', 'ecwid-shopping-cart' ) => 'yes' ),
						'std'        => 'yes',
					),
					array(
						'type'       => 'checkbox',
						'param_name' => 'show_categories',
						'value'      => array( __( 'Show categories', 'ecwid-shopping-cart' ) => 'yes' ),
						'group'      => __( 'Settings', 'ecwid-shopping-cart' ),
						'std'        => 'yes',
					),
					array(
						'type'       => 'checkbox',
						'param_name' => 'show_minicart',
						'value'      => array( __( 'Show minicart', 'ecwid-shopping-cart' ) => 'yes' ),
						'group'      => __( 'Settings', 'ecwid-shopping-cart' ),
						'std'        => 'yes',
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'default_category_id',
						'heading'    => __( 'Category shown by default', 'ecwid-shopping-cart' ),
						'value'      => $cats,
						'group'      => __( 'Settings', 'ecwid-shopping-cart' ),
					),
				),
			)
		);
	}


	// Element HTML
	public function vc_ecwid_store( $atts ) {

		$atts = shortcode_atts(
			array(
				'show_search'         => 1,
				'show_categories'     => 1,
				'show_minicart'       => 1,
				'default_category_id' => 0,
			),
			$atts
		);

		$ecwid_attributes = array(
			'widgets' => '',
		);

		$widgets = array(
			'search',
			'categories',
			'minicart',
		);
		foreach ( $widgets as $widget ) {
			if ( $atts[ 'show_' . $widget ] ) {
				$ecwid_attributes['widgets'] .= $widget . ' ';
			}
		}

		$ecwid_attributes['widgets']            .= 'productbrowser';
		$ecwid_attributes['default_category_id'] = $atts['default_category_id'];

		return ecwid_shortcode( $ecwid_attributes );
	}

} // End Element Class

new Ec_WPBakeryShortCode_Store();
