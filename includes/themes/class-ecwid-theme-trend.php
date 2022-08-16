<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';
require_once ECWID_PLUGIN_DIR . '/includes/class-ecwid-ajax-defer-renderer.php';

class Ecwid_Theme_Trend extends Ecwid_Theme_Base {

	protected $name = 'Trend';

	protected $shortcodes = array();

	public function __construct() {
		parent::__construct();

		if ( get_option( 'ecwid_defer_rendering' ) ) {
			update_option( Ecwid_Ajax_Defer_Renderer::OPTION_DEFER_RENDERING, true );
			delete_option( 'ecwid_defer_rendering' );
		}

		if ( ! get_option( Ecwid_Ajax_Defer_Renderer::is_enabled() ) ) {
			return;
		}

		// That actually means that ajax loading is disabled. Really ambigious naming
		if ( class_exists( 'BW' ) && method_exists( 'BW', 'get_option' ) && ! @BW::get_option( 'disable_ajax_loading' ) ) {
			return;
		}

		add_filter( Ecwid_Ajax_Defer_Renderer::FILTER_ENABLED, '__return_true' );
	}
}

return new Ecwid_Theme_Trend();
