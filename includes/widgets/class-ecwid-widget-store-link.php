<?php

require_once ECWID_PLUGIN_DIR . '/includes/widgets/class-ecwid-widget-base.php';

class Ecwid_Widget_Store_Link extends Ecwid_Widget_Base {

	function __construct() {
		$this->_hide_title = true;
		$widget_ops = array('classname' => 'widget_ecwid_store_link', 'description' => __('Displays a link to the store page in sidebar for customer to quickly access your store from any page on the site.', 'ecwid-shopping-cart'));
		parent::__construct('ecwidstorelink', __('Store Page Link', 'ecwid-shopping-cart'), $widget_ops);
	}

	function _render_widget_content( $args, $instance ) {
		
		$html = '<div>';

		$html .= '<a href="' . Ecwid_Store_Page::get_store_url() . '" data-ecwid-page="/">' . $instance['label'] . '</a>';
		$html .= '</div>';

		return $html;
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['label'] = strip_tags(stripslashes($new_instance['label']));

		return $instance;
	}

	function form($instance){
		$instance = wp_parse_args( (array) $instance, array( 'label' => __('Shop', 'ecwid-shopping-cart') ) );

		$label = htmlspecialchars($instance['label']);

		echo '<p><label for="' . $this->get_field_name('label') . '">' . __('Text') . ': <input style="width:100%;" id="' . $this->get_field_id('label') . '" name="' . $this->get_field_name('label') . '" type="text" value="' . $label . '" /></label></p>';
	}

}