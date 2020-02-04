<?php

require_once dirname( __FILE__ ) . '/class-ecwid-gutenberg-block-base.php';

class Ecwid_Gutenberg_Block_Store extends Ecwid_Gutenberg_Block_Base {
	
	protected $_name = 'store';

	public function get_block_name() {
		return 'ecwid/store-block';
	}

	public function get_params() {
		$params = array(
			'attributes' => $this->get_attributes_for_editor(),
			'isNewProductList' => $this->_is_new_product_list(),
			'isNewDetailsPage' => $this->_is_new_details_page(),
			'storeBlockTitle' => _x( 'Store', 'gutenberg-store-block-stub', 'ecwid-shopping-cart' ),
			'shortcodeName' => Ecwid_Shortcode_Base::get_current_store_shortcode_name(),
			'title' => __( 'Store Home Page', 'ecwid-shopping-cart' ),
			'icon'=> self::get_icon_path(),
			'isDemoStore' => ecwid_is_demo_store(),
			'customizeMinicartText' =>
				sprintf(
					__(
						'You can enable an extra shopping bag icon widget that will appear on your site pages. Open “<a href="%1$s">Appearance → Customize → %2$s</a>” menu to enable it.',
						'ecwid-shopping-cart'
					),
					'customize.php?autofocus[section]=' . Ecwid_Customizer::SECTION_MINICART . '&return=' . urlencode( remove_query_arg( wp_removable_query_args(), wp_unslash( $_SERVER['REQUEST_URI'] ) )
					),
					Ecwid_Config::get_brand()
				)

		);
		
		$params = array_merge(
			$params,
			$this->_get_common_block_params()
		);
		
		return $params;
	}

	public function render_callback( $params ) {
		
		$print_js_refresh_config = false;
		$is_wp_customize = isset( $_REQUEST['wp_customize'] ) && $_REQUEST['wp_customize'] == 'on';

		if ( $_SERVER['REQUEST_METHOD'] != 'GET' && !$is_wp_customize ) {
			return '';
		}

		$result = "[ecwid";
		
		$params['widgets'] = 'productbrowser';
		if ( isset($params['show_categories']) ) {
			$params['widgets'] .= ' categories';
		}
		if ( isset($params['show_search']) ) {
			$params['widgets'] .= ' search';
		}

		foreach ($params as $key => $value) {
			$result .= " $key='$value'";
		}

		$result .= ']';
		
		$config_js = array();

		$attributes = $this->get_attributes_for_editor();

		$store_page_data = array();
		
		foreach ( $attributes as $key => $attribute ) {

			$name = $attribute['name'];
			if ( !isset( $params[$name] ) ) {
				$store_page_data[$name] = $attribute['default'];
			} 
			
			$value = null;
			
			if ( isset( $params[$name] ) ) {
				$value = $params[$name];
			}

			if ( $name == 'show_description_under_image' ) {

				$layout = ( isset($params['product_details_layout']) ) ? $params['product_details_layout'] : null;
				if ( is_null( $layout ) ) {
					$layout = $attributes['product_details_layout']['default'];
				}

				$applicableLayouts = array( 'TWO_COLUMNS_SIDEBAR_ON_THE_LEFT', 'TWO_COLUMNS_SIDEBAR_ON_THE_RIGHT' );
				if ( in_array( $layout, $applicableLayouts ) ) {
					if ( $layout == 'TWO_COLUMNS_SIDEBAR_ON_THE_LEFT' ) {
						$name = 'product_details_two_columns_with_left_sidebar_show_product_description_on_sidebar';
					} else if ( $layout == 'TWO_COLUMNS_SIDEBAR_ON_THE_RIGHT' ) {
						$name = 'product_details_two_columns_with_right_sidebar_show_product_description_on_sidebar';
					}

					$attribute['is_storefront_api'] = true;

					$api = new Ecwid_Api_V3();
					$settings = $api->get_store_profile();

					if( $settings ){
						$design_settings = $settings->designSettings;
						$value = isset( $params['show_description_under_image'] ) ? !$params['show_description_under_image'] : $design_settings->$name;
						$attribute['profile_default'] = $design_settings->$name;
					}
				}
			}
			

			if ( isset($attribute['is_storefront_api']) && $attribute['is_storefront_api'] && strpos( $name, 'chameleon') === false ) {

				if ( is_null( $value ) ) {
					$value = $attribute['default'];
				}
				
				$profile_default = isset( $attribute['profile_default'] ) 
					? $attribute['profile_default'] 
					: $attribute['default'];
				$is_profile_default = $profile_default == $value;
				
				if ( !$is_profile_default ) {
					if ( @$attribute['type'] == 'boolean') {
						$config_js[] = 'window.ec.storefront.' . $name . "=" . ( $value ? 'true' : 'false' ) . ";";
					} else {
						$config_js[] = 'window.ec.storefront.' . $name . "='" . $value . "';";
					}
					$store_page_data[$name] = $value;
				}
			}
		}

		$colors = array();
		foreach ( array( 'foreground', 'background', 'link', 'price', 'button' ) as $kind ) {
			$color = ( isset($params['chameleon_color_' . $kind]) ) ? $params['chameleon_color_' . $kind] : false;
			if ( $color ) {
				$colors['color-' . $kind] = $color;
			}
		}

		if ( empty( $colors ) ) {
			$colors = 'auto';
		}

		$colors = json_encode($colors);

		$chameleon = apply_filters( 'ecwid_chameleon_settings', array( 'colors' => $colors ) );

		if ( !is_array($chameleon ) ) {
			$chameleon = array(
				'colors' => $colors,
			);
		}

		if ( !isset( $chameleon['colors'] ) ) {
			$chameleon['colors'] = json_encode($colors);
		}

		$store_page_data['chameleon-colors'] = $chameleon['colors'];

		Ecwid_Store_Page::save_store_page_params( $store_page_data );

		$chameleon_config_js = '';
		if ( $chameleon['colors'] != '"auto"' ) {
			$chameleon_config_js .= 'window.ec.config.chameleon = window.ec.config.chameleon || Object();' . PHP_EOL;
			$chameleon_config_js .= 'window.ec.config.chameleon.colors = ' . $chameleon['colors'] . ';' . PHP_EOL;
		}

		if( count($config_js) || !empty($chameleon_config_js) ) {
			$result .= '<script data-cfasync="false" type="text/javascript">' . PHP_EOL;
			$result .= 'window.ec = window.ec || Object();' . PHP_EOL;
			
			if( count($config_js) ) {
				$result .= 'window.ec.storefront = window.ec.storefront || Object();' . PHP_EOL;
				$result .= implode(PHP_EOL, $config_js) . PHP_EOL;	
			}
			
			$result .= $chameleon_config_js;
			$result .= '</script>' . PHP_EOL;
		}

		return $result;
	}

