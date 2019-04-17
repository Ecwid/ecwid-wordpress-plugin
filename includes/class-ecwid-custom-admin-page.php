<?php

class Ecwid_Custom_Admin_Page {
	const TAB_NAME = 'ecwid';

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

	public function themes_install_init_tab() {
		$tab_name = self::TAB_NAME;

		$iframe_src = ecwid_get_iframe_src( time(), 'apps:view=app&name=templatemonster-themes' );
		$iframe_src .= '&hide_profile_header=true';

		$tab_content = '<div style="overflow:hidden;">';
		$tab_content .= $this->get_iframe_html( $iframe_src );
		$tab_content .= '</div>';

		$link_html = '<li><a href="#" data-sort="' . $tab_name . '">' . __('Themes for Ecwid', 'ecwid-shopping-cart') . '</a></li>';

		$is_show_tab = (int)(isset($_REQUEST['browse']) && $_REQUEST['browse'] == $tab_name);

		$content = <<<HTML
			<script type="text/javascript">//<![CDATA[
				function ecwid_switch_theme_tab( sort ){
					if( sort == '$tab_name' ) {
						if( jQuery('#ecwid-frame').length == 0 ) {
							jQuery('.theme-browser').before('$tab_content');
							jQuery('#ecwid-frame').css({'margin-top': '-70px'});
						}

						jQuery('#ecwid-frame').show();
						jQuery('.filter-count, .button.drawer-toggle, .search-form, .theme-browser .themes').hide();
					} else {
						jQuery('#ecwid-frame').hide();
						jQuery('.filter-count, .button.drawer-toggle, .search-form, .theme-browser .themes').show();
					}
				}

				jQuery(document).ready(function(){
					jQuery('.filter-links').append('$link_html');

					jQuery(document).on('click', '.filter-links li > a', function(){
						ecwid_switch_theme_tab( jQuery(this).data('sort') );
					});
		HTML;

		if ( isset( $_REQUEST['browse'] ) && $_REQUEST['browse'] == $tab_name ) {
			$content .=  <<<HTML
				ecwid_switch_theme_tab('$tab_name');
			HTML;
		}

		$content .= <<<HTML
				});
				//]]>
			</script>
		HTML;

		echo $content;
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