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

        $categories = $this->_get_categories_for_selector();

        if( !count($categories) && ecwid_is_demo_store() ) {
            return;
        }

        if( count($categories) > 0 ) {

          $this->start_controls_section(
                'section_content',
                array(
                    'label' => __( 'Store Front Page', 'ecwid-shopping-cart' ),
                )
            );

    		$this->add_control(
    			'default_category_id',
    			array(
    				'label' => __( 'Default category', 'ecwid-shopping-cart' ),
                    'description' => __( 'The category that is shown by default on the Store Front Page', 'ecwid-shopping-cart' ),
    				'type' => Elementor\Controls_Manager::SELECT2,
    				'default' => 0,
    				'options' => $categories
    			)
    		);

            $this->end_controls_section();
        }

        if( !ecwid_is_demo_store() ) {
            $this->start_controls_section(
                'section_appearance',
                array( 'label' => __( 'Appearance', 'ecwid-shopping-cart' ) )
            );

            $design_edit_link = get_admin_url( null, 'admin.php?page=' . Ecwid_Admin::ADMIN_SLUG . '-admin-design' );

            $this->add_control(
                'design',
                array(
                    'label' => __( 'Design', 'ecwid-shopping-cart' ),
                    'show_label' => false,
                    'type' => Elementor\Controls_Manager::RAW_HTML,
                    'raw' => sprintf(
                        __("You can control how your store looks and feels by clicking on the <a %s>Design</a> menu in your %s admin.", 'ecwid-shopping-cart'),
                        'href="' . $design_edit_link . '" target="_blank"',
                        Ecwid_Config::get_brand()
                    )
                )
            );

            $this->end_controls_section();
        }
    }

    protected function render() {
    	$settings = $this->get_settings_for_display();

        if( !isset($settings['default_category_id']) ) {
            $settings['default_category_id'] = '';
        }

        $shortcode_name = Ecwid_Shortcode_Base::get_shortcode_name();

    	$shortcode = sprintf( '[%s widgets="productbrowser" default_category_id="%s"]',
            $shortcode_name,
    		$settings['default_category_id']
    	);

    	echo do_shortcode( $shortcode );
    }

    protected function _get_categories_for_selector() {
    	    
        $categories = ecwid_get_categories_for_selector();

        if( !count($categories) || ecwid_is_demo_store() ) {
            return array();
        }

        $result[] = __( 'Store root category', 'ecwid-shopping-cart' );

        if( count( $categories ) ) {
            foreach ($categories as $category) {
                $result[ $category->id ] = $category->name;
            }
        }

        return $result;
    }

}