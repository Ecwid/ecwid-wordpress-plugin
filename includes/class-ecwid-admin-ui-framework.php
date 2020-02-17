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
        wp_enqueue_style(
            'ecwid-app-ui',
            'https://djqizrxa6f10j.cloudfront.net/ecwid-sdk/css/1.3.7/ecwid-app-ui.css',
            array(),
            get_option('ecwid_plugin_version')
        );
        
        wp_enqueue_script( 
            'ecwid-app-ui',
            'https://djqizrxa6f10j.cloudfront.net/ecwid-sdk/css/1.3.7/ecwid-app-ui.min.js', 
            array(),
            get_option('ecwid_plugin_version'), 
            'in_footer'
        );
    }

    static public function print_fix_js()
    {
        $js = '';
        $js .= "jQuery(document.body).addClass('ecwid-no-padding');" . PHP_EOL;
        $js .= "jQuery(document.body).css({ 'font-size': '13px' });" . PHP_EOL;
        $js .= "jQuery('#wpbody').css({ 'background-color': 'rgb(240, 242, 244)' });" . PHP_EOL;
        
        return sprintf( "<script type='text/javascript'>//<![CDATA[" . PHP_EOL . " %s //]]></script>", $js );
    }

    public function is_need_include_assets()
    {
        $ignore_pages = $this->get_pages_exclude_framework();

        if( isset($_GET['page']) && in_array($_GET['page'], $ignore_pages) ) {
            return false;
        }

        if ( isset($_GET['page']) && strpos($_GET['page'], 'ec-store') === 0 ) {
            return true;
        }

        return false;
    }

    public function get_pages_exclude_framework() {
        $pages = array(
            'ec-store-advanced',
            'ec-store-help'
        );

        if( ecwid_is_demo_store() || isset($_GET['reconnect']) ) {
            $pages[] = 'ec-store';
        }

        return $pages;
    }
}

new Ecwid_Admin_UI_Framework();
?>