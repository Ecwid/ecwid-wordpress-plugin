<?php

require_once ECWID_PLUGIN_DIR . '/lib/ecwid_api_v3.php';
require_once ECWID_PLUGIN_DIR . '/lib/ecwid_platform.php';

class Ecwid_Products {

	protected $_api;
	protected $_status;
	protected $_sync_progress_callback;

	const POST_TYPE_PRODUCT = 'ec-product';
	const DB_ALIAS_OUT_OF_STOCK = 'ecwid_out_of_stock';
	const OPTION_ENABLED = 'ecwid_local_base_enabled';
	const OPTION_NO_SSE = 'ecwid_local_base_no_sse';
	const OPTION_NO_IMAGES = 'ecwid_local_base_no_images';

	public function __construct() {

        $this->_api = new Ecwid_Api_V3(get_ecwid_store_id());
        $this->_status = new Ecwid_Products_Sync_Status();
        $this->_status->load();

		add_action( 'ecwid_update_store_id', array( $this, 'on_update_store_id' ) );

		if ( ! self::is_enabled() ) {
			return;
		}

		add_action( 'init', array($this, 'register_post_type' ) );
		add_action( 'admin_init', array($this, 'register_post_type' ) );
		add_filter( 'the_content', array( $this, 'content' ) );
		add_filter( 'post_thumbnail_html', array( $this, 'thumbnail' ) );
		add_action( 'wp_ajax_ecwid_get_post_link', array($this, 'ajax_get_post_link' ) );
		add_action( 'wp_ajax_nopriv_ecwid_get_post_link', array($this, 'ajax_get_post_link' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend' ) );
		add_filter( 'post_type_link', array( $this, 'replace_product_page_url_on_search' ), 10, 3 );
		add_action( 'template_redirect', array( $this, 'redirect_to_store_page' ) );
		add_action( 'ecwid_on_plugin_update', array( $this, 'on_plugin_update' ) );

		if (EcwidPlatform::get('hide_out_of_stock')) {
			add_filter( 'posts_where_paged', array( $this, 'where_out_of_stock' ) );
			add_filter( 'posts_join_paged', array( $this, 'join_out_of_stock' ) );
		}

		$this->_sync_progress_callback = '__return_false';
	}

	public function on_plugin_update() {
		add_option( self::OPTION_NO_SSE, false );
		add_option( self::OPTION_NO_IMAGES, false );
	}

	public function enqueue_frontend() {
		wp_enqueue_script('ecwid-product-page', ECWID_PLUGIN_URL . 'js/product.js', array('jquery'), get_option('ecwid_plugin_version'));
		wp_localize_script('ecwid-product-page', 'ecwidProduct', array(
			'ajaxurl' => admin_url('admin-ajax.php')
		));
	}

	public function replace_product_page_url_on_search( $url, $post, $leavename = false ) {
		if ( $post->post_type == self::POST_TYPE_PRODUCT ) {
			$new_url = $this->_get_post_link( $post->ID );

			if ($new_url) {
				return $new_url;
			}
		}

		return $url;
	}

	public function redirect_to_store_page() {
		$post = get_post();

		if ( $post && $post->post_type == self::POST_TYPE_PRODUCT && is_single() ) {
			$url = $this->_get_post_link($post->ID);

			if ($url) {
				wp_redirect($url, 301);
				exit();
			}
		}
	}


	public function where_out_of_stock($where) {
		if (!is_search()) {
			return $where;
		}
		$where .= ' AND ' . self::DB_ALIAS_OUT_OF_STOCK . '.meta_value=1 ';

		return $where;
	}

	public function join_out_of_stock($join) {
		if (!is_search()) {
			return $join;
		}

		if (!$join) {
			$join = '';
		}

		global $wpdb;

		$join .= 'LEFT JOIN ' . $wpdb->postmeta .' ' . self::DB_ALIAS_OUT_OF_STOCK
		         . ' ON ' . $wpdb->posts . '.id = ' . self::DB_ALIAS_OUT_OF_STOCK . '.post_id'
			     . ' AND ' . self::DB_ALIAS_OUT_OF_STOCK . '.meta_key=' . '"in_stock"';

		return $join;
	}



	public function ajax_get_post_link() {

		if ( !isset( $_REQUEST['product_id'] ) ) {
			return;
		}

		$product_id = intval( @$_REQUEST['product_id'] );

		$link = $this->get_product_link( $product_id );

		if ( $link ) {
			echo json_encode($link);
		}

		exit();
	}

	public function get_product_link( $product_id ) {
		$post_id = $this->_find_post_by_product_id( $product_id );

		if ($post_id) {
			return $this->_get_post_link( $post_id );
		}

		return '';
	}

	protected function _get_post_link( $post_id ) {

		$store_page_url = Ecwid_Store_Page::get_store_url();

		if (! $store_page_url) {
			return '';
		}

		$url = get_post_meta( $post_id, '_ecwid_seo_url', true );

		if ( $url ) {
			return $url;
		}

		$ecwid_product_id = get_post_meta( $post_id, 'ecwid_id', true );

		$url = Ecwid_Store_Page::get_product_url_from_api( $ecwid_product_id );
		if ( $url ) {
			return $url;
		}

		return Ecwid_Store_Page::get_product_url_default_fallback( $ecwid_product_id );
	}


	public function on_update_store_id() {
		$this->_status->reset_dates();
	}

	public function set_sync_progress_callback($callback) {
		$this->_sync_progress_callback = $callback;
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


    public static function is_enabled() {
        return self::is_feature_available() && get_option( self::OPTION_ENABLED, false );
    }

    public static function enable() {
	    flush_rewrite_rules(true);
        update_option( self::OPTION_ENABLED, 1 );
    }

    public static function disable() {
        flush_rewrite_rules(true);
        update_option( self::OPTION_ENABLED, false );
    }

    public static function is_feature_available() {
		return Ecwid_Api_V3::get_token() != false;
	}

    public static function reset_sync_date() {
		Ecwid_Products_Sync_Status::reset_dates();
	}

	public function register_post_type() {

        register_post_type( self::POST_TYPE_PRODUCT,
            array(
                'public'              => TRUE,
                'capability_type'     => 'product',
                'map_meta_cap'        => TRUE,
                'publicly_queryable'  => TRUE,
                'exclude_from_search' => FALSE,
                'hierarchical'        => FALSE,
                'show_in_nav_menus'   => TRUE,
                'show_ui'             => false,
				'labels' => array(
					'name' => __( 'Products', 'ecwid-shopping-cart' )
				)
            )
        );
	}


	public function is_in_sync() {
		$stats = $this->_api->get_store_update_stats();

		$update_time = strtotime($stats->productsUpdated);

		$last_update = EcwidPlatform::get(self::OPTION_UPDATE_TIME);

		return $last_update > $update_time;
	}

	public function set_last_update_time($time) {
		EcwidPlatform::set(self::OPTION_UPDATE_TIME, $time);
	}

	public function get_last_sync_time() {
	    return $this->_status->get_last_sync_time();
    }

	public function estimate_sync() {

		if ( !Ecwid_Api_V3::get_token() ) return array('last_update' => 0);

		$updated = $this->_api->search_products( array(
			'updatedFrom' => $this->_status->get_updated_from(),
			'limit'       => 1,
			'offset'      => 0,
			'sortBy'      => 'UPDATED_TIME_ASC'
		) );


		$deleted = $this->_api->get_deleted_products( array(
			'from_date' => $this->_status->get_deleted_from(),
			'limit'       => 1,
			'offset'      => 0
		) );

		$result = array(
			'total_deleted' => $deleted->total,
			'total_updated' => $updated->total
		);

		$result['last_update_string'] = Ecwid_Api_V3::format_time($this->_status->get_last_sync_time());
        $result['last_update'] = $this->_status->get_last_sync_time();

        if ($updated->total > 0) {
			$result['updated_from'] = $updated->items[0]->updated;
			$result['last_updated'] = Ecwid_Api_V3::format_time($this->_status->last_deleted_product_time);
		}

		if ($deleted->total > 0) {
			$result['deleted_from'] = $deleted->items[0]->date;
			$result['last_deleted'] = Ecwid_Api_V3::format_time($this->_status->last_deleted_product_time);
		}

		$api = new Ecwid_Api_V3();
		$profile = $api->get_store_profile();

		if ($profile && $profile->settings) {
			EcwidPlatform::set('hide_out_of_stock', $profile->settings->hideOutOfStockProductsInStorefront);
		}
		
		return $result;
	}

	public function sync($settings = null) {

		$did_something = false;

		if (!$settings || $settings['mode'] == 'deleted') {
			$did_something = $this->_process_deleted_products( $settings );
		}

		if (!$settings || $settings['one_at_a_time'] && !$did_something) {

			$did_something = $this->_process_products($settings);
		}

		if (!$settings || $settings['one_at_a_time'] && !$did_something) {

			$this->_status->update_last_sync_time( time() );

			return true;
		}

		return false;
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

	protected function _process_products($settings) {
		$over = FALSE;

		$offset = 0;
		$limit  = 100;
		if ($settings && @$settings['offset']) {
			$offset = $settings['offset'];
		}

		if ($settings && $settings['from']) {
			$updated_from = $settings['from'];
		} else {
			$updated_from = $this->_status->get_updated_from();
		}

		while ( ! $over ) {

			$this->_status_event(array(
				'event' => 'fetching_products',
				'offset' => $offset,
				'limit' => $limit
			));

			$params = array(
				'updatedFrom' => $updated_from,
				'limit'       => $limit,
				'offset'      => $offset,
				'sortBy'      => 'UPDATED_TIME_ASC'
			);

			$products = $this->_api->search_products( $params );

			$this->_status_event(
				array_merge(
					$params,
					array(
						'event' => 'found_updated',
						'total' => $products->total,
						'count' => $products->count
					)
				)
			);

			if ( $products->total == 0 || $products->count == 0 ) {
				$over = TRUE;
				return false;
			}

			foreach ( $products->items as $product ) {
				$this->_process_product( $product );
			}

			if ( $products->total < $offset + $limit || @$settings['one_at_a_time'] ) {
				break;
			}

			$offset += $limit;
		}

		return true;
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

			$this->_status_event(
				array(
					'event' => 'deleted_disabled_product',
					'product' => $product
				)
			);

			return null;
		}

		return $this->_sync_product( $product, $id );
	}

	protected function _sync_product( $product, $existing_post_id = null ) {

		$meta = array(
			'_price'         	=> $product->price,
			'_regular_price' 	=> $product->price,
			'image'          	=> $product->imageUrl,
			'ecwid_id'       	=> $product->id,
			'_sku'           	=> $product->sku,
			'_visibility'    	=> 'visible',
			'_ecwid_url'	 	=> substr( $product->url, strpos( $product->url, '#!' ) ),
			'in_stock'  		=> $product->inStock ? '1' : '0',
			'_updatedTimestamp' => $product->updateTimestamp,
		);

		if ( Ecwid_Seo_Links::is_enabled() ) {
			$meta['_ecwid_seo_url'] = $product->url;
		}

		$post_id = wp_insert_post(
			array(
				'ID'           => $existing_post_id,
				'post_title'   => $product->name,
				'post_content' => $product->description,
				'post_type'    => self::POST_TYPE_PRODUCT,
				'post_status'  => 'publish'
			)
		);

		foreach ($meta as $key => $value) {
			add_post_meta($post_id, $key, $value, true);
		}

		if (! get_option(self::OPTION_NO_IMAGES ) ) {
			$image_id = get_post_meta( $post_id, '_thumbnail_id' );

			if ( ! $image_id ) {
				$this->_upload_product_thumbnail( $product, $post_id );
			}
		}

		$this->_status->update_last_updated($product->updateTimestamp);

		$this->_status_event(
			array(
				'event' => $existing_post_id ? 'updated_product' : 'created_product',
				'product' => $product
			)
		);

		return $post_id;
	}

	protected function _process_deleted_products($settings = array()) {
		$over = FALSE;

		$offset = 0;
		$limit  = 100;

		if ($settings && @$settings['offset']) {
			$offset = $settings['offset'];
		}

		if ($settings && $settings['from']) {
			$deleted_from = $settings['from'];
		} else {
			$deleted_from = $this->_status->get_updated_from();
		}

		$deleted_from = $this->_status->get_deleted_from();
		while ( ! $over ) {

			$this->_status_event(array(
				'event' => 'fetching_deleted_product_ids',
				'offset' => $offset,
				'limit' => $limit
			));

			$params = array(
				'from_date' => $deleted_from,
				'limit'       => $limit,
				'offset'      => $offset
			);

			$products = $this->_api->get_deleted_products( $params );

			$this->_status_event(
				array_merge(
					$params,

					array(
						'event' => 'found_deleted',
						'total' => $products->total,
						'count' => $products->count
					)
				)
			);

			if ( $products->total == 0 ) {
				$over = TRUE;
				return false;
			}

			foreach ( $products->items as $product ) {
				$post_id = $this->_find_post_by_product_id($product->id);

				if ($post_id) {
					wp_delete_post( $post_id );
					$this->_status_event(
						array(
							'event' => 'deleted_product',
							'product' => $product
						)
					);
				} else {
					$this->_status_event(
						array(
							'event' => 'skipped_deleted',
							'product' => $product
						)
					);
				}

				$this->_status->update_last_deleted($product->date);
			}

			if ( $products->total < $offset + $limit || @$settings['one_at_a_time'] ) {
				return true;
			}

			$offset += $limit;
		}
	}

	protected function _status_event($event) {
		if ($this->_sync_progress_callback) {
			call_user_func($this->_sync_progress_callback, $event);
		}
	}

	protected function _upload_product_thumbnail( $product, $post_id ) {
		$file = download_url( $product->imageUrl );

		if (is_wp_error($file)) return;

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
}

class Ecwid_Products_Sync_Status {

	const OPTION_UPDATE_TIME = 'update_time';
	const OPTION_LAST_PRODUCT_UPDATE_TIME = 'last_product_update_time';
	const OPTION_LAST_PRODUCT_DELETE_TIME = 'last_product_delete_time';
	const OPTION_LAST_UPDATED_POST_ID = 'last_updated_post_id';

	public $last_sync_time;
	public $last_updated_product_time;
	public $last_deleted_product_time;
	public $current_operation;
	public $error;
	protected $_last_updated_post_id;

	public function load() {
		$this->last_sync_time = EcwidPlatform::get(self::OPTION_UPDATE_TIME, 0);
		$this->last_updated_product_time = EcwidPlatform::get(self::OPTION_LAST_PRODUCT_UPDATE_TIME, 0);
		$this->last_deleted_product_time = EcwidPlatform::get(self::OPTION_LAST_PRODUCT_DELETE_TIME, 0);
	}

	public function get_last_sync_time() {
		return $this->last_sync_time;
	}

	public function update_last_sync_time($date) {
		$this->_set_date_option(self::OPTION_UPDATE_TIME, $date);
		$this->last_sync_time = $date;
		$this->update_last_deleted($date);
		$this->update_last_updated($date);
	}

	public function update_last_deleted($date) {
		$this->_set_date_option(self::OPTION_LAST_PRODUCT_DELETE_TIME, $date);
	}

	public function set_last_updated_post_id($id) {
		EcwidPlatform::set(self::OPTION_LAST_UPDATED_POST_ID, $id);
	}

	public function update_last_updated($date) {
		$this->_set_date_option(self::OPTION_LAST_PRODUCT_UPDATE_TIME, $date);
	}

	public function get_updated_from() {

		if (!$this->last_updated_product_time) {
			return $this->get_last_sync_time();
		}

		return $this->last_updated_product_time;
	}

	public function get_deleted_from() {
		if (!$this->last_deleted_product_time) {
			return $this->get_last_sync_time();
		}

		return $this->last_deleted_product_time;
	}

	public function _set_date_option($option, $date) {
		if (!is_int($date)) {
			$date = strtotime($date);
		}
		EcwidPlatform::set($option, $date);
	}

	public static function reset_dates() {
		foreach(
			array(
				self::OPTION_LAST_PRODUCT_DELETE_TIME,
				self::OPTION_LAST_PRODUCT_UPDATE_TIME,
				self::OPTION_UPDATE_TIME
			) as $option) {
			EcwidPlatform::set($option, 0);
		}
	}
}

$ecwid_products = new Ecwid_Products();