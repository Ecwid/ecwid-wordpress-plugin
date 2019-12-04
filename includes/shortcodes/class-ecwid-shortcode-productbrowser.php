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

		$option_print_html_catalog = get_option('ecwid_print_html_catalog', 'Y');

		if ( !Ecwid_Static_Page::is_data_available() || @$this->_params['noHTMLCatalog'] || empty( $option_print_html_catalog ) ) {
			return '<div id="dynamic-ec-store">' . $default_render . '</div>';
		}


		$code = '';
		global $ecwid_current_theme;
		if ( $ecwid_current_theme ) {

			$code = <<<HTML
<script>
if( typeof document.documentElement.id == 'undefined' || document.documentElement.id === '' ) {
	document.documentElement.id = 'ecwid_html';
}

if( typeof document.body.id == 'undefined' || document.body.id === '' ) {
	document.body.id = 'ecwid_body';
}
</script>
HTML;
		}

		$classname = '';

		if ( Ecwid_Static_Page::is_enabled_static_home_page() && Ecwid_Static_Page::is_feature_available() ) {
			$code .= self::_get_js_switch_dynamic('static-ec-store', 'dynamic-ec-store');
			$classname = 'hide-ec-dynamic-placeholder';
		} else {
			$code .= self::_get_js_hide_static('#static-ec-store');
		}


		$code .= '<div id="dynamic-ec-store" class="' . $classname . '">' . $default_render . '</div>' . PHP_EOL;

		$static_html_code = Ecwid_Static_Page::get_html_code();
		$code .= '<div id="static-ec-store">' . htmlspecialchars_decode( $static_html_code ) . '</div>' . PHP_EOL;

		$js_code = Ecwid_Static_Page::get_js_code();
		if( !empty( $js_code ) ) {
			$code .= sprintf('<script data-cfasync="false" type="text/javascript">%s</script>', $js_code) . PHP_EOL;
		}

		return $code;
	}

	protected function _get_js_switch_dynamic( $static_container_id, $dynamic_container_id ) {
		return <<<HTML
			<script data-cfasync="false" type="text/javascript">
				window.ec.storefront.staticPages = window.ec.storefront.staticPages || Object();
				ec.storefront.staticPages.staticStorefrontEnabled = true;
				ec.storefront.staticPages.staticContainerID = '$static_container_id';
				ec.storefront.staticPages.dynamicContainerID = '$dynamic_container_id';
				ec.storefront.staticPages.autoSwitchStaticToDynamicWhenReady = true;
			</script>

HTML;
	}

	protected function _get_js_hide_static( $html_selector ) {
		return <<<HTML
			<script data-cfasync="false" type="text/javascript">
				function createClass(name,rules){
					var style = document.createElement('style');
					style.type = 'text/css';
					document.getElementsByTagName('head')[0].appendChild(style);
					if(!(style.sheet||{}).insertRule) 
						(style.styleSheet || style.sheet).addRule(name, rules);
					else
						style.sheet.insertRule(name+'{'+rules+'}',0);
				}
				createClass('$html_selector','display:none;');
			</script>

HTML;
	}


	public function render_placeholder( ) {

		$store_id = get_ecwid_store_id();
    
		$params = array(
			'default_category_id' => 0
		);
		if ( $this->_lang ) {
			$params['lang'] = $this->_lang;
		}

		if ( isset($this->_params['defaultCategoryId']) ) {
			$params['default_category_id'] = $this->_params['defaultCategoryId'];
		}

		if ( @$this->_params['defaultProductId'] ) {
			$params['default_product_id'] = $this->_params['defaultProductId'];
		}

		Ecwid_Store_Page::save_store_page_params( $params );
		
		$classname = $this->_get_html_class_name();
		
		$result = <<<HTML
	<div id="ecwid-store-$store_id" class="ecwid-shopping-cart-$classname" data-ecwid-default-category-id="$params[default_category_id]"></div>
HTML;

		return $result;
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
			'id' => "ecwid-store-$store_id",
			'views' => "grid($products_per_column_in_grid,$products_per_row_in_grid) list($products_in_list) table($products_in_table)"
		);

		if ( ecwid_is_legacy_appearance_used() ) {
			$legacy_input_params = array(
				'categoriesPerRow' => $cats_per_row,
				'categoryView' => $default_view,
				'searchView' => $search_view,
			);

			$input_params = array_merge($input_params, $legacy_input_params);
		}

		if ($ecwid_default_category_id) {
			$input_params['defaultCategoryId'] = $ecwid_default_category_id;
		}

		if ( isset($shortcode_params['default_product_id']) && $shortcode_params['default_product_id'] > 0 ) {
			$input_params['defaultProductId'] = $shortcode_params['default_product_id'];
		}

		if ( isset($shortcode_params['no_html_catalog']) ) {
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