<?php

define( 'ECWID_THEMES_DIR', ECWID_PLUGIN_DIR . '/includes/themes' );

add_action('after_switch_theme', 'ecwid_after_switch_theme');


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
	$themes = array(
		'bretheon',
		'responsive',
		'twentyfourteen',
		'pagelines',
		'envision',
		'twentyfifteen',
		'customizr',
		'evolve',
		'twentytwelve',
		'genesis',
		'twentysixteen'
	);

	if (empty($theme_name)) {
		$theme_name = ecwid_get_theme_identification();
	}

	$theme_file = '';

	if (in_array($theme_name, $themes)) {

		$theme_file = ECWID_THEMES_DIR . '/class-ecwid-theme-' . $theme_name . '.php';
	}

	$theme_file = apply_filters( 'ecwid_get_theme_file', $theme_file );

	if ( !empty( $theme_file ) && is_file( $theme_file ) && is_readable( $theme_file ) ) {
		require_once( $theme_file );
	}
}

function ecwid_after_switch_theme()
{
	ecwid_apply_theme();

	global $ecwid_current_theme;

	update_option(
		'ecwid_advanced_theme_layout',
		isset($ecwid_current_theme) && $ecwid_current_theme->has_advanced_layout ? 'Y' : 'N'
	);
}