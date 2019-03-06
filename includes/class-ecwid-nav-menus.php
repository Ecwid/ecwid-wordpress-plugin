<?php

class Ecwid_Nav_Menus {

    const OPTION_USE_JS_API_FOR_CATS_MENU = 'ecwid_use_js_api_to_open_store_categories_pages';
    const OPTVAL_USE_JS_API_FOR_CATS_MENU_TRUE = 'on';
	const OPTVAL_USE_JS_API_FOR_CATS_MENU_FALSE = 'off';
	const OPTVAL_USE_JS_API_FOR_CATS_MENU_AUTO = 'auto';
	const FILTER_USE_JS_API_FOR_CATS_MENU = 'ecwid_use_js_api_for_categories_menu';

	protected $item_types;

	public function __construct() {
		add_action('init', array( $this, 'init' ));

		add_filter('wp_get_nav_menu_items', array( $this, 'process_menu_items' ));

		if ( is_admin() ) {
			add_filter('admin_init', array( $this, 'add_meta_box' ));

			add_action('admin_enqueue_scripts', array( $this, 'enqueue_admin_assets' ) );
		} else {
			add_action('wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
			add_filter( 'nav_menu_link_attributes', array( $this, 'nav_menu_link_attributes' ), 10, 2 );
		}
	}

	public function init() {
		register_post_type('ecwid_menu_item',
			array(
				'labels' => array(
					'name' => __( 'Ecwid Menu Item', 'ecwid-shopping-cart' ),
				),
				'supports' => array( 'title' ),

				'public'              => FALSE,
				'exclude_from_search' => TRUE,
				'publicly_queryable'  => FALSE,
				'show_ui'             => FALSE,
				'show_in_menu'        => FALSE,
				'show_in_nav_menus'   => FALSE,
				'show_in_admin_bar'   => FALSE,
				'has_archive'         => FALSE,
			)
		);
	}

	public static function should_use_js_api_for_categories_menu()
    {
		$value = get_option( self::OPTION_USE_JS_API_FOR_CATS_MENU );
		
		if ( $value == self::OPTVAL_USE_JS_API_FOR_CATS_MENU_TRUE ) {
		    return true;
        }
        if ( $value == self::OPTVAL_USE_JS_API_FOR_CATS_MENU_FALSE ) {
		    return false;
        }
        
        $value = apply_filters( self::FILTER_USE_JS_API_FOR_CATS_MENU, $value );
		
		return $value == self::OPTVAL_USE_JS_API_FOR_CATS_MENU_TRUE;
    }
	
	static public function add_menu_on_activate( ) {

		$locations = get_nav_menu_locations();

		if (empty($locations)) return;

		foreach ($locations as $name => $menu_id) {
			if ($menu_id > 0) {
				break;
			}
		}
		if ($menu_id == 0) return;

		$existing = self::_find_existing_store_page_menu($menu_id);
		if ($existing) return;

		$items = wp_get_nav_menu_items($menu_id);

		wp_update_nav_menu_item($menu_id, 0, array(
				'menu-item-title' => 'Store',
				'menu-item-object' => 'ecwid-store-with-categories',
				'menu-item-type' => 'ecwid_menu_item',
				'menu-item-status' => 'publish')
		);
	}

	static public function replace_auto_added_menu() {

		$options = get_option('nav_menu_options');
		$autofill = $options['auto_add'];
		if (empty($autofill)) {
			return false;
		}

		$store_page_id = get_option('ecwid_store_page_id');
		foreach ($autofill as $menu_id) {
			$items = wp_get_nav_menu_items($menu_id);
			foreach ($items as $item) {
				if ( $item->object == 'page' && $item->object_id == $store_page_id && time() - strtotime($item->post_date_gmt) < 60 ) {
					$result = wp_update_nav_menu_item($menu_id, $item->db_id, array(
							'menu-item-title' => $item->title,
							'menu-item-status' => $item->status,
							'menu-item-object' => 'ecwid-store-with-categories',
							'menu-item-type' => 'ecwid_menu_item')
					);
				}
			}
		}
	}

	static protected function _find_existing_store_page_menu($menu_id) {
		$items = wp_get_nav_menu_items($menu_id);

		if (empty($items)) return null;

		foreach ($items as $item) {
			if ( $item->object == 'page' && $item->object_id == Ecwid_Store_Page::get_current_store_page_id() )
				return $item;

			if ($item->object == 'ecwid-store-with-categories' || $item->object == 'ecwid-store') {
				return $item;
			}
		}

		return null;
	}

	public function add_meta_box() {
		add_meta_box('ecwid_nav_links', __('Store', 'ecwid-shopping-cart'), array( $this, 'create_menu_items'), 'nav-menus', 'side');
	}

	public function enqueue_frontend_assets() {
		 
		if ( Ecwid_Store_Page::get_current_store_page_id() != get_the_ID() && !Ecwid_Ajax_Defer_Renderer::is_enabled() ) {
			return;
		}

		wp_enqueue_script( 'ecwid-menu', ECWID_PLUGIN_URL . 'js/nav-menu-frontend.js', array( 'jquery' ), get_option('ecwid_plugin_version') );
		wp_localize_script( 'ecwid-menu', 'ecwid_menu_data', array(
			'items' => $this->get_nav_menu_items()
		) );
	}

	public function enqueue_admin_assets() {
		$screen = get_current_screen();

		if ($screen->base != 'nav-menus') return;

		wp_enqueue_style('ecwid-nav-menu',  ECWID_PLUGIN_URL . 'css/nav-menu.css', array(), get_option('ecwid_plugin_version'));

		EcwidPlatform::set('nav-menus-opened-once', null);

		$first_run = false;
		// It opens the page twice on the very first run of that page
		if (EcwidPlatform::get('nav-menus-opened-once', false) < 2) {
			EcwidPlatform::set('nav-menus-opened-once', EcwidPlatform::get('nav-menus-opened-once') + 1);
			$first_run = EcwidPlatform::get('nav-menus-opened-once') <= 2;
		}

		wp_enqueue_script('ecwid-admin-menu-js', ECWID_PLUGIN_URL . 'js/nav-menu.js', array(), get_option('ecwid_plugin_version'));
		wp_localize_script('ecwid-admin-menu-js', 'ecwid_nav_menu_params', array(
			'store_page' => __('Store Page', 'ecwid-shopping-cart'),
			'reset_cats_cache' => __('Refresh categories list', 'ecwid-shopping-cart'),
			'cache_updated' => __('Done', 'ecwid-shopping-cart'),
			'reset_cache_message' => __('The store top-level categories are automatically added to this drop-down menu', 'ecwid-shopping-cart'),
			'first_run' => $first_run,
			'register_link' => ecwid_get_register_link(),
			'items' => $this->get_nav_menu_items()
		));
	}
    
	public function process_menu_items($items)
	{
		if ( is_admin() || empty($items) ) {
			return $items;
		}

		$types = $this->get_nav_menu_items();

		$counter = 0;

		for ( $i = 0; $i < count($items); $i++ ) {
            
			if (!isset($items[$i])) {
				continue;
			}
			
			$item = $items[$i];
			$items[$i]->menu_order = $i + 1;

			$ecwid_menu_type = isset($types[$item->object]) ? $types[$item->object] : null;

			if ( $ecwid_menu_type && isset( $ecwid_menu_type['ecwid-page'] ) ) {
				$item->url = Ecwid_Store_Page::get_menu_item_url($ecwid_menu_type);
				$item->ecwid_page_type = $ecwid_menu_type['ecwid-page'];
			}

			if ($item->object == 'ecwid-store-with-categories' || $item->object == 'ecwid-store') {
				$item->url = Ecwid_Store_Page::get_store_url();
			}
            
			if ($item->object == 'ecwid-store-with-categories') {
				$posts = EcwidPlatform::cache_get( 'nav_categories_posts' );
                
				if ( !$posts ) {
					$posts = array();
					$api = new Ecwid_Api_V3();
					$result = $api->get_categories( array( 'parent' => 0 ) );
					
					if ( $result && $result->count > 0 ) {
					    
					    $categories = $result->items;
					    usort( $categories, Ecwid_Category::usort_callback() );
					    
                        foreach ($categories as $category) {
                            $category = Ecwid_Category::get_by_id( $category->id );
                            $post = new stdClass;
                            $post->ID = -1;
                            $post->post_author = '';
                            $post->post_date = '';
                            $post->post_date_gmt = '';
                            $post->post_password = '';
                            $post->post_name = '';
                            $post->post_type = $item->post_type;
                            $post->post_status = 'publish';
                            $post->to_ping = '';
                            $post->pinged = '';
                            $post->post_parent = 0;
                            $post->url = $category->get_link( Ecwid_Store_Page::get_store_url() );
                            $post->classes = '';
                            $post->type = 'post';
                            $post->db_id = 0;
                            $post->title = $category->name;
                            $post->target = '';
                            $post->object = '';
                            $post->attr_title = '';
                            $post->description = '';
                            $post->xfn = '';
                            $post->object_id = 0;
    						$post->ecwid_page_type = 'category';
    						$post->ecwid_category_id = $category->id;
    
                            $posts[] = $post;
                        }
    
                        EcwidPlatform::cache_set( 'nav_categories_posts', $posts, DAY_IN_SECONDS );
					}
                }

				foreach ( $posts as $post ) {
					$counter++;
					$post->menu_item_parent = $item->ID;
					array_splice( $items, $i + $counter, 0, array( $post ) );
				}
				$counter++;
            }
		}
		
		return $items;
	}

	public function nav_menu_link_attributes( $attributes, $item )
    {
		if ( !isset( $item->ecwid_page_type ) ) {
            return $attributes;
        }

		if ( in_array( $item->ecwid_page_type, array( 'cart', 'account/orders' ) ) ) {
			$attributes['rel'] = 'nofollow';
		}

		if ( Ecwid_Store_Page::get_current_store_page_id() != get_the_ID() ) {
		    return $attributes;
        }
        
		$attributes['data-ecwid-page'] = $item->ecwid_page_type;
        
        if ( $item->ecwid_page_type == 'category' ) {
            $attributes['data-ecwid-category-id'] = $item->ecwid_category_id;
        }
        
        
        return $attributes;
    }
	
	public function create_menu_items() {
		$menu_links = $this->get_nav_menu_items();
		?>
		<div id="posttype-ecwid-links" class="posttypediv">
			<div id="tabs-panel-ecwid-links" class="tabs-panel tabs-panel-active">
				<ul id="ecwid-links-checklist" class="categorychecklist form-no-clear">
					<?php
					$i = -1;
					foreach ($menu_links as $key => $value) {
						?>
						<li>
							<label class="menu-item-title">
								<input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo esc_attr($i); ?>][menu-item-object-id]" value="<?php echo esc_attr($i); ?>" /> <?php echo $value['list-name']; ?>
							</label>
							<input type="hidden" class="menu-item-object" name="menu-item[<?php echo esc_attr($i); ?>][menu-item-object]" value="<?php echo $value['classes']; ?>" />
							<input type="hidden" class="menu-item-type" name="menu-item[<?php echo esc_attr($i); ?>][menu-item-type]" value="ecwid_menu_item" />
							<input type="hidden" class="menu-item-title" name="menu-item[<?php echo esc_attr($i); ?>][menu-item-title]" value="<?php echo esc_html($value['label'] ); ?>" />
							<input type="hidden" class="menu-item-url" name="menu-item[<?php echo esc_attr($i); ?>][menu-item-url]" value="<?php echo esc_url(Ecwid_Store_Page::get_store_url() . '#!/~/' . $value['url']); ?>" />
						</li>
						<?php
						$i--;
					}
					?>
				</ul>
			</div>
			<p class="button-controls">
				<span class="list-controls">
					<a href="<?php echo admin_url('nav-menus.php?page-tab=all&selectall=1#posttype-ecwid-links'); ?>" class="select-all"><?php _e('Select All'); ?></a>
				</span>
				<span class="add-to-menu">
					<input type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e('Add to Menu'); ?>" name="add-post-type-menu-item" id="submit-posttype-ecwid-links">
					<span class="spinner"></span>
				</span>
			</p>
		</div>
		<?php

	}

