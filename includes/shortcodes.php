<?php

include_once "shortcodes/class-ecwid-shortcode-productbrowser.php";

$ecwid_productbrowser_shortcode = new Ecwid_Shortcode_ProductBrowser();
add_shortcode( $ecwid_productbrowser_shortcode->get_shortcode_tag(), array( $ecwid_productbrowser_shortcode, 'render' ) );