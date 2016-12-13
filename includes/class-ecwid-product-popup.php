<?php

class Ecwid_Product_Popup {
    public function __construct()
    {
        $version = get_bloginfo( 'version' );
        if ( version_compare( $version, '3.9' ) < 0 ) {
            return;
        }

        add_action( 'current_screen', array($this, 'init') );
    }

    public function init()
    {
        $current_screen = get_current_screen();

        if ($current_screen->base != 'post') {
            return;
        }

        add_filter( 'mce_external_plugins',  array( $this, 'add_mce_plugin' ) );
        add_action( 'media_buttons_context', array( $this, 'add_editor_button' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
        add_action( 'in_admin_header',       array( $this, 'add_popup' ) );
    }

    public function add_mce_plugin($plugins) {

        $plugins_array = array(
            'ecwid' => ECWID_PLUGIN_URL . 'js/store-editor-mce.js',
            'ecwid_common' => ECWID_PLUGIN_URL . 'js/store-editor-common.js',
        );

        return array_merge($plugins, $plugins_array);
    }

    public function add_editor_button($context) {

        $title = __( 'Add Product', 'ecwid-shopping-cart' );
        $button = <<<HTML
	<a href="#" id="insert-ecwid-button" class="button add-ecwid ecwid_button" title="$title">
		$title
	</a>
HTML;

        return $context . $button;
    }

    public function add_scripts() {
        wp_enqueue_style( 'ecwid-product-popup', ECWID_PLUGIN_URL . 'css/product-popup.css', array(), get_option('ecwid_plugin_version') );
    }

    public function add_popup() {
        require_once( ECWID_PLUGIN_DIR . 'templates/product-popup.php' );
    }
}

$ecwid_product_popup = new Ecwid_Product_Popup();
