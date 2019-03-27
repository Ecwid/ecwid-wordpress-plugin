<?php

require_once ECWID_PLUGIN_DIR . '/includes/class-ecwid-stub-renderer.php';

class Ecwid_Theme_Enfold_Stub_Renderer extends Ecwid_Stub_Renderer {
	protected function _should_apply() {
		return isset($_REQUEST['avia_request']) && isset($_REQUEST['text']) && has_shortcode($_REQUEST['text'], 'ecwid');
	}
}

return new Ecwid_Theme_Enfold_Stub_Renderer();