	protected function get_nav_menu_items() {
		if ($this->item_types != null) {
			return $this->item_types;
		}

		$this->item_types = array(
			'ecwid-cart' => array(
				'list-name'  => __('Cart', 'ecwid-shopping-cart'),
				'classes'    => 'ecwid-cart',
				'url'        => 'cart',
				'label'      => __('Shopping Cart', 'ecwid-shopping-cart'),
				'name'		 => 'cart',
				'ecwid-page' => 'cart',
				'nofollow'   => true
			),
			'ecwid-product-search' => array(
				'list-name'  => __('Product Search', 'ecwid-shopping-cart'),
				'classes'    => 'ecwid-product-search',
				'url'        => 'search',
				'label'      => __('Product Search', 'ecwid-shopping-cart'),
				'name' 		 => 'search',
                'ecwid-page' => 'search'
			),
			'ecwid-my-account' => array(
				'list-name'  => __('My Account', 'ecwid-shopping-cart'),
				'classes'    => 'ecwid-my-account',
				'url'        => 'accountSettings',
				'label'      => __('My Account', 'ecwid-shopping-cart'),
				'name'		 => 'account',
				'ecwid-page' => 'account/orders',
                'nofollow'   => true
			),
			'ecwid-store' => array(
				'list-name'  => __('Store', 'ecwid-shopping-cart'),
				'classes'    => 'ecwid-store',
				'url'        => '',
				'label'      => __('Store', 'ecwid-shopping-cart'),
				'name'		 => 'store',
				'ecwid-page' => '/'
			),
			'ecwid-store-with-categories' => array(
				'list-name'  => __('Store with Categories Menu', 'ecwid-shopping-cart'),
				'classes'    => 'ecwid-store-with-categories',
				'url'        => '',
				'label'      => __('Store', 'ecwid-shopping-cart'),
				'name'		 => 'store-with-categories',
                'ecwid-page' => '/'
			)
		);

		return $this->item_types;
	}
}
$ecwid_menus = new Ecwid_Nav_Menus();