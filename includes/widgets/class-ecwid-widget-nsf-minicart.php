<?php

// nsf stands for new storefront I guess
class Ecwid_Widget_NSF_Minicart extends WP_Widget {
	
	protected $__idbase;

	const FIELD_TITLE = 'title';
	const FIELD_LAYOUT = 'layout';
	const FIELD_ICON = 'icon';
	const FIELD_FIXED_SHAPE = 'fixed-shape';
 	
	function __construct() {
		
		$this->__idbase = 'ecwidnsfminicart';
		
		$widget_ops = array('classname' => 'widget_' . $this->__idbase, 'description' => __("Adds a cart widget for customer to see the products they added to the cart.", 'ecwid-shopping-cart') );
		parent::__construct($this->__idbase, __('Shopping Cart', 'ecwid-shopping-cart'), $widget_ops);

	}

	function widget($args, $instance) {
		extract($args);

		$instance = wp_parse_args( (array) $instance, array(
			self::FIELD_TITLE => '',
			self::FIELD_LAYOUT => 'BIG_ICON_TITLE_SUBTOTAL',
			self::FIELD_ICON => 'BAG',
			self::FIELD_FIXED_SHAPE => 'RECT'
		) );
		
		$title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);

		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		echo '<div>';

		require 'nsf-minicart.tpl.php';
		
		echo '</div>';

		echo $after_widget;
	}

	function update($new_instance, $old_instance) {
		
		$new_instance = wp_parse_args( (array) $new_instance, array(
			self::FIELD_TITLE => '',
			self::FIELD_LAYOUT => 'BIG_ICON_DETAILS_SUBTOTAL',
			self::FIELD_ICON => 'BAG',
			self::FIELD_FIXED_SHAPE => '',
		) );
		
		$new_instance['title'] = strip_tags( stripslashes( $new_instance['title'] ) );
		
		return $new_instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 
			self::FIELD_TITLE => '',
			self::FIELD_LAYOUT => 'BIG_ICON_TITLE_SUBTOTAL',
			self::FIELD_ICON => 'BAG',
			self::FIELD_FIXED_SHAPE => 'RECT'
		) );
		
		require 'nsf-minicart-editor.tpl.php';
	}
}
