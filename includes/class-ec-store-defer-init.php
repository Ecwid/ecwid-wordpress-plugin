<?php
class Ec_Store_Defer_Init {

	const OPTION_DEFER_STORE_INIT = 'ecwid_defer_store_init';

	public function __construct() {
		add_action( 'wp_footer', array( $this, 'defer_script_js_load' ) );
	}

	public static function is_enabled() {
		if ( get_option( self::OPTION_DEFER_STORE_INIT, '' ) === 'off' ) {
			return false;
		}
		return true;
	}

	public function defer_script_js_load() {
		$widgets = apply_filters( 'ecwid_defer_widgets', array() );

		$ecwid_store_id = get_ecwid_store_id();
		$app_ecwid_com  = Ecwid_Config::get_scriptjs_domain();

		$lang = ecwid_get_current_user_locale();
		$lang = apply_filters( 'ecwid_lang', $lang );

		$script_src = "https://$app_ecwid_com/script.js?$ecwid_store_id&lang=$lang";
		?>
		<script data-cfasync="false" type="text/javascript">
			(function () {
				var ec_widgets = <?php echo json_encode( $widgets ); ?>

				window.ecwid_script_defer = true;
				window._xnext_initialization_scripts = window._xnext_initialization_scripts || [];
				window._xnext_initialization_scripts.push(...ec_widgets);

				var ecwid_lazy_scriptjs_load = function() {
					var script = document.createElement('script');
					script.charset = 'utf-8';
					script.type = 'text/javascript';
					script.src = '<?php echo esc_attr( $script_src ); ?>';
					script.id = 'ecwid-script';
					script.setAttribute('data-cfasync', 'false');

					document.body.appendChild(script);

					script.addEventListener('load', function() {
						Ecwid.init();
					});
				}

				document.addEventListener('mousemove', function(){ 
					ecwid_lazy_scriptjs_load();
				});
			})();
		</script>
		<?php
	}

	public static function print_js_widget( $widget_type, $id, $arg = array() ) {

		if ( self::is_enabled() ) {
			$widget_type = preg_replace( '/^x([a-z0-9]+)/i', '$1', $widget_type );

			if ( $widget_type === 'Search' ) {
				$widget_type = 'SearchWidget';
			}
		}

		if ( self::is_enabled() ) {
			?>
			<!-- noptimize -->
			<script data-cfasync="false" type="text/javascript">
			window._xnext_initialization_scripts = window._xnext_initialization_scripts || [];
			window._xnext_initialization_scripts.push({widgetType: '<?php echo esc_js( $widget_type ); ?>', id: '<?php echo esc_js( $id ); ?>', arg: ["style="]});
			</script>
			<!-- noptimize -->
			<?php
		} else {
			?>
			<!-- noptimize --><script data-cfasync="false" type="text/javascript"><?php echo esc_js( $widget_type ); ?></script><!-- noptimize -->
			<?php
		}
	}

}

$ec_store_defer_init = new Ec_Store_Defer_Init();
