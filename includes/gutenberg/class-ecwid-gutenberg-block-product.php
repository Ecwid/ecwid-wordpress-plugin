<?php

require_once dirname( __FILE__ ) . '/class-ecwid-gutenberg-block-base.php';

class Ecwid_Gutenberg_Block_Product extends Ecwid_Gutenberg_Block_Base {
	
	protected $_name = 'product';
	
	public function get_block_name() {
		return 'ecwid/product-block';
	}
	
	public function render_callback( $params ) {

		if ( !@$params['id'] ) return '';

		$params = wp_parse_args(
			$params,
			array(
				'id' => 0,
				'show_picture' => true,
				'show_title' => true,
				'show_price' => true,
				'show_options' => true,
				'show_addtobag' => true,
				'show_border' => true,
				'center_align' => true,
				'show_price_on_button' => true
			)
		);

		$display = array(
			'picture', 'title', 'price', 'options', 'qty', 'addtobag'
		);

		$params['display'] = '';
		$display_string = '';
		foreach ( $display as $name ) {
			if ( @$params['show_' . $name] ) {
				$params['display'] .= ' ' . $name;
			}
		}

		$params['version'] = 2;

		$shortcode = new Ecwid_Shortcode_Product( $params );

		$contents = $shortcode->render();

		$align = @$params['align'];
		if ( $align == 'right' || $align == "left" ) {
			$contents = '<div class="align' . $align . '">' . $contents . '</div>';
		}
		
		return $contents;
	}

	public function get_icon_path()
	{
		return 'M16.43,5.12c-0.13-1.19-0.15-1.19-1.35-1.33c-0.21-0.02-0.21-0.02-0.43-0.05c-0.01,0.06,0.06,0.78,0.14,1.13
	c0.57,0.37,0.87,0.98,0.87,1.71c0,1.14-0.93,2.07-2.07,2.07s-2.07-0.93-2.07-2.07c0-0.54,0.09-0.97,0.55-1.4
	c-0.06-0.61-0.19-1.54-0.18-1.64C10.14,3.46,8.72,3.46,8.58,3.6l-8.17,8.13c-0.56,0.55-0.56,1.43,0,1.97l5.54,5.93
	c0.56,0.55,1.46,0.55,2.01,0l8.67-8.14C17.04,11.09,16.68,7.14,16.43,5.12z M16.06,0.04c-1.91,0-3.46,1.53-3.46,3.41c0,0.74,0.4,3.09,0.44,3.28c0.07,0.34,0.52,0.56,0.86,0.49
	C14,7.19,14.07,7.15,14.12,7.1c0.24-0.11,0.32-0.39,0.25-0.68c-0.09-0.45-0.39-2.44-0.39-2.94c0-1.16,0.94-2.09,2.11-2.09
	c1.24,0,2.11,0.96,2.11,2.34c0,2.43-0.31,4.23-0.32,4.26c-0.1,0.17-0.1,0.38-0.03,0.55c0.03,0.17,0.13,0.31,0.28,0.4
	c0.1,0.06,0.22,0.09,0.33,0.09c0.21,0,0.42-0.1,0.54-0.3c0.06-0.09,0.52-2.17,0.52-5.03C19.52,1.61,18.04,0.04,16.06,0.04z';
	}
}