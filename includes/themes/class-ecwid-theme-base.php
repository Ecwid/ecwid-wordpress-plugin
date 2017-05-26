<?php

class Ecwid_Theme_Base {

	public $has_advanced_layout = false;

	protected $adjust_pb_scroll = false;

	protected $name;

	protected $has_js = false;
	protected $has_css = false;
	protected $css_parent = false;

	public $historyjs_html4mode = false;

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

		return $theme;
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
		wp_enqueue_script(
			'ecwid-scroller',
			ECWID_PLUGIN_URL . 'js/create_scroller.js' ,
			array( 'jquery' ),
			get_option('ecwid_plugin_version')
		);
	}

	protected function add_css( $parent = null ) {

		if (is_null($parent)) {
			$parent = array( $this->name . '-style' );
		} else if (empty($parent)) {
			$parent = array();
		} else {
			$parent = array( $parent );
		}

		wp_enqueue_style(
			'ecwid-theme-css',
			ECWID_PLUGIN_URL . 'css/themes/' . $this->name . '.css',
			$parent,
			get_option('ecwid_plugin_version')
		);
	}

}