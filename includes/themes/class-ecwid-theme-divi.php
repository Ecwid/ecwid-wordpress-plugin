<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Divi extends Ecwid_Theme_Base {

	protected $name = 'Divi';

	public function __construct() {
		parent::__construct();

		add_filter( Ecwid_Nav_Menus::FILTER_USE_JS_API_FOR_CATS_MENU, array( $this, 'filter_use_js_api_for_cats_menu' ) );

		if ( $this->is_wireframe_view() ) {
			add_filter( 'ecwid_scriptjs_code', '__return_false' );
		}

		if ( $this->is_visual_view() ) {
			remove_all_filters( 'ecwid_inline_js_config' );
		}

		if ( isset( $_REQUEST['page_id'] ) && $this->is_visual_view() ) {
			add_action( 'wp_footer', array( $this, 'add_scriptjs_code' ) );
		}

		add_filter( 'single_post_title', array( $this, 'single_post_title' ), 10000, 2 );

		add_action( 'plugins_loaded', array( $this, 'init_builder_integration' ) );
	}

	public function init_builder_integration() {
		require_once ECWID_PLUGIN_DIR . 'includes/integrations/class-ecwid-integration-divibuilder.php';
	}

	public function is_wireframe_view() {
		return isset( $_REQUEST['et_bfb'] );
	}

	public function is_visual_view() {
		return isset( $_REQUEST['et_fb'] ) && ! isset( $_REQUEST['et_bfb'] );
	}

	public function add_scriptjs_code() {
		echo ecwid_get_scriptjs_code(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	public function single_post_title( $post_title, $post ) {
		$ecwid_title = _ecwid_get_seo_title();
		if ( ! empty( $ecwid_title ) ) {
			return $ecwid_title;
		}

		return $post_title;
	}
}

return new Ecwid_Divi();