	public function get_attributes_for_editor() {
		$api = new Ecwid_Api_V3();

		if ( $api->is_available() && $api->get_store_profile() ) {
			$settings = $api->get_store_profile()->designSettings;
		} else {
			$settings = new stdClass();
		}

		$attributes = Ecwid_Product_Browser::get_attributes();
		
		$to_remove  = array(
			'product_details_two_columns_with_left_sidebar_show_product_description_on_sidebar',
			'product_details_two_columns_with_right_sidebar_show_product_description_on_sidebar'
		);
		foreach ( $to_remove as $name ) {
			unset( $attributes[ $name ] );
		}

		$attributes['show_description_under_image'] = array(
			'name'    => 'show_description_under_image',
			'title'   => __( 'Show description under the image', 'ecwid-shopping-cart' ),
			'default' => false,
			'type'    => 'boolean'
		);

		foreach ( $attributes as $key => $attribute ) {
			$name = $attribute['name'];

			$default = null;
			if ( property_exists( $settings, $name ) ) {
				$default = $settings->$name;
			}

			$prop_to_default_exceptions = array(
				'product_list_category_image_aspect_ratio' => 'product_list_image_aspect_ratio',
				'product_list_category_image_size'         => 'product_list_image_size'
			);

			if ( array_key_exists( $name, $prop_to_default_exceptions ) ) {
				$another_name = $prop_to_default_exceptions[ $name ];
				if ( property_exists( $settings, $another_name ) ) {
					$default = $settings->$another_name;
				}
			}

			if ( $default !== null ) {
				$attributes[$key]['profile_default'] = $attributes[ $key ]['default'] = $default;
			}
		}

		$categories = ecwid_get_categories_for_selector();

		if ( $categories ) {
			$attributes['default_category_id']['values'] = array(
				array(
					'value' => '0',
					'title' => __( 'Store root category', 'ecwid-shopping-cart' )
				)
			);
			foreach ( $categories as $category ) {
				$attributes['default_category_id']['values'][] = array(
					'value' => $category->id,
					'title' => $category->name
				);
			}

			$attributes['default_category_id']['default'] = '';
		} else {
			$api  = new Ecwid_Api_V3();
			$cats = $api->get_categories( array() );

			if ( $cats && $cats->total == 0 ) {
				unset( $attributes['default_category_id'] );
			}
		}

		$attributes['widgets'] = array( 'type' => 'string', 'default' => '', 'name' => 'widgets' );
		
		return $attributes;
	}

