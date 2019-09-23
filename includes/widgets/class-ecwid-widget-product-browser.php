<?php


require_once ECWID_PLUGIN_DIR . '/includes/widgets/class-ecwid-widget-base.php';

class Ecwid_Widget_Product_Browser extends Ecwid_Widget_Base {

	protected $_hide_title = true;

	function __construct() {
		$widget_ops = array('classname' => 'widget_ecwid_product_browser', 'description' => __("Your store will be shown here", 'ecwid-shopping-cart') );
		parent::__construct('ecwidproductbrowser', __('Online store', 'ecwid-shopping-cart'), $widget_ops);

	}

	function _render_widget_content( $args, $instance ) {

		$html = '[ecwid widgets="productbrowser" default_category_id="0"]';

		return $html;
	}

}