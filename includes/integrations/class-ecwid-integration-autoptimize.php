<?php
require_once ECWID_PLUGIN_DIR . 'includes/integrations/class-ecwid-integration-cache-base.php';

class Ecwid_Integration_Autoptimize extends Ecwid_Integration_Cache_Base {

	public function __construct() {
		add_filter( 'autoptimize_filter_js_exclude', array( $this, 'hook_js_exclude' ) );

		parent::__construct();
	}

	public function hook_js_exclude( $exclude ) {
		$code = implode( ', ', $this->get_excluded_js() );

		return $exclude . ', ' . $code;
	}

	public function clear_external_cache() {
		if ( class_exists( 'autoptimizeCache' ) ) {
			autoptimizeCache::clearall_actionless();
		}
	}

	public function clear_external_cache_for_page( $page_id ) {}
}

$ecwid_integration_autoptimize = new Ecwid_Integration_Autoptimize();
