<?php


class Ecwid_Seo_Links {

	const OPTION_ENABLED = 'ecwid_seo_links_enabled';

	public function __construct()
	{
		// Should always run, check for enabled inside: once the option is turned on, it should rebuild the rules right away,
		// therefore the action must me registered
		add_action( 'rewrite_rules_array', array( $this, 'build_rewrite_rules' ), 1, 1 );

		add_action( 'init', array( $this, 'init' ) );
		add_action( 'ecwid_on_fresh_install', array( $this, 'on_fresh_install' ) );
	}

	public function init() {

		if ( self::is_enabled() ) {

			add_filter( 'redirect_canonical', array( $this, 'redirect_canonical' ), 10, 2 );
			add_action( 'template_redirect', array( $this, 'redirect_escaped_fragment' ) );
			add_filter( 'get_shortlink', array( $this, 'get_shortlink' ) );

			add_action( 'ecwid_print_inline_js_config', array( $this, 'add_js_config') );

			add_filter( 'wp_unique_post_slug_is_bad_hierarchical_slug', array( $this,  'is_post_slug_bad'), 10, 4 );
			add_filter( 'wp_unique_post_slug_is_bad_flat_slug', array( $this,  'is_post_slug_bad' ), 10, 2 );
			add_filter( 'wp_unique_post_slug_is_bad_attachment_slug', array( $this,  'is_post_slug_bad' ), 10, 2 );
		}
	}

	public function on_fresh_install() {
		add_option( self::OPTION_ENABLED, 'Y' );
	}

	public function on_plugin_update() {
		add_option( self::OPTION_ENABLED, '' );
	}

	public function redirect_canonical( $redir, $req ) {

		if ($this->is_store_on_home_page() && get_queried_object_id() == get_option('page_on_front')) {
			return false;
		}

		return $redir;
	}

	public function redirect_escaped_fragment() {
		if ( ecwid_can_display_html_catalog() ) {
			$params = ecwid_parse_escaped_fragment( $_GET[ '_escaped_fragment_' ] );

			if ( !isset( $params[ 'mode' ] ) ) {
				return;
			}

			if ( $params['mode'] == 'product' ) {
				$redirect = Ecwid_Store_Page::get_product_url( $params['id'] );
			} else if ($params['mode'] == 'category') {
				$redirect = Ecwid_Store_Page::get_category_url( $params['id'] );
			}

			if ($redirect) {
				wp_redirect( $redirect, 301 );
			}
		}
	}

	public function get_shortlink( $shortlink ) {
		if ( self::is_product_browser_url() ) {
			return '';
		}

		return $shortlink;
 	}

	public function is_post_slug_bad( $value, $slug, $type = '', $parent = '' ) {

		if ( !$this->is_store_on_home_page() ) {
			return $value;
		}

		if ( $this->slug_matches_seo_pattern( $slug ) ) {
			return true;
		}

		return $value;
	}
	public function slug_matches_seo_pattern($slug) {
		static $pattern = '';

		if ( !$pattern ) {
			$patterns = $this->get_seo_links_patterns();

			$pattern = '!(^' . implode('$|^', $patterns) . '$)!';
		}

		return preg_match($pattern, $slug);
 	}

 	protected function get_seo_links_patterns() {
		return array(
			'[^/]*-p[0-9]+',
			'[^/]*-c[0-9]+',
			'cart',
			'checkout',
			'checkout\/shipping',
			'checkout\/payment',
			'checkout\/place-order',
			'checkout\/order-confirmation',
			'account',
			'account\/settings',
			'account\/orders',
			'account\/address-book',
			'account\/favorites',
			'search',
			'search\?.*'
		);
	}

	public function is_store_on_home_page() {
		$front_page = get_option( 'page_on_front' );

		if ( Ecwid_Store_Page::is_store_page( $front_page ) ) {
			return true;
		}

		return false;
	}

	public function add_js_config() {

		global $wp_query;
		$page_id = $wp_query->get( 'page_id' );

		$has_store = Ecwid_Store_Page::is_store_page( $page_id );

		if ( !$has_store ) return;

		$url = esc_js( ecwid_get_store_page_base_url() );

		echo <<<JS
			window.ec.config.storefrontUrls = window.ec.config.storefrontUrls || {};
			window.ec.config.storefrontUrls.cleanUrls = true;
			window.ec.config.baseUrl = '$url';
JS;
	}

	public static function maybe_extract_html_catalog_params() {

		$current_url = add_query_arg( null, null );
		$matches = array();
		if ( !preg_match( self::_get_pb_preg_pattern(), $current_url, $matches ) ) {
			return array();
		}

		$modes = array(
			'p' => 'product',
			'c' => 'category'
		);

		return array( 'mode' => $modes[$matches[1]], 'id' => $matches[2] );
	}

	public static function is_product_browser_url( $url = '' ) {
		if (!$url) {
			$url = add_query_arg( null, null );
		}

		return preg_match( self::_get_pb_preg_pattern(), $url );
	}

	protected static function _get_pb_preg_pattern() {
		return $pattern = '!.*-(p|c)([0-9]*)!';
	}

	public function build_rewrite_rules( $original_rules ) {

		if ( !self::is_enabled() ) return $original_rules;

		$rules = array();

		if ( $this->is_store_on_home_page() ) {
			$patterns = $this->get_seo_links_patterns();
			foreach ($patterns as $pattern) {
				$rules['^' . $pattern . '$'] = 'index.php?page_id=' . get_option( 'page_on_front' );
			}
		}

		$pages = Ecwid_Store_Page::get_store_pages_array();

		if ( is_array( $pages ) ) {

			foreach ($pages as $page_id) {
				$patterns = $this->get_seo_links_patterns();
				$link = get_page_uri($page_id);
				foreach ($patterns as $pattern) {
					$rules['^' . $link . '/' . $pattern . '.*'] = 'index.php?page_id=' . $page_id;
				}
			}

			if (
				is_plugin_active('polylang/polylang.php')
				&& function_exists('pll_get_post_language')
				&& class_exists('PLL_Model')
				&& method_exists('PLL_Model', 'get_links_model')
			) {
				$options = get_option('polylang');
				$model = new PLL_Model($options);
				$links_model = $model->get_links_model();
				if ($links_model instanceof PLL_Links_Directory) {
					$patterns = $this->get_seo_links_patterns();
					foreach ($pages as $page_id) {
						$link = get_page_uri($page_id);
						$language = pll_get_post_language($page_id);
						foreach ($patterns as $pattern) {
							$rules['^' . $language . '/' . $link . '/' . $pattern . '.*'] = 'index.php?page_id=' . $page_id;
						}
					}
				}
			}
		}

		return array_merge( $rules, $original_rules );
	}

	public static function is_enabled() {

		return self::is_feature_available() && get_option( self::OPTION_ENABLED );
	}

	public static function enable() {
		update_option( self::OPTION_ENABLED, true );
		flush_rewrite_rules();
	}

	public static function disable() {
		update_option( self::OPTION_ENABLED, false );
		flush_rewrite_rules();
	}

	public static function is_feature_available() {
		$permalink = get_option( 'permalink_structure' );

		return $permalink != '';
	}

	public static function should_display_option() {
		return ecwid_migrations_is_original_plugin_version_older_than( '5.0.8' );
	}

}

$ecwid_seo_links = new Ecwid_Seo_Links();