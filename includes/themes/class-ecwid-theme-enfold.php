<?php
require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';
require_once ECWID_PLUGIN_DIR . '/includes/class-ecwid-stub-renderer.php';

class Ecwid_Theme_Enfold extends Ecwid_Theme_Base
{
	public function __construct()
	{
		parent::__construct();

		$__ecwid_integration_enfold_builder = new Ecwid_Integration_Enfold_Builder();
	}
}

class Ecwid_Integration_Enfold_Builder extends Ecwid_Stub_Renderer
{
	protected function _should_apply() {
		return isset($_REQUEST['avia_request']);
	}
}

return new Ecwid_Theme_Enfold();