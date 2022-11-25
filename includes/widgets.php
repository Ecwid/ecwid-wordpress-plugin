<?php

require_once 'widgets/class-ecwid-widget-badge.php';
require_once 'widgets/class-ecwid-widget-minicart.php';
require_once 'widgets/class-ecwid-widget-minicart-miniview.php';
require_once 'widgets/class-ecwid-widget-floating-shopping-cart.php';
require_once 'widgets/class-ecwid-widget-recently-viewed.php';
require_once 'widgets/class-ecwid-widget-latest-products.php';
require_once 'widgets/class-ecwid-widget-search.php';
require_once 'widgets/class-ecwid-widget-store-link.php';
require_once 'widgets/class-ecwid-widget-vertical-categories-list.php';
require_once 'widgets/class-ecwid-widget-random-product.php';

require_once 'widgets/class-ecwid-widget-nsf-minicart.php';
require_once 'widgets/class-ecwid-widget-product-browser.php';


if ( ecwid_migrations_is_original_plugin_version_older_than( '4.3' ) ) {
	include_once 'widgets/class-ecwid-widget-vcategories.php';
}

function ecwid_sidebar_widgets_init() {

	$disable_widgets = apply_filters( 'ecwid_disable_widgets', false );

	if ( $disable_widgets ) {
		return;
	}

	if ( ! Ecwid_Config::is_wl() ) {
		register_widget( 'Ecwid_Widget_Badge' );
	}

	register_widget( 'Ecwid_Widget_Search' );

	if ( version_compare( get_bloginfo( 'version' ), '4.0' ) >= 0 ) {
		register_widget( 'Ecwid_Widget_NSF_Minicart' );
	}

	$need_to_disable_defer_init = false;
	$old_minicarts              = array(
		'ecwidminicart_miniview'    => 'Ecwid_Widget_Minicart_Miniview',
		'ecwidminicart'             => 'Ecwid_Widget_Minicart',
		'ecwidfloatingshoppingcart' => 'Ecwid_Widget_Floating_Shopping_Cart',
	);

	foreach ( $old_minicarts as $idbase => $widget_class ) {
		if ( is_active_widget( false, false, $idbase ) || version_compare( get_bloginfo( 'version' ), '4.0' ) < 0 ) {
			register_widget( $widget_class );

			$need_to_disable_defer_init = true;
		}
	}

	if ( $need_to_disable_defer_init ) {
		add_filter( 'ecwid_is_defer_store_init_enabled', '__return_false', 10000 );
	}

	register_widget( 'Ecwid_Widget_Store_Link' );

	if ( Ecwid_Api_V3::is_available() ) {
		register_widget( 'Ecwid_Widget_Recently_Viewed' );
		register_widget( 'Ecwid_Widget_Latest_Products' );

		register_widget( 'Ecwid_Widget_Vertical_Categories_List' );
		register_widget( 'Ecwid_Widget_Random_Product' );
	}

	if ( ecwid_migrations_is_original_plugin_version_older_than( '4.3' ) ) {
		register_widget( 'Ecwid_Widget_VCategories' );
	}

}

add_action( 'widgets_init', 'ecwid_sidebar_widgets_init' );
