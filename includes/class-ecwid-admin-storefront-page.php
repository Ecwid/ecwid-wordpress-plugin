<?php
class Ecwid_Admin_Storefront_Page
{
	const TEMPLATES_DIR = ECWID_PLUGIN_DIR . '/templates/admin/storefront-settings/';
	
	public function __construct() {

		add_action( 'wp_ajax_ecwid_storefront_set_status', array( $this, '_ajax_set_page_status' ) );

		add_action( 'enqueue_block_editor_assets', array( $this, 'gutenberg_show_inline_script' ) );
	}

	public static function do_page() {
		$page_id = get_option( Ecwid_Store_Page::OPTION_MAIN_STORE_PAGE_ID );

		if( $page_id ) {
			$page_link = get_permalink( $page_id );
			$page_edit_link = get_edit_post_link( $page_id );
			$page_status = get_post_status($page_id);


			if( self::is_used_gutenberg() ) {
				$design_edit_link = get_edit_post_link( $page_id ) . '&ec-show-store-settings';
			} else {
				
				$page = Ecwid_Admin_Main_Page::PAGE_HASH_DASHBOARD;
				$time = time() - get_option('ecwid_time_correction', 0);
				$iframe_src = ecwid_get_iframe_src($time, $page);
				
				if( !$iframe_src ) {
					//TO-DO какая ссылка для WL
					$design_edit_link = 'https://' . Ecwid_Config::get_cpanel_domain() . '/#design';
				} else {
					$design_edit_link = get_admin_url( null, 'admin.php?page=' . Ecwid_Admin::ADMIN_SLUG . '-admin-design' );
				}
			}
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

	public static function is_used_gutenberg() {
		$version = get_bloginfo('version');

		if ( version_compare( $version, '5.0' ) < 0 ) {
			
			if( is_plugin_active('gutenberg/gutenberg.php') ) {
				return true;
			}

			return false;
		}

		$plugins_disabling_gutenberg = array(
			'classic-editor/classic-editor.php',
			'elementor/elementor.php',
			'divi-builder/divi-builder.php',
			'beaver-builder-lite-version/fl-builder.php'
		);

		foreach ( $plugins_disabling_gutenberg as $plugin ) {
			if ( is_plugin_active( $plugin ) ) {
				return false;
			}
		}

		return true;
	}


	public function gutenberg_show_inline_script() {
		
		if( !array_key_exists( 'ec-show-store-settings', $_GET ) ) {
			return;
		}

		$script = "
			var ec_selected_store_block = false;
			wp.data.subscribe(function () {
				if( ec_selected_store_block ) {
					return false;
				}

				var blocks = wp.data.select( 'core/block-editor' ).getBlocks();
				if( blocks.length > 0 ) {

					var block = blocks.find(obj => {
							return obj.name === 'ecwid/store-block'
						});

					if( typeof block != 'undefined' ) {
						ec_selected_store_block = true;

						var client_id = block.clientId;
						wp.data.dispatch( 'core/block-editor' ).selectBlock( client_id );
						wp.data.dispatch( 'core/edit-post' ).openGeneralSidebar( 'edit-post/block' );
					}
				}
			});
		";

		wp_register_script( 'ec-blockeditor-inline-js', '', [], '', true );
		wp_enqueue_script( 'ec-blockeditor-inline-js'  );
		wp_add_inline_script( 'ec-blockeditor-inline-js', $script );
	}
}

$_ecwid_admin_storefront_page = new Ecwid_Admin_Storefront_Page();