	protected function _is_new_product_list() {
		$api = new Ecwid_Api_V3();

		return ecwid_is_demo_store() || !Ecwid_Api_V3::is_available() || $api->is_store_feature_enabled( Ecwid_Api_V3::FEATURE_NEW_PRODUCT_LIST );
	}

	protected function _is_new_details_page() {
		$api = new Ecwid_Api_V3();

		return ecwid_is_demo_store() || !Ecwid_Api_V3::is_available() || $api->is_store_feature_enabled( Ecwid_Api_V3::FEATURE_NEW_DETAILS_PAGE );
	}

	public function get_icon_path()
	{
		return 'M15.32,15.58c-0.37,0-0.66,0.3-0.66,0.67c0,0.37,0.3,0.67,0.66,0.67c0.37,0,0.67-0.3,0.67-0.67
    C15.98,15.88,15.69,15.58,15.32,15.58z M15.45,0H4.55C2.04,0,0,2.04,0,4.55v10.91C0,17.97,2.04,20,4.55,20h10.91c2.51,0,4.55-2.04,4.55-4.55V4.55
    C20,2.04,17.96,0,15.45,0z M12.97,4.94C13.54,4.94,14,5.4,14,5.96s-0.46,1.03-1.03,1.03c-0.57,0-1.03-0.46-1.03-1.03
    C11.95,5.4,12.41,4.94,12.97,4.94z M12.97,8.02c0.57,0,1.03,0.46,1.03,1.03c0,0.57-0.46,1.03-1.03,1.03c-0.57,0-1.03-0.46-1.03-1.03
    C11.95,8.48,12.41,8.02,12.97,8.02z M9.98,4.94c0.57,0,1.03,0.46,1.03,1.03s-0.46,1.03-1.03,1.03c-0.57,0-1.03-0.46-1.03-1.03
    C8.95,5.4,9.41,4.94,9.98,4.94z M9.98,8.02c0.57,0,1.03,0.46,1.03,1.03s-0.46,1.03-1.03,1.03c-0.57,0-1.03-0.46-1.03-1.03
    C8.95,8.48,9.41,8.02,9.98,8.02z M7.03,4.94c0.57,0,1.03,0.46,1.03,1.03S7.6,6.99,7.03,6.99C6.46,6.99,6,6.53,6,5.96
    C6,5.4,6.46,4.94,7.03,4.94z M7.03,8.02c0.57,0,1.03,0.46,1.03,1.03s-0.46,1.03-1.03,1.03C6.46,10.08,6,9.62,6,9.05
    C6,8.48,6.46,8.02,7.03,8.02z M4.6,18.02c-1.02,0-1.86-0.83-1.86-1.86c0-1.03,0.83-1.86,1.86-1.86c1.03,0,1.86,0.83,1.86,1.86
    C6.45,17.19,5.62,18.02,4.6,18.02z M15.32,18.1c-1.02,0-1.86-0.83-1.86-1.86c0-1.03,0.83-1.86,1.86-1.86c1.03,0,1.86,0.83,1.86,1.86
    C17.17,17.27,16.34,18.1,15.32,18.1z M18.48,2.79l-1.92,7.14c-0.51,1.91-2.03,3.1-4,3.1H7.2c-1.91,0-3.26-1.09-3.84-2.91L1.73,5
    C1.7,4.9,1.72,4.79,1.78,4.71c0.06-0.09,0.16-0.14,0.27-0.14l0.31,0c0.75,0,1.41,0.49,1.64,1.2l1.2,3.76
    c0.32,1.02,1.26,1.7,2.33,1.7h4.81c1.1,0,2.08-0.74,2.36-1.81l1.55-5.78c0.2-0.75,0.89-1.28,1.67-1.28h0.24
    c0.1,0,0.2,0.05,0.26,0.13C18.48,2.58,18.5,2.68,18.48,2.79z M4.6,15.5c-0.37,0-0.66,0.3-0.66,0.67c0,0.37,0.3,0.67,0.66,0.67c0.37,0,0.67-0.3,0.67-0.67
    S4.96,15.5,4.6,15.5z';
	}

}