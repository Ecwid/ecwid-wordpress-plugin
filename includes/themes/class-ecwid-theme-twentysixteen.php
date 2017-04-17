<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_2016 extends Ecwid_Theme_Base
{
	protected $name = 'Twenty Sixteen';

	protected $adjust_pb_scroll = true;

	public function __construct()
	{
		parent::__construct();

		if ( Ecwid_Store_Page::is_store_page() ) {
			wp_enqueue_style( 'ecwid-theme', ECWID_PLUGIN_URL . 'css/themes/2016.css', array('twentysixteen-style'), get_option('ecwid_plugin_version') );
		}
		add_action('ecwid_plugin_installed', array($this, 'on_ecwid_plugin_installed'));
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

$ecwid_current_theme = new Ecwid_Theme_2016();