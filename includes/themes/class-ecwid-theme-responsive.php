<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_Responsive extends Ecwid_Theme_Base
{
	public $has_advanced_layout = true;

	public function __construct()
	{
		parent::__construct();

		if (!is_admin()) {

			if ( $this->need_advanced_layout() ) {
				wp_enqueue_style( 'ecwid-theme-adjustments' , ECWID_PLUGIN_URL . 'css/themes/responsive-adjustments.css', array(), get_option('ecwid_plugin_version'), 'all' );
				wp_enqueue_script( 'ecwid-theme', ECWID_PLUGIN_URL . 'js/themes/responsive.js', array( 'jquery' ), get_option('ecwid_plugin_version'), true );

				add_filter( 'ecwid_minicart_shortcode_content', array( $this, 'minicart_shortcode_content' ) );
				add_filter( 'ecwid_search_shortcode_content', array( $this, 'search_shortcode_content' ) );
			}

			wp_enqueue_style( 'ecwid-open-sans' , 'https://fonts.googleapis.com/css?family=Open+Sans:400,700&subset=latin,cyrillic-ext,cyrillic,greek-ext,vietnamese,greek,latin-ext');
			wp_enqueue_style( 'dashicons' );
			wp_enqueue_style( 'ecwid-theme-fixes' , ECWID_PLUGIN_URL . 'css/themes/responsive.css', array(), get_option('ecwid_plugin_version'), 'all' );

			add_filter('body_class', array($this, 'body_class'));

		} else {

			add_action('ecwid_store_page_created', array($this, 'on_create_store_page'));
			add_action('switch_theme', array($this, 'switch_theme'));

		}

	}

	public function switch_theme()
	{

	}

	public function minicart_shortcode_content( $content )
	{

		if ( Ecwid_Store_Page::is_store_page() ) {
			$content = '<script data-cfasync="false" type="text/javascript"> xMinicart("style=","layout=Mini"); </script>';
		}

		return $content;
	}

	public function search_shortcode_content( $content ) {

		$content .= '<script data-cfasync="false" type="text/javascript">jQuery(document.body).addClass("ecwid-with-search"); </script>';

		return $content;
	}

	public function body_class($classes)
	{
		if (get_option('ecwid_show_search_box')) {
			$classes[] = 'ecwid-with-search';
		}

		return $classes;
	}

	public function on_create_store_page($page_id)
	{
		update_post_meta($page_id, '_wp_page_template', 'full-width-page.php');
	}
	
	protected function need_advanced_layout()
 	{
 		return get_option('ecwid_advanced_theme_layout') == 'Y';
	}

}

return new Ecwid_Theme_Responsive();