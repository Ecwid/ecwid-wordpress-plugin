<?php

require_once ECWID_PLUGIN_DIR . '/includes/widgets/class-ecwid-widget-base.php';

abstract class Ecwid_Widget_Products_List_Base extends Ecwid_Widget_Base {

	protected $_max     = 10;
	protected $_min     = 1;
	protected $_default = 3;

	protected $_title;
	protected $_widget_name;
	protected $_class_name;
	protected $_description;

	protected $_instance;

	protected $_widget_class = 'productsList';

	abstract protected function _get_products();

	public function __construct() {

		$id_base   = str_replace( '_', '', $this->_widget_name );
		$classname = 'widget_' . $this->_widget_name;

		$widget_ops = array(
			'classname'   => $classname,
			'description' => $this->_description,
		);

		parent::__construct( $id_base, $this->_title, $widget_ops );

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	protected function _init( $title, $description, $widget_name = null, $class_name = null ) {
		$this->_title       = $title;
		$this->_description = $description;
		if ( is_null( $widget_name ) ) {
			$widget_name = strtolower( $title );
			$widget_name = preg_replace( '![^a-z0-9_\s-]!', '', $widget_name );
			$widget_name = preg_replace( '![\s-]+!', '_', $widget_name );
			$widget_name = 'ecwid_' . $widget_name;
		}

		$this->_widget_name = $widget_name;

		if ( is_null( $class_name ) ) {
			$class_name = str_replace( '_', '-', $this->_widget_name );
		}
		$this->_class_name = $class_name;
	}

	public function enqueue() {
		if ( is_active_widget( false, false, $this->id_base ) ) {
			wp_enqueue_style( 'ecwid-products-list-css', ECWID_PLUGIN_URL . 'css/products-list.css', array(), get_option( 'ecwid_plugin_version' ) );
			wp_enqueue_script( 'ecwid-products-list-js', ECWID_PLUGIN_URL . 'js/products-list.js', array( 'jquery-ui-widget' ), get_option( 'ecwid_plugin_version' ) );
		}
	}

	protected function _render_widget_content( $args, $instance ) {

		$this->_args     = $args;
		$this->_instance = wp_parse_args( $instance, array( 'number_of_products' => $this->_default ) );

		$html  = '';
		$html .= ecwid_get_scriptjs_code();

		$html .= '<div class="' . $this->_class_name . '" data-ecwid-max="' . $this->_instance['number_of_products'] . '">';

		$counter = 1;
		$ids     = array();

		ob_start();
		$this->_print_widget_content( $instance );
		$html .= ob_get_contents();
		ob_end_clean();

		$html .= '</div>';

		return $html;
	}

	protected function _print_widget_content( $instance ) {
		$products = $this->_get_products();

		if ( $products ) {
			$this->_print_products( $products );
			$this->_print_js_init();
		}
	}

	protected function _print_js_init() {

		$data_attr = "data-$this->_class_name-initialized";
		?>
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('.<?php echo esc_js( $this->_class_name ); ?>:not([<?php echo esc_js( $data_attr ); ?>=1])')
					.<?php echo esc_js( $this->_widget_class ); ?>()
					.attr('<?php echo esc_js( $data_attr ); ?>', 1);
			});
		</script>
		<?php
	}

	protected function _print_products( $products ) {
		$next = 1;
		foreach ( $products as $obj ) {

			$product = Ecwid_Product::get_by_id( $obj->id );

			if ( ! $product->id ) {
				continue;
			}

			$force_image = '';
			if ( isset( $product->imageUrl ) && strpos( $product->imageUrl, 'https://' ) == 0 ) {//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
				$force_image = $product->imageUrl; //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			}

			$name  = esc_html( $product->name );
			$price = $product->price;

			$widget_id = 'ec-store-widget-product-' . $product->id;
			?>
			<a class="product" href="<?php echo esc_url( $product->link ); ?>" data-ecwid-product-id="<?php echo esc_attr( $product->id ); ?>" alt="<?php echo esc_attr( $name ); ?>" title="<?php echo esc_attr( $name ); ?>" data-ecwid-page="product">
				<div class="ecwid ecwid-SingleProduct ecwid-Product ecwid-Product-<?php echo esc_attr( $product->id ); ?>" data-single-product-link="<?php echo esc_url( $product->link ); ?>" data-single-product-id="<?php echo esc_attr( $product->id ); ?>" itemscope itemtype="http://schema.org/Product" id="<?php echo esc_attr( $widget_id ); ?>">
					<div itemprop="image" data-force-image="<?php echo esc_attr( $force_image ); ?>"></div>
					<div class="ecwid-title" itemprop="name" content="<?php echo esc_attr( $name ); ?>"></div>
					<div itemtype="http://schema.org/Offer" itemscope itemprop="offers"><div class="ecwid-productBrowser-price ecwid-price" itemprop="price" content="<?php echo esc_attr( $price ); ?>"></div></div>
				</div>
				<?php echo Ec_Store_Defer_Init::print_js_widget( 'xSingleProduct', $widget_id );//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</a>
			<?php
			$next++;
		}//end foreach
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		foreach ( $this->_get_form_fields() as $field ) {
			$name = $field['name'];
			if ( $name === 'number_of_products' ) {
				$instance[ $name ] = $this->_get_valid_number_of_products( $new_instance['number_of_products'] );
			} else {
				$instance[ $name ] = wp_strip_all_tags( stripslashes( $new_instance[ $name ] ) );
			}
		}

		return $instance;
	}

	public function form( $instance ) {

		$default_args = array();
		foreach ( $this->_get_form_fields() as $field ) {
			$default_args[ $field['name'] ] = $field['default'];
		}

		$instance = wp_parse_args( (array) $instance, $default_args );

		foreach ( $this->_get_form_fields() as $field ) {
			if ( $field['type'] == 'int' ) {
				$value = intval( $instance[ $field['name'] ] );
			} else {
				$value = htmlspecialchars( $instance[ $field['name'] ] );
			}
			?>
			<p>
				<label for="<?php echo esc_attr( $this->get_field_name( $field['name'] ) ); ?>">
					<?php echo esc_html( $field['title'] ); ?>:
					<input style="width:100%" id="<?php echo esc_attr( $this->get_field_name( $field['name'] ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $field['name'] ) ); ?>" type="text" value="<?php echo esc_attr( $value ); ?>" />
				</label>
			</p>
			<?php
		}//end foreach
	}

	protected function _get_valid_number_of_products( $num ) {
		$num = intval( $num );
		if ( $num > $this->_max ) {
			$num = $this->_max;
		} elseif ( $num < $this->_min ) {
			$num = $this->_default;
		}
		return $num;
	}

	protected function _get_form_fields() {
		return array(
			array(
				'name'    => 'title',
				'title'   => __( 'Title' ),
				'type'    => 'text',
				'default' => $this->_title,
			),
			array(
				'name'    => 'number_of_products',
				'title'   => __( 'Number of products to show', 'ecwid-shopping-cart' ),
				'type'    => 'int',
				'default' => 3,
			),
		);
	}

}
