<?php

class Ecwid_Theme_Base {

	const PROP_USE_JS_API_FOR_CATS_NAV_MENU = 'js-api-for-cats-nav-menu';
	const PROP_AJAX_DEFER_RENDERING         = 'ajax-defer-rendering';

	const ACTION_APPLY_THEME = 'ecwid-apply-theme';

	const OPTION_LEGACY_CUSTOM_SCROLLER = 'ec_store_custom_scroller';

	public $has_advanced_layout = false;

	protected $adjust_pb_scroll = false;

	protected $name;

	protected $scroll_indent           = 100;
	protected $scroll_indent_admin_bar = 32;

	protected $has_js     = false;
	protected $has_css    = false;
	protected $css_parent = false;

	public $historyjs_html4mode             = false;
	protected $use_js_api_for_cats_nav_menu = false;

	public static $instance = null;

	public function __construct() {     }

	public static function create( $name, $props ) {

		$theme       = new Ecwid_Theme_Base();
		$theme->name = $name;

		if ( is_admin() ) {
			return;
		}

		if ( array_key_exists( 'scroll', $props ) ) {
			$theme->create_scroller( $props );
		}

		if ( in_array( 'js', $props, true ) ) {
			$theme->add_js();
		}

		if ( in_array( 'css', $props, true ) ) {
			$theme->add_css();
		}

		if ( in_array( 'css-no-parent', $props, true ) ) {
			$theme->add_css( '' );
		}

		if ( in_array( 'historyjs_html4mode', $props, true ) ) {
			$theme->historyjs_html4mode = true;
		}

		if ( in_array( self::PROP_USE_JS_API_FOR_CATS_NAV_MENU, $props, true ) ) {
			add_filter( Ecwid_Nav_Menus::FILTER_USE_JS_API_FOR_CATS_MENU, array( $theme, 'filter_use_js_api_for_cats_menu' ) );
		}

		if ( in_array( self::PROP_AJAX_DEFER_RENDERING, $props, true ) ) {
			add_filter( Ecwid_Ajax_Defer_Renderer::FILTER_ENABLED, '__return_true' );
		}

		if ( in_array( 'title', $props, true ) ) {

			$store_page_params = Ecwid_Store_Page::get_store_page_params();

			if ( @$store_page_params['product_details_show_product_name'] ) {
				add_filter( 'option_' . Ecwid_Store_Page::OPTION_REPLACE_TITLE, '__return_false' );
			}
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
			get_option( 'ecwid_plugin_version' ),
			false
		);
	}

	public function create_scroller( $props ) {
		$this->scroll_indent = $props['scroll'];

		if ( is_admin_bar_showing() ) {
			$this->scroll_indent += $this->scroll_indent_admin_bar;
		}

		add_filter( 'ecwid_inline_js_config', array( $this, 'ecwid_inline_js_config_hook' ) );
	}

	public function ecwid_inline_js_config_hook( $js ) {
		$js .= 'window.ec.config.scroll_indent = ' . $this->scroll_indent . ';';
		return $js;
	}

	protected function add_css( $parent = null ) {

		$name = strtolower( $this->name );
		if ( is_null( $parent ) ) {
			$parent = array( $name . '-style' );
		} elseif ( empty( $parent ) ) {
			$parent = array();
		} else {
			$parent = array( $parent );
		}

		wp_enqueue_style(
			'ecwid-theme-css',
			ECWID_PLUGIN_URL . 'css/themes/' . $name . '.css',
			$parent,
			get_option( 'ecwid_plugin_version' )
		);
	}

}
