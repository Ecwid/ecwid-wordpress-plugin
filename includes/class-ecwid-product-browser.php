<?php

class Ecwid_Product_Browser
{
	public static function get_attributes()
	{
		return array(   
			'product_list_show_product_images' => array(
				'name' => 'product_list_show_product_images',
				'title' => __( 'Show product thumbnails', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => true,
				'is_storefront_api' => true
			),
			
			'product_list_image_size' => array(
				'name' => 'product_list_image_size',
				'title' => __( 'Product thumbnail size', 'ecwid-shopping-cart' ),
				'values' => self::_get_sizes(),
				'default' => 'MEDIUM',
				'is_storefront_api' => true
			),
			
			'product_list_image_aspect_ratio' => array(
				'name' => 'product_list_image_aspect_ratio',
				'title' => __( 'Product thumbnail aspect ratio', 'ecwid-shopping-cart' ),
				'values' => self::_get_aspect_ratios(),
				'default' => 'SQUARE',
				'is_storefront_api' => true
			),
			
			'product_list_show_frame' => array(
				'name' => 'product_list_show_frame',
				'title' => __( 'Show product card border', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => false,
				'is_storefront_api' => true
			),
			
			'product_list_product_info_layout' => array(
				'name' => 'product_list_product_info_layout',
				'title' => __( 'Product card text align', 'ecwid-shopping-cart' ),
				'values' => array(
					array(
						'value' => 'LEFT',
						'title' => __( 'Left', 'ecwid-shopping-cart' ),
						'icon'  => 'textalignleft',
					),
					array(
						'value' => 'CENTER',
						'title' => __( 'Center', 'ecwid-shopping-cart' ),
						'icon'  => 'textaligncenter'
					),
					array(
						'value' => 'RIGHT',
						'title' => __( 'Right', 'ecwid-shopping-cart' ),
						'icon'  => 'textalignright'
					),
					array(
						'value' => 'JUSTIFY',
						'title' => __( 'Justify', 'ecwid-shopping-cart' ),
						'icon'  => 'textalignjustify'
					)					
				),
				'default' => 'CENTER',
				'is_storefront_api' => true
			),
			
			'product_list_title_behavior' => array(
				'name' => 'product_list_title_behavior',
				'title' => __( 'Product title', 'ecwid-shopping-cart' ),
				'values' => self::_get_behaviors(),
				'default' => 'SHOW',
				'is_storefront_api' => true
			),

			'product_list_price_behavior' => array(
				'name' => 'product_list_price_behavior',
				'title' => __( 'Product price', 'ecwid-shopping-cart' ),
				'values' => self::_get_behaviors(),
				'default' => 'SHOW',
				'is_storefront_api' => true
			),

			'product_list_sku_behavior' => array(
				'name' => 'product_list_sku_behavior',
				'title' => __( 'Product SKU', 'ecwid-shopping-cart' ),
				'values' => self::_get_behaviors(),
				'default' => 'HIDE',
				'is_storefront_api' => true
			),

			'product_list_buybutton_behavior' => array(
				'name' => 'product_list_buybutton_behavior',
				'title' => __( 'Buy now buttons', 'ecwid-shopping-cart' ),
				'values' => self::_get_behaviors(),
				'default' => 'SHOW',
				'is_storefront_api' => true
			),
			
			'product_list_show_additional_image_on_hover' => array(
				'name' => 'product_list_show_additional_image_on_hover',
				'title' => __( 'Show additional image on hover', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => false,
				'is_storefront_api' => true
			),
		
			'product_list_category_title_behavior' => array(
				'name' => 'product_list_category_title_behavior',
				'title' => __( 'Category card layout', 'ecwid-shopping-cart' ),
				'values' => array(
					array(
						'value' => 'SHOW_BELOW_IMAGE',
						'title' => __( 'Title under image', 'ecwid-shopping-cart' )
					),
					array(
						'value' => 'SHOW_ON_IMAGE',
						'title' => __( 'Title on image', 'ecwid-shopping-cart' )
					),
					array(
						'value' => 'SHOW_ON_HOVER',
						'title' => __( 'Image and title on mouse over', 'ecwid-shopping-cart' )
					),
					array(
						'value' => 'SHOW_TEXT_ONLY',
						'title' => __( 'Title only', 'ecwid-shopping-cart' )
					),
					array(
						'value' => 'HIDE',
						'title' => __( 'Image only', 'ecwid-shopping-cart' )
					)
				),
				'default' => 'SHOW_ON_HOVER',
				'is_storefront_api' => true
			),
		
			'product_list_category_image_size' => array(
				'name' => 'product_list_category_image_size',
				'title' => __( 'Category thumbnail size', 'ecwid-shopping-cart' ),
				'values' => self::_get_sizes(),
				'default' => 'MEDIUM',
				'is_storefront_api' => true
			),	
			
			'product_list_category_image_aspect_ratio' => array(
				'name' => 'product_list_category_image_aspect_ratio',
				'title' => __( 'Category thumbnail aspect ratio', 'ecwid-shopping-cart' ),
				'values' => self::_get_aspect_ratios(),
				'default' => 'SQUARE_1',
				'is_storefront_api' => true
			),
			
			'show_categories' => array(
				'name' => 'show_categories',
				'title' => __( 'Display categories menu', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => false
			),
			
			'show_search' => array(
				'name' => 'show_search',
				'title' => __( 'Display search box', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => false
			),

			'show_breadcrumbs' => array(
				'name' => 'show_breadcrumbs',
				'title' => __( 'Display breadcrumbs', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => true,
				'is_storefront_api' => true
			),

			'show_footer_menu' => array(
				'name' => 'show_footer_menu',
				'title' => __( 'Display footer menu', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => true,
				'is_storefront_api' => true
			),

			'show_signin_link' => array(
				'name' => 'show_signin_link',
				'title' => __( 'Display sign in link', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => true,
				'is_storefront_api' => true
			),

			'product_list_show_sort_viewas_options' => array(
				'name' => 'product_list_show_sort_viewas_options',
				'title' => __( 'Display sort by link', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => true,
				'is_storefront_api' => true
			),

			'default_category_id' => array(
				'name' => 'default_category_id',
				'title' => __( 'Default category ID', 'ecwid-shopping-cart' ),
				'type' => 'default_category_id',
				'default' => 0
			),

			'default_product_id' => array(
				'name' => 'default_product_id',
				'title' => __( 'Default product_ID', 'ecwid-shopping-cart' ),
				'type' => 'default_product_id',
				'default' => 0
			),

			'product_details_layout' => array(
				'name' => 'product_details_layout',
				'title' => __( 'Product page layout', 'ecwid-shopping-cart' ),
				'values' => self::_get_product_details_layouts(),
				'default' => 'TWO_COLUMNS_SIDEBAR_ON_THE_RIGHT',
				'is_storefront_api' => true
			),

			'product_details_two_columns_with_left_sidebar_show_product_description_on_sidebar' => array(
				'name' => 'product_details_two_columns_with_left_sidebar_show_product_description_on_sidebar',
				'title' => __( 'Show description under the image', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => true,
				'is_storefront_api' => true
			),


			'product_details_two_columns_with_right_sidebar_show_product_description_on_sidebar' => array(
				'name' => 'product_details_two_columns_with_right_sidebar_show_product_description_on_sidebar',
				'title' => __( 'Show description under the image', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => true,
				'is_storefront_api' => true
			),

			'product_details_gallery_layout' => array(
				'name' => 'product_details_gallery_layout',
				'title' => __( 'Image gallery layout', 'ecwid-shopping-cart' ),
				'values' => self::_get_gallery_layouts(),
				'default' => 'IMAGE_SINGLE_THUMBNAILS_HORIZONTAL',
				'is_storefront_api' => true
			),

			'product_details_show_product_name' => array(
				'name' => 'product_details_show_product_name',
				'title' => __( 'Title', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => true,
				'is_storefront_api' => true
			),

			'product_details_show_breadcrumbs' => array(
				'name' => 'product_details_show_breadcrumbs',
				'title' => __( 'Breadcrumbs', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => true,
				'is_storefront_api' => true
			),
			'product_details_show_product_sku' => array(
				'name' => 'product_details_show_product_sku',
				'title' => _x( 'Product SKU', 'product-browser-attributes', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => true,
				'is_storefront_api' => true
			),
			'product_details_show_product_price' => array(
				'name' => 'product_details_show_product_price',
				'title' => _x( 'Product price', 'product-browser-attributes', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => true,
				'is_storefront_api' => true
			),
			'product_details_show_qty' => array(
				'name' => 'product_details_show_qty',
				'title' => __( 'Product quantity', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => true,
				'is_storefront_api' => true
			),
			'product_details_show_number_of_items_in_stock' => array(
				'name' => 'product_details_show_number_of_items_in_stock',
				'title' => __( 'Available qty in stock', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => true,
				'is_storefront_api' => true
			),
			'product_details_show_in_stock_label' => array(
				'name' => 'product_details_show_in_stock_label',
				'title' => __( '"In stock" label', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => true,
				'is_storefront_api' => true
			),
			'product_details_show_wholesale_prices' => array(
				'name' => 'product_details_show_wholesale_prices',
				'title' => __( 'Wholesale prices', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => true,
				'is_storefront_api' => true
			),
			'product_details_show_share_buttons' => array(
				'name' => 'product_details_show_share_buttons',
				'title' => __( 'Display sign in link', 'ecwid-shopping-cart' ),
				'type' => 'boolean',
				'default' => true,
				'is_storefront_api' => true
			),

			'chameleon_color_button' => array(
				'name' => 'chameleon_color_button',
				'title' => __( 'Button color', 'ecwid-shopping-cart' ),
				'default' => get_option( 'ecwid_chameleon_colors_button' )
			),
			
			'chameleon_color_foreground' => array(
				'name' => 'chameleon_color_foreground',
				'title' => __( 'Text color', 'ecwid-shopping-cart' ),
				'default' => get_option( 'ecwid_chameleon_colors_foreground' )
			),

			'chameleon_color_price' => array(
				'name' => 'chameleon_color_price',
				'title' => __( 'Price color', 'ecwid-shopping-cart' ),
				'default' => get_option( 'ecwid_chameleon_colors_price' )
			),

			'chameleon_color_link' => array(
				'name' => 'chameleon_color_link',
				'title' => __( 'Link color', 'ecwid-shopping-cart' ),
				'default' => get_option( 'ecwid_chameleon_colors_link' )
			),

			'chameleon_color_background' => array(
				'name' => 'chameleon_color_background',
				'title' => __( 'Background color', 'ecwid-shopping-cart' ),
				'default' => get_option( 'ecwid_chameleon_colors_background' )
			),

			'product_filters_position_search_page' => array(
				'name' => 'product_filters_position_search_page',
				'title' => __( 'Filters panel position', 'ecwid-shopping-cart' ),
				'values' => array(
					array(
						'value' => 'LEFT',
						'title' => __( 'On the left', 'ecwid-shopping-cart' )
					),
					array(
						'value' => 'RIGHT',
						'title' => __( 'On the right', 'ecwid-shopping-cart' )
					)
				),
				'default' => 'LEFT',
				'is_storefront_api' => true
			),

		);

	}
	
	protected static function _get_behaviors()
	{
		return array(
			array(
				'value' => 'SHOW',
				'title' => __( 'Show', 'ecwid-shopping-cart' )
			),
			array(
				'value' => 'HIDE',
				'title' => __( 'Hide', 'ecwid-shopping-cart' )
			),
			array(
				'value' => 'SHOW_ON_HOVER',
				'title' => __( 'Show on hover', 'ecwid-shopping-cart' ),
			)
		);
	}
	
	protected static function _get_sizes()
	{
		return array(
			array(
				'value' => 'SMALL',
				'title' => __( 'S', 'ecwid-shopping-cart' )
			),
			array(
				'value' => 'MEDIUM',
				'title' => __( 'M', 'ecwid-shopping-cart' )
			),
			array(
				'value' => 'LARGE',
				'title' => __( 'L', 'ecwid-shopping-cart' )
			)
		);		
	}
	
	protected static function _get_aspect_ratios()
	{
		return array(
			array(
				'value' => 'PORTRAIT_0667',
				'title' => __( 'Portrait 2:3', 'ecwid-shopping-cart' ),
				'icon'  => 'aspect916',
			),
			array(
				'value' => 'PORTRAIT_075',
				'title' => __( 'Portrait 3:4', 'ecwid-shopping-cart' ),
				'icon'  => 'aspect34',
			),
			array(
				'value' => 'SQUARE',
				'title' => __( 'Square 1:1', 'ecwid-shopping-cart' ),
				'icon'  => 'aspect11'
			),
			array(
				'value' => 'LANDSCAPE_1333',
				'title' => __( 'Landscape 4:3', 'ecwid-shopping-cart' ),
				'icon'  => 'aspect43'
			),
			array(
				'value' => 'LANDSCAPE_15',
				'title' => __( 'Landscape 3:2', 'ecwid-shopping-cart' ),
				'icon'  => 'aspect169'
			)
		);
	}

	protected static function _get_product_details_layouts()
	{
		return array(
			array(
				'value' => 'TWO_COLUMNS_SIDEBAR_ON_THE_RIGHT',
				'title' => __( '2 columns, image on the left', 'ecwid-shopping-cart' )
			),
			array(
				'value' => 'TWO_COLUMNS_SIDEBAR_ON_THE_LEFT',
				'title' => __( '2 columns, image on the right', 'ecwid-shopping-cart' )
			),
			array(
				'value' => 'THREE_COLUMNS_SIDEBAR_ON_THE_RIGHT',
				'title' => __( '3 columns, image on the left', 'ecwid-shopping-cart' )
			),
			array(
				'value' => 'THREE_COLUMNS_SIDEBAR_ON_THE_LEFT',
				'title' => __( '3 columns, image on the right', 'ecwid-shopping-cart' )
			),
		);
	}

	protected static function _get_gallery_layouts()
	{
		return array(
			array(
				'value' => 'IMAGE_SINGLE_THUMBNAILS_HORIZONTAL',
				'title' => __( 'Horizontal thumbnails', 'ecwid-shopping-cart' ),
				'icon'  => 'galleryLayoutVertical',
			),
			array(
				'value' => 'IMAGE_SINGLE_THUMBNAILS_VERTICAL',
				'title' => __( 'Vertical thumbnails', 'ecwid-shopping-cart' ),
				'icon'  => 'galleryLayoutHorizontal'
			),
			array(
				'value' => 'IMAGE_FEED',
				'title' => __( 'Image feed', 'ecwid-shopping-cart' ),
				'icon'  => 'galleryLayoutFeed'
			)
		);
	}
}