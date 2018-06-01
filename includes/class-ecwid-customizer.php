<?php

require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-floating-minicart.php';

class Ecwid_Customizer
{
	public function __construct()
	{
		add_action( 'customize_register', array( $this, 'customize_register' ) );

		add_action( 'customize_preview_init', array( $this, 'preview_init' ) );

	}
	
	public function customize_register( $wp_customize )
	{
		$section = 'ec-store';
		
		$wp_customize->add_section( $section, array(
			'title' 	 => Ecwid_Config::get_brand(),
			'priority' 	 => 50,
			'capability' => Ecwid_Admin::get_capability()
		) );
		
		$wp_customize->add_setting( 'ec_show_floating_cart_widget', array(
			'type' 		=> 'option',
			'transport'	=> 'postMessage'
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, Ecwid_Floating_Minicart::OPTION_WIDGET_DISPLAY, array(
			'type'		=> 'select',
			'label'		=> __( 'Show minicart', 'ecwid-shopping-cart' ),
			'section'	=> $section,
			'settings'	=> Ecwid_Floating_Minicart::OPTION_WIDGET_DISPLAY,
			'choices'	=> Ecwid_Floating_Minicart::get_display_options()
		) ) );
		
		
		$wp_customize->add_setting( Ecwid_Floating_Minicart::OPTION_LAYOUT, array(
			'type'		=> 'option',
			'transport'	=> 'postMessage'
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, Ecwid_Floating_Minicart::OPTION_LAYOUT, array(
			'type'		=> 'select',
			'label'		=> __( 'Layout', 'ecwid-shopping-cart' ),
			'section'	=> $section,
			'settings'	=> Ecwid_Floating_Minicart::OPTION_LAYOUT,
			'choices'	=> Ecwid_Floating_Minicart::get_layouts()
		) ) );


		$wp_customize->add_setting( Ecwid_Floating_Minicart::OPTION_FIXED_SHAPE, array(
			'type'		=> 'option',
			'transport'	=> 'postMessage'
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, Ecwid_Floating_Minicart::OPTION_FIXED_SHAPE, array(
			'type'		=> 'select',
			'label'		=> __( 'Border', 'ecwid-shopping-cart' ),
			'section'	=> $section,
			'settings'	=> Ecwid_Floating_Minicart::OPTION_FIXED_SHAPE,
			'choices'	=> Ecwid_Floating_Minicart::get_fixed_shapes()
		) ) );


		$wp_customize->add_setting( Ecwid_Floating_Minicart::OPTION_ICON, array(
			'type'		=> 'option',
			'transport'	=> 'postMessage'
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, Ecwid_Floating_Minicart::OPTION_ICON, array(
			'type'		=> 'select',
			'label'		=> __( 'Icon', 'ecwid-shopping-cart' ),
			'section'	=> $section,
			'settings'	=> Ecwid_Floating_Minicart::OPTION_ICON,
			'choices'	=> Ecwid_Floating_Minicart::get_icons()
		) ) );


		$wp_customize->add_setting( Ecwid_Floating_Minicart::OPTION_FIXED_POSITION, array(
			'type'		=> 'option',
			'transport'	=> 'postMessage'
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, Ecwid_Floating_Minicart::OPTION_FIXED_POSITION, array(
			'type'		=> 'select',
			'label'		=> __( 'Position', 'ecwid-shopping-cart' ),
			'section'	=> $section,
			'settings'	=> Ecwid_Floating_Minicart::OPTION_FIXED_POSITION,
			'choices'	=> Ecwid_Floating_Minicart::get_fixed_positions()
		) ) );

		
		$wp_customize->add_setting( Ecwid_Floating_Minicart::OPTION_SHOW_EMPTY_CART, array(
			'type'		=> 'option',
			'transport'	=> 'postMessage'
		) );
		$wp_customize->add_control( new WP_Customize_Control( $wp_customize, Ecwid_Floating_Minicart::OPTION_SHOW_EMPTY_CART, array(
			'type'		=> 'checkbox',
			'label'		=> __( 'Show empty cart', 'ecwid-shopping-cart' ),
			'section'	=> $section,
			'settings'	=> Ecwid_Floating_Minicart::OPTION_SHOW_EMPTY_CART,
			'choices'	=> Ecwid_Floating_Minicart::get_fixed_positions()
		) ) );
	}
	
	public function preview_init() {
		EcwidPlatform::enqueue_script( 'ecwid-minicart-customize', array(), true );
	}
}

new Ecwid_Customizer();