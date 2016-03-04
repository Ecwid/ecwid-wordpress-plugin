<?php
class Ecwid_Widget_VCategories extends WP_Widget {

	function __construct() {
		$widget_ops = array('classname' => 'widget_ecwid_vcategories', 'description' => __('Adds vertical categories block to let the customer navigate your store.', 'ecwid-shopping-cart'));
		parent::__construct('ecwidvcategories', __('Store Categories', 'ecwid-shopping-cart'), $widget_ops);
	}

	function widget($args, $instance) {
		extract($args);
		$title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);

		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		echo '<div>';
		echo '<!-- noptimize -->';

		echo ecwid_get_scriptjs_code();
		echo ecwid_get_product_browser_url_script();
		echo '<script data-cfasync="false" type="text/javascript"> xVCategories("style="); </script>';

		echo '<!-- /noptimize -->';
		echo '</div>';

		echo $after_widget;
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
