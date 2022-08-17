<?php

$user_agent = isset( $_SERVER['HTTP_USER_AGENT'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ) : '';

if ( strpos( $user_agent, 'Google-Site-Verification' ) ) {

	add_action( 'wp_head', 'ecwid_add_kliken_code' );

	function ecwid_add_kliken_code() {
		$api = new Ecwid_Api_V3();

		$info = $api->get_starter_site_info();

		if ( ! $info || ! isset( $info->customHeaderHtmlCode ) ) {//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			return;
		}

		$pattern = '%' .
				'(<!--Kliken Google Site Verification Token Tag-->\s*' .
				"<meta name='google-site-verification' content='(.*)' />\s*" .
				'<!--Kliken Google Site Verification Token Tag-->)%s';

		$matches = array();
		if ( preg_match( $pattern, $info->customHeaderHtmlCode, $matches ) ) {//phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			echo $matches[1]; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}
}//end if
