<?php

class Ecwid_Floating_Minicart
{
	const OPTION_WIDGET_DISPLAY = 'ec_show_floating_cart_widget';
	const OPTION_FIXED_POSITION = 'ec_store_cart_widget_fixed_position';
	const OPTION_ICON = 'ec_store_cart_widget_icon';
	const OPTION_FIXED_SHAPE = 'ec_store_cart_widget_fixed_shape';
	const OPTION_LAYOUT = 'ec_store_cart_widget_layout';
	const OPTION_SHOW_EMPTY_CART = 'ec_store_cart_widget_show_empty_cart';
	const OPTION_HORIZONTAL_INDENT = 'ec_store_cart_widget_horizontal_indent';
	const OPTION_VERTICAL_INDENT = 'ec_store_cart_widget_vertical_indent';
	
	const DISPLAY_NONE = 'do_not_show';
	const DISPLAY_STORE	= 'show_on_store_pages';
	const DISPLAY_ALL = 'show_on_all_pages';	
	
	const CUSTOMIZE_ID = 'ec-customize-cart';
		
	public function __construct()
	{
		add_action( 'wp_footer', array( $this, 'display' ) );
	}
	
	public function display()
	{
		$display = get_option( self::OPTION_WIDGET_DISPLAY );
		
		if ( !array_key_exists( $display, self::get_display_options() ) ) {
			$display = self::DISPLAY_NONE;
		}
		
		if ( $display == self::DISPLAY_NONE && !is_customize_preview() ) {
			return;
		}
		
		if ( $display == self::DISPLAY_STORE && !Ecwid_Store_Page::is_store_page() ) {
			return;
		}
		
		echo ecwid_get_scriptjs_code();
		
		$position = esc_attr( get_option( self::OPTION_FIXED_POSITION ) );
		$shape = esc_attr( get_option( self::OPTION_FIXED_SHAPE ) );
		$layout = esc_attr( get_option( self::OPTION_LAYOUT ) );
		$show_empty = esc_attr( get_option( self::OPTION_SHOW_EMPTY_CART ) );
		$icon = esc_attr( get_option( self::OPTION_ICON ) );
		
		$hindent = esc_attr( get_option( self::OPTION_HORIZONTAL_INDENT ) );
		$vindent = esc_attr( get_option( self::OPTION_VERTICAL_INDENT ) );
		
		$customize_id = is_customize_preview() ? 'id="' . self::CUSTOMIZE_ID . '"' : '';
		echo <<<HTML
<div $customize_id class='ec-cart-widget' 
    data-fixed='true' 
    data-fixed-position='$position' 
    data-fixed-shape='$shape'
    data-horizontal-indent="$hindent" 
    data-vertical-indent="$vindent" 
    data-layout='$layout' 
    data-show-empty-cart='$show_empty'
    data-show-buy-animation='true'
    data-icon='$icon'
></div>

<script>
			Ecwid.init();
</script>
<!--			
			var interval = setInterval(function(){
			    
			    if (jQuery('.ec-minicart').length > 0) {
					jQuery('.ec-minicart').append('<span class="customize-partial-edit-shortcut customize-partial-edit-shortcut-custom_header"><button aria-label="Click to edit this element." title="Click to edit this element." class="customize-partial-edit-shortcut-button"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M13.89 3.39l2.71 2.72c.46.46.42 1.24.03 1.64l-8.01 8.02-5.56 1.16 1.16-5.58s7.6-7.63 7.99-8.03c.39-.39 1.22-.39 1.68.07zm-2.73 2.79l-5.59 5.61 1.11 1.11 5.54-5.65zm-2.97 8.23l5.58-5.6-1.07-1.08-5.59 5.6z"></path></svg></button></span>');
					clearInterval(interval);
					jQuery('.ec-minicart .customize-partial-edit-shortcut').click(function() {
						wp.customize.preview.send('focus-control-for-setting', 'ec_show_floating_cart_widget');
						return false; 
					});
			    }
			}, 500);
-->
HTML;
	}
	
	public static function create_default_options() {
		
		$options = self::_get_default_options();
		if ( !ecwid_is_recent_installation() ) {
			$options[self::OPTION_WIDGET_DISPLAY] = self::DISPLAY_NONE;
		}
		
		foreach ( $options as $name => $value ) {
			add_option( $name, $value );
		}
	}
	
	protected static function _get_default_options() {
		return array(
			self::OPTION_WIDGET_DISPLAY => self::DISPLAY_NONE,
			self::OPTION_SHOW_EMPTY_CART => true,
			self::OPTION_LAYOUT => 'MEDIUM_ICON_COUNTER',
			self::OPTION_FIXED_SHAPE => 'PILL',
			self::OPTION_FIXED_POSITION => 'BOTTOM_RIGHT',
			self::OPTION_ICON => 'BAG',
			self::OPTION_HORIZONTAL_INDENT => '30',
			self::OPTION_VERTICAL_INDENT => '30',
		);
	}
	
	public static function get_display_options() {
		return array(
			self::DISPLAY_NONE 	=> __( 'Do not show', 'ecwid-shopping-cart' ),
			self::DISPLAY_STORE	=> __( 'Show on store pages', 'ecwid-shopping-cart' ),
			self::DISPLAY_ALL	=> __( 'Show on all pages', 'ecwid-shopping-cart' )
		);
	}
	
	public static function get_layouts() {
		return array(
			'SMALL_ICON' => __( 'Small icon', 'ecwid-shopping-cart' ),
			'SMALL_ICON_COUNTER' => __( 'Small icon and item count', 'ecwid-shopping-cart' ),
			'COUNTER_ONLY' => __( 'Item count only', 'ecwid-shopping-cart' ),
			'TITLE_COUNTER' => __( 'Label and item count', 'ecwid-shopping-cart' ),
			'MEDIUM_ICON_COUNTER' => __( 'Icon and item count', 'ecwid-shopping-cart' ),
			'MEDIUM_ICON_TITLE_COUNTER' => __( 'Icon, label and item count', 'ecwid-shopping-cart' ),
			'BIG_ICON_TITLE_SUBTOTAL' => __( 'Icon, label, item count and subtotal', 'ecwid-shopping-cart' ),
			'BIG_ICON_DETAILS_SUBTOTAL' => __( 'Icon, label, item count, subtotal and link', 'ecwid-shopping-cart' )
		);
	}

	public static function get_icons() {
		return array(
			'BAG' => __( 'Bag', 'ecwid-shopping-cart' ),
			'CART' => __( 'Cart', 'ecwid-shopping-cart' ),
			'BASKET' => __( 'Basket', 'ecwid-shopping-cart' )
		);
	}

	public static function get_fixed_shapes() {
		return array(
			'RECT' => __( 'Rectangle', 'ecwid-shopping-cart' ),
			'PILL' => __( 'Pill', 'ecwid-shopping-cart' ),
			'' => __( 'No border', 'ecwid-shopping-cart' )
		);
	}

	public static function get_fixed_positions() {
		return array(
			'BOTTOM_RIGHT' => __( 'Bottom right', 'ecwid-shopping-cart' ),
			'TOP_RIGHT' => __( 'Top right', 'ecwid-shopping-cart' ),
			'TOP_LEFT' => __( 'Top left', 'ecwid-shopping-cart' ),
			'BOTTOM_LEFT' => __( 'Bottom left', 'ecwid-shopping-cart' )
		);
	}
}

$minicart = new Ecwid_Floating_Minicart();