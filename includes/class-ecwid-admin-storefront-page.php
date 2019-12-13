<?php
class Ecwid_Admin_Storefront_Page
{
	const TEMPLATES_DIR = ECWID_PLUGIN_DIR . '/templates/admin/storefront-settings/';
	
	public function __construct() {

		add_action( 'wp_ajax_ecwid_storefront_set_status', array( $this, '_ajax_set_page_status' ) );
	}

	public static function do_page() {
		$page_id = get_option( Ecwid_Store_Page::OPTION_MAIN_STORE_PAGE_ID );

		if( $page_id ) {
			$page_link = get_permalink( $page_id );
			$page_edit_link = get_edit_post_link( $page_id );
			$page_status = get_post_status($page_id);
		}

		require_once self::TEMPLATES_DIR . 'main.tpl.php';
	}

	public function _ajax_set_page_status() {

		$page_statuses = array(
			0 => 'draft',
			1 => 'publish'
		);

		if( !isset( $_GET['status'] ) ) {
			return false;
		}

		$status = intval( $_GET['status'] );
		if( !array_key_exists( $status, $page_statuses ) ) {
			return false;
		}

		$page_id = get_option( Ecwid_Store_Page::OPTION_MAIN_STORE_PAGE_ID );

		wp_update_post(array(
			'ID' => $page_id,
			'post_status' => $page_statuses[ $status ]
		));

		wp_send_json(array('status' => 'success'));
	}	
}

$_ecwid_admin_storefront_page = new Ecwid_Admin_Storefront_Page();