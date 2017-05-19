<?php

include_once "widgets/class-ecwid-widget-badge.php";
include_once "widgets/class-ecwid-widget-minicart.php";
include_once "widgets/class-ecwid-widget-minicart-miniview.php";
include_once "widgets/class-ecwid-widget-recently-viewed.php";
include_once "widgets/class-ecwid-widget-search.php";
include_once "widgets/class-ecwid-widget-store-link.php";
include_once "widgets/class-ecwid-widget-floating-shopping-cart.php";
include_once "widgets/class-ecwid-widget-vertical-categories-list.php";

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

	register_widget('Ecwid_Widget_Minicart_Miniview');
	register_widget('Ecwid_Widget_Minicart');
	register_widget('Ecwid_Widget_Store_Link');
	register_widget('Ecwid_Widget_Recently_Viewed');
	register_widget('Ecwid_Widget_Floating_Shopping_Cart');
	register_widget('Ecwid_Widget_Vertical_Categories_List');

	if (ecwid_migrations_is_original_plugin_version_older_than('4.3')) {
		register_widget('Ecwid_Widget_VCategories');
	}

}

add_action('widgets_init', 'ecwid_sidebar_widgets_init');