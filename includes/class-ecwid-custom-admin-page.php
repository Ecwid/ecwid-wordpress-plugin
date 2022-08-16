<?php

class Ecwid_Custom_Admin_Page {
	const TAB_NAME = 'ec-apps';

	public function __construct() {
		if ( Ecwid_Api_V3::get_token() && ! Ecwid_Config::is_wl() ) {
			add_action( 'current_screen', array( $this, 'init' ) );
		}
	}

	public function init( $current_screen ) {

		if ( $current_screen->id === 'plugin-install' ) {
			add_filter( 'install_plugins_tabs', array( $this, 'plugin_install_init_tab' ), 10, 1 );
			add_action( 'install_plugins_' . self::TAB_NAME, array( $this, 'plugin_install_render_tab' ), 10, 1 );
		}

		if ( $current_screen->id === 'theme-install' ) {
			add_action( 'install_themes_tabs', array( $this, 'themes_install_init_tab' ) );
			add_action( 'wp_ajax_query-themes', array( $this, 'themes_install_ajax' ), 1 );
		}

	}

	public function plugin_install_init_tab( $tabs ) {
		$tabs[ self::TAB_NAME ] = __( 'Plugins for Ecwid', 'ecwid-shopping-cart' );
		return $tabs;
	}

	public function plugin_install_render_tab( $paged ) {
		$iframe_src  = ecwid_get_iframe_src( time(), 'appmarket' );
		$iframe_src .= '&hide_profile_header=true';

		?>
		<script type='text/javascript'>
			jQuery(document).ready(function() {
				jQuery('.search-form.search-plugins').hide();
			});
		</script>
		<p></p>
		<iframe seamless id="ecwid-frame" frameborder="0" width="100%" height="700" scrolling="no" src="<?php echo esc_url( $iframe_src ); ?>"></iframe>
		<?php
	}

	public function themes_install_init_tab( $tabs ) {

		$tab_content = sprintf(
			/* translators: %s: affiliate link */
			__(
				'Ecwid is compatible with any WordPress theme. Be it a free theme from WordPress.org catalog, a premium theme by a third-party vendor or a custom-made theme, your Ecwid store will work good with it. If you want a premium theme, we recommend <a href="%s">TemplateMonster themes</a>',
				'ecwid-shopping-cart'
			),
			'https://www.templatemonster.com/ecwid-ready-wordpress-themes/?aff=Ecwid'
		);
		?>

		<script type='text/javascript'>
			function ecwid_switch_theme_tab( sort ){
				if( sort == '<?php echo esc_js( self::TAB_NAME ); ?>' ) {
					if( jQuery('#ec-theme-tab').length == 0 ) {
						jQuery('.theme-browser').before('<div id="ec-theme-tab"><?php echo wp_kses_post( $tab_content ); ?></div>');
					}

					jQuery('#ec-theme-tab').show();
					jQuery('.filter-count, .button.drawer-toggle, .search-form, .theme-browser, .no-themes').hide();
				} else {
					jQuery('#ec-theme-tab').hide();
					jQuery('.theme-browser').removeAttr('style');
					jQuery('.filter-count, .button.drawer-toggle, .search-form').show();
				}
			}

			jQuery(document).ready(function(){
				jQuery('.filter-links').append('<li><a href="#" data-sort="<?php echo esc_js( self::TAB_NAME ); ?>"><?php echo esc_html__( 'Themes for Ecwid', 'ecwid-shopping-cart' ); ?></a></li>');

				jQuery(document).on('click', '.filter-links li a', function(){
					ecwid_switch_theme_tab( jQuery(this).data('sort') );
				});

				<?php
				if ( isset( $_GET['browse'] ) && $_GET['browse'] === self::TAB_NAME ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
					?>
					ecwid_switch_theme_tab('<?php echo esc_js( self::TAB_NAME ); ?>');
					<?php
				}
				?>
			});
		</script>
		<?php

		return $tabs;
	}

	public function themes_install_ajax() {
		if ( isset( $_REQUEST['request']['browse'] ) && $_REQUEST['request']['browse'] === self::TAB_NAME ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$themes_data = array(
				'data' => array(
					'info' => array(
						'page'    => 1,
						'pages'   => 1,
						'results' => 0,
					),
				),
			);
			wp_send_json_success( $themes_data );
		}
	}
}

$ecwid_custom_admin_page = new Ecwid_Custom_Admin_Page();
