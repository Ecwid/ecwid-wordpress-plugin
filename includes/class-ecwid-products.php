<?php

require_once ECWID_PLUGIN_DIR . '/lib/ecwid_api_v3.php';
require_once ECWID_PLUGIN_DIR . '/lib/ecwid_platform.php';

class Ecwid_Products {

	protected $_api;

	const OPTION_UPDATE_TIME = 'update_time';
	const POST_TYPE_PRODUCT = 'product';

	public function __construct() {

		$this->_api = new Ecwid_Api_V3(get_ecwid_store_id());

		add_action('init', array($this, 'register_post_type'));
		add_action('admin_init', array($this, 'register_post_type'));
		add_filter( 'the_content', array( $this, 'content' ) );
		add_filter( 'post_thumbnail_html', array( $this, 'thumbnail' ) );
	}

	public function content($content) {

		if ( get_post_type() == self::POST_TYPE_PRODUCT ) {

			$ecwid_id = get_post_meta(get_the_ID(), 'ecwid_id');
			$ecwid_id = $ecwid_id[0];

			if (is_singular()) {
				ob_start();
				require ECWID_PLUGIN_DIR . '/templates/product.php';

				$contents = ob_get_contents();
				ob_end_clean();

				return $contents;
			}
		}

		return $content;
	}

	public function thumbnail($html) {

		if (get_post_type() == self::POST_TYPE_PRODUCT && is_singular()) {
			return '';
		}

		return $html;
	}


	public function register_post_type() {

		// if woocommerce not active
		if (ecwid_get_woocommerce_status() != 2) {
			register_post_type( self::POST_TYPE_PRODUCT,
				array(
					'public'              => TRUE,
					'capability_type'     => 'product',
					'map_meta_cap'        => TRUE,
					'publicly_queryable'  => TRUE,
					'exclude_from_search' => FALSE,
					'hierarchical'        => FALSE,
					'show_in_nav_menus'   => TRUE,
					'show_ui'             => false
				)
			);
		}
	}


	public function is_in_sync() {
		$stats = $this->_api->get_store_update_stats();

		$update_time = strtotime($stats->productsUpdated);

		$last_update = EcwidPlatform::get(self::OPTION_UPDATE_TIME);

		return $last_update > $update_time;
	}

	public function get_last_update_time() {
		return strftime('%F %T', EcwidPlatform::get(self::OPTION_UPDATE_TIME));
	}

	public function set_last_update_time($time) {
		EcwidPlatform::set(self::OPTION_UPDATE_TIME, $time);
	}

	public function sync() {
		$this->_process_deleted_products();
		$this->_process_products();

		$this->set_last_update_time(time());
	}

	public function delete_all_products() {
		global $wpdb;

		$result = $wpdb->get_col( $wpdb->prepare(
			"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '%s'", 'ecwid_id'
		));

		foreach ($result as $post_id) {
			wp_delete_post($post_id);
		}
	}

	public function disable_all_products() {
		global $wpdb;

		$result = $wpdb->get_col( $wpdb->prepare(
			"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '%s'", 'ecwid_id'
		));

		foreach ($result as $post_id) {
			wp_update_post(array(
				'ID' => $post_id,
				'post_status' => 'draft'
			));
		}
	}

	public function enable_all_products() {
		global $wpdb;

		$result = $wpdb->get_col( $wpdb->prepare(
			"SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '%s'", 'ecwid_id'
		));

		foreach ($result as $post_id) {
			wp_update_post(array(
				'ID' => $post_id,
				'post_status' => 'publish'
			));
		}
	}

