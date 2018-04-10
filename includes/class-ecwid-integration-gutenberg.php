<?php 

class Ecwid_Integration_Gutenberg {
	public function __construct() {
		
		if ( isset($_GET['classic-editor'] ) ) return;
		
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		add_action( 'admin_enqueue_scripts', function() {
			EcwidPlatform::enqueue_script( 'store-editor-gutenberg' );
			EcwidPlatform::enqueue_style( 'store-popup' );

			wp_localize_script( 'ecwid-store-editor-gutenberg', 'EcwidGutenbergParams', 
				array(
					'ecwid_pb_defaults' => ecwid_get_default_pb_size(),
					'storeImageUrl' => site_url('?file=ecwid_store_svg.svg'),
					'title' => sprintf( __( '%s store', 'ecwid-shopping-cart'), Ecwid_Config::get_brand() ),
					'storeShortcodeName' => Ecwid_Shortcode_Base::get_current_store_shortcode_name()
				)
			);

		} );

		register_block_type('ecwid/store-block', array(
			'editor_script' => 'ecwid-block-store',
			'render_callback' => array( $this, 'render_callback' ),
		));
		
		add_action( 'in_admin_header', array( $this, 'add_popup' ) );
	}
	
	public function enqueue_block_editor_assets() {
		EcwidPlatform::enqueue_script( 'gutenberg-block', array( 'wp-blocks', 'wp-i18n', 'wp-element' ) );
		EcwidPlatform::enqueue_style( 'gutenberg-block', array( 'wp-edit-blocks' ) );
	}
	
	public function render_callback( $params ) {
		if ( $_SERVER['REQUEST_METHOD'] != 'GET' ) {
			return '';
		}
		return ecwid_shortcode( $params );
	}

	public function add_popup() {
		$categories = ecwid_get_categories_for_selector();

		require ECWID_PLUGIN_DIR . '/templates/store-popup.php';
	}
	
	protected function _get_version_for_assets( $asset_file_path )
	{
		if ( $_SERVER['HTTP_HOST'] == 'localhost' ) {
			return filemtime( ECWID_PLUGIN_DIR . '/' . $asset_file_path );
		}
		
		return get_option( 'ecwid_plugin_version' );
	}
}

$ecwid_gutenberg = new Ecwid_Integration_Gutenberg();