<?php

require_once ECWID_PLUGIN_DIR . 'includes/integrations/class-ecwid-integration-cache-base.php';

class Ecwid_Integration_WPRocket extends Ecwid_Integration_Cache_Base {

	public function __construct() {
		add_filter( 'rocket_excluded_inline_js_content', array( $this, 'hook_excluded_inline_js' ) );
		add_filter( 'rocket_exclude_defer_js', array( $this, 'hook_excluded_defer_js' ) );
		add_filter( 'rocket_delay_js_exclusions', array( $this, 'hook_delay_js_exclusions' ) );

		parent::__construct();
	}

	public function hook_excluded_inline_js( $excluded_js ) {
		$excluded_js = array_merge( $excluded_js, $this->get_excluded_js() );

		return $excluded_js;
	}

	public function hook_excluded_defer_js( $excluded_js ) {
		$excluded_js[] = Ecwid_Config::get_scriptjs_domain();

		return $excluded_js;
	}

	public function hook_delay_js_exclusions( $excluded_js ) {
		$excluded_js = array_merge( $excluded_js, $this->get_excluded_js() );

		return $excluded_js;
	}

	public function clear_external_cache() {
		error_log( 'clear_external_cache' );
		if ( function_exists( 'rocket_clean_domain' ) ) {
			rocket_clean_domain();
		}
	}
}

$ecwid_integration_wprocket = new Ecwid_Integration_WPRocket();
