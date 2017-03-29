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
		'pixova-lite'			=> array( 'js', 'scroll' ),
		'accesspress-mag'		=> array( 'css' ),
		'attitude'				=> array( 'css-no-parent' ),
		'customizr'				=> array( 'js', 'css' ),
		'edin'					=> array( 'js' ),
		'evolve'				=> array( 'css-no-parent' ),
		'mantra'				=> array( 'css-no-parent' ),
		'pagelines'				=> array( 'js', 'scroll' ),
		'responsiveboat'		=> array( 'css' ),
		'twentyfourteen'		=> array( 'css', 'scroll' ),
		'twentytwelve'			=> array( 'js', 'scroll' ),
		'sliding-door'			=> array( 'css-no-parent' ),
		'zerif-lite'			=> array( 'css-no-parent' ),
		'storefront'			=> array( 'css' ),
		'salient'				=> array( 'css-no-parent'),
		'flora'					=> array( 'js' ),
        'thevoux-wp'			=> array( 'js' ),
		'zerogravity'			=> array( 'css' ),
		'skt-design-agency-pro' => array( 'css-no-parent' ),
		'uncode'			    => array( 'css-no-parent' )
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
		'twentyseventeen'
	);

	$custom_themes = apply_filters( 'ecwid_custom_themes', $custom_themes );

	if (empty($theme_name)) {
		$theme_name = ecwid_get_theme_identification();
	}

	$theme_file = '';

	if (function_exists('wp_get_theme') && wp_get_theme()->Name == 'ResponsiveBoat') {
		$theme_name = 'responsiveboat';
	}

	$theme_name = '';
	if (!$theme_name) {
		return;
	}

	if ( in_array($theme_name, $custom_themes) ) {
		$theme_file = ECWID_THEMES_DIR . '/class-ecwid-theme-' . $theme_name . '.php';
		$theme_file = apply_filters( 'ecwid_get_theme_file', $theme_file );
		if ( !empty( $theme_file ) && is_file( $theme_file ) && is_readable( $theme_file ) ) {
			require_once( $theme_file );
		}
	} else if ( array_key_exists( $theme_name, $generic_themes ) ) {
		Ecwid_Theme_Base::create( $theme_name, $generic_themes[$theme_name] );
	}
}

function ecwid_after_switch_theme()
{
	ecwid_apply_theme();
}