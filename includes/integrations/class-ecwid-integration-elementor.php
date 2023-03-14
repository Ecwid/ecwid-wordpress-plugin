<?php

require_once ECWID_PLUGIN_DIR . '/includes/class-ecwid-stub-renderer.php';

class Ecwid_Integration_Elementor {

	const EC_WIDGETS_PATH = '/includes/integrations/elementor';

	public $has_widget = false;

	public function __construct() {

		$is_needed_php_version = version_compare( phpversion(), '5.6', '>=' );
		$is_needed_wp_version  = version_compare( get_bloginfo( 'version' ), '5.4.1', '>=' );

		if ( $is_needed_php_version && $is_needed_wp_version ) {
			add_action( 'elementor/elements/categories_registered', array( $this, 'add_custom_widget_categories' ) );
			add_action( 'elementor/widgets/widgets_registered', array( $this, 'init_custom_widgets' ), 10, 1 );
		}

		if ( $this->_should_apply() ) {
			add_action( 'widgets_init', array( $this, 'init_sidebar_widgets' ) );
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_filter( 'ecwid_page_has_product_browser', array( $this, 'page_has_product_browser' ), 10, 3 );
	}

	protected function _should_apply() {
		global $pagenow;
		return ! ( is_admin() && $pagenow == 'widgets.php' );
	}

	public function init_sidebar_widgets() {
		if ( class_exists( 'Ecwid_Widget_Product_Browser' ) ) {
			register_widget( 'Ecwid_Widget_Product_Browser' );
		}
	}

	private function include_custom_widgets_files() {
		require_once ECWID_PLUGIN_DIR . self::EC_WIDGETS_PATH . '/class-ec-elementor-widget-store.php';
		require_once ECWID_PLUGIN_DIR . self::EC_WIDGETS_PATH . '/class-ec-elementor-widget-buynow.php';
	}

	public function init_custom_widgets( $widgets_manager ) {

		if ( ! class_exists( '\Elementor\Plugin' ) || ! class_exists( '\Elementor\Widget_Base' ) ) {
			return;
		}

		$this->include_custom_widgets_files();

		$widgets_manager->register_widget_type( new Ec_Elementor_Widget_Store() );
		$widgets_manager->register_widget_type( new Ec_Elementor_Widget_Buynow() );
	}

	public function add_custom_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'ec-store',
			array(
				'title' => sprintf(
					// translators: %s: brand
					__( '%s Store', 'ecwid-shopping-cart' ),
					Ecwid_Config::get_brand()
				),
				'icon'  => 'fa fa-plug',
			)
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'ec-elementor', ECWID_PLUGIN_URL . 'css/integrations/elementor.css', array(), get_option( 'ecwid_plugin_version' ) );
	}

	public function page_has_product_browser( $result, $content, $post_id ) {
		if ( $result ) {
			return true;
		}

		$meta = get_post_meta( $post_id, '_elementor_data', true );

		if ( ! empty( $meta ) ) {

			if ( ! is_array( $meta ) ) {
				$data = json_decode( $meta, true );
			}

			if ( is_array( $data ) ) {
				$this->has_widget = false;
				array_walk_recursive( $data, array( $this, 'filter_page_data' ) );
			}

			if ( $this->has_widget ) {
				return true;
			}
		}

		return $result;
	}

	public function filter_page_data( $item, $key ) {
		if ( $key === 'widgetType' && $item === 'ec_store' ) {
			$this->has_widget = true;
		}
	}
}


class Ec_Integration_Elementor_Stub_Renderer extends Ecwid_Stub_Renderer {

	public function __construct() {
		parent::__construct();
	}

	protected function _should_apply() {

		if ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], array( 'elementor_ajax', 'elementor' ) ) ) {
			return true;
		}

		if ( isset( $_GET['elementor-preview'] ) ) {
			return true;
		}

		return false;
	}

	public function enqueue_scripts() {
		parent::enqueue_scripts();
	}
}

$__ecwid_integration_elementor             = new Ecwid_Integration_Elementor();
$__ecwid_integration_elementor_stub_render = new Ec_Integration_Elementor_Stub_Renderer();