	protected function _process_products() {
		$over = FALSE;

		$offset = 0;
		$limit  = 100;

		$start = microtime(true);

		$total_fetch = 0;
		$total_insert = 0;

		while ( ! $over ) {
			$this->_debug_status('offset: ' . $offset);

			$time = microtime(true);
			$products = $this->_api->search_products( array(
				'updatedFrom' => $this->get_last_update_time(),
				'limit'       => $limit,
				'offset'      => $offset
			) );
			$total_fetch += microtime(true) - $time;

			$this->_debug_status('found: ' . $products->total);

			if ( $products->total == 0 ) {
				$over = TRUE;
				break;
			}

			$time = microtime(true);
			foreach ( $products->items as $product ) {
				$this->_process_product( $product );
			}
			$total_insert += microtime(true) - $time;

			if ( $products->total < $offset + $limit ) {
				break;
			}

			$this->_debug_status('offset: ' . $offset);

			$offset += $limit;
		}

		$over = microtime(true);
		$this->_debug_status('total: ' . ($over - $start));
		$this->_debug_status('fetch:' . $total_fetch);
		$this->_debug_status('insert:' . $total_insert);
	}

	protected function _find_post_by_product_id($product_id) {
		global $wpdb;

		$row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->postmeta WHERE meta_key = '%s' AND meta_value = '%s' LIMIT 1", 'ecwid_id', $product_id ) );

		$id = null;
		if (!empty($row)) {
			$id = $row->post_id;
		}

		return $id;
	}

	protected function _process_product( $product ) {

		$id = $this->_find_post_by_product_id( $product->id );

		if ( !$product->enabled ) {
			if ( !is_null( $id ) ) {
				wp_delete_post( $id );
			}

			return null;
		}

		return $this->_sync_product( $product, $id );
	}

	protected function _sync_product( $product, $post_id = null ) {

		$post_id = wp_insert_post(
			array(
				'ID'           => $post_id,
				'post_title'   => $product->name,
				'post_content' => $product->description,
				'post_type'    => self::POST_TYPE_PRODUCT,
				'meta_input'   => array(
					'_price'         => $product->price,
					'_regular_price' => $product->price,
					'image'          => $product->imageUrl,
					'ecwid_id'       => $product->id,
					'_sku'           => $product->sku,
					'_visibility'    => 'visible',
					'_stock_status'  => 'instock',
					'_virtual'       => 'no',

				),
				'post_status'  => 'publish'
			)
		);

		$image_id = get_post_meta( $post_id, '_thumbnail_id' );

		if ( ! $image_id ) {
			$file = download_url( $product->imageUrl );

			$uploaded = wp_upload_bits( basename( $product->imageUrl ), NULL, file_get_contents( $file ) );
			unlink( $file );

			$filetype = wp_check_filetype( $uploaded['file'], NULL );
			$file     = $uploaded['file'];

			$wp_upload_dir = wp_upload_dir();
			$attachment    = array(
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $file ),
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $file ) ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

			$attachment_id = wp_insert_attachment( $attachment, $file, $post_id );
			$attach_data   = wp_generate_attachment_metadata( $attachment_id, $file );
			wp_update_attachment_metadata( $attachment_id, $attach_data );
			set_post_thumbnail( $post_id, $attachment_id );
		}

		return $post_id;
	}

	protected function _process_deleted_products() {
		$over = FALSE;

		$offset = 0;
		$limit  = 100;

		while ( ! $over ) {
			$this->_debug_status('offset: ' . $offset);

			$products = $this->_api->get_deleted_products( array(
				'from_date' => $this->get_last_update_time(),
				'limit'       => $limit,
				'offset'      => $offset
			) );

			$this->_debug_status('found: ' . $products->total);

			if ( $products->total == 0 ) {
				$over = TRUE;
				break;
			}

			foreach ( $products->items as $product ) {
				$post_id = $this->_find_post_by_product_id($product->id);

				if ($post_id) {
					wp_delete_post( $post_id );
				}
			}

			if ( $products->total < $offset + $limit ) {
				break;
			}

			$this->_debug_status('offset: ' . $offset);

			$offset += $limit;
		}
	}

	protected function _debug_status($message) {
		if (!defined('DOING_AJAX')) {
			echo $message . '<br />';
			flush();
		}
	}
}

$p = new Ecwid_Products();
