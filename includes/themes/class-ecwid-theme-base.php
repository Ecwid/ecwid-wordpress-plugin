<?php

class Ecwid_Theme_Base {

	const PROP_USE_JS_API_FOR_CATS_NAV_MENU = 'js-api-for-cats-nav-menu';
	const PROP_AJAX_DEFER_RENDERING = 'ajax-defer-rendering';
	
	const ACTION_APPLY_THEME = 'ecwid-apply-theme';
	
	const OPTION_LEGACY_CUSTOM_SCROLLER = 'ec_store_custom_scroller';
	
	public $has_advanced_layout = false;

	protected $adjust_pb_scroll = false;

	protected $name;

	protected $has_js = false;
	protected $has_css = false;
	protected $css_parent = false;

	public $historyjs_html4mode = false;
	protected $use_js_api_for_cats_nav_menu = false;

	public static $instance = null;

	public function __construct()
	{
	}

	public static function create($name, $props) {

		$theme = new Ecwid_Theme_Base();
		$theme->name = $name;

		if (is_admin()) return;

		if ( in_array( 'scroll', $props ) ) {
			$theme->create_scroller();
		}

		if ( in_array( 'js', $props ) ) {
			$theme->add_js();
		}

		if ( in_array( 'css', $props ) ) {
			$theme->add_css();
		}

		if ( in_array( 'css-no-parent', $props ) ) {
			$theme->add_css( '' );
		}

		if (in_array( 'historyjs_html4mode', $props ) ) {
			$theme->historyjs_html4mode = true;
		}

		if (in_array( self::PROP_USE_JS_API_FOR_CATS_NAV_MENU, $props ) ) {
			add_filter( Ecwid_Nav_Menus::FILTER_USE_JS_API_FOR_CATS_MENU, array( $theme, 'filter_use_js_api_for_cats_menu' ) );
		}
		
		if ( in_array( self::PROP_AJAX_DEFER_RENDERING, $props ) ) {
			add_filter( Ecwid_Ajax_Defer_Renderer::FILTER_ENABLED, '__return_true' );
		}

		return $theme;
	}

	public function filter_use_js_api_for_cats_menu( $value ) {
		return Ecwid_Nav_Menus::OPTVAL_USE_JS_API_FOR_CATS_MENU_TRUE;
	}
	
	protected function add_js() {
		wp_enqueue_script(
			'ecwid-theme-js',
			ECWID_PLUGIN_URL . 'js/themes/' . $this->name . '.js',
			array( 'jquery' ),
			get_option('ecwid_plugin_version')
		);
	}

	protected function create_scroller() {
		
		if ( get_option( self::OPTION_LEGACY_CUSTOM_SCROLLER, false ) ) {
			wp_enqueue_script(
				'ecwid-scroller',
				ECWID_PLUGIN_URL . 'js/create_scroller.js' ,
				array( 'jquery' ),
				get_option('ecwid_plugin_version')
			);
		}
	}

	protected function add_css( $parent = null ) {

		$name = strtolower( $this->name );
		if (is_null($parent)) {
			$parent = array( $name . '-style' );
		} else if (empty($parent)) {
			$parent = array();
		} else {
			$parent = array( $parent );
		}
		
		wp_enqueue_style(
			'ecwid-theme-css',
			ECWID_PLUGIN_URL . 'css/themes/' . $name . '.css',
			$parent,
			get_option('ecwid_plugin_version')
		);
	}

}