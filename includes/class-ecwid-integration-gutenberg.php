<?php 

class Ecwid_Integration_Gutenberg {
	
	const STORE_BLOCK = 'ecwid/store-block';
	const PRODUCT_BLOCK = 'ecwid/product-block';
	
	public function __construct() {
		
		if ( isset( $_GET['classic-editor'] ) ) return;
		
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		add_action( 'admin_enqueue_scripts', function() {
			EcwidPlatform::enqueue_script( 'store-editor-gutenberg' );
			EcwidPlatform::enqueue_style( 'store-popup' );
			
			wp_localize_script( 'ecwid-store-editor-gutenberg', 'EcwidGutenbergParams', 
				array(
					'ecwid_pb_defaults' => ecwid_get_default_pb_size(),
					'storeImageUrl' => site_url('?file=ecwid_store_svg.svg'),
					'storeBlockTitle' => sprintf( __( '%s store', 'ecwid-shopping-cart'), Ecwid_Config::get_brand() ),
					'storeShortcodeName' => Ecwid_Shortcode_Base::get_current_store_shortcode_name(),
					'storeBlock' => self::STORE_BLOCK,
					'productBlockTitle' => sprintf( __( '%s product', 'ecwid-shopping-cart'), Ecwid_Config::get_brand() ),
					'productShortcodeName' => Ecwid_Shortcode_Product::get_shortcode_name(),
					'productBlock' => self::PRODUCT_BLOCK,
					'storeId' => get_ecwid_store_id(),
					'chooseProduct' => __( 'Choose product', 'ecwid-shopping-cart' ),
					'editAppearance' => __( 'Edit Appearance', 'ecwid-shopping-cart' ),
					'yourStoreWill' => __( 'Your store will be shown here', 'ecwid-shopping-cart' ),
					'storeIdLabel' => __( 'Store ID', 'ecwid-shopping-cart' )
				)
			);

		} );

		add_action( "rest_insert_post", array( $this, 'on_save_post' ), 10, 3 );
		add_action( "rest_insert_page", array( $this, 'on_save_post' ), 10, 3 );

		register_block_type('ecwid/store-block', array(
			'editor_script' => 'ecwid-block-store',
			'render_callback' => array( $this, 'render_callback' ),
		));
		
		add_action( 'in_admin_header', array( $this, 'add_popup' ) );
	}
	
	public function on_save_post( $post, $request, $creating ) {
		if (strpos( $post->post_content, '<!-- wp:' . self::STORE_BLOCK ) !== false ) {
			Ecwid_Store_Page::add_store_page( $post->ID );
		}
	}
	
	public function enqueue_block_editor_assets() {
		EcwidPlatform::enqueue_script( 'gutenberg-block', array( 'wp-blocks', 'wp-i18n', 'wp-element' ) );
		EcwidPlatform::enqueue_style( 'gutenberg-block', array( 'wp-edit-blocks' ) );

		EcwidPlatform::enqueue_script( 'gutenberg-block-product', array( 'wp-blocks', 'wp-i18n', 'wp-element' ) );
		
		$storeImageUrl = site_url('?file=ecwid_store_svg.svg');
		
		wp_add_inline_style('ecwid-gutenberg-block', <<<CSS
.editor-block-list__block[data-type="ecwid/store-block"] .editor-block-list__block-edit {
	background-image: url("$storeImageUrl")
}
CSS
);
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