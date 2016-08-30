<?php

require_once ECWID_PLUGIN_DIR . '/lib/ecwid_api_v3.php';
require_once ECWID_PLUGIN_DIR . '/lib/ecwid_platform.php';

class Ecwid_Products {

	protected $_api;

	const OPTION_UPDATE_TIME = 'update_time';
	const POST_TYPE_PRODUCT = 'ecwid_product';

	public function __construct() {
		$this->_api = new Ecwid_Api_V3(get_ecwid_store_id());

		add_action('init', array($this, 'register_post_type'));
		add_action('admin_init', array($this, 'register_post_type'));
		add_filter( 'the_content', array( __CLASS__, 'content' ) );

	}

	public function content($content) {

		if ( get_post_type() == self::POST_TYPE_PRODUCT ) {

			$ecwid_id = get_post_meta(get_the_ID(), 'ecwid_id');
			$ecwid_id = $ecwid_id[0];

			ob_start();
			require ECWID_PLUGIN_DIR . '/templates/product.php';

			$contents = ob_get_contents();
			return 'abc';
		}

		return $content;
	}

	public function sync() {
		$over = false;

		$offset = 0;
		$limit = 3;

		while (!$over) {
			echo 'offset: ' . $offset . '<br />';

			$products = $this->_api->search_products(array(
				'updatedFrom' => $this->get_last_update_time(),
				'limit' => $limit,
				'offset' => $offset
			));

			echo 'found: ' . $products->total . '<br />';

			if ($products->total == 0) {
				$over = true;
				break;
			}

			foreach ($products->items as $product) {
				$this->_sync_product($product);
			}

			if ($products->total < $offset + $limit) {
				break;
			}

			echo 'offset: ' . $offset . '<br />';

			$offset += $limit;
		}
	}

	public function is_in_sync() {
		$stats = $this->_api->get_store_update_stats();

		$update_time = strtotime($stats->productsUpdated);

		$last_update = $this->get_last_update_time();

		return $last_update > $update_time;
	}

	public function get_last_update_time() {
		return EcwidPlatform::get(self::OPTION_UPDATE_TIME);
	}

	protected function _sync_product($product) {

		$q = new WP_Query(array(
			'post_type' => self::POST_TYPE_PRODUCT,
			'meta_key' => 'ecwid_id',
			'meta_value' => $product->id
          )
		);

		global $wpdb;

		$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->postmeta WHERE meta_key = '%s' AND meta_value = '%s' LIMIT 1", 'ecwid_id', $product->id ) );

		$id = null;
		if (!empty($row)) {
			$id = $row->post_id;
		}

		wp_insert_post(
			array(
				'ID' => $id,
				'post_title' => $product->name,
				'post_content' => $product->description,
				'post_type' => self::POST_TYPE_PRODUCT,
				'meta_input' => array(
					'price' => $product->price,
					'image' => $product->imageUrl,
					'ecwid_id' => $product->id
				),
				'post_status' => 'publish'
			)
		);
	}

	public function register_post_type() {

		register_post_type( self::POST_TYPE_PRODUCT,
			array(
				'public'              => true,
				'capability_type'     => 'product',
				'map_meta_cap'        => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'hierarchical'        => false,
				'show_in_nav_menus'   => true
			)
		);
	}
}

$p = new Ecwid_Products();