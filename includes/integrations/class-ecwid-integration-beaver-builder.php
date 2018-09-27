<?php

require_once ECWID_PLUGIN_DIR . '/includes/class-ecwid-stub-renderer.php';

class Ecwid_Integration_Beaver_Builder extends Ecwid_Stub_Renderer {
	protected function _should_apply() {
		return isset( $_GET['fl_builder'] );
	}
}

$__ecwid_integration_beaver_builder = new Ecwid_Integration_Beaver_Builder();