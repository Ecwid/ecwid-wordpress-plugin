<?php
class Ecwid_Widget_Store_Link extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_ecwid_store_link', 'description' => __('Displays a link to the store page in sidebar for customer to quickly access your store from any page on the site.', 'ecwid-shopping-cart'));
		parent::__construct('ecwidstorelink', __('Store Page Link', 'ecwid-shopping-cart'), $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		echo $before_widget;

		echo '<div>';

		echo '<a href="' . Ecwid_Store_Page::get_store_url() . '">' . $instance['label'] . '</a>';
		echo '</div>';

		echo $after_widget;
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