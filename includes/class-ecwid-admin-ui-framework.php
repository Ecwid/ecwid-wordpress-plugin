<?php

class Ecwid_Admin_UI_Framework {

	public function __construct() {
		if ( $this->is_need_include_assets() ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'register_assets' ) );
		}
	}

	public function register_assets() {
		wp_enqueue_style(
			'ecwid-app-ui',
			'https://djqizrxa6f10j.cloudfront.net/ecwid-sdk/css/1.3.7/ecwid-app-ui.css',
			array(),
			get_option( 'ecwid_plugin_version' )
		);

		wp_enqueue_script(
			'ecwid-app-ui',
			'https://djqizrxa6f10j.cloudfront.net/ecwid-sdk/css/1.3.7/ecwid-app-ui.min.js',
			array(),
			get_option( 'ecwid_plugin_version' ),
			'in_footer'
		);
	}

	public static function print_fix_js() {
		?>
		<script type='text/javascript'>//<![CDATA[
			jQuery(document.body).addClass('ecwid-no-padding');
			jQuery(document.body).css({ 'font-size': '13px' });
			jQuery('#wpbody').css({ 'background-color': 'rgb(240, 242, 244)' });
		//]]></script>
		<?php
	}

	public function is_need_include_assets() {
		if ( ! isset( $_GET['page'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return false;
		}

		$ignore_pages = $this->get_pages_exclude_framework();
		$page         = sanitize_text_field( wp_unslash( $_GET['page'] ) ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended

		if ( in_array( $page, $ignore_pages, true ) ) {
			return false;
		}

		if ( strpos( $page, 'ec-developers' ) === 0 ) {
			return true;
		}
		if ( strpos( $page, 'ec-store' ) === 0 ) {
			return true;
		}

		return false;
	}

	public function get_pages_exclude_framework() {
		$pages = array(
			'ec-store-advanced',
			'ec-store-help',
		);

		if ( ecwid_is_demo_store() || isset( $_GET['reconnect'] ) || Ecwid_Api_V3::get_token() === false ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$pages[] = 'ec-store';
		}

		return $pages;
	}
}

new Ecwid_Admin_UI_Framework();
