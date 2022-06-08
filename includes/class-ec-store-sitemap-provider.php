<?php
class Ec_Store_Sitemap_Provider extends WP_Sitemaps_Provider {

	public function __construct() {
		$this->name        = 'ecstore';
		$this->object_type = 'product';
	}

	public static function init() {
		$provider = new self();
		wp_register_sitemap_provider( 'ecstore', $provider );
	}

	public function get_url_list( $page_num, $post_type = '' ) {
		$this->url_list = array();

		ecwid_build_sitemap( array( $this, 'sitemap_callback' ) );

		return $this->url_list;
	}

	public function get_max_num_pages( $post_type = '' ) {
		return 1;
	}

	public function sitemap_callback( $url, $priority, $frequency, $obj ) {

		$sitemap_entry = array(
			'loc'        => $url,
			'changefreq' => $frequency,
			'priority'   => $priority,
		);

		if ( isset( $obj->updated ) ) {
			$sitemap_entry['lastmod'] = $obj->updated;
		}

		$this->url_list[] = $sitemap_entry;
	}
}

add_filter( 'init', 'Ec_Store_Sitemap_Provider::init' );
