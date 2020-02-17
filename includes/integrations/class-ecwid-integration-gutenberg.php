<?php 

require_once ECWID_PLUGIN_DIR . '/includes/class-ecwid-product-browser.php';

class Ecwid_Integration_Gutenberg {
	
	const STORE_BLOCK = 'ecwid/store-block';
	const PRODUCT_BLOCK = 'ecwid/product-block';
	
	public function __construct() {

		if ( isset( $_GET['classic-editor'] ) ) return;
		
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_action( "rest_insert_post", array( $this, 'on_save_post' ), 10, 3 );
		add_action( "rest_insert_page", array( $this, 'on_save_post' ), 10, 3 );
		
		register_block_type(self::STORE_BLOCK, array(
			'editor_script' => 'ecwid-gutenberg-store',
			'render_callback' => array( $this, 'render_callback' ),
        ));
		
		register_block_type(self::PRODUCT_BLOCK, array(
			'editor_script' => 'ecwid-gutenberg-store',
			'render_callback' => array( $this, 'product_render_callback' ),
		));
		
		add_filter( 'block_categories', array( $this, 'block_categories' ) );
	}

	public function admin_enqueue_scripts()
	{
		wp_enqueue_script( 'gutenberg-store' );
		EcwidPlatform::enqueue_style( 'store-popup' );
	}
	
