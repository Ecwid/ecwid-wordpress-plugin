<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';
require_once ECWID_PLUGIN_DIR . '/includes/class-ecwid-ajax-defer-renderer.php';

class Ecwid_Theme_Trend extends Ecwid_Theme_Base
{
	protected $name = 'Trend';

	protected $shortcodes = array();

	public function __construct()
	{
		parent::__construct();

		if ( get_option('ecwid_defer_rendering') ) {
			update_option( Ecwid_Ajax_Defer_Renderer::OPTION_DEFER_RENDERING, true );
			delete_option( 'ecwid_defer_rendering' );
		}
		
		if ( !get_option( Ecwid_Ajax_Defer_Renderer::is_enabled() ) ) {
			return;
		}

		// That actually means that ajax loading is disabled. Really ambigious naming
		if (class_exists('BW') && method_exists('BW', 'get_option') && !@BW::get_option('disable_ajax_loading')) {
			return;
		}

		add_filter('ecwid_disable_widgets', '__return_true');
		add_filter('ecwid_shortcode_custom_renderer', array($this, 'get_custom_renderer'));
		add_filter('the_content', array($this, 'add_shortcodes'));
	}

	public function get_custom_renderer() {
		return array($this, 'render_shortcode');
	}

	public function render_shortcode($shortcode) {

		if ($shortcode instanceof Ecwid_Shortcode_Base) {
			return $shortcode->render_placeholder() . $this->_render_shortcode_script($shortcode);
		}

		return '';
	}

	public function add_shortcodes($content) {
		$ecwid_store_id = get_ecwid_store_id();
		$before = <<<HTML
<script>
ecwid_shortcodes = [];
</script>
HTML;

		$app_ecwid_com = Ecwid_Config::get_scriptjs_domain();

		$after = <<<HTML
<script>
		window.ecwid_script_defer = true;
		window.ecwid_dynamic_widgets = true;

		if (typeof Ecwid != 'undefined' && Ecwid.destroy) Ecwid.destroy();

if (typeof ecwid_shortcodes != 'undefined') {
			window._xnext_initialization_scripts = ecwid_shortcodes;

			if (!document.getElementById('ecwid-script')) {
				var script = document.createElement('script');
				script.charset = 'utf-8';
				script.type = 'text/javascript';
				script.src = 'https://$app_ecwid_com/script.js?$ecwid_store_id';
				script.id = 'ecwid-script'
		
				document.body.appendChild(script);
				
				var catalog = document.getElementById('ecwid-html-catalog-$ecwid_store_id');
				catalog.parentElement.removeChild(catalog);
			} else {
			ecwid_onBodyDone();
		}

}
</script>
HTML;
		return $before . $content . $after;
	}

	protected function _render_shortcode_script(Ecwid_Shortcode_Base $shortcode) {

		$args = $shortcode->build_params_string();
		$id = $shortcode->get_html_id();
		$widgetType = substr($shortcode->get_ecwid_widget_function_name(), 1);
		if ($widgetType == 'Search') {
			$widgetType = 'SearchWidget';
		}
		$store_id = get_ecwid_store_id();

		$code = <<<HTML
<script type="text/javascript">
ecwid_shortcodes[ecwid_shortcodes.length] = {
	widgetType: '$widgetType',
	id: '$id',
	arg: [$args]
};
</script>
HTML;
		return $code;

	}
}

return new Ecwid_Theme_Trend();