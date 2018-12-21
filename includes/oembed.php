<?php

add_filter('embed_content', 'ecwid_oembed_content', 10, 1);

function ecwid_oembed_content($data)
{
	echo ecwid_get_embed_content();
}

function ecwid_get_embed_content()
{
	$html = '';
	$root_category_id = 0;

	$post_content = get_post(get_the_ID())->post_content;
	$shortcodes = array();
	
	foreach (Ecwid_Shortcode_Base::get_store_shortcode_names() as $shortcode_name) {
		$shortcodes = ecwid_find_shortcodes($post_content, $shortcode_name);
		if ($shortcodes) {
			break;
		}
	}
		
	if (!$shortcodes || !isset($shortcodes[0]) || !isset($shortcodes[0][3])) {
		return;
	}

	$attributes = $shortcodes[0][3];
	if (!preg_match('/default_category_id=.([\\d]*)./', $attributes, $matches)) {
		return;
	}
	
	$root_category_id  = 0;
	if (!is_numeric($matches[1])) {
		return;
	} else if (isset($matches[1])) {
		$root_category_id  = $matches[1];
	}

	$api = new Ecwid_Api_V3();
	
	$categories = $api->get_categories(array('parent' => $root_category_id));
	
	$max_items = 5;

	$items = array();

	$see_more = false;
	$result = '';
	if (!empty($categories->items)) {
		foreach ($categories->items as $category) {
			$category = Ecwid_Category::get_by_id( $category->id );
			$items[$category->link] = $category->name;
			if (count($items) >= $max_items) {
				$see_more = true;
				break;
			}
		}
	}

	if (ecwid_is_paid_account()) {
		$api = new Ecwid_Api_V3();

		$category = $api->get_category($root_category_id);

		if ($category) {
			$trimmed = ecwid_trim_description($category->description);
			$result .= '<div>' . ecwid_trim_description($category->description);

			$descr_length = function_exists( 'mb_strlen' ) ? mb_strlen( $category->description ) : strlen( $category->description );
			$trimmed_length = function_exists( 'mb_strlen' ) ? mb_strlen( $trimmed ) : strlen( $trimmed );
			
			if ( $trimmed_length < $descr_length && $trimmed_length == ECWID_TRIMMED_DESCRIPTION_LENGTH ) {
				$result .= '... <a class="wp-embed-more" href="' . get_permalink() . '">' . __('See more', 'ecwid-shopping-cart') . '</a>';
			}
			$result .= '</div>';
		}

		if (!$see_more) {
			$products = $api->search_products(array( 'category' => $root_category_id ));
			
			if ($products->items) {
				foreach ($products->items as $product) {
					$product = Ecwid_Product::get_by_id( $product->id );
					$items[$product->link] = $product->name;
					if (count($items) >= $max_items) {
						$see_more = TRUE;
						break;
					}
				}
			}
		}
	}

	$result .= '<ul>';
	if ($items) {
		foreach ($items as $url => $title) {
			$result .= '<li><a href="' . esc_attr($url) . '">' . esc_html($title) . '</a></li>';
		}
	}

	if ($see_more) {
		$result .= '<li><a class="wp-embed-more" href="' . get_permalink() . '">' . __('See more', 'ecwid-shopping-cart') . '</a></li>';
	}

	$result .= '</ul>';
	return $result;
}

function _ecwid_find_category_in_horizontal_categories_tree($categories, $root_id) {
	foreach($categories as $category) {
		if ($category->id == $root_id) {
			return $category->sub;
		}

		if (!is_null($category->sub)) {
			$result = _ecwid_find_category_in_horizontal_categories_tree($category->sub, $root_id);
			if ($result !== false) {
				return $result;
			}
		}
	}
	return false;
}

