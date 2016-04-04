<?php

class Ecwid_Nav_Menus {
	public function __construct() {
		add_action('init', array( $this, 'init' ));

		add_filter('wp_get_nav_menu_items', array( $this, 'process_menu_items' ));

		if ( is_admin() ) {
			add_filter('admin_init', array( $this, 'add_meta_box' ));

			add_action('admin_enqueue_scripts', array( $this, 'add_admin_styles' ) );
			add_action('admin_enqueue_scripts', array( $this, 'add_admin_scripts') );
		}
	}

	public function init() {
		register_post_type('ecwid_menu_item',
			array(
				'labels'              => array(
					'name' => __('Ecwid Menu Item'),
				),
				'supports'            => array( 'title' ),

				// Doesn't need to be listed most places as it's not a valid content item on it's own
				'public'              => FALSE, // Base setting. More specific settings below
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

	public function add_meta_box() {
		add_meta_box('ecwid_nav_links', __('Store', 'ecwid-shopping-cart'), array( $this, 'create_menu_items'), 'nav-menus', 'side');
	}

	public function add_admin_styles() {
		if (get_current_screen()->base == 'nav-menus') {
			wp_enqueue_style('ecwid-nav-menu',  ECWID_PLUGIN_URL . 'css/nav-menu.css', array(), get_option('ecwid_plugin_version'));
		}
	}

	public function add_admin_scripts() {
		$screen = get_current_screen();

		if ($screen->base == 'nav-menus') {
			EcwidPlatform::set('nav-menus-opened-once', null);

			$first_run = false;
			// It opens the page twice on the very first run of that page
			if (EcwidPlatform::get('nav-menus-opened-once', false) < 2) {
				EcwidPlatform::set('nav-menus-opened-once', EcwidPlatform::get('nav-menus-opened-once') + 1);
				$first_run = EcwidPlatform::get('nav-menus-opened-once') <= 2;
			}

			wp_enqueue_script('ecwid-admin-menu-js', ECWID_PLUGIN_URL . 'js/nav-menu.js', array(), get_option('ecwid_plugin_version'));
			wp_localize_script('ecwid-admin-menu-js', 'ecwid_params', array(
				'store_page' => __('Store Page', 'ecwid-shopping-cart'),
				'reset_cats_cache' => __('Refresh categories list', 'ecwid-shopping-cart'),
				'cache_updated' => __('Done', 'ecwid-shopping-cart'),
				'reset_cache_message' => __('The store top-level categories are automatically added to this drop-down menu', 'ecwid-shopping-cart'),
				'first_run' => $first_run,
				'register_link' => ecwid_get_register_link()
			));
		}
	}

	public function process_menu_items($items)
	{

		if (is_admin()) {
			return $items;
		}

		$categories = ecwid_get_categories();

		$counter = 0;
		foreach ($items as $key => $item) {

			$items[$key]->menu_order += $counter;

			if ($item->object == 'ecwid-my-account') {
				$item->url = ecwid_get_store_page_url() . '#!/~/';
			}
			if (in_array('ecwid-store-with-categories', $item->classes) && !empty($categories)) {
				foreach ($categories as $category) {
					$counter ++;
					$post = new stdClass;
					$post->ID = 0;
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
					$post->menu_order = $item->menu_order + $counter;
					$post->menu_item_parent = $item->ID;
					$post->url = ecwid_get_store_page_url() . $category->link;
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
					array_splice($items, $key + $counter, 0, array($post));
				}
				$counter++;
			}
		}

		return $items;
	}

	public function create_menu_items() {
		$menu_links = array(
			'Cart'                  => array(
				'list-name' => __('Cart', 'ecwid-shopping-cart'),
				'classes'   => 'ecwid-cart',
				'url'       => 'cart',
				'label'     => __('Shopping Cart', 'ecwid-shopping-cart')
			),
			'Search'                => array(
				'list-name' => __('Product Search', 'ecwid-shopping-cart'),
				'classes'   => 'ecwid-product-search',
				'url'       => 'search',
				'label'     => __('Product Search', 'ecwid-shopping-cart')
			),
			'Account'               => array(
				'list-name' => __('My Account', 'ecwid-shopping-cart'),
				'classes'   => 'ecwid-my-account',
				'url'       => 'accountSettings',
				'label'     => __('My Account', 'ecwid-shopping-cart')
			),
			'Store'                 => array(
				'list-name' => __('Store', 'ecwid-shopping-cart'),
				'classes'   => 'ecwid-store',
				'url'       => '',
				'label'     => __('Store', 'ecwid-shopping-cart')
			),
			'Store with categories' => array(
				'list-name' => __('Store with Categories Menu', 'ecwid-shopping-cart'),
				'classes'   => 'ecwid-store-with-categories',
				'url'       => '',
				'label'     => __('Store', 'ecwid-shopping-cart')
			)
		);
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
							<input type="hidden" class="menu-item-title" name="menu-item[<?php echo esc_attr($i); ?>][menu-item-title]" value="<?php echo esc_html(isset( $value['label'] ) ? $value['label'] : $key); ?>" />
							<input type="hidden" class="menu-item-url" name="menu-item[<?php echo esc_attr($i); ?>][menu-item-url]" value="<?php echo esc_url(ecwid_get_store_page_url() . '#!/~/' . $value['url']); ?>" />
							<input type="hidden" class="menu-item-classes" name="menu-item[<?php echo esc_attr($i); ?>][menu-item-classes]" value="<?php echo $value['classes']; ?>" />
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
}
$ecwid_menus = new Ecwid_Nav_Menus();
