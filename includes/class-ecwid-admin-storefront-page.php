<?php
class Ecwid_Admin_Storefront_Page
{
	const ADMIN_SLUG = 'ec-storefront-settings';
	public static $templates_dir;
	
	public function __construct() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'register_script_for_select_block' ) );

		add_action( 'wp_ajax_ecwid_storefront_set_status', array( $this, 'ajax_set_status' ) );
		add_action( 'wp_ajax_ecwid_storefront_set_store_on_front', array( $this, 'ajax_set_store_on_front' ) );
		add_action( 'wp_ajax_ecwid_storefront_set_display_cart_icon', array( $this, 'ajax_set_display_cart_icon' ) );
		add_action( 'wp_ajax_ecwid_storefront_set_page_slug', array( $this, 'ajax_set_page_slug' ) );
		add_action( 'wp_ajax_ecwid_storefront_set_mainpage', array( $this, 'ajax_set_mainpage' ) );
		add_action( 'wp_ajax_ecwid_storefront_create_page', array( $this, 'ajax_create_page' ) );
	}

	public static function do_page() {

		self::$templates_dir = ECWID_PLUGIN_DIR . '/templates/admin/storefront/';

		$page_id = get_option( Ecwid_Store_Page::OPTION_MAIN_STORE_PAGE_ID );
		$store_pages = false;

		if( ecwid_is_demo_store() ) {
			$page_status = 'demo';
			$page_link = get_permalink( $page_id );
		} elseif( !$page_id ) {
			$page_status = 'no-pages';
		}

		if( $page_id && !isset( $page_status ) ) {
			
            $page_data = self::get_page_data( $page_id );
            extract( $page_data, EXTR_PREFIX_ALL, 'page' );

			$store_on_front = Ecwid_Seo_Links::is_store_on_home_page();

			if( self::is_gutenberg_active() ) {
				$design_edit_link = get_edit_post_link( $page_id ) . '&ec-show-store-settings';
			} else {
				$page = Ecwid_Admin_Main_Page::PAGE_HASH_DASHBOARD;
				$time = time() - get_option('ecwid_time_correction', 0);
				$iframe_src = ecwid_get_iframe_src($time, $page);
				
				if( !$iframe_src ) {
					$design_edit_link = 'https://' . Ecwid_Config::get_cpanel_domain() . '/#design';
				} else {
					$design_edit_link = get_admin_url( null, 'admin.php?page=' . Ecwid_Admin::ADMIN_SLUG . '-admin-design' );
				}
			}

			if( class_exists( 'Ecwid_Floating_Minicart' ) ) {
				$minicart_hide = get_option( Ecwid_Floating_Minicart::OPTION_WIDGET_DISPLAY ) == Ecwid_Floating_Minicart::DISPLAY_NONE;
				$customizer_minicart_link = admin_url('customize.php') . '?autofocus[section]=ec-store-minicart&url=' . urlencode($page_link);
			}

			if ( count ( Ecwid_Store_Page::get_store_pages_array_for_selector() ) > 1 ) {
				$store_pages = Ecwid_Store_Page::get_store_pages_array_for_selector();
			}

			$categories = ecwid_get_categories_for_selector();

			$api = new Ecwid_Api_V3();
			$res = $api->get_products( array() );
			if( $res ) {
				$products = $res->items;
				$products_total = $res->total;
			}

			$need_show_draft_warning = time() - get_option( 'ecwid_installation_date' ) > 3 * DAY_IN_SECONDS;
		}

        wp_enqueue_script('ecwid-admin-storefront-js', ECWID_PLUGIN_URL . 'js/admin-storefront.js', array(), get_option('ecwid_plugin_version'));

        echo Ecwid_Admin_UI_Framework::print_fix_js();
		require_once self::$templates_dir . 'main.php';
	}

    public function get_page_data( $page_id ) {
    	$status = get_post_status( $page_id );

    	if( !$status ) {
    		$status = 'no-pages';
    	}

    	if( $status == 'trash' ) {
    		$status = 'draft';
    	}

        $page = array(
            'link' => get_permalink( $page_id ),
            'edit_link' => get_edit_post_link( $page_id ),
            'slug' => urldecode( get_post_field( 'post_name', $page_id ) ),
            'status' => $status
        );

        return $page;
    }

	public function ajax_set_status() {
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
        $new_status = $page_statuses[ $status ];

		wp_update_post(array(
			'ID' => $page_id,
			'post_status' => $new_status
		));

		$page_data = self::get_page_data( $page_id );
        wp_send_json(
            array(
                'status' => 'success',
                'storepage' => $page_data
            )
        );
	}

	public function ajax_set_store_on_front() {
		$status = intval( $_GET['status'] );

        $store_page_id = get_option( Ecwid_Store_Page::OPTION_MAIN_STORE_PAGE_ID );

		if( $status ) {
			$this->_set_previous_frontpage_settings();
			$page_id = $store_page_id;
			$type = 'page';
		} else {			
			$saved_settings = $this->_get_previous_frontpage_settings();
			$page_id = $saved_settings['page_on_front'];
			$type = $saved_settings['show_on_front'];
		}

		update_option( 'page_on_front', $page_id );
		update_option( 'show_on_front', $type );

        $page_data = self::get_page_data( $store_page_id );
		wp_send_json(
            array(
                'status' => 'success',
                'storepage' => $page_data
            )
        );
	}

    public function ajax_set_mainpage() {
        $page_id = intval( $_GET['page'] );

        if( !Ecwid_Store_Page::is_store_page( $page_id ) ) {
            wp_send_json(array('status' => 'error'));
        }

        if( get_option( 'show_on_front' ) == 'page' ) {
            $front_page_id = get_option( 'page_on_front' );
            if( Ecwid_Store_Page::is_store_page($front_page_id) ) {
                update_option( 'page_on_front', $page_id );
            }
        }
        
        Ecwid_Store_Page::update_main_store_page_id( $page_id );

        $page_data = self::get_page_data( $page_id );
        wp_send_json(
            array(
                'status' => 'success',
                'storepage' => $page_data
            )
        );
    }

	public function ajax_set_display_cart_icon() {
		$status = intval( $_GET['status'] );

		if( $status ) {
			update_option( Ecwid_Floating_Minicart::OPTION_WIDGET_DISPLAY, Ecwid_Floating_Minicart::DISPLAY_ALL );
			update_option( Ecwid_Floating_Minicart::OPTION_SHOW_EMPTY_CART, 1 );
		} else {
			update_option( Ecwid_Floating_Minicart::OPTION_WIDGET_DISPLAY, Ecwid_Floating_Minicart::DISPLAY_NONE );
		}
		
		wp_send_json(array('status' => 'success'));
	}

	public function ajax_set_page_slug() {
		$slug = sanitize_title( $_GET['slug'] );

		$args = array(
			'name' => $slug,
			'post_type' => 'page',
			'post_status' => 'publish',
			'numberposts' => 1,
			'exclude' => get_option( Ecwid_Store_Page::OPTION_MAIN_STORE_PAGE_ID )
		);
		$posts = get_posts($args);

		if( !$posts ) {
			$page_id = get_option( Ecwid_Store_Page::OPTION_MAIN_STORE_PAGE_ID );
			wp_update_post(array(
				'ID' => $page_id,
				'post_name' => $slug
			));
			
			$page_data = self::get_page_data( $page_id );
            wp_send_json(
                array(
                    'status' => 'success',
                    'storepage' => $page_data
                )
            );
		} else {
			wp_send_json(
				array(
					'status' => 'error',
					'message' => __( 'Page with that name already exists.', 'ecwid-shopping-cart' )
				)
			);
		}
	}

	public function ajax_create_page() {
		$type = sanitize_title( $_GET['type'] );

		if( isset($_GET['item_id']) ) {
			$item_id = intval( $_GET['item_id'] );
		} else {
			$item_id = '';
		}

		$pages = array(
			'store' => array(
				'title' => __('Store', 'ecwid-shopping-cart'),
				'block' => 'ecwid/store-block',
				'block_add_shortcode' => true,
				'params' => array(
					'default_category_id' => ''
				)
			),
			'category' => array(
				'title' => __('Category', 'ecwid-shopping-cart'),
				'block' => 'ec-store/category-page',
				'params' => array(
					'default_category_id' => $item_id
				)
			),
			'product' => array(
				'title' => __('Product', 'ecwid-shopping-cart'),
				'block' => 'ec-store/product-page',
				'params' => array(
					'default_product_id' => $item_id
				)
			),
			'cart' => array(
				'title' => __('Cart', 'ecwid-shopping-cart'),
				'block' => 'ec-store/cart-page',
				'params' => array(
					'default_page' => 'cart'
				)
			),
			'search' => array(
				'title' => __('Search products', 'ecwid-shopping-cart'),
				'block' => 'ec-store/filters-page',
				'params' => array(
					'default_page' => 'search'
				)
			)
		);

		if( !isset( $pages[$type] ) ) {
			wp_send_json(array('status' => 'error'));
		}

		$page = $pages[$type];

		if( !isset( $page['params'] ) ) {
			$page['params'] = array();
		}

		$shortcode_params = '';
		foreach( $page['params'] as $key => $value ) {
			$shortcode_params .= sprintf( '%s="%s"', $key, $value );
		}

		$shortcode = sprintf( '[ec_store widgets="productbrowser" %s]', $shortcode_params );

		if( self::is_gutenberg_active() ) {
			if( count($page['params']) ) {
				$block_params = json_encode( $page['params'] );
			} else {
				$block_params = '';
			}

			if( $page['block_add_shortcode'] ) {
				$shortcode = str_replace('ec_store', 'ecwid', $shortcode);
				$content = sprintf( '<!-- wp:%1$s %2$s -->%3$s<!-- /wp:%1$s -->', $page['block'], $block_params, $shortcode );
			} else {
				$content = sprintf( '<!-- wp:%1$s %2$s /-->', $page['block'], $block_params );
			}
		} else {
			$content = $shortcode;
		}

		$post = array(
			'post_title' 	=> $page['title'],
			'post_content' 	=> $content,
			'post_status' 	=> 'draft',
			'post_author' 	=> 1,
			'post_type' 	=> 'page',
			'comment_status' => 'closed'
		);
		
		$page_id = wp_insert_post( $post );
		$url = get_edit_post_link( $page_id, 'context' );

		if( $type != 'store' ) {
			$page_id = get_option( Ecwid_Store_Page::OPTION_MAIN_STORE_PAGE_ID );
		}
		$page_data = self::get_page_data( $page_id );

        wp_send_json(
            array(
                'status' => 'success',
                'url' => $url,
                'storepage' => $page_data,
                'open_page' => ($type != 'store')
            )
        );
	}

	private function _set_previous_frontpage_settings() {
		$settings = array(
			'page_on_front' => get_option( 'page_on_front' ),
			'show_on_front' => get_option( 'show_on_front' )
		);

		update_option( 'ecwid_frontpage_settings', $settings );
	}

	private function _get_previous_frontpage_settings() {
		$settings = get_option( 'ecwid_frontpage_settings', false );

		if( !$settings ) {
			$settings = array(
				'page_on_front' => 0,
				'show_on_front' => 'posts'
			);
		}

		return $settings;
	}

	public static function is_gutenberg_active() {
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

	public function register_script_for_select_block() {
		
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

		wp_register_script( 'ec-blockeditor-inline-js', '', array(), '', true );
		wp_enqueue_script( 'ec-blockeditor-inline-js'  );
		wp_add_inline_script( 'ec-blockeditor-inline-js', $script );
	}

    public static function render_dropdown_list_items( $items ) {
        if( !is_array($items) ) {
            return false;
        }

        foreach ($items as $key => $item) {
            $attributes = '';
            $text = '';

            if( $item['is_separator'] ) {
                echo '<li class="list-dropdown__separator"></li>';
                continue;
            }

            if( isset($item['attributes']) && is_array($item['attributes']) ) {
                foreach ($item['attributes'] as $attribute => $attribute_value) {
                    $attributes .= sprintf(' %s="%s"', $attribute, $attribute_value);
                }
            }

            if( isset($item['text']) ) {
                $text = $item['text'];
            }

            echo sprintf('<li><a%s>%s</a></li>', $attributes, $text);
        }
    }

    public static function get_dropdown_items( $status, $page_data ) {

    	if( empty($page_data) ) {
    		return;
    	}

    	extract( $page_data, EXTR_PREFIX_ALL, 'page' );

        $items['publish'] = array(
            array(
                'text' => __('View store page', 'ecwid-shopping-cart'),
                'attributes' => array(
                    'href' => $page_link,
                    'target' => '_blank'
                )
            ),
            array(
                'text' => __('Edit page', 'ecwid-shopping-cart'),
                'attributes' => array(
                    'href' => $page_edit_link,
                    'target' => '_blank'
                )
            ),
            array(
                'is_separator' => 1
            ),
            array(
                'text' => __('Switch to draft', 'ecwid-shopping-cart'),
                'attributes' => array(
                    'data-storefront-status' => '0'
                )
            )
        );

        $items['draft'] = array(
            array(
                'text' => __('Preview', 'ecwid-shopping-cart'),
                'attributes' => array(
                    'href' => $page_link,
                    'target' => '_blank'
                )
            ),
            array(
                'text' => __('Edit', 'ecwid-shopping-cart'),
                'attributes' => array(
                    'href' => $page_edit_link,
                    'target' => '_blank'
                )
            ),
            array(
                'text' => __('Publish', 'ecwid-shopping-cart'),
                'attributes' => array(
                    'data-storefront-status' => '1'
                )
            )
        );

        if( isset($items[$status])) {
            return $items[$status];
        }

        return false;
    }

    static public function get_page_url() {
		return admin_url( self::get_relative_page_url() );
	}

	static public function get_relative_page_url() {
		return 'admin.php?page=' . self::ADMIN_SLUG;
	}
}

$_ecwid_admin_storefront_page = new Ecwid_Admin_Storefront_Page();