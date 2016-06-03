<?php

include_once "widgets/class-ecwid-widget-badge.php";
include_once "widgets/class-ecwid-widget-minicart.php";
include_once "widgets/class-ecwid-widget-minicart-miniview.php";
include_once "widgets/class-ecwid-widget-recently-viewed.php";
include_once "widgets/class-ecwid-widget-search.php";
include_once "widgets/class-ecwid-widget-store-link.php";
include_once "widgets/class-ecwid-widget-vcategories.php";
include_once "widgets/class-ecwid-widget-floating-shopping-cart.php";
include_once "widgets/class-ecwid-widget-vertical-categories-list.php";

function ecwid_sidebar_widgets_init() {
	register_widget('Ecwid_Widget_Badge');
	register_widget('Ecwid_Widget_Search');
	register_widget('Ecwid_Widget_VCategories');
	register_widget('Ecwid_Widget_Minicart_Miniview');
	register_widget('Ecwid_Widget_Minicart');
	register_widget('Ecwid_Widget_Store_Link');
	register_widget('Ecwid_Widget_Recently_Viewed');
	register_widget('Ecwid_Widget_Floating_Shopping_Cart');
	register_widget('Ecwid_Widget_Vertical_Categories_List');
}

add_action('widgets_init', 'ecwid_sidebar_widgets_init');