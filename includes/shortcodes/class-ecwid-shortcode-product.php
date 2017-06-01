<?php

require_once ECWID_SHORTCODES_DIR . '/class-ecwid-shortcode-base.php';

class Ecwid_Shortcode_Product extends Ecwid_Shortcode_Base {

	protected $_version;
	public static function get_shortcode_name() {
		if ( Ecwid_Config::is_wl() ) {
			return 'ec_product';
		}

		return 'ecwid_product';
	}

	protected function _process_params( $shortcode_params = array() ) {
		$attributes = shortcode_atts(
			array(
				'id' => null,
				'display' => 'picture title price options addtobag',
				'link' => 'yes',
				'version' => '1',
				'show_border' => '1',
				'show_price_on_button' => '1',
                'center_align' => '1'
			),
			$shortcode_params
		);

		$id = $attributes['id'];

		if (is_null($id) || !is_numeric($id) || $id <= 0) {
			$this->_should_render = false;
			return;
		}


		if ($attributes['link'] == 'yes' && !ecwid_is_store_page_available()) {
			$attributes['link'] = 'no';
		}


		$version = $attributes['version'];
		if (!in_array($version, array('1', '2'))) {
			$attributes['version'] = 1;
		}

		$this->params = $attributes;
	}

	public function render_placeholder() {
		$widget_parts = array();

		if ($this->params['version'] == 1) {
			$widget_parts = $this->_get_widget_parts_v1();
		} else if ($this->params['version'] == 2) {
			$widget_parts = $this->_get_widget_parts_v2();
		}

		$display_items = $widget_parts['display_items'];

		$result = $widget_parts['opening_div'];

		$items = preg_split('![^0-9^a-z^A-Z^\-^_]!', $this->params['display']);

		if (is_array($items) && count($items) > 0) foreach ($items as $item) {
			if (array_key_exists($item, $display_items)) {
				if ($this->params['link'] == 'yes' && in_array($item, array('title', 'picture'))) {
					$product_link = Ecwid_Store_Page::get_product_url( $this->params['id'] );
					$result .= '<a href="' . esc_url($product_link) . '">' . $display_items[$item] . '</a>';
				} else {
					$result .= $display_items[$item];
				}
			}
		}

		$result .= '</div>';

		$result .= ' '; // APPS-892, otherwise there is no space between consecutive widgets

		update_option('ecwid_single_product_used', time());

		return $result;
	}

	public function get_ecwid_widget_function_name() {
		return $this->params['version'] == 1 ? 'xSingleProduct' : 'xProduct';
	}

	protected function _get_widget_parts_v1() {
		return array(
			'display_items' => array(
				'picture'  => '<div itemprop="picture"></div>',
				'title'    => '<div class="ecwid-title" itemprop="title"></div>',
				'price'    => '<div itemtype="http://schema.org/Offer" itemscope itemprop="offers">'
				              . '<div class="ecwid-productBrowser-price ecwid-price" itemprop="price"></div>'
				              . '</div>',
				'options'  => '<div itemprop="options"></div>',
				'qty' 	   => '<div itemprop="qty"></div>',
				'addtobag' => '<div itemprop="addtobag"></div>'
			),
			'opening_div' => sprintf('<div class="ecwid ecwid-SingleProduct ecwid-Product ecwid-Product-%d" '
			                         . 'itemscope itemtype="http://schema.org/Product" '
			                         . 'data-single-product-id="%d" id="%s">', $this->params['id'], $this->params['id'], $this->get_html_id())
		);
	}

	protected function _get_widget_parts_v2() {
		$price_location_attributes = '  data-spw-price-location="button"';

		$main_div_classes = array(
		    'ecwid',
            'ecwid-SingleProduct-v2',
            'ecwid-Product',
            'ecwid-Product-' . $this->params['id']
        );

		if ($this->params['show_border'] != 0) { // defaults to 1
			$bordered_class = '';
			$main_div_classes[] = 'ecwid-SingleProduct-v2-bordered';
		}

		if ($this->params['center_align'] == 1) { // defaults to 0
		    $main_div_classes[] = 'ecwid-SingleProduct-v2-centered';
        }

        $main_div_class = implode( ' ', $main_div_classes );

		if ($this->params['show_price_on_button'] == 0) { // defaults to 1
			$price_location_attributes = '';
		}

		return array(
			'display_items' => array(
				'picture'  => '<div itemprop="picture"></div>',
				'title'    => '<div class="ecwid-title" itemprop="title"></div>',
				'price'    => '<div itemtype="http://schema.org/Offer" itemscope itemprop="offers">'
				              . '<div class="ecwid-productBrowser-price ecwid-price" itemprop="price"' . $price_location_attributes . '>'
				              . '<div itemprop="priceCurrency"></div>'
				              . '</div>'
				              . '</div>',
				'options'  => '<div customprop="options"></div>',
				'qty' 	   => '<div customprop="qty"></div>',
				'addtobag' => '<div customprop="addtobag"></div>'
			),
			'opening_div' => sprintf('<div class="' . $main_div_class . '" '
			                         . 'itemscope itemtype="http://schema.org/Product" data-single-product-id="%d" id="%s">',
									$this->params['id'],
									$this->get_html_id()
			)
		);
	}


    public function build_params_string($params = null) {
        if ( !is_null( $params ) && array_key_exists( 'id', $params ) ) {
            unset( $params['id'] );
        }

        return parent::build_params_string( $params );
    }
}
