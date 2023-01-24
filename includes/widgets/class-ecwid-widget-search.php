<?php

require_once ECWID_PLUGIN_DIR . '/includes/widgets/class-ecwid-widget-base.php';

class Ecwid_Widget_Search extends Ecwid_Widget_Base {

	public static function is_active_widget() {
		return is_active_widget( false, false, 'ecwidsearch' );
	}

	public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_ecwid_search',
			'description' => __( 'Displays a simple search box for your customers to find a product in your store', 'ecwid-shopping-cart' ),
		);
		parent::__construct( 'ecwidsearch', __( 'Product Search', 'ecwid-shopping-cart' ), $widget_ops );
	}

	public function _render_widget_content( $args, $instance ) {

		$widget_id = 'ec-store-search-' . $this->id_base . '-' . $this->widget_number++;

		$html  = '';
		$html .= '<div id="' . $widget_id . '">';

		$html .= ecwid_get_scriptjs_code();
		$html .= ecwid_get_product_browser_url_script();

		$html .= Ec_Store_Defer_Init::print_js_widget( 'xSearch', $widget_id );

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

		echo '<p><label for="' . esc_attr( $this->get_field_name( 'title' ) ) . '">' . esc_attr__( 'Title:' ) . ' <input style="width:100%;" id="' . esc_attr( $this->get_field_id( 'title' ) ) . '" name="' . esc_attr( $this->get_field_name( 'title' ) ) . '" type="text" value="' . esc_attr( $title ) . '" /></label></p>';
	}

}
