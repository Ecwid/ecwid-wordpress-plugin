<?php
// nsf stands for new storefront I guess
class Ecwid_Widget_NSF_Minicart extends WP_Widget {
	
	protected $__idbase;

	const FIELD_TITLE = 'title';
	const FIELD_LAYOUT = 'layout';
	const FIELD_ICON = 'icon';
	const FIELD_FIXED_SHAPE = 'fixed-shape';
	const FIELD_SHOW_BUY_ANIMATION = 'show-buy-animation';
 	
	function __construct() {
		
		$this->__idbase = 'ecwid_minicart2';
		
		$widget_ops = array('classname' => 'widget_' . $this->__idbase, 'description' => __("Adds a cart widget for customer to see the products they added to the cart.", 'ecwid-shopping-cart') );
		parent::__construct($this->__idbase, __('NEW Shopping Cart', 'ecwid-shopping-cart'), $widget_ops);

	}

	function widget($args, $instance) {
		extract($args);
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
			self::FIELD_SHOW_BUY_ANIMATION => 'FALSE' // Must be the opposite of default checkbox checked value

		) );
		
		$new_instance['title'] = strip_tags( stripslashes( $new_instance['title'] ) );
		
		return $new_instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 
			self::FIELD_TITLE => '',
			self::FIELD_LAYOUT => 'BIG_ICON_DETAILS_SUBTOTAL',
			self::FIELD_ICON => 'BAG',
			self::FIELD_FIXED_SHAPE => 'RECT',
			self::FIELD_SHOW_BUY_ANIMATION => 'TRUE'
			
		) );
		
		require 'nsf-minicart-editor.tpl.php';
	}
	
	protected function _get_layouts() {
		return array(
			'SMALL_ICON' => __( 'Small icon', 'ecwid-shopping-cart' ),
			'SMALL_ICON_COUNTER' => __( 'Small icon counter', 'ecwid-shopping-cart' ),
			'COUNTER_ONLY' => __( 'Counter only', 'ecwid-shopping-cart' ),
			'TITLE_COUNTER' => __( 'Title counter', 'ecwid-shopping-cart' ),
			'MEDIUM_ICON_COUNTER' => __( 'Medium icon counter', 'ecwid-shopping-cart' ),
			'MEDIUM_ICON_TITLE_COUNTER' => __( 'Medium icon title counter', 'ecwid-shopping-cart' ),
			'BIG_ICON_TITLE_SUBTOTAL' => __( 'Big icon title subtotal', 'ecwid-shopping-cart' ),
			'BIG_ICON_DETAILS_SUBTOTAL' => __( 'Big icon details subtotal', 'ecwid-shopping-cart' )	
		);
	}
	
	protected function _get_icons() {
		return array(
			'BAG' => __( 'Bag', 'ecwid-shopping-cart' ),
			'CART' => __( 'Cart', 'ecwid-shopping-cart' ),
			'BASKET' => __( 'Basket', 'ecwid-shopping-cart' )
		);
	}
	
	protected function _get_fixed_shapes() {
		return array(
			'RECT' => __( 'Rectangle', 'ecwid-shopping-cart' ),
			'PILL' => __( 'Pill', 'ecwid-shopping-cart' ),
			'' => __( 'No border', 'ecwid-shopping-cart' )
		);
	}

}
