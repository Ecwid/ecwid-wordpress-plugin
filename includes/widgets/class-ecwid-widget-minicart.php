<?php

require_once ECWID_PLUGIN_DIR . '/includes/widgets/class-ecwid-widget-base.php';

class Ecwid_Widget_Minicart extends Ecwid_Widget_Base {

	function __construct() {
		$widget_ops = array('classname' => 'widget_ecwid_minicart', 'description' => __("Adds a cart widget for customer to see the products they added to the cart.", 'ecwid-shopping-cart') );
		parent::__construct('ecwidminicart', __('Shopping Cart (deprecated)', 'ecwid-shopping-cart'), $widget_ops);

	}

	function _render_widget_content( $args, $instance ) {

		$html = '';
		echo '<div>';

		$html .= '<!-- noptimize -->';
		$html .= ecwid_get_scriptjs_code();
		$html .= ecwid_get_product_browser_url_script();
		$html .= '<script data-cfasync="false" type="text/javascript"> xMinicart("style="); </script>';

		$html .= '<!-- /noptimize -->';
		$html .= '</div>';

		return $html;
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));

		return $instance;
	}

	function form($instance){
		$instance = wp_parse_args( (array) $instance, array('title'=>'') );

		$title = htmlspecialchars($instance['title']);

		echo '<p><label for="' . $this->get_field_name('title') . '">' . __('Title:') . ' <input style="width:100%;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';
	}

}
