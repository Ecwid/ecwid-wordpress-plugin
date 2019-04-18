<?php

require_once ECWID_SHORTCODES_DIR . '/class-ecwid-shortcode-base.php';

class Ecwid_Shortcode_ProductBrowser extends Ecwid_Shortcode_Base {

	public static function get_shortcode_name() {
		return 'productbrowser';
	}

	protected function _get_html_class_name() {
		return 'product-browser';
	}

	public function get_html_id() {
		return 'ecwid-store-' . get_ecwid_store_id();
	}

	public function get_ecwid_widget_function_name() {
		return 'xProductBrowser';
	}

	public function render() {
		
		Ecwid_Store_Page::add_store_page( get_the_ID() );
		if( current_user_can( Ecwid_Admin::get_capability() ) ) {
			
			$seo_links = new Ecwid_Seo_Links();
			$seo_links->check_base_urls_on_view_store_page_as_admin();
		}

		$default_render = parent::render();
		
		$data = Ecwid_Static_Home_Page::get_data_for_current_page();
		if ( !$data || @$this->_params['noHTMLCatalog'] ) {
			return $default_render;
		}

		$code = '';
		global $ecwid_current_theme;
		if ( $ecwid_current_theme ) {

			$code = <<<HTML
<script>
document.documentElement.id = 'ecwid_html';
document.body.id = 'ecwid_body';
</script>
HTML;
		}


		$code .= '<div id="static-ecwid">' . htmlspecialchars_decode( $data->htmlCode ) . '</div>';

		$code .= '<div id="dynamic-ecwid">' . $default_render . '</div>';
		
		$code .= <<<HTML
<script language="JavaScript">
    EcwidStaticPageLoader.processStaticHomePage('static-ecwid', 'dynamic-ecwid');
	if ( location.hash != '' && location.hash.indexOf('#!/c/0/') !== 0) {
	    EcwidStaticPageLoader.switchToDynamicMode();
	}
</script>
HTML;

		return $code;
	}

	public function render_placeholder( ) {
		$store_id = get_ecwid_store_id();

		$plain_content = '';
		
		$html_catalog_params = false;

		
		if ( Ecwid_Api_V3::is_available() && !Ecwid_Static_Home_Page::get_data_for_current_page() ) {

			if (ecwid_should_display_escaped_fragment_catalog()) {
				$html_catalog_params = ecwid_parse_escaped_fragment($_GET['_escaped_fragment_']);
			} elseif (Ecwid_Seo_Links::is_enabled() && Ecwid_Store_Page::is_store_page()) {
				$html_catalog_params = Ecwid_Seo_Links::maybe_extract_html_catalog_params();
			}

			$html_catalog_params['default_category_id'] = @ (int)$this->_params['defaultCategoryId'];
			$html_catalog_params['default_product_id'] = @ (int)$this->_params['defaultProductId'];

			if (
				$html_catalog_params !== false 
				&& get_option('ecwid_print_html_catalog', 'Y') 
				&& !@$this->_params['noHTMLCatalog']
			) {
				$plain_content = $this->_build_html_catalog($store_id, $html_catalog_params);
			}
		}

		$params = array(
			'default_category_id' => 0
		);
		if ( $this->_lang ) {
			$params['lang'] = $this->_lang;
		}
		if ( @$this->_params['defaultCategoryId'] ) {
			$params['default_category_id'] = $this->_params['defaultCategoryId'];
		}

		Ecwid_Store_Page::save_store_page_params( $params );
		
		$result = '';
		
		$classname = $this->_get_html_class_name();
		
		
		$result .= <<<HTML
	<div id="ecwid-store-$store_id" class="ecwid-shopping-cart-$classname" data-ecwid-default-category-id="$html_catalog_params[default_category_id]">
HTML;
		
		if ( ! @$this->_params['noHTMLCatalog'] ) 
			$result .= <<<HTML
		<script>
			function createClass(name,rules){
				var style = document.createElement('style');
				style.type = 'text/css';
				document.getElementsByTagName('head')[0].appendChild(style);
				if(!(style.sheet||{}).insertRule) 
					(style.styleSheet || style.sheet).addRule(name, rules);
				else
					style.sheet.insertRule(name+'{'+rules+'}',0);
			}
			createClass('#ecwid-html-catalog-$store_id','display:none;');
		</script>
HTML;
		
		
		$result .= <<<HTML
		<div id="ecwid-html-catalog-$store_id">{$plain_content}</div>
	</div>
HTML;

		return $result;
	}

