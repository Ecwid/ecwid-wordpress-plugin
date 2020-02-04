<?php

class Ecwid_Admin_UI_Framework
{
    public function __construct()
    {
        if( $this->is_need_include_assets() ) {
            add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );
        }
    }

    public function register_assets()
    {
        wp_enqueue_style('ecwid-app-ui',
            'https://djqizrxa6f10j.cloudfront.net/ecwid-sdk/css/1.3.7/ecwid-app-ui.css', array(),
            get_option('ecwid_plugin_version'));
        wp_enqueue_script('ecwid-app-ui',
            'https://djqizrxa6f10j.cloudfront.net/ecwid-sdk/css/1.3.7/ecwid-app-ui.min.js', array(),
            get_option('ecwid_plugin_version'), 'in_footer');
    }

    public function is_need_include_assets()
    {
        $pages = $this->get_pages_using_framework();

        if ( isset($_GET['page']) && in_array($_GET['page'], $pages) ) {
            return true;
        }

        return false;
    }

    public function get_pages_using_framework() {
        return array(
            'ec-store-import-woocommerce',
            'ec-storefront-settings'
        );
    }
}

new Ecwid_Admin_UI_Framework();
?>