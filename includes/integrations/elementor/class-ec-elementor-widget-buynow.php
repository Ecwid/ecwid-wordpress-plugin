<?php

class Ec_Elementor_Widget_Buynow extends \Elementor\Widget_Base {
	
	public function get_name() {
		return 'ec_buynow';
	}

    public function get_title() {
    	return __( 'Buy Now Button', 'ecwid-shopping-cart' );
    }

    public function get_icon() {
    	return 'eicon-button';
    }

    public function get_categories() {
    	return array( 'ec-store' );
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'section_product',
            array( 'label' => __( 'Linked product', 'ecwid-shopping-cart' ) )
        );
     
        $this->add_control(
            'product_id',
            array(
                'label' => __( 'Choose product', 'ecwid-shopping-cart' ),
                'type' => Elementor\Controls_Manager::SELECT,
                'default' => 1,
                'options' => $this->_get_products_for_selector()
            )
        );
     
        $this->end_controls_section();


        $this->start_controls_section(
            'section_appearance',
            array(
                'label' => __( 'Appearance', 'ecwid-shopping-cart' ),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE
            )
        );

        $this->add_control(
            'show_price_on_button',
            array(
                'label' => __( 'Show price inside the «Buy now» button', 'ecwid-shopping-cart' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => 1,
                'return_value' => 1
            )
        );

        $this->add_control(
            'center_align',
            array(
                'label' => __( 'Center align on a page', 'ecwid-shopping-cart' ),
                'type' => Elementor\Controls_Manager::SWITCHER,
                'default' => 1,
                'return_value' => 1
            )
        );
     
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $is_editor_page = isset($_REQUEST['action']) && in_array( $_REQUEST['action'], array('elementor_ajax', 'elementor') );
        if( !$is_editor_page && intval($settings['product_id']) <= 1 ) {
            return;
        }

        $shortcode_name = Ecwid_Shortcode_Product::get_shortcode_name();

        $shortcode = sprintf( '[%s id="%s" display="price addtobag" show_border="" show_price_on_button="%s" center_align="%s" version="2"]',
            $shortcode_name,
            $settings['product_id'],
            $settings['show_price_on_button'],
            $settings['center_align']
        );

        echo do_shortcode( $shortcode );
    }

    protected function _get_products_for_selector() {

        $api = new Ecwid_Api_V3();
        $products = $api->get_products( array('enabled' => true) );

        if( !$products ) {
            return array();
        }

        $result[1] = __( 'Choose product', 'ecwid-shopping-cart' );

        if( count( $products->items ) ) {
            foreach ($products->items as $product) {
                $result[ $product->id ] = $product->name;
            }
        }

        return $result;
    }

}