<?php

require_once ECWID_PLUGIN_DIR . '/includes/class-ecwid-stub-renderer.php';

class Ecwid_Theme_Enfold_Stub_Renderer extends Ecwid_Stub_Renderer {

	public $historyjs_html4mode = false;

	protected function _should_apply() {
		return isset($_REQUEST['avia_request']);
	}
}

return new Ecwid_Theme_Enfold_Stub_Renderer();