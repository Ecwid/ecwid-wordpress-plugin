<?php

abstract class Ecwid_Integration_Cache_Base {

	public function __construct() {
		add_action( 'ecwid_clean_external_cache', array( $this, 'clear_external_cache' ), 10000 );
	}

	abstract public function clear_external_cache();

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
