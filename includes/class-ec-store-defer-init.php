<?php
class Ec_Store_Defer_Init {

	const OPTION_NAME = 'ecwid_defer_store_init_enabled';

	const OPTION_VALUE_ENABLED  = 'Y';
	const OPTION_VALUE_DISABLED = 'N';
	const OPTION_VALUE_AUTO     = '';

	public function __construct() {
		if ( ! is_admin() ) {
			add_action( 'wp_footer', 'Ec_Store_Defer_Init::defer_script_js_load' );
		}
	}

	public static function is_enabled() {
		if ( array_key_exists( 'ec-enable-defer-store-init', $_GET ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return true;
		}

		if ( get_option( self::OPTION_NAME, self::OPTION_VALUE_ENABLED ) === self::OPTION_VALUE_DISABLED ) {
			return false;
		}

		return apply_filters( 'ecwid_is_defer_store_init_enabled', true );
	}

	public static function defer_script_js_load() {
		if ( ! self::is_enabled() ) {
			return false;
		}

		$hide_defer_load_script = apply_filters( 'ecwid_hide_defer_load_script', false );
		if ( $hide_defer_load_script ) {
			return false;
		}

		$widgets = apply_filters( 'ecwid_defer_widgets', array() );

		$ecwid_store_id = get_ecwid_store_id();
		$app_ecwid_com  = Ecwid_Config::get_scriptjs_domain();

		$lang = ecwid_get_current_user_locale();
		$lang = apply_filters( 'ecwid_lang', $lang );

		$script_src = "https://$app_ecwid_com/script.js?$ecwid_store_id&lang=$lang";
		?>
		<script data-cfasync="false" type="text/javascript">
			(function () {
				var ec_widgets = <?php echo wp_json_encode( $widgets ); ?>

				window.ecwid_script_defer = true;
				window._xnext_initialization_scripts = window._xnext_initialization_scripts || [];
				window._xnext_initialization_scripts.push(...ec_widgets);

				var ecwidLazyScriptjsLoad = function() {
					if ( ! document.getElementById( 'ecwid-script' ) ) {
						var script = document.createElement('script');
						script.charset = 'utf-8';
						script.type = 'text/javascript';
						script.src = '<?php echo $script_src; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>';
						script.id = 'ecwid-script';
						script.setAttribute('data-cfasync', 'false');

						document.body.appendChild(script);

						script.addEventListener('load', function() {
							var nodes = document.getElementsByClassName('ec-cart-widget')
							if (nodes.length > 0) {
								Ecwid.init();
							}

							if( typeof ecwidSaveDynamicCss !== 'undefined' ) {
								ecwidSaveDynamicCss();
							}

							if( !window.needLoadEcwidAsync && typeof Ecwid._onComplete !== undefined ) {
								Ecwid._onComplete();
							}
						});
					}
				}

				var isTouchDevice = false;
				var documentEventsForLazyLoading = ['mousedown', 'mouseup', 'mousemove', 'contextmenu', 'keydown', 'keyup'];
				var documentTouchEventsForLazyLoading = ['touchstart', 'touchend', 'touchcancel', 'touchmove'];

				if (!!('ontouchstart' in window)) {
					isTouchDevice = true;
				}

				var toggleEvent = function (el, add, event) {
					if (add) {
						el.addEventListener(event, ecwidLazyScriptjsLoad);
					} else {
						el.removeEventListener(event, ecwidLazyScriptjsLoad);
					}
				}

				if (isTouchDevice) {
					documentTouchEventsForLazyLoading.forEach(
						function applyEvent(event) {
							toggleEvent(document, true, event);
						}
					);
				} else {
					documentEventsForLazyLoading.forEach(
						function applyEvent(event) {
							toggleEvent(document, true, event);
						}
					);
				}
			})();
		</script>
		<?php
	}

	public static function print_js_widget( $widget_type, $id, $arg = '' ) {
		ob_start();

		if ( self::is_enabled() ) {
			$widget_type = preg_replace( '/^x([a-z0-9]+)/i', '$1', $widget_type );

			if ( $widget_type === 'Search' ) {
				$widget_type = 'SearchWidget';
			}
			?>
			<!--noptimize-->
			<script data-cfasync="false" data-no-optimize="1" type="text/javascript">
			window._xnext_initialization_scripts = window._xnext_initialization_scripts || [];
			window._xnext_initialization_scripts.push({widgetType: '<?php echo esc_js( $widget_type ); ?>', id: '<?php echo esc_js( $id ); ?>', arg: [<?php echo $arg;//phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>]});
			</script>
			<!--/noptimize-->
			<?php
		} else {
			?>
			<!--noptimize--><script data-cfasync="false" data-no-optimize="1" type="text/javascript"><?php echo esc_js( $widget_type ); ?>();</script><!--/noptimize-->
			<?php
		}

		return ob_get_clean();
	}

}

$ec_store_defer_init = new Ec_Store_Defer_Init();
