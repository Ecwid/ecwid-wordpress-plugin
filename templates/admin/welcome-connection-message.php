<?php
if ( ! $connection_error ) {

	$note = sprintf(
		__( 'To display your store on this site, you need to allow WordPress to access your %1$s products. Please press connect to provide permission.', 'ecwid-shopping-cart' ),
		Ecwid_Config::get_brand()
	);

	if ( $state == 'connect' ) {
		echo wp_kses_post( $this->get_welcome_page_note( $note ) );
	}
} else {

	$error_note = __( 'Connection error - after clicking button you need to login and provide permissions to use our plugin. Please, try again.', 'ecwid-shopping-cart' );

	$oauth_error = $ecwid_oauth->get_error();

	if ( ! empty( $oauth_error ) ) {
		if ( $ecwid_oauth->get_error() == 'other' ) {

			$error_note = sprintf( __( 'Looks like your site does not support remote POST requests that are required for %s API to work. Please, contact your hosting provider to enable cURL.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() );
		} else {

			$error_note = sprintf( __( 'To sell using %1$s, you must allow WordPress to access the %1$s plugin. The connect button will direct you to your %1$s account where you can provide permission.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() );
		}
	} elseif ( ! ecwid_test_oauth() ) {
		$error_note = sprintf( __( 'Looks like your site does not support remote POST requests that are required for %s API to work. Please, contact your hosting provider to enable cURL.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() );
	}

	echo wp_kses_post( $this->get_welcome_page_note( $error_note, 'ec-connection-error' ) );
}//end if

if ( $ecwid_oauth->get_reconnect_message() ) {
	echo wp_kses_post( $this->get_welcome_page_note( $ecwid_oauth->get_reconnect_message() ) );
}
