<?php


class Ecwid_Seo_Links {

	const OPTION_ENABLED = 'ecwid_seo_links_enabled';
	const OPTION_ALL_BASE_URLS = 'ecwid_all_base_urls';

	public function __construct()
	{
		// therefore the action must me registered
		add_action( 'init', array( $this, 'build_rewrite_rules' ) );

		add_action( 'init', array( $this, 'init' ) );
		add_action( 'ecwid_on_fresh_install', array( $this, 'on_fresh_install' ) );
		add_action( 'save_post', array( $this, 'on_save_post' ) );
	}

	public function init() {

		if ( self::is_enabled() ) {

			add_filter( 'redirect_canonical', array( $this, 'redirect_canonical' ), 10, 2 );
			add_action( 'template_redirect', array( $this, 'redirect_escaped_fragment' ) );
			add_filter( 'get_shortlink', array( $this, 'get_shortlink' ) );
			
			add_action( 'ecwid_inline_js_config', array( $this, 'add_js_config') );

			add_filter( 'wp_unique_post_slug_is_bad_hierarchical_slug', array( $this,  'is_post_slug_bad'), 10, 4 );
			add_filter( 'wp_unique_post_slug_is_bad_flat_slug', array( $this,  'is_post_slug_bad' ), 10, 2 );
			add_filter( 'wp_unique_post_slug_is_bad_attachment_slug', array( $this,  'is_post_slug_bad' ), 10, 2 );
			
			if ( is_admin() ) {
				add_action( 'current_screen', array( $this, 'check_base_urls_on_edit_store_page' ) ); 
			}
		}
	}

	public function on_save_post( $post_id ) {
		if ( wp_is_post_revision( $post_id ) )
			return;
		
		if ( Ecwid_Store_Page::is_store_page( $post_id ) ) {
			if ( !$this->are_base_urls_ok() ) {
				flush_rewrite_rules();
			}
		}		
	}
	
	public function check_base_urls_on_edit_store_page() {

		$current_screen = get_current_screen();
		
		if ( $current_screen->base != 'post' || !in_array( $current_screen->post_type, array( 'post', 'page' ) ) ) {
			return;
		}
		
		$id = (isset( $_GET['post'] )) ? $_GET['post'] : false;
		
		if ( !$id ) {
			return;
		}
		
		if ( Ecwid_Store_Page::is_store_page( $id ) ) {
			if ( !$this->are_base_urls_ok() ) {
				flush_rewrite_rules();
			}	
		}
	}
	
	public function check_base_urls_on_view_store_page_as_admin() {
		$id = get_the_ID();
		
		if ( Ecwid_Store_Page::is_store_page( $id ) ) {
			if ( !$this->are_base_urls_ok() ) {
				flush_rewrite_rules();
			}
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
		if ( ecwid_should_display_escaped_fragment_catalog() ) {
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
			$patterns = self::get_seo_links_patterns();

			$pattern = '!(^' . implode('$|^', $patterns) . '$)!';
		}

		return preg_match($pattern, $slug);
 	}

 	protected static function get_seo_links_patterns() {
		return array(
			'.*-p([0-9]+)(\/.*|\?.*)?',
			'.*-c([0-9]+)(\/.*|\?.*)?',
			'cart',
			'checkout.*',
			'account',
			'account\/settings',
			'account\/orders',
			'account\/address-book',
			'account\/favorites',
			'search',
			'search\?.*',
			'signin',
			'pages\/about',
			'pages\/shipping-payment',
			'pages\/returns',
			'pages\/terms',
			'pages\/privacy-policy',
			'signIn.*',
			'resetPassword.*',
			'checkoutAB.*',
			'downloadError.*',
			'orderFailure.*',
			'checkoutCC.*',
			'checkoutEC.*',
			'checkoutAC.*',
			'FBAutofillCheckout.*'
		);
	}

	public function is_store_on_home_page() {
		$front_page = get_option( 'page_on_front' );
		
		if ( !$front_page ) {
			return false;
		}
		
		if ( Ecwid_Store_Page::is_store_page( $front_page ) ) {
			return true;
		}

		return false;
	}

	public function add_js_config( $config ) {
		
		$page_id = get_queried_object_id();

		$has_store = Ecwid_Store_Page::is_store_page( $page_id );
		
		if ( !$has_store ) {
			if ( !Ecwid_Ajax_Defer_Renderer::is_enabled() ) {
				return $config;
			}
		}

		if ( Ecwid_Api_V3::is_available() && self::is_404_seo_link() ) {
			return $config;
		}

		$url = esc_js( get_permalink( $page_id ) );


		if( parse_url($url, PHP_URL_SCHEME) == 'https' && parse_url($url, PHP_URL_PORT) == '443' ) {
			$url = str_replace( ':443', '', $url );
		}

		$result = <<<JS
			window.ec.config.storefrontUrls = window.ec.config.storefrontUrls || {};
			window.ec.config.storefrontUrls.cleanUrls = true;
			window.ec.config.baseUrl = '$url';
			window.ec.storefront = window.ec.storefront || {};
			window.ec.storefront.sharing_button_link = "DIRECT_PAGE_URL";
JS;
		$config .= $result;
		
		return $config;
	}

	public static function is_404_seo_link() {
		
		if ( ! self::is_product_browser_url() ) {
			return false;
		}
		
		$params = self::maybe_extract_html_catalog_params();
		if ( !$params ) return false;
		
		// Root is always ok
		$is_root_cat = $params['mode'] == 'category' && $params['id'] == 0;
		if ( $is_root_cat ) {
			return false;
		}
		
		$result = false;
		
		if ($params['mode'] == 'product') {
			$result = Ecwid_Product::get_by_id( $params['id'] );
		} elseif (!$is_root_cat && $params['mode'] == 'category') {
			$result = Ecwid_Category::get_by_id( $params['id'] );
		}
		
		// Can't parse params, assume its ok
		if ( !$result ) {
			return false;
		}
		
		// product/category not found, 404
		if ( is_object ( $result ) && ( !isset( $result->id ) || !$result->enabled ) ) {
			return true;
		}
		
		return false;
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
	
	public static function is_seo_link() {
		if ( !Ecwid_Store_Page::is_store_page() ) return false;
		
		$url = add_query_arg( null, null );
		
		$link = urldecode( self::_get_relative_permalink( get_the_ID() ) );
		$site_url = parse_url( get_bloginfo('url') );
		$site_path = (isset($site_url['path'])) ? $site_url['path'] : '';
		
		foreach ( self::get_seo_links_patterns() as $pattern ) {
			$pattern = '#' . $site_path . preg_quote( $link ) . $pattern . '#';
			
			if ( preg_match( $pattern, $url ) ) {
				return true;
			}
		}
		
		return false;
	}

	protected static function _get_pb_preg_pattern() {
		return $pattern = '!.*-(p|c)([0-9]+)(\/.*|\?.*)?$!';
	}

	public function build_rewrite_rules( ) {

		if ( !self::is_enabled() ) return;
		
		$all_base_urls = $this->_build_all_base_urls();
		
		foreach ( $all_base_urls as $page_id => $links ) {
			$patterns = self::get_seo_links_patterns();
			
			$post = get_post( $page_id );
			if ( ! $post ) continue;
			if ( !in_array( $post->post_type, array( 'page', 'post' ) ) ) continue;
			
			$param_name = $post->post_type == 'page' ? 'page_id' : 'p';
			
			foreach ( $links as $link ) {
				$link = trim( $link, '/' );

				if (strpos($link, 'index.php') !== 0) {
					$link = '^' . preg_quote( $link );
				}
				
				foreach ( $patterns as $pattern ) {
					add_rewrite_rule( $link . '/' . $pattern . '.*', 'index.php?' . $param_name . '=' . $page_id, 'top' );
				}
			}
		}

		if ( $this->is_store_on_home_page() ) {
			$patterns = self::get_seo_links_patterns();
			foreach ( $patterns as $pattern ) {
				add_rewrite_rule( '^' . $pattern . '$', 'index.php?page_id=' . get_option( 'page_on_front' ), 'top' );
			}
		}

		update_option( self::OPTION_ALL_BASE_URLS, array_merge( $all_base_urls, array( 'home' => $this->is_store_on_home_page() ) ) );
	}
	
	public function are_base_urls_ok() {
		if (! self::is_feature_available() ) {
			return true;
		}
		
		$all_base_urls = $this->_build_all_base_urls();
		
		$flattened = array();
		foreach ( $all_base_urls as $page_id => $links ) {
			foreach ( $links as $link ) {
				$flattened[$link] = $link;
			}
		}
		
		$saved = get_option( self::OPTION_ALL_BASE_URLS );

		if ( empty( $saved ) || !is_array( $saved ) ) {
			return false;
		}
		
		$saved_home = false;
		if ( isset( $saved['home'] ) ) {
			$saved_home = $saved['home'];
			unset( $saved['home'] );
		}
		
		$flattened_saved = array();
		foreach ( $saved as $page_id => $links ) {
			foreach ( $links as $link ) {
				$flattened_saved[$link] = $link;
			}
		}
		
		$rules = get_option( 'rewrite_rules' );
		
		if ( empty( $rules ) ) return false;
			
		foreach ( $flattened as $link ) {
			$link = trim( $link, '/' );
			
			$patterns = self::get_seo_links_patterns();
			$pattern = $patterns[0];
			
			$rules_pattern = '^' . $link . '/' . $pattern . '.*';
			
			if ( !array_key_exists( $rules_pattern, $rules ) ) {
				return false;
			}
		}
		
		$are_the_same = array_diff( $flattened, $flattened_saved );
		
		return empty( $are_the_same ) && $saved_home == $this->is_store_on_home_page();
	}
	
	protected function _build_all_base_urls()
	{
		$base_urls = array();

		$pages = Ecwid_Store_Page::get_store_pages_array();

		if ( is_array( $pages ) ) {
			foreach ( $pages as $page_id ) {
				
				if ( !isset( $base_urls[$page_id] ) ) {
					$base_urls[$page_id] = array();
				}
				
				$link = urldecode( self::_get_relative_permalink( $page_id, true ) );

				$base_urls[$page_id][] = $link;
			}
		}
		
		return $base_urls;
	}
	
	protected static function _get_relative_permalink( $item_id, $not_filter_return_value = false ) {
		$permalink = parse_url( get_permalink( $item_id ) );
		$home_url = parse_url( home_url() );

		if( !isset($permalink['path']) ) $permalink['path'] = '/';
		if( !isset($home_url['path']) ) $home_url['path'] = '';

		if( isset($home_url['query']) ) {
			$home_url['path'] = substr( $home_url['path'], 0, -1 );
		}

		$default_link = substr( $permalink['path'], strlen( $home_url['path'] ) );

		if( $not_filter_return_value ) {
			return $default_link;
		}

		return apply_filters( 'ecwid_relative_permalink', $default_link, $item_id );
	}

	public static function is_noindex_page() {
		
		if ( !Ecwid_Store_Page::is_store_page() ) {
			return false;
		}

		$relative_permalink = self::_get_relative_permalink( get_the_ID() );

		$noindex_pages = array(
			'cart',
			'account',
			'checkout',
			'signin'
		);
		
		$home_url = home_url();
		$path = parse_url( $home_url, PHP_URL_PATH );
		$seo_part = str_replace( $path . $relative_permalink, '', $_SERVER['REQUEST_URI'] );
		
		foreach ( $noindex_pages as $page ) {
			if ( preg_match( '!' . $page . '([\?\/]+.*|)$' . '!', $seo_part ) ) {
				return true;
			}
		}
		
		return false;
	}
	
	public static function is_enabled() {

		return self::is_feature_available() && get_option( self::OPTION_ENABLED );
	}

	public static function enable() {
		update_option( self::OPTION_ENABLED, true );
		Ecwid_Store_Page::schedule_flush_rewrites();
		ecwid_invalidate_cache( true );
	}

	public static function disable() {
		update_option( self::OPTION_ENABLED, false );
		Ecwid_Store_Page::schedule_flush_rewrites();
		ecwid_invalidate_cache( true );
	}

	public static function is_feature_available() {
		$permalink = get_option( 'permalink_structure' );

		return $permalink != '';
	}

	public static function should_display_option() {
		return ecwid_migrations_is_original_plugin_version_older_than( '5.2' ) || !self::is_enabled();
	}

}

$ecwid_seo_links = new Ecwid_Seo_Links();