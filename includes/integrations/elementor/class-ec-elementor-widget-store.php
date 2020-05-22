<?php

class Ec_Elementor_Widget_Store extends \Elementor\Widget_Base {
	
	public function get_name() {
		return 'ec_store';
	}

    public function get_title() {
    	return __( 'Store', 'ecwid-shopping-cart' );
    }

    public function get_icon() {}

    public function get_categories() {
    	return array( 'basic' );
    }

    protected function _register_controls() {}

    protected function render() {}

}