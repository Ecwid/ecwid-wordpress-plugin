<?php

class Ecwid_Store_Editor {
	public function __construct()
	{
		$version = get_bloginfo( 'version' );
		if ( version_compare( $version, '3.5' ) < 0 ) {
			return;
		}

		add_action( 'template_redirect',     array( $this, 'get_store_svg' ) );

		add_action( 'current_screen', array($this, 'init') );
	}
	
	public function init()
	{
		$current_screen = get_current_screen();

		if ( $current_screen->base != 'post' || !in_array( $current_screen->post_type, array( 'post', 'page' ) ) ) {
			return;
		}
		
		if ( is_plugin_active( 'elementor/elementor.php' ) && @$_GET['action'] == 'elementor' ) {
			return;	
		}

		if ( is_plugin_active( 'gutenberg/gutenberg.php' ) && !isset( $_GET['classic-editor'] ) ) {
			return;
		}		
		
		add_filter( 'mce_external_plugins',  array( $this, 'add_mce_plugin' ) );
		add_action( 'media_buttons_context', array( $this, 'add_editor_button' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'add_scripts' ) );
		add_action( 'in_admin_header',       array( $this, 'add_popup' ) );
	}

	public function add_mce_plugin($plugins) {

		$plugins_array = array(
			'ecwid' => ECWID_PLUGIN_URL . 'js/store-editor-mce.js',
			'ecwid_common' => ECWID_PLUGIN_URL . 'js/store-editor-common.js',
		);

		return array_merge($plugins, $plugins_array);
	}

	public function add_editor_button($context) {

		$image_code = file_get_contents( ECWID_PLUGIN_DIR . 'images/store.svg' );

		$title = __( 'Add Store', 'ecwid-shopping-cart' );
		$button = <<<HTML
	<a href="#" id="insert-ecwid-button" class="button add-ecwid ecwid_button" title="$title">
		<span class="ecwid-store-icon">$image_code</span>
		$title
	</a>
HTML;

		$title = __( 'Edit Store', 'ecwid-shopping-cart' );
		$button .= <<<HTML
	<a href="#" id="update-ecwid-button" class="button update-ecwid ecwid_button" title="$title">
		<span class="ecwid-store-icon">$image_code</span>
		$title
	</a>
HTML;

		return $context . $button;
	}

	public function add_scripts() {
		wp_enqueue_style( 'ecwid-store-editor-css', ECWID_PLUGIN_URL . 'css/store-popup.css', array(), get_option('ecwid_plugin_version') );
		wp_enqueue_script( 'ecwid-store-editor-common-js', ECWID_PLUGIN_URL . 'js/store-editor-common.js', array(), get_option('ecwid_plugin_version') );
		wp_enqueue_script( 'ecwid-store-editor-page-js', ECWID_PLUGIN_URL . 'js/store-editor-page.js', array(), get_option('ecwid_plugin_version') );
		wp_localize_script( 'ecwid-store-editor-page-js', 'ecwid_i18n', array( 'edit_store_appearance' => __( 'Edit Appearance', 'ecwid-shopping-cart' ) ) );
		wp_localize_script( 'ecwid-store-editor-page-js', 'ecwid_pb_defaults', ecwid_get_default_pb_size() );
		add_editor_style( ECWID_PLUGIN_URL . 'css/page-editor.css' );
	}

	public function get_store_svg() {
		// TODO: Move this to admin-post
		if (isset($_GET['file']) && $_GET['file'] == 'ecwid_store_svg.svg' && current_user_can('administrator')) {
			ecwid_load_textdomain();
			header( 'Content-type: image/svg+xml' );
			require_once( ECWID_PLUGIN_DIR . 'templates/store-svg.php' );
			die();
		}
	}

	public function add_popup() {
		$categories = ecwid_get_categories_for_selector();
		
		require_once( ECWID_PLUGIN_DIR . 'templates/store-popup.php' );
	}
}

$ecwid_store_editor = new Ecwid_Store_Editor();
