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
        $dashboard_link = admin_url( 'admin.php?page=ec-store' );

        $this->start_controls_section(
            'content_section',
            array(
                'label' => __( 'Online Store', 'ecwid-shopping-cart' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT
            )
        );

        if( count($categories) ) {
            $this->add_control(
                'default_category_id',
                array(
                    'label' => __( 'Default category', 'ecwid-shopping-cart' ),
                    'description' => __( 'The category that is shown by default on the Store Front Page', 'ecwid-shopping-cart' ),
                    'type' => Elementor\Controls_Manager::SELECT,
                    'default' => 0,
                    'options' => $categories
                )
            );
        }

        if( ecwid_is_demo_store() ) {
            $html_raw = sprintf(
                '<a href="%s" target="_blank" class="elementor-button elementor-button-default elementor-button-success elementor-input-style" style="text-align: center;">%s</a>',
                $dashboard_link,
                __( 'Set up your store', 'ecwid-shopping-cart' )
            );
        } else {
            $html_raw = sprintf(
                __( 'To manage your store, go to <a %s>the Store Dashboard page</a>', 'ecwid-shopping-cart' ),
                'href="' . $dashboard_link . '" target="_blank"'
            );
        }

		$this->add_control(
            'content',
            array(
                'label' => __( 'Store', 'ecwid-shopping-cart' ),
                'show_label' => false,
                'type' => Elementor\Controls_Manager::RAW_HTML,
                'raw' => $html_raw
            )
        );

        $this->end_controls_section();


        if( !ecwid_is_demo_store() ) {

            $this->start_controls_section(
                'section_appearance',
                array(
                    'label' => __( 'Appearance', 'ecwid-shopping-cart' ),
                    'tab' => \Elementor\Controls_Manager::TAB_STYLE
                )
            );

            $design_edit_link = get_admin_url( null, 'admin.php?page=' . Ecwid_Admin::ADMIN_SLUG . '-admin-design' );

            $this->add_control(
                'design',
                array(
                    'label' => __( 'Design', 'ecwid-shopping-cart' ),
                    'show_label' => false,
                    'type' => Elementor\Controls_Manager::RAW_HTML,
                    'raw' => sprintf(
                        __("You can control your store look and feel on the <a %s>Design settings page</a> in your store control panel.", 'ecwid-shopping-cart'),
                        'href="' . $design_edit_link . '" target="_blank"'
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