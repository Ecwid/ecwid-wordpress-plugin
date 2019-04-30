<?php

require_once dirname( __FILE__ ) . '/class-ecwid-gutenberg-block-base.php';

class Ecwid_Gutenberg_Block_Minicart extends Ecwid_Gutenberg_Block_Base {

	protected $_name = 'minicart';
	
	public function render_callback( $params ) {
		
		$params = wp_parse_args(
			$params,
			array(
				'is_ecwid_shortcode' => true
			)
		);

		ob_start();
		?>
		<!-- noptimize -->
		<?php

		echo ecwid_get_scriptjs_code();
		echo ecwid_get_product_browser_url_script();

		$attributes = $this->get_attributes_for_editor();
		
		foreach ( array( 'fixed_shape', 'layout', 'icon' ) as $param ) {
		    if ( !@$params[$param] ) {
		        $params[$param] = $attributes[$param]['default'];
            }
        }
        
		?>
        
        <div class='ec-cart-widget'
		     data-fixed='false'
		     data-fixed-shape='<?php echo @$params['fixed_shape']; ?>'
		     data-layout='<?php echo @$params['layout']; ?>'
		     data-icon='<?php echo @$params['icon']; ?>'
		></div>
        
		<script>
            Ecwid.init();
		</script>
		<!-- /noptimize -->
		<?php

		$contents = ob_get_contents();
		ob_end_clean();

		$align = @$params['align'];
        if ( $align == 'right' || $align == "left" ) {
            $contents = '<div class="align' . $align . '">' . $contents . '</div>';
        }

		return $contents;
	}

	public function get_attributes_for_editor() {

		$minicart_attributes = array(
			'layout' => array(
				'name' => 'layout',
				'title' => __( 'Layout', 'ecwid-shopping-cart' ),
				'values' => Ecwid_Floating_Minicart::get_layouts(),
				'default' => 'BIG_ICON_TITLE_SUBTOTAL'
			),
			'icon' => array(
				'name' => 'icon',
				'title' => __( 'Cart icon', 'ecwid-shopping-cart' ),
				'values' => Ecwid_Floating_Minicart::get_icons(),
				'default' => 'BAG'
			),
			'fixed_shape' => array(
				'name' => 'fixed_shape',
				'title' => __( 'Border', 'ecwid-shopping-cart' ),
				'values' => Ecwid_Floating_Minicart::get_fixed_shapes(),
				'default' => 'RECT'
			)
		);

		$attributes = array();
		
		foreach ( $minicart_attributes as $name => $attr ) {
			$result = array();
			$result['name'] = $attr['name'];
			$result['title'] = $attr['title'];
			$result['default'] = $attr['default'];
			$result['values'] = array();

			foreach ( $attr['values'] as $value => $title ) {
				$result['values'][] = array(
					'value' => $value,
					'title' => $title
				);
			}

			$attributes[$name] = $result;
		}

		return $attributes;
	}
}