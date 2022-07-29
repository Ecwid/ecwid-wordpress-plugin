<?php

class Ecwid_Product_Popup {
	public function __construct() {
		$version = get_bloginfo( 'version' );
		if ( version_compare( $version, '3.9' ) < 0 ) {
			return;
		}
		if ( ! Ecwid_Api_V3::is_available() ) {
			return;
		}

		add_action( 'init', array( $this, 'init' ) );
		add_action( 'current_screen', array( $this, 'init_current_screen' ) );
	}

	public function init() {
		add_action( 'wp_ajax_ecwid-search-products', array( $this, 'search_products' ) );
		add_action( 'wp_ajax_ecwid-save-spw-params', array( $this, 'save_display_params' ) );
	}

	public function init_current_screen() {
		$current_screen = get_current_screen();
		$version        = get_bloginfo( 'version' );

		$is_post_screen    = $current_screen->base == 'post';
		$is_widgets_screen = false;

		if ( strpos( $version, '5.8' ) === 0 || version_compare( $version, '5.8' ) >= 0 ) {
			$is_widgets_screen = $current_screen->base == 'widgets';
		}

		if ( ! $is_post_screen && ! $is_widgets_screen ) {
			return;
		}

		if ( $is_post_screen && ! in_array( $current_screen->post_type, array( 'page', 'post' ) ) ) {
			return;
		}

		if ( is_plugin_active( 'elementor/elementor.php' ) && isset( $_GET['action'] ) && $_GET['action'] == 'elementor' ) {
			return;
		}

		if ( Ecwid_Api_V3::get_token() ) {
			add_action( 'media_buttons', array( $this, 'add_editor_button' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
			add_action( 'in_admin_header', array( $this, 'add_popup' ) );
		}
	}

	public function save_display_params() {
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$params = isset( $_REQUEST['params'] ) ? map_deep( wp_unslash( $_REQUEST['params'] ), 'sanitize_text_field' ) : array();

		EcwidPlatform::set( 'spw_display_params', $params );
	}

	public function search_products() {
		if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$api = new Ecwid_Api_V3();

		$allowed = array( 'keyword', 'sortBy' );

		foreach ( $allowed as $name ) {
			if ( array_key_exists( $name, $_REQUEST ) ) {
				$params[ $name ] = sanitize_text_field( wp_unslash( $_REQUEST[ $name ] ) );
			}
		}

		$params['limit']  = 10;
		$params['offset'] = 0;

		if ( array_key_exists( 'page', $_REQUEST ) ) {
			$params['offset'] = $params['limit'] * ( intval( $_REQUEST['page'] ) - 1 );
		}

		$result = $api->search_products( $params );

		if ( $result && $result->count > 0 ) {

			$output = array(
				'total'  => $result->total,
				'count'  => $result->count,
				'offset' => $result->offset,
				'limit'  => $params['limit'],
				'items'  => array(),
			);

			foreach ( $result->items as $product ) {
				$output['items'][] = array(
					'id'    => $product->id,
					'name'  => $product->name,
					'thumb' => @$product->thumbnailUrl, //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
					'sku'   => $product->sku,
				);
			}
			echo json_encode( $output );

		}//end if

		wp_die();
	}

	public function add_editor_button( $editor_id ) {

		$title = __( 'Add Product', 'ecwid-shopping-cart' );
		?>
		<button id="insert-ecwid-product-button" class="button add-ecwid-product ecwid_button" title="<?php echo esc_attr( $title ); ?>">
			<?php echo esc_html( $title ); ?>
		</button>
		<?php
	}

	public function add_scripts() {
		wp_enqueue_style( 'ecwid-product-popup', ECWID_PLUGIN_URL . 'css/product-popup.css', array(), get_option( 'ecwid_plugin_version' ) );
		wp_enqueue_script( 'ecwid-product-popup', ECWID_PLUGIN_URL . 'js/product-popup.js', array(), get_option( 'ecwid_plugin_version' ) );

		$data = array();
		if ( ! Ecwid_Api_V3::get_token() ) {
			$data = array( 'no_token' => 1 );
		} else {
			$data = EcwidPlatform::get( 'spw_display_params' );
		}

		if ( ! isset( $data['display'] ) ) {
			$data['display'] = array(
				'picture'  => 1,
				'title'    => 1,
				'price'    => 1,
				'addtobag' => 1,
				'options'  => 1,
			);
		}

		if ( ! isset( $data['attributes'] ) ) {
			$data['attributes'] = array(
				'show_price_on_button' => 1,
				'center_align'         => 1,
				'show_border'          => 1,
			);
		}

		$data['labels'] = array(
			'firstPage' => __( 'First Page', 'ecwid-shopping-cart' ),
			'prevPage'  => __( 'Previous Page', 'ecwid-shopping-cart' ),
			'nextPage'  => __( 'Next Page', 'ecwid-shopping-cart' ),
			'lastPage'  => __( 'Last Page', 'ecwid-shopping-cart' ),
		);

		wp_localize_script( 'ecwid-product-popup', 'ecwidSpwParams', $data );
	}

	public function add_popup() {
		require_once ECWID_PLUGIN_DIR . 'templates/product-popup.php';
	}
}

$ecwid_product_popup = new Ecwid_Product_Popup();
