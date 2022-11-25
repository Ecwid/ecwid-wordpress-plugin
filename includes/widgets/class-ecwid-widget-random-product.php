<?php

require_once ECWID_PLUGIN_DIR . '/includes/widgets/class-ecwid-widget-base.php';

class Ecwid_Widget_Random_Product extends Ecwid_Widget_Base {

	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_ecwid_random_product',
			'description' => __( 'Displays a random product from your store to attract customer attention.', 'ecwid-shopping-cart' ),
		);
		parent::__construct( 'ecwidrandomproduct', __( 'Random Product', 'ecwid-shopping-cart' ), $widget_ops );
	}

	protected function _render_widget_content( $args, $instance ) {
		$product = Ecwid_Product::get_random_product();

		if ( ! $product ) {
			return '';
		}

		$name      = esc_attr( $product->name );
		$url       = $product->link;
		$widget_id = 'ec-store-widget-random-' . $product->id;

		$content = "<div class='ecwid ecwid-random-product ecwid-SingleProduct-v2 ecwid-SingleProduct-v2-bordered ecwid-SingleProduct-v2-centered ecwid-Product ecwid-Product-$product->id' itemscope itemtype='http://schema.org/Product' data-single-product-id='$product->id'>
            <div id='$widget_id'>
                <a href='$url' data-ecwid-page='product' data-ecwid-product-id='$product->id'><div itemprop='image'></div></a>
                <a href='$url' data-ecwid-page='product' data-ecwid-product-id='$product->id'><div class='ecwid-title' itemprop='name' content='$name'></div></a>
                <a href='$url' data-ecwid-page='product' data-ecwid-product-id='$product->id'>
                    <div itemtype='http://schema.org/Offer' itemscope itemprop='offers'>
                        <div class='ecwid-productBrowser-price ecwid-price' itemprop='price' content='$product->defaultDisplayedPrice' data-spw-price-location='button'>
                            <div itemprop='priceCurrency'></div>
                        </div>
                    </div>
                </a>
            </div>
        </div>";

		$content .= Ec_Store_Defer_Init::print_js_widget( 'xProduct', $widget_id );

		$html  = '';
		$html .= '<div>';

		$html .= ecwid_get_scriptjs_code();
		$html .= $content;

		$html .= '</div>';

		return $html;
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = wp_strip_all_tags( stripslashes( $new_instance['title'] ) );

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );

		$title = htmlspecialchars( $instance['title'] );

		if ( ! $title ) {
			$title = __( 'Random Product', 'ecwid-shopping-cart' );
		}

		echo '<p><label for="' . esc_attr( $this->get_field_name( 'title' ) ) . '">' . esc_html__( 'Title:' ) . ' <input style="width:100%;" id="' . esc_attr( $this->get_field_id( 'title' ) ) . '" name="' . esc_attr( $this->get_field_name( 'title' ) ) . '" type="text" value="' . esc_attr( $title ) . '" /></label></p>';
	}

}
