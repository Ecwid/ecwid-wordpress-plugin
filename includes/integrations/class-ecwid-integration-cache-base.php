<?php

abstract class Ecwid_Integration_Cache_Base {

	public function __construct() {
		add_action( 'ecwid_page_clear_cache', array( $this, 'clear_cache' ) );
	}

	abstract public function clear_cache( $page_id = 0 );

	public static function get_excluded_js() {
		$excluded_js = array(
			'window.ec.',
			'window.ecwid_script_defer',
			'window._xnext_initialization_scripts ',
			'EcStaticPageUtils',
			'createClass',
			'xSearch',
			'xSingleProduct',
			'xProduct',
			'xProductBrowser',
			'xCategoriesV2',
			'xMinicart',
			'ecwid_html',
			'ecwid_body',
			'ecwid',
			'jquery',
			Ecwid_Config::get_scriptjs_domain(),
		);

		if ( class_exists( 'Ecwid_Static_Page' ) ) {
			$excluded_js[] = Ecwid_Static_Page::HANDLE_STATIC_PAGE . '.js';
		}

		return $excluded_js;
	}
}