	public function block_categories( $categories ) {
		return array_merge(
			$categories,
			array(
				array(
					'slug'  => 'ec-store',
					'title' => sprintf( __( '%s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ),
					'icon'  => '<svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path fill="#555d66" d="' . $this->_get_store_icon_path() . '"/><path d="M19 13H5v-2h14v2z" /></svg>'
				),
			)
		);
	}
	
	public function on_save_post( $post, $request, $creating ) {
		if (strpos( $post->post_content, '<!-- wp:' . self::STORE_BLOCK ) !== false ) {
			Ecwid_Store_Page::add_store_page( $post->ID );
		}
	}
	
	public function enqueue_block_editor_assets() {

		wp_enqueue_script( 'ecwid-gutenberg-store', ECWID_PLUGIN_URL . 'js/gutenberg/blocks.build.js', array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ) );
		wp_enqueue_style( 'ecwid-gutenberg-block', ECWID_PLUGIN_URL . 'css/gutenberg/blocks.editor.build.css' );
		
		$locale_data = '';
		if ( function_exists( 'gutenberg_get_jed_locale_data' ) ) {
			$locale_data = gutenberg_get_jed_locale_data( 'ecwid-shopping-cart' );
		} else if ( function_exists( 'wp_get_jed_locale_data' ) ) {
			$locale_data = wp_get_jed_locale_data( 'ecwid-shopping-cart' );
		}
		
		
		if ( $locale_data ) {
			wp_add_inline_script(
				'ecwid-gutenberg-store',
				'wp.i18n.setLocaleData( ' . json_encode( $locale_data ) . ', "ecwid-shopping-cart"' . ');',
				'before'
			);
		}
		
		$api = new Ecwid_Api_V3();
		wp_localize_script( 'ecwid-gutenberg-store', 'EcwidGutenbergStoreBlockParams', 
			array(
				'attributes' => $this->_get_attributes_for_editor(),
				'is_new_product_list' => $this->_is_new_product_list(),
				'is_new_details_page' => $this->_is_new_details_page(),
				'storeBlockTitle' => _x( 'Store', 'gutenberg-store-block-stub', 'ecwid-shopping-cart' )
			)
		);
		
		$is_demo_store = ecwid_is_demo_store();
		wp_localize_script( 'ecwid-gutenberg-store', 'EcwidGutenbergParams',
			array(
				'ecwid_pb_defaults' => ecwid_get_default_pb_size(),
				'storeImageUrl' => site_url('?file=ecwid_store_svg.svg'),
				'storeBlockTitle' => sprintf( __( '%s Store', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ),
				'storeShortcodeName' => Ecwid_Shortcode_Base::get_current_store_shortcode_name(),
				'storeBlock' => self::STORE_BLOCK,
				'productBlockTitle' => sprintf( __( '%s product', 'ecwid-shopping-cart'), Ecwid_Config::get_brand() ),
				'productShortcodeName' => Ecwid_Shortcode_Product::get_shortcode_name(),
				'productBlock' => self::PRODUCT_BLOCK,
				'storeId' => get_ecwid_store_id(),
				'chooseProduct' => __( 'Choose product', 'ecwid-shopping-cart' ),
				'editAppearance' => __( 'Edit Appearance', 'ecwid-shopping-cart' ),
				'yourStoreWill' => __( 'Your store will be shown here', 'ecwid-shopping-cart' ),
				'storeIdLabel' => __( 'Store ID', 'ecwid-shopping-cart' ),
				'yourProductLabel' => __( 'Your product', 'ecwid-shopping-cart' ),
				'storeIcon' => $this->_get_store_icon_path(),
				'productIcon' => $this->_get_product_icon_path(),
				'isDemoStore' => $is_demo_store,
				'isApiAvailable' => Ecwid_Api_V3::is_available(),
				'products' => $this->_get_products_data(),
				'customizeMinicartText' =>
					sprintf(
						__(
							'You can enable an extra shopping bag icon widget that will appear on your site pages. Open “<a href="%1$s">Appearance → Customize → %2$s</a>” menu to enable it.',
							'ecwid-shopping-cart'
						),
						'customize.php?autofocus[section]=' . Ecwid_Customizer::SECTION_MINICART . '&return=' . urlencode( remove_query_arg( wp_removable_query_args(), wp_unslash( $_SERVER['REQUEST_URI'] ) )
						),
						Ecwid_Config::get_brand()
					)
			)
		);

	}
	
	protected function _get_products_data() {

		$blocks = self::get_blocks_on_page();

		$productIds = array();
		foreach ( $blocks as $block ) {
			if ( $block['blockName'] == self::PRODUCT_BLOCK ) {
				$productIds[] = $block['attrs']['id'];
			}
		}
		
		if ( empty( $productIds ) ) {
			return array();
		}
		
		$result = array();
		foreach ( $productIds as $id ) {
			$product = Ecwid_Product::get_by_id( $id );
			
			$result[$id] = array(
				'name' => $product->name,
				'imageUrl' => $product->thumbnailUrl
			);
		}
		
		return $result;
	}
	
	protected function _is_new_product_list() {
		$api = new Ecwid_Api_V3();
		
		return ecwid_is_demo_store() || !Ecwid_Api_V3::is_available() || $api->is_store_feature_enabled( Ecwid_Api_V3::FEATURE_NEW_PRODUCT_LIST );	
	}

	protected function _is_new_details_page() {
		$api = new Ecwid_Api_V3();

		return ecwid_is_demo_store() || !Ecwid_Api_V3::is_available() || $api->is_store_feature_enabled( Ecwid_Api_V3::FEATURE_NEW_DETAILS_PAGE );
	}
	
	public function product_render_callback( $params ) {
		
		if ( !@$params['id'] ) return '';
		
		$params = wp_parse_args(
			$params,
			array(
				'id' => 0,
				'show_picture' => true,
				'show_title' => true,
				'show_price' => true,
				'show_options' => true,
				'show_addtobag' => true,
				'show_border' => true,
				'center_align' => true,
				'show_price_on_button' => true
			)
		);
		
		$display = array(
			'picture', 'title', 'price', 'options', 'qty', 'addtobag' 
		);
		
		$params['display'] = '';
		$display_string = '';
		foreach ( $display as $name ) {
			if ( @$params['show_' . $name] ) {
				$params['display'] .= ' ' . $name;
			}
		}
		
		$params['version'] = 2;

		$shortcode = new Ecwid_Shortcode_Product( $params );

		return $shortcode->render();
	}
	
	public function render_callback( $params ) {
		
		if ( $_SERVER['REQUEST_METHOD'] != 'GET' ) {
			return '';
		}

		$result = "[ecwid";
		
		$params['widgets'] = 'productbrowser';
		if ( @$params['show_categories'] ) {
			$params['widgets'] .= ' categories';
		}
		if ( @$params['show_search'] ) {
			$params['widgets'] .= ' search';
		}
		
		foreach ($params as $key => $value) {
			$result .= " $key='$value'";
		} 
		
		$result .= ']';
		
		$result .= <<<HTML
<script type="text/javascript">
		window.ec = window.ec || Object();
		window.ec.storefront = window.ec.storefront || Object();
HTML;
		
		$attributes = $this->_get_attributes_for_editor();
		
		$store_page_data = array();
		
		foreach ( $attributes as $key => $attribute ) {
			
			$name = $attribute['name'];
			// we do not print defaults
			if ( !isset( $params[$name] ) ) {
				$store_page_data[$name] = $attribute['default'];
				continue;
			}
			
			$value = $params[$name];
			
			if ( $name == 'show_description_under_image' ) {
				$attribute['is_storefront_api'] = true;
				$value = !@$params[$name];
				
				$layout = @$params['product_details_layout'];
				if ( is_null( $layout ) ) {
					$layout = $attributes['product_details_layout']['default'];
				}
				
				if ( $layout == 'TWO_COLUMNS_SIDEBAR_ON_THE_LEFT' ) {
					$name = 'product_details_two_columns_with_left_sidebar_show_product_description_on_sidebar';
				} else if ( $layout == 'TWO_COLUMNS_SIDEBAR_ON_THE_RIGHT' ) {
					$name = 'product_details_two_columns_with_right_sidebar_show_product_description_on_sidebar';
				} else {
					unset( $attribute['is_storefront_api'] );
				}
			}
			
			if ( @$attribute['is_storefront_api'] ) {
				
				if ( @$attribute['type'] == 'boolean') {
					$result .= 'window.ec.storefront.' . $name . "=" . ( $value ? 'true' : 'false' ) . ";" . PHP_EOL;
				} else {
					$result .= 'window.ec.storefront.' . $name . "='" . $value . "';" . PHP_EOL;
				}
				$store_page_data[$name] = $value;
			}
		}
		
		$colors = array();
		foreach ( array( 'foreground', 'background', 'link', 'price', 'button' ) as $kind ) {
			$color = @$params['chameleon_color_' . $kind];
			if ( $color ) {
				$colors['color-' . $kind] = $color;
			}
		}
		
		if ( empty( $colors ) ) {
			$colors = 'auto';
		}

		$colors = json_encode($colors);
		
		$chameleon = apply_filters( 'ecwid_chameleon_settings', array( 'colors' => $colors ) );

		if ( !is_array($chameleon ) ) {
			$chameleon = array(
				'colors' => $colors,
			);
		}

		if ( !isset( $chameleon['colors'] ) ) {
			$chameleon['colors'] = json_encode($colors);
		}

		$store_page_data['chameleon-colors'] = $chameleon['colors'];
		
		Ecwid_Store_Page::save_store_page_params( $store_page_data );
		
		if ( $chameleon['colors'] != 'auto' ) {
			$result .= <<<JS
window.ec.config.chameleon = window.ec.config.chameleon || Object();
window.ec.config.chameleon.colors = $chameleon[colors];
JS;
		}
		$result .= "
		if ( typeof Ecwid != 'undefined' ) {
			Ecwid.OnAPILoaded.add(function() {
				Ecwid.refreshConfig();
			});
		}
		</script>";
		
		return $result;
	}

	public static function get_block_names() {
		return array(
			self::STORE_BLOCK,
			self::PRODUCT_BLOCK
		);
	}
	
	/**
	 * @param $post
	 *
	 * @return array
	 */
	public static function get_blocks_on_page() {
		$post = get_post();
		
		if ( !$post ) return array();
		
		if ( function_exists( 'gutenberg_parse_blocks' ) ) {
			$blocks = gutenberg_parse_blocks( $post->post_content );
		} else {
			$blocks = parse_blocks( $post->post_content );
		}
		
		if ( empty( $blocks ) ) {
			return array();
		}

		$result = array();
		
		$ecwid_blocks = self::get_block_names();
		foreach ( $blocks as $block ) {
			if ( in_array( $block['blockName'], $ecwid_blocks ) ) {
				$result[$block['blockName']] = $block;
			}
		}

		return $result;
	}

	protected function _get_version_for_assets( $asset_file_path )
	{
		if ( $_SERVER['HTTP_HOST'] == 'localhost' ) {
			return filemtime( ECWID_PLUGIN_DIR . '/' . $asset_file_path );
		}
		
		return get_option( 'ecwid_plugin_version' );
	}
	
	protected function _get_attributes_for_editor() {
		$api = new Ecwid_Api_V3();

		if ( $api->is_available() && $api->get_store_profile() ) {
			$settings = $api->get_store_profile()->designSettings;
		} else {
			$settings = new stdClass();
		}

		$attributes = Ecwid_Product_Browser::get_attributes();
		$to_remove  = array(
			'product_details_two_columns_with_left_sidebar_show_product_description_on_sidebar',
			'product_details_two_columns_with_right_sidebar_show_product_description_on_sidebar'
		);
		foreach ( $to_remove as $name ) {
			unset( $attributes[ $name ] );
		}

		$attributes['show_description_under_image'] = array(
			'name'    => 'show_description_under_image',
			'title'   => __( 'Show description under the image', 'ecwid-shopping-cart' ),
			'default' => false,
			'type'    => 'boolean'
		);

		foreach ( $attributes as $key => $attribute ) {
			$name = $attribute['name'];

			$default = null;
			if ( property_exists( $settings, $name ) ) {
				$default = $settings->$name;
			}

			$prop_to_default_exceptions = array(
				'product_list_category_image_aspect_ratio' => 'product_list_image_aspect_ratio',
				'product_list_category_image_size'         => 'product_list_image_size'
			);

			if ( array_key_exists( $name, $prop_to_default_exceptions ) ) {
				$another_name = $prop_to_default_exceptions[ $name ];
				if ( property_exists( $settings, $another_name ) ) {
					$default = $settings->$another_name;
				}
			}

			if ( $default !== null ) {
				$attributes[ $key ]['default'] = $default;
			}
		}

		$categories = ecwid_get_categories_for_selector();

		if ( $categories ) {
			$attributes['default_category_id']['values'] = array(
				array(
					'value' => '',
					'title' => __( 'Store root category', 'ecwid-shopping-cart' )
				)
			);
			foreach ( $categories as $category ) {
				$attributes['default_category_id']['values'][] = array(
					'value' => $category->id,
					'title' => $category->name
				);
			}
		} else {
			$api  = new Ecwid_Api_V3();
			$cats = $api->get_categories( array() );

			if ( $cats && $cats->total == 0 ) {
				unset( $attributes['default_category_id'] );
			}
		}

		$attributes['widgets'] = array( 'type' => 'string', 'default' => '', 'name' => 'widgets' );

		return $attributes;
	}
		
	public static function get_store_block_data_from_current_page() {
		
		$blocks = self::get_blocks_on_page();
		
		$store_block = null;
		foreach ( $blocks as $block ) {
			if ( $block['blockName'] == self::STORE_BLOCK ) {
				$store_block = $block;
				break;
			}
		}
		
		if ( !$store_block ) return array();
		
		return $store_block['atts'];
	}
	
	protected function _get_store_icon_path()
	{
		return 'M15.32,15.58c-0.37,0-0.66,0.3-0.66,0.67c0,0.37,0.3,0.67,0.66,0.67c0.37,0,0.67-0.3,0.67-0.67
    C15.98,15.88,15.69,15.58,15.32,15.58z M15.45,0H4.55C2.04,0,0,2.04,0,4.55v10.91C0,17.97,2.04,20,4.55,20h10.91c2.51,0,4.55-2.04,4.55-4.55V4.55
    C20,2.04,17.96,0,15.45,0z M12.97,4.94C13.54,4.94,14,5.4,14,5.96s-0.46,1.03-1.03,1.03c-0.57,0-1.03-0.46-1.03-1.03
    C11.95,5.4,12.41,4.94,12.97,4.94z M12.97,8.02c0.57,0,1.03,0.46,1.03,1.03c0,0.57-0.46,1.03-1.03,1.03c-0.57,0-1.03-0.46-1.03-1.03
    C11.95,8.48,12.41,8.02,12.97,8.02z M9.98,4.94c0.57,0,1.03,0.46,1.03,1.03s-0.46,1.03-1.03,1.03c-0.57,0-1.03-0.46-1.03-1.03
    C8.95,5.4,9.41,4.94,9.98,4.94z M9.98,8.02c0.57,0,1.03,0.46,1.03,1.03s-0.46,1.03-1.03,1.03c-0.57,0-1.03-0.46-1.03-1.03
    C8.95,8.48,9.41,8.02,9.98,8.02z M7.03,4.94c0.57,0,1.03,0.46,1.03,1.03S7.6,6.99,7.03,6.99C6.46,6.99,6,6.53,6,5.96
    C6,5.4,6.46,4.94,7.03,4.94z M7.03,8.02c0.57,0,1.03,0.46,1.03,1.03s-0.46,1.03-1.03,1.03C6.46,10.08,6,9.62,6,9.05
    C6,8.48,6.46,8.02,7.03,8.02z M4.6,18.02c-1.02,0-1.86-0.83-1.86-1.86c0-1.03,0.83-1.86,1.86-1.86c1.03,0,1.86,0.83,1.86,1.86
    C6.45,17.19,5.62,18.02,4.6,18.02z M15.32,18.1c-1.02,0-1.86-0.83-1.86-1.86c0-1.03,0.83-1.86,1.86-1.86c1.03,0,1.86,0.83,1.86,1.86
    C17.17,17.27,16.34,18.1,15.32,18.1z M18.48,2.79l-1.92,7.14c-0.51,1.91-2.03,3.1-4,3.1H7.2c-1.91,0-3.26-1.09-3.84-2.91L1.73,5
    C1.7,4.9,1.72,4.79,1.78,4.71c0.06-0.09,0.16-0.14,0.27-0.14l0.31,0c0.75,0,1.41,0.49,1.64,1.2l1.2,3.76
    c0.32,1.02,1.26,1.7,2.33,1.7h4.81c1.1,0,2.08-0.74,2.36-1.81l1.55-5.78c0.2-0.75,0.89-1.28,1.67-1.28h0.24
    c0.1,0,0.2,0.05,0.26,0.13C18.48,2.58,18.5,2.68,18.48,2.79z M4.6,15.5c-0.37,0-0.66,0.3-0.66,0.67c0,0.37,0.3,0.67,0.66,0.67c0.37,0,0.67-0.3,0.67-0.67
    S4.96,15.5,4.6,15.5z';
	}
	
	protected function _get_product_icon_path() 
	{
		return 'M16.43,5.12c-0.13-1.19-0.15-1.19-1.35-1.33c-0.21-0.02-0.21-0.02-0.43-0.05c-0.01,0.06,0.06,0.78,0.14,1.13
	c0.57,0.37,0.87,0.98,0.87,1.71c0,1.14-0.93,2.07-2.07,2.07s-2.07-0.93-2.07-2.07c0-0.54,0.09-0.97,0.55-1.4
	c-0.06-0.61-0.19-1.54-0.18-1.64C10.14,3.46,8.72,3.46,8.58,3.6l-8.17,8.13c-0.56,0.55-0.56,1.43,0,1.97l5.54,5.93
	c0.56,0.55,1.46,0.55,2.01,0l8.67-8.14C17.04,11.09,16.68,7.14,16.43,5.12z M16.06,0.04c-1.91,0-3.46,1.53-3.46,3.41c0,0.74,0.4,3.09,0.44,3.28c0.07,0.34,0.52,0.56,0.86,0.49
	C14,7.19,14.07,7.15,14.12,7.1c0.24-0.11,0.32-0.39,0.25-0.68c-0.09-0.45-0.39-2.44-0.39-2.94c0-1.16,0.94-2.09,2.11-2.09
	c1.24,0,2.11,0.96,2.11,2.34c0,2.43-0.31,4.23-0.32,4.26c-0.1,0.17-0.1,0.38-0.03,0.55c0.03,0.17,0.13,0.31,0.28,0.4
	c0.1,0.06,0.22,0.09,0.33,0.09c0.21,0,0.42-0.1,0.54-0.3c0.06-0.09,0.52-2.17,0.52-5.03C19.52,1.61,18.04,0.04,16.06,0.04z';
	}
}

$ecwid_gutenberg = new Ecwid_Integration_Gutenberg();