<?php

require_once ECWID_PLUGIN_DIR . '/includes/class-ecwid-stub-renderer.php';

class Ecwid_Integration_Elementor {
	
	const EC_WIDGETS_PATH = ECWID_PLUGIN_DIR . '/includes/integrations/elementor';

	public function __construct() {

		if (version_compare( phpversion(), '5.6', '>=' ) ) {
			add_action( 'init', array( $this, 'init_custom_widgets') );
		}

		if( $this->_should_apply() ) {
			add_action( 'widgets_init', array( $this, 'init_sidebar_widgets') );
		}

		wp_enqueue_style('ec-elementor', ECWID_PLUGIN_URL . 'css/integrations/elementor.css', array(), get_option('ecwid_plugin_version'));
	}

	protected function _should_apply() {
		global $pagenow;
		return !(is_admin() && $pagenow == 'widgets.php');
	}

	public function init_sidebar_widgets() {
		if( class_exists( 'Ecwid_Widget_Product_Browser' ) ) {
			register_widget('Ecwid_Widget_Product_Browser');
		}
	}

	private function include_custom_widgets_files() {
		require_once self::EC_WIDGETS_PATH . '/class-ec-elementor-widget-store.php';
		require_once self::EC_WIDGETS_PATH . '/class-ec-elementor-widget-buynow.php';
	}

	public function init_custom_widgets() {
		
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_custom_widget_categories' ) );

		$this->include_custom_widgets_files();

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Ec_Elementor_Widget_Store() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Ec_Elementor_Widget_Buynow() );
	}

	public function add_custom_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'ec-store',
			array(
				'title' => sprintf(
					__( '%s Store', 'ecwid-shopping-cart'),
					Ecwid_Config::get_brand()
				),
				'icon' => 'fa fa-plug',
			)
		);
	}
}


class Ec_Integration_Elementor_Stub_Renderer extends Ecwid_Stub_Renderer {

	public function __construct() {
		parent::__construct();
	}

	protected function _should_apply() {
		return @$_REQUEST['action'] == 'elementor_ajax' || @$_REQUEST['action'] == 'elementor' || isset( $_GET['elementor-preview'] );
	}
	
	public function enqueue_scripts() {
		parent::enqueue_scripts();
	}
}


$__ecwid_integration_elementor = new Ecwid_Integration_Elementor();
$__ecwid_integration_elementor_stub_render = new Ec_Integration_Elementor_Stub_Renderer();
