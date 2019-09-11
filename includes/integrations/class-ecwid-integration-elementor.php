<?php

require_once ECWID_PLUGIN_DIR . '/includes/class-ecwid-stub-renderer.php';

class Ecwid_Integration_Elementor extends Ecwid_Stub_Renderer {

	public function __construct() {
		parent::__construct();

		$__ecwid_integration_elementor_widgets = new Ecwid_Integration_Elementor_Widgets();
	}

	protected function _should_apply() {
		return @$_REQUEST['action'] == 'elementor_ajax' || @$_REQUEST['action'] == 'elementor' || isset( $_GET['elementor-preview'] );
	}
	
	public function enqueue_scripts() {
		parent::enqueue_scripts();
	}
}

$__ecwid_integration_elementor = new Ecwid_Integration_Elementor();

class Ecwid_Integration_Elementor_Widgets {
	
	public function __construct() {

		if( $this->_should_apply() ) {
			add_action( 'widgets_init', array( $this, 'sidebar_widgets_init') );
		}
	}

	public function sidebar_widgets_init() {
		if( class_exists( 'Ecwid_Widget_Product_Browser' ) ) {
			register_widget('Ecwid_Widget_Product_Browser');
		}
	}

	protected function _should_apply() {
		global $pagenow;
		return !(is_admin() && $pagenow == 'widgets.php');
	}
}