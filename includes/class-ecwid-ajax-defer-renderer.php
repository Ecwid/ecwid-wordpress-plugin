<?php 

class Ecwid_Ajax_Defer_Renderer {
	
	const ALWAYS_ON = 'on';
	const ALWAYS_OFF = 'off';
	const AUTO = 'auto';
	const OPTION_DEFER_RENDERING = 'ecwid_ajax_defer_rendering';
	const FILTER_ENABLED = 'ecwid_enable_defer_rendering';
	
	protected $_already_enabled = false;
	
	protected static $instance = null;
	
	public static function get_instance()
	{
		if (!self::$instance) {
			self::$instance = new Ecwid_Ajax_Defer_Renderer();
		}

		return self::$instance;
	}
		
	protected function __construct()
	{
		add_option( self::OPTION_DEFER_RENDERING, self::AUTO );
		
		add_action( 'template_redirect', array( $this, 'init' ) );// to make sure it is called after ecwid_apply_theme
		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'ecwid_on_plugin_upgrade', array( $this, 'plugin_upgrade' ) ); 
	}
	
	public function init()
	{
		if ( self::is_enabled() && !$this->_already_enabled ) {
			add_filter( 'ecwid_disable_widgets', '__return_true' );
			add_filter( 'ecwid_shortcode_custom_renderer', array( $this, 'get_custom_renderer' ) );
			add_filter( 'the_content', array( $this, 'add_shortcodes' ) );
			$this->_already_enabled = true;
		}
	}

	public function plugin_upgrade()
	{
		$old_option = 'ecwid_defer_rendering';
		
		$value = get_option( $old_option , null );
		
		if ( !is_null( $value ) ) {
			if ( !$value ) {
				update_option( self::OPTION_DEFER_RENDERING, self::ALWAYS_OFF );
			}
			
			delete_option( $old_option );
		}
	}
	
	
	public static function is_enabled()
	{
		$option_value = get_option( self::OPTION_DEFER_RENDERING );
		
		if ( $option_value == self::AUTO ) {
			$filter_results = apply_filters( self::FILTER_ENABLED, false );
			
			return $filter_results;
		} else if ( $option_value == self::ALWAYS_ON ) {
			return true;
		} else {
			return false;
		}
	}
	
	public function get_custom_renderer() {
		return array($this, 'render_shortcode');
	}

	public function render_shortcode($shortcode) {

		if ( $shortcode instanceof Ecwid_Shortcode_Base ) {
			return $shortcode->render_placeholder() . $this->_render_shortcode_script( $shortcode );
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

		$lang = ecwid_get_current_user_locale();
		$lang = apply_filters( 'ecwid_lang', $lang );
		
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
				script.src = 'https://$app_ecwid_com/script.js?$ecwid_store_id&lang=$lang';
				script.id = 'ecwid-script';
				script.setAttribute('data-cfasync', 'false');
		
				document.body.appendChild(script);
				var el = document.getElementById('ecwid-html-catalog-$ecwid_store_id');
				if (el) {
				    el.style.display = 'none';
				}
				if ( typeof Ecwid != 'undefined' ) {
					Ecwid.OnPageLoad.add(function() {
						var catalog = document.getElementById('ecwid-html-catalog-$ecwid_store_id');
						catalog.parentElement.removeChild(catalog);
					});
				}
			} else {
				ecwid_onBodyDone();
			}
		}
</script>
HTML;
		return $before . $content . $after;
	}

	protected function _render_shortcode_script($shortcode) {

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

add_action('init', array('Ecwid_Ajax_Defer_Renderer', 'get_instance'), 0);