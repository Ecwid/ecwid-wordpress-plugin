<?php

define( 'ECWID_THEMES_DIR', ECWID_PLUGIN_DIR . 'includes/themes' );

add_action('after_switch_theme', 'ecwid_after_switch_theme');

require ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';


function ecwid_get_theme_name()
{
	$version = get_bloginfo('version');

	if (version_compare( $version, '3.4' ) < 0) {
		$theme_name = get_current_theme();
	} else {
		$theme = wp_get_theme();
		$theme_name = $theme->Name;
	}

	return $theme_name;
}

function ecwid_get_theme_identification()
{
	$version = get_bloginfo('version');

	if (version_compare( $version, '3.4' ) < 0) {
		$theme_name = get_template();
	} else {
		$theme = wp_get_theme();
		$theme_name = $theme->template;
	}

	return $theme_name;
}

function ecwid_apply_theme($theme_name = null)
{
	$generic_themes = array(
		'pixova-lite'			=> array( 'js', 'scroll' => 75 ),
		'accesspress-mag'		=> array( 'css' ),
		'attitude'				=> array( 'css-no-parent' ),
		'customizr'				=> array( 'js', 'css-no-parent', Ecwid_Theme_Base::PROP_USE_JS_API_FOR_CATS_NAV_MENU ),
		'edin'					=> array( 'js' ),
		'evolve'				=> array( 'css-no-parent' ),
		'mantra'				=> array( 'css-no-parent' ),
		'pageline'				=> array( 'scroll' => 60 ),
		'responsiveboat'		=> array( 'css' ),
		'twentyfourteen'		=> array( 'css', 'scroll' => 0, Ecwid_Theme_Base::PROP_USE_JS_API_FOR_CATS_NAV_MENU ),
		'twentytwelve'			=> array( 'js' ),
		'sliding-door'			=> array( 'css-no-parent' ),
		'zerif-lite'			=> array( 'css-no-parent' ),
		'storefront'			=> array( 'css' ),
		'salient'				=> array( 'css-no-parent'),
		'flora'					=> array( 'historyjs_html4mode' ),
        'thevoux-wp'			=> array( 'historyjs_html4mode' ),
		'zerogravity'			=> array( 'css' ),
		'skt-design-agency-pro' => array( 'css-no-parent' ),
		'uncode'			    => array( 'css-no-parent' ),
		'ribosome'              => array( 'css' ),
		'optimizepress'         => array( 'css' ),
		'edge'                  => array( 'js', 'scroll' => 50 ),
		'newsmag'               => array( 'css-no-parent'),
		'optimizer'             => array( 'css' ),
		'businesso'				=> array( 'css' ),
		'flatsome'				=> array( Ecwid_Theme_Base::PROP_USE_JS_API_FOR_CATS_NAV_MENU ),
		'themify-music'			=> array( Ecwid_Theme_Base::PROP_AJAX_DEFER_RENDERING ),
		'bridge'	    		=> array( Ecwid_Theme_Base::PROP_AJAX_DEFER_RENDERING ),
		'Parallax-One'			=> array( 'css' ),
		'twentytwenty'			=> array( 'css' ),
		'jevelin3'				=> array( 'css-no-parent' ),
		'newstore'				=> array( 'css' )
	);
	$generic_themes = apply_filters('ecwid_generic_themes', $generic_themes);

	$custom_themes = array(
		'bretheon',
		'responsive',
		'envision',
		'twentyfifteen',
		'genesis',
		'twentysixteen',
		'central',
		'mfupdate',
		'trend',
		'Boundless',
		'twentyseventeen',
		'themify-music',
		'Avada',
		'twentynineteen',
		'woodiex',
		'enfold',
		'vantage',
		'zenlife',
		'Divi'
	);

	$custom_themes = apply_filters( 'ecwid_custom_themes', $custom_themes );

	if (empty($theme_name)) {
		$theme_name = ecwid_get_theme_identification();
	}

	$theme_file = '';
	
	if (function_exists('wp_get_theme') && wp_get_theme()->Name == 'ResponsiveBoat') {
		$theme_name = 'responsiveboat';
	}

	if ($theme_name == 'evolve-plus') {
		$theme_name = 'evolve';
	}
	
	if (!$theme_name) {
		return;
	}

	global $ecwid_current_theme;

	if ( in_array($theme_name, $custom_themes) ) {
		$theme_file = ECWID_THEMES_DIR . '/class-ecwid-theme-' . $theme_name . '.php';
		$theme_file = apply_filters( 'ecwid_get_theme_file', $theme_file );
		$theme_file = strtolower($theme_file);

		if ( !empty( $theme_file ) && is_file( $theme_file ) && is_readable( $theme_file ) ) {
			$ecwid_current_theme = require_once( $theme_file );
		}
	} else if ( array_key_exists( $theme_name, $generic_themes ) ) {
		
		$ecwid_current_theme = Ecwid_Theme_Base::create( $theme_name, $generic_themes[$theme_name] );
	}
	
	do_action( Ecwid_Theme_Base::ACTION_APPLY_THEME, $ecwid_current_theme );
}

function ecwid_after_switch_theme()
{
	ecwid_apply_theme();
}
