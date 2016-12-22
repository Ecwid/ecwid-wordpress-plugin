<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_2017 extends Ecwid_Theme_Base
{
	protected $name = 'Twenty Seventeen';

	public function __construct()
	{
		parent::__construct();
		wp_enqueue_style( 'ecwid-theme', ECWID_PLUGIN_URL . 'css/themes/2017.css', array('twentyseventeen-style'), get_option('ecwid_plugin_version') );
		add_action( 'ecwid_plugin_installed', array( $this, 'on_ecwid_plugin_installed' ) );
		add_action( 'ecwid_chameleon_settings', array( $this, 'chameleon_settings' ) );
	}

	public function chameleon_settings( $chameleon ) {
		if ( get_theme_mod( 'colorscheme', 'light' ) == 'dark' && $chameleon['colors'] == '"auto"') {
			$chameleon['colors'] = json_encode(
				array(
					'color-background' => '#222',
	                'color-foreground' => '#fff',
	                'color-link' => '#fff',
	                'color-button' => '#888',
	                'color-price' => '#ddd'
				)
			);
		}

		return $chameleon;
	}

	public function on_ecwid_plugin_installed()
	{
		$widgets = get_option('sidebars_widgets');

		if ( strpos( implode( ' ', $widgets['sidebar-1'] ), 'ecwidstorelink' ) === false ) {

			array_unshift( $widgets['sidebar-1'], 'ecwidstorelink-2' );
			wp_set_sidebars_widgets( $widgets );

			$options = get_option( 'widget_ecwidstorelink' );

			if (!$options) {
				$options = array(
					2 => array(
						'label' => __( 'Shop', 'ecwid-shopping-cart' )
					),
					'_multiwidget' => 1
				);
			} else {
				$options[2] = array(
					'label' => __( 'Shop', 'ecwid-shopping-cart' )
				);
			}

			update_option( 'widget_ecwidstorelink', $options );
		}
	}
}

$ecwid_current_theme = new Ecwid_Theme_2017();