<?php

require_once ECWID_PLUGIN_DIR . '/includes/class-ecwid-stub-renderer.php';

class Ecwid_Integration_Elementor extends Ecwid_Stub_Renderer {
	protected function _should_apply() {
		return @$_REQUEST['action'] == 'elementor_ajax' || @$_REQUEST['action'] == 'elementor' || isset( $_GET['elementor-preview'] );
	}
	
	public function enqueue_scripts() {
		parent::enqueue_scripts();
	}
}

$__ecwid_integration_elementor = new Ecwid_Integration_Elementor();