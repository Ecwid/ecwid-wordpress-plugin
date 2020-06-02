<?php

class Ec_Elementor_Widget_Store extends \Elementor\Widget_Base {
	
	public function get_name() {
		return 'ec_store';
	}

    public function get_title() {
    	return __('Online store', 'ecwid-shopping-cart');
    }

    public function get_icon() {
    	return 'eicon-basket-medium';
    }

    public function get_categories() {
    	return array( 'ec-store' );
    }

    protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Store Front Page', 'ecwid-shopping-cart' ),
			)
		);

		$this->add_control(
			'default_category_id',
			array(
				'label' => __( 'Default category ID', 'ecwid-shopping-cart' ),
				'type' => Elementor\Controls_Manager::SELECT2,
				'default' => 0,
				'options' => $this->_get_categories_for_selector()
			)
		);
	 
	    $this->end_controls_section();
    }

    protected function render() {
    	$settings = $this->get_settings_for_display();

    	$shortcode = sprintf( '[ec_store widgets="productbrowser" default_category_id="%s"]',
    		$settings['default_category_id']
    	);

    	echo do_shortcode( $shortcode );
    }

    protected function _get_categories_for_selector() {
    	    
        $categories = ecwid_get_categories_for_selector();

        $result[] = __( 'Store root category', 'ecwid-shopping-cart' );

        if( count( $categories ) ) {
            foreach ($categories as $category) {
                $result[ $category->id ] = $category->name;
            }
        }

        return $result;
    }

    /*protected function _content_template() {

    	echo '<h1>123</h1>';
    	echo Ecwid_Static_Page::get_html_code();

	}*/

}