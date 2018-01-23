<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_2015 extends Ecwid_Theme_Base
{
	protected $name = 'Twenty Fifteen';

	public function __construct()
	{
		parent::__construct();

		if ( Ecwid_Store_Page::is_store_page() ) {
			wp_enqueue_style( 'ecwid-theme', ECWID_PLUGIN_URL . 'css/themes/2015.css', array('twentyfifteen-style'), get_option('ecwid_plugin_version') );
		}

		add_action( 'ecwid_plugin_installed', array( $this, 'on_ecwid_plugin_installed' ) );
		add_filter( Ecwid_Nav_Menus::FILTER_USE_JS_API_FOR_CATS_MENU, array( $this, 'filter_use_js_api_for_cats_menu' ) );

	}

	public function on_ecwid_plugin_installed()
	{
		$widgets = get_option('sidebars_widgets');

		if (strpos(implode(' ', $widgets['sidebar-1']), 'ecwidstorelink') === false) {
			array_unshift($widgets['sidebar-1'], 'ecwidstorelink-2');
			wp_set_sidebars_widgets($widgets);

			$options = get_option('widget_ecwidstorelink');
			if (!$options) {
				$options = array(
					2 => array(
						'label' => __('Shop', 'ecwid-shopping-cart')
					),
					'_multiwidget' => 1
				);
			} else {
				$options[2] = array(
					'label' => __('Shop', 'ecwid-shopping-cart')
				);
			}

			update_option('widget_ecwidstorelink', $options);
		}
	}
}

$ecwid_current_theme = new Ecwid_Theme_2015();