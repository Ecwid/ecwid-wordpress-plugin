<?php

include_once "shortcodes/class-ecwid-shortcode-productbrowser.php";
include_once "shortcodes/class-ecwid-shortcode-minicart.php";
include_once "shortcodes/class-ecwid-shortcode-search.php";
include_once "shortcodes/class-ecwid-shortcode-categories.php";
include_once "shortcodes/class-ecwid-shortcode-product.php";

add_shortcode('ecwid_productbrowser', 'ecwid_render_shortcode');
add_shortcode('ecwid_minicart', 'ecwid_render_shortcode');
add_shortcode('ecwid_search', 'ecwid_render_shortcode');
add_shortcode('ecwid_categories', 'ecwid_render_shortcode');
add_shortcode('ecwid_product', 'ecwid_render_shortcode');
add_shortcode('ecwid_searchbox', 'ecwid_searchbox_shortcode');
add_shortcode('ec_product', 'ecwid_render_shortcode');

function ecwid_render_shortcode($params, $content = '', $name) {
	$names = array('productbrowser', 'minicart', 'search', 'categories', 'product');

	$prefix = substr($name, 0, 6);

	if ($prefix != 'ecwid_') return '';

	$base = substr($name, 6);

	if (in_array($base, $names)) {
		$classname = 'Ecwid_Shortcode_' . $base;

		$shortcode = new $classname($params);

		return $shortcode->render( array( 'legacy' => true ) );
	}
}


function ecwid_searchbox_shortcode($params, $content = '', $name) {

    $shortcode = new Ecwid_Shortcode_Search($params);

    return $shortcode->render( array( 'legacy' => true ) );
}