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
		if ( current_user_can( Ecwid_Admin::get_capability() ) ) {

			$seo_links = new Ecwid_Seo_Links();
			$seo_links->check_base_urls_on_view_store_page_as_admin();
		}

		$option_print_html_catalog = get_option( 'ecwid_print_html_catalog', 'Y' );

		if ( ! Ecwid_Static_Page::is_data_available() || @$this->_params['noHTMLCatalog'] || empty( $option_print_html_catalog ) ) {

			add_filter( 'ecwid_is_defer_store_init_enabled', '__return_false' );

			$code = self::get_dynamic_html_code();
			if ( ! empty( $this->_params['default_page'] ) ) {
				$code .= $this->get_js_for_open_page( $this->_params['default_page'] );
			}

			return $code;
		}

		$code  = '';
		$code .= self::get_js_for_adding_html_id();

		$classname         = '';
		$is_default_render = false;

		if ( Ecwid_Static_Page::is_enabled_static_home_page() ) {
			add_filter( 'ecwid_hide_defer_load_script', '__return_true', 10000 );
			$code     .= self::get_js_for_switch_dynamic( 'static-ec-store-container', 'dynamic-ec-store-container' );
			$classname = 'hide-ec-dynamic-placeholder';
		} else {
			$is_default_render = true;
			$code             .= self::get_js_for_hide_static( '#static-ec-store-container' );

			add_filter( 'ecwid_is_defer_store_init_enabled', '__return_false' );
		}

		$code .= '<div id="static-ec-store-container">';
		$code .= htmlspecialchars_decode( Ecwid_Static_Page::get_html_code() );

		$js_code = Ecwid_Static_Page::get_js_code();
		if ( ! empty( $js_code ) ) {
			$code .= sprintf( '<!--noptimize--><script id="ec-static-inline-js" data-cfasync="false" data-no-optimize="1" type="text/javascript">%s</script><!--/noptimize-->', $js_code ) . PHP_EOL;
		}
		$code .= '</div>';

		$code .= self::get_dynamic_html_code( $is_default_render, $classname );

		$force_dynamic_js_code = 'if( typeof window.ec.storefront.staticPages != "undefined" && typeof window.ec.storefront.staticPages.forceDynamicLoadingIfRequired != "undefined" ) {
            window.ec.storefront.staticPages.forceDynamicLoadingIfRequired();
        }';

		wp_add_inline_script( 'ecwid-' . Ecwid_Static_Page::HANDLE_STATIC_PAGE, $force_dynamic_js_code );

		return $code;
	}

	protected function get_dynamic_html_code( $is_default_render = true, $classname = '' ) {

		if ( ! Ec_Store_Defer_Init::is_enabled() || $is_default_render ) {
			$default_render = parent::render();
			$code           = '<div id="dynamic-ec-store-container" class="' . $classname . '">' . $default_render . '</div>' . PHP_EOL;
		} else {
			$code = '<div id="dynamic-ec-store-container"><div id="dynamic-ec-store"></div></div>' . PHP_EOL;
		}

		return $code;
	}

	protected function get_js_for_adding_html_id() {
		global $ecwid_current_theme;
		if ( $ecwid_current_theme ) {
			ob_start();
			?>
			<!--noptimize-->
			<script data-cfasync="false" data-no-optimize="1">
				if( typeof document.documentElement.id == 'undefined' || document.documentElement.id === '' ) {
					document.documentElement.id = 'ecwid_html';
				}

				if( typeof document.body.id == 'undefined' || document.body.id === '' ) {
					document.body.id = 'ecwid_body';
				}
			</script>
			<!--/noptimize-->
			<?php
			return ob_get_clean();
		}
		return '';
	}

	protected function get_js_for_switch_dynamic( $static_container_id, $dynamic_container_id ) {

		$store_id         = get_ecwid_store_id();
		$script_js_params = ecwid_get_scriptjs_params();
		$script_js_link   = 'https://' . Ecwid_Config::get_scriptjs_domain() . '/script.js?' . $store_id . $script_js_params;

		$widget_params_string = $this->build_params_string(
			array_merge(
				$this->_params,
				array( 'id' => 'dynamic-ec-store' )
			)
		);

		ob_start();
		?>
		<!--noptimize-->
		<script data-cfasync="false" data-no-optimize="1" type="text/javascript">
			window.ec.storefront = window.ec.storefront || {};
			window.ec.storefront.staticPages = window.ec.storefront.staticPages || Object();

			ec.storefront.staticPages.staticStorefrontEnabled = true;
			ec.storefront.staticPages.staticContainerID = '<?php echo esc_js( $static_container_id ); ?>';
			ec.storefront.staticPages.dynamicContainerID = '<?php echo esc_js( $dynamic_container_id ); ?>';
			ec.storefront.staticPages.autoSwitchStaticToDynamicWhenReady = true;
			<?php if ( Ec_Store_Defer_Init::is_enabled() ) { ?>
			ec.storefront.staticPages.lazyLoading = {
				scriptJsLink: '<?php echo $script_js_link; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>',
				xProductBrowserArguments: [<?php echo $widget_params_string; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>]
			}
			<?php } ?>
		</script>
		<!--/noptimize-->
		<?php
		return ob_get_clean();
	}

	protected function get_js_for_hide_static( $html_selector ) {
		ob_start();
		?>
		<!--noptimize-->
		<script data-cfasync="false" data-no-optimize="1" type="text/javascript">
			function createClass(name,rules){
				var style = document.createElement('style');
				style.type = 'text/css';
				document.getElementsByTagName('head')[0].appendChild(style);
				if(!(style.sheet||{}).insertRule) 
					(style.styleSheet || style.sheet).addRule(name, rules);
				else
					style.sheet.insertRule(name+'{'+rules+'}',0);
			}
			createClass('<?php echo esc_js( $html_selector ); ?>','display:none;');
		</script>
		<!--/noptimize-->
		<?php
		return ob_get_clean();
	}


	public function render_placeholder() {

		$store_id = get_ecwid_store_id();

		$params = array(
			'default_category_id' => 0,
		);
		if ( $this->_lang ) {
			$params['lang'] = $this->_lang;
		}

		if ( isset( $this->_params['defaultCategoryId'] ) ) {
			$params['default_category_id'] = $this->_params['defaultCategoryId'];
		}

		if ( ! empty( $this->_params['defaultProductId'] ) ) {
			$params['default_product_id'] = $this->_params['defaultProductId'];
		}

		Ecwid_Store_Page::save_store_page_params( $params );

		$classname = $this->_get_html_class_name();

		$pb_placeholder = '';
		if ( Ec_Store_Defer_Init::is_enabled() ) {
			ob_start();
			require ECWID_PLUGIN_DIR . '/templates/shortcode-pb-placeholder.php';
			$pb_placeholder = ob_get_clean();
		}

		$result = '<div id="ecwid-store-' . $store_id . '" class="ecwid-shopping-cart-' . $classname . '" data-ecwid-default-category-id="' . $params['default_category_id'] . '">' . $pb_placeholder . '</div>';

		return $result;
	}


	protected function _process_params( $shortcode_params = array() ) {

		$atts = shortcode_atts(
			array(
				'categories_per_row' => false,
				'grid'               => false,
				'list'               => false,
				'table'              => false,
				'search_view'        => false,
				'category_view'      => false,
			),
			$shortcode_params
		);

		$grid = explode( ',', $atts['grid'] );
		if ( count( $grid ) === 2 ) {
			$atts['grid_rows'] = intval( $grid[0] );
			$atts['grid_cols'] = intval( $grid[1] );
		} else {
			list($atts['grid_rows'], $atts['grid_cols']) = array( false, false );
		}

		$list_of_views = array( 'list', 'grid', 'table' );

		$cats_per_row                = $atts['categories_per_row'] ? $atts['categories_per_row'] : get_option( 'ecwid_pb_categoriesperrow' );
		$products_per_column_in_grid = $atts['grid_rows'] ? $atts['grid_rows'] : get_option( 'ecwid_pb_productspercolumn_grid' );
		$products_per_row_in_grid    = $atts['grid_cols'] ? $atts['grid_cols'] : get_option( 'ecwid_pb_productsperrow_grid' );
		$products_in_list            = $atts['list'] ? $atts['list'] : get_option( 'ecwid_pb_productsperpage_list' );
		$products_in_table           = $atts['table'] ? $atts['table'] : get_option( 'ecwid_pb_productsperpage_table' );
		$default_view                = $atts['category_view'] ? $atts['category_view'] : get_option( 'ecwid_pb_defaultview' );
		$search_view                 = $atts['search_view'] ? $atts['search_view'] : get_option( 'ecwid_pb_searchview' );

		$ecwid_default_category_id = $this->_get_param_default_category_id( $shortcode_params );

		$store_id = get_ecwid_store_id();

		if ( empty( $cats_per_row ) ) {
			$cats_per_row = 3;
		}
		if ( empty( $products_per_column_in_grid ) ) {
			$products_per_column_in_grid = 3;
		}
		if ( empty( $products_per_row_in_grid ) ) {
			$products_per_row_in_grid = 3;
		}
		if ( empty( $products_in_list ) ) {
			$products_in_list = 10;
		}
		if ( empty( $products_in_table ) ) {
			$products_in_table = 20;
		}

		if ( empty( $default_view ) || ! in_array( $default_view, $list_of_views ) ) {
			$default_view = 'grid';
		}
		if ( empty( $search_view ) || ! in_array( $search_view, $list_of_views ) ) {
			$search_view = 'list';
		}

		$input_params = array(
			'id'    => "ecwid-store-$store_id",
			'views' => "grid($products_per_column_in_grid,$products_per_row_in_grid) list($products_in_list) table($products_in_table)",
		);

		if ( ecwid_is_legacy_appearance_used() ) {
			$legacy_input_params = array(
				'categoriesPerRow' => $cats_per_row,
				'categoryView'     => $default_view,
				'searchView'       => $search_view,
			);

			$input_params = array_merge( $input_params, $legacy_input_params );
		}

		if ( $ecwid_default_category_id ) {
			$input_params['defaultCategoryId'] = $ecwid_default_category_id;
		}

		if ( isset( $shortcode_params['default_product_id'] ) && $shortcode_params['default_product_id'] > 0 ) {
			$input_params['defaultProductId'] = $shortcode_params['default_product_id'];
		}

		if ( isset( $shortcode_params['no_html_catalog'] ) ) {
			$input_params['noHTMLCatalog'] = $shortcode_params['no_html_catalog'];
		}

		if ( isset( $shortcode_params['default_page'] ) ) {
			$input_params['default_page'] = $shortcode_params['default_page'];
		}

		$this->_params = $input_params;
	}

	/**
	 * Get default category id
	 *
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

	public function get_js_for_open_page( $page = '' ) {
		$allowed_pages = array(
			'cart',
			'search',
		);

		if ( ! in_array( $page, $allowed_pages ) ) {
			return false;
		}

		ob_start();
		?>
		<script>
		Ecwid.OnAPILoaded.add(function() {
			Ecwid.OnPageLoad.add(function(page) {
				if ("CATEGORY" == page.type && 0 == page.categoryId && !page.hasPrevious) {
					Ecwid.openPage("<?php echo esc_js( $page ); ?>");
				}
			})
		});
		</script>
		<?php

		return ob_get_clean();
	}

	public static function init_static_js_repair() {

		if ( is_admin() || wp_doing_ajax() || ! Ecwid_Store_Page::is_store_page() ) {
			return;
		}

		ob_start();
		add_action( 'shutdown', 'Ecwid_Shortcode_ProductBrowser::is_needed_static_js_repair', 0 );
	}

	public static function is_needed_static_js_repair() {
		$output = ob_get_clean();

		$pattern = '/<script id="ec-static-inline-js"(.*?)>(.*?)<\/script>/is';

		$is_found_static_js = preg_match( $pattern, $output, $m );

		if ( $is_found_static_js ) {
			preg_match( $pattern, $output, $matches );

			if ( ! empty( $matches[2] ) ) {
				$static_js = $matches[2];

				if ( strpos( $static_js, '&#038;' ) !== false ) {
					$static_js = str_replace( '&#038;', '&', $static_js );
					$output    = preg_replace( $pattern, "<script id=\"ec-static-inline-js\"$1>$static_js</script>", $output, 1 );
				}
			}
		}

		echo $output; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

add_action( 'template_redirect', 'Ecwid_Shortcode_ProductBrowser::init_static_js_repair' );
