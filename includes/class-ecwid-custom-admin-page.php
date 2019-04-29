<?php

class Ecwid_Custom_Admin_Page {
	const TAB_NAME = 'ec-apps';

	public function __construct() {		
		if( Ecwid_Api_V3::get_token() && !Ecwid_Config::is_wl() ) {
			add_action( 'current_screen', array( $this, 'init' ) );
		}
	}

	public function init( $current_screen ) {
		
		if( $current_screen->id == 'plugin-install' ){
			add_filter( 'install_plugins_tabs', array( $this, 'plugin_install_init_tab'), 10, 1 );
			add_action( 'install_plugins_' . self::TAB_NAME, array( $this, 'plugin_install_render_tab'), 10, 1 );
		}

		if( $current_screen->id == 'theme-install' ){
			add_action( 'install_themes_tabs', array( $this, 'themes_install_init_tab') );
			add_action( 'wp_ajax_query-themes', array( $this, 'themes_install_ajax'), 1 );
		}

	}

	public function get_iframe_html( $iframe_src ) {
		$html = '<iframe seamless id="ecwid-frame" frameborder="0" width="100%" height="700" scrolling="no" src="' . $iframe_src . '"></iframe>';

		return $html;
	}

	public function plugin_install_init_tab( $tabs ) {
		$tabs[ self::TAB_NAME ] = __('Plugins for Ecwid', 'ecwid-shopping-cart');
		return $tabs;
	}

	public function plugin_install_render_tab( $paged ) {
		$iframe_src = ecwid_get_iframe_src( time(), 'appmarket' );
		$iframe_src .= '&hide_profile_header=true';

		echo <<<HTML
			<script type='text/javascript'>//<![CDATA[
				jQuery(document).ready(function() {
					jQuery('.search-form.search-plugins').hide();
				});
				//]]>
			</script>
			<p></p>
HTML;
		echo $this->get_iframe_html( $iframe_src );
	}

	public function themes_install_init_tab( $tabs ) {
		$iframe_src = ecwid_get_iframe_src( time(), 'apps:view=app&name=templatemonster-themes' );
		$iframe_src .= '&hide_profile_header=true';

		$tab_content = sprintf(
			__(
				'Ecwid is compatible with any WordPress theme. Be it a free theme from WordPress.org catalog, a premium theme by a third-party vendor or a custom-made theme, your Ecwid store will work good with it. If you want a premium theme, we recommend <a href="%s">TemplateMonster themes</a>'
				, 'ecwid-shopping-cart'
			), 
			'https://www.templatemonster.com/ecwid-ready-wordpress-themes/?aff=Ecwid'
		);
		$tab_content = sprintf( '<div id="ec-theme-tab">%s</div>', $tab_content );

		$link_html = sprintf( '<li><a href="#" data-sort="%s">%s</a></li>', self::TAB_NAME, __('Themes for Ecwid', 'ecwid-shopping-cart') );

		if ( isset( $_REQUEST['browse'] ) && $_REQUEST['browse'] == self::TAB_NAME ) {
			$init_script =  sprintf( 'ecwid_switch_theme_tab("%s");', self::TAB_NAME );
		} else {
			$init_script = '';
		}

		$content = <<<HTML
			<script type="text/javascript">//<![CDATA[
				function ecwid_switch_theme_tab( sort ){
					if( sort == '%s' ) {
						if( jQuery('#ec-theme-tab').length == 0 ) {
							jQuery('.theme-browser').before('%s');
							// jQuery('#ecwid-frame').css({'margin-top': '-70px'});
						}

						jQuery('#ec-theme-tab').show();
						jQuery('.filter-count, .button.drawer-toggle, .search-form, .theme-browser').hide();
					} else {
						jQuery('#ec-theme-tab').hide();
						jQuery('.theme-browser').removeAttr('style');
						jQuery('.filter-count, .button.drawer-toggle, .search-form').show();
					}
				}

				jQuery(document).ready(function(){
					jQuery('.filter-links').append('%s');

					jQuery(document).on('click', '.filter-links li > a', function(){
						ecwid_switch_theme_tab( jQuery(this).data('sort') );
					});

					%s
				});
				//]]>
			</script>
HTML;

		echo sprintf( $content, self::TAB_NAME, $tab_content, $link_html, $init_script );

		return $tabs;
	}

	public function themes_install_ajax() {
		if( $_REQUEST['request']['browse'] == self::TAB_NAME ) {
			$themes_data = array(
				"data" => array(
					"info" => array( "page" => 1, "pages" => 1, "results" => 0 ),
				)
			);
			wp_send_json_success( $themes_data );
		}
	}
}

$ecwid_custom_admin_page = new Ecwid_Custom_Admin_Page();