	/**
	 * @param $store_id
	 * @param $params
	 * @return string
	 */
	public function _build_html_catalog($store_id, $params)
	{
		include_once ECWID_PLUGIN_DIR . 'lib/ecwid_catalog.php';
		
		$id = get_the_ID();
		if (!$id) {
			$id = Ecwid_Store_Page::get_current_store_page_id();
		}
		
		if ($id) {
			$page_url = get_permalink( $id );
		} else {
			$page_url = '';
		}
		
		$catalog = new EcwidCatalog($store_id, $page_url);

		$url = false;
		
		$is_default_category = false;

		if ( isset( $params['mode'] ) && !empty( $params['mode'] ) ) {
			if ( $params['mode'] == 'product' ) {
				$plain_content = $catalog->get_product( $params['id'] );
				$url = Ecwid_Store_Page::get_product_url( $params['id'] );
			} elseif ( $params['mode'] == 'category' ) {
				$plain_content = $catalog->get_category( $params['id'] );
				$url = Ecwid_Store_Page::get_category_url( $params['id'] );
			}

		} else {

			$cat_id = intval( $this->_get_param_default_category_id( $params ) );
			if ( $cat_id ) {
				$plain_content = $catalog->get_category( $cat_id );
			} else if ( @$this->_params['defaultProductId'] ) {
				$plain_content = $catalog->get_product( $this->_params['defaultProductId'] );
			}
			
			if ( empty( $plain_content ) ) {
				$plain_content = $catalog->get_category( 0 );
			} else {
				$is_default_category = true;
			} 
		}

		if ( $url && !$is_default_category && !Ecwid_Seo_Links::is_product_browser_url() ) {
			$parsed = parse_url($url);

			if ($parsed['fragment']) {
				$plain_content .= '<script data-cfasync="false" type="text/javascript"> if (!document.location.hash) document.location.hash = "' . $parsed['fragment'] . '";</script>';
			} else {
				$plain_content .= '<script data-cfasync="false" type="text/javascript"> document.location = "' . esc_js($url) . '";</script>';
			}
		}
		
		return $plain_content;
	}

	protected function _process_params( $shortcode_params = array() ) {

		$atts = shortcode_atts(
			array(
				'categories_per_row' => false,
				'grid' => false,
				'list' => false,
				'table' => false,
				'search_view' => false,
				'category_view' => false
			), $shortcode_params
		);

		$grid = explode(',', $atts['grid']);
		if (count($grid) == 2) {
			$atts['grid_rows'] = intval($grid[0]);
			$atts['grid_cols'] = intval($grid[1]);
		} else {
			list($atts['grid_rows'], $atts['grid_cols']) = array(false, false);
		}

		$list_of_views = array('list','grid','table');

		$cats_per_row = $atts['categories_per_row'] ? $atts['categories_per_row'] : get_option('ecwid_pb_categoriesperrow');
		$products_per_column_in_grid = $atts['grid_rows'] ? $atts['grid_rows'] : get_option('ecwid_pb_productspercolumn_grid');
		$products_per_row_in_grid = $atts['grid_cols'] ? $atts['grid_cols'] : get_option('ecwid_pb_productsperrow_grid');
		$products_in_list = $atts['list'] ? $atts['list'] : get_option('ecwid_pb_productsperpage_list');
		$products_in_table = $atts['table'] ? $atts['table'] : get_option('ecwid_pb_productsperpage_table');
		$default_view = $atts['category_view'] ? $atts['category_view'] : get_option('ecwid_pb_defaultview');
		$search_view = $atts['search_view'] ? $atts['search_view'] : get_option('ecwid_pb_searchview');

		$ecwid_default_category_id = $this->_get_param_default_category_id( $shortcode_params );

		$store_id = get_ecwid_store_id();

		if (empty($cats_per_row)) {
			$cats_per_row = 3;
		}
		if (empty($products_per_column_in_grid)) {
			$products_per_column_in_grid = 3;
		}
		if (empty($products_per_row_in_grid)) {
			$products_per_row_in_grid = 3;
		}
		if (empty($products_in_list)) {
			$products_in_list = 10;
		}
		if (empty($products_in_table)) {
			$products_in_table = 20;
		}

		if (empty($default_view) || !in_array($default_view, $list_of_views)) {
			$default_view = 'grid';
		}
		if (empty($search_view) || !in_array($search_view, $list_of_views)) {
			$search_view = 'list';
		}

		$input_params = array(
			'categoriesPerRow' => $cats_per_row,
			'views' => "grid($products_per_column_in_grid,$products_per_row_in_grid) list($products_in_list) table($products_in_table)",
			'categoryView' => $default_view,
			'searchView' => $search_view,
			'id' => "ecwid-store-$store_id"
		);

		if ($ecwid_default_category_id) {
			$input_params['defaultCategoryId'] = $ecwid_default_category_id;
		}

		if (isset($shortcode_params['default_product_id'])) {
			$input_params['defaultProductId'] = $shortcode_params['default_product_id'];
		}

		if (isset($shortcode_params['no_html_catalog'])) {
			$input_params['noHTMLCatalog'] = $shortcode_params['no_html_catalog'];
		}

		$this->_params = $input_params;
	}

	/**
	 * @param $shortcode_params
	 *
	 * @return mixed|void
	 */
	protected function _get_param_default_category_id( $shortcode_params ) {
		$ecwid_default_category_id =
			! empty( $shortcode_params ) && array_key_exists( 'default_category_id', $shortcode_params )
				? $shortcode_params['default_category_id']
				: get_option( 'ecwid_default_category_id' );

		return $ecwid_default_category_id;
	}
}