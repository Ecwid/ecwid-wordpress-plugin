<?php

include_once "widgets/class-ecwid-widget-badge.php";
include_once "widgets/class-ecwid-widget-minicart.php";
include_once "widgets/class-ecwid-widget-minicart-miniview.php";
include_once "widgets/class-ecwid-widget-floating-shopping-cart.php";
include_once "widgets/class-ecwid-widget-recently-viewed.php";
include_once "widgets/class-ecwid-widget-latest-products.php";
include_once "widgets/class-ecwid-widget-search.php";
include_once "widgets/class-ecwid-widget-store-link.php";
include_once "widgets/class-ecwid-widget-vertical-categories-list.php";
include_once "widgets/class-ecwid-widget-random-product.php";

include_once "widgets/class-ecwid-widget-nsf-minicart.php";


if (ecwid_migrations_is_original_plugin_version_older_than('4.3')) {
	include_once "widgets/class-ecwid-widget-vcategories.php";
}

function ecwid_sidebar_widgets_init() {

	$disable_widgets = apply_filters('ecwid_disable_widgets', false);

	if ($disable_widgets) {
		return;
	}

	if ( !Ecwid_Config::is_wl() ) {
		register_widget('Ecwid_Widget_Badge');
	}

	register_widget('Ecwid_Widget_Search');

	if ( version_compare( get_bloginfo('version'), '4.0' ) >= 0 ) {
		register_widget('Ecwid_Widget_NSF_Minicart');
	}

	$old_minicarts = array(
		'ecwidminicart_miniview' => 'Ecwid_Widget_Minicart_Miniview', 
		'ecwidminicart' => 'Ecwid_Widget_Minicart', 
		'ecwidfloatingshoppingcart' => 'Ecwid_Widget_Floating_Shopping_Cart' );
	
	foreach ( $old_minicarts as $idbase => $widget_class ) {
		if ( is_active_widget( false, false, $idbase ) || version_compare( get_bloginfo('version'), '4.0' ) < 0 ) {
			register_widget( $widget_class );
		}
	}

	register_widget('Ecwid_Widget_Store_Link');
	
	if ( Ecwid_Api_V3::is_available() ) {
		register_widget('Ecwid_Widget_Recently_Viewed');
		register_widget('Ecwid_Widget_Latest_Products');
	
		register_widget('Ecwid_Widget_Vertical_Categories_List');
		register_widget('Ecwid_Widget_Random_Product');
	}
	
	if (ecwid_migrations_is_original_plugin_version_older_than('4.3')) {
		register_widget('Ecwid_Widget_VCategories');
	}

}

add_action('widgets_init', 'ecwid_sidebar_widgets_init');