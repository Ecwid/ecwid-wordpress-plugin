<?php

add_filter('embed_content', 'ecwid_embed_content', 10, 1);

function ecwid_embed_content($data)
{
	return $data;
}

function ecwid_oembed_response_data($data)
{

	$root_category_id = 0;

	$post_content = get_post(get_the_ID())->post_content;
	$shortcodes = ecwid_find_shortcodes($post_content, 'ecwid');

	if (!$shortcodes || !isset($shortcodes[0]) || !isset($shortcodes[0][3])) {
		return $data;
	}

	$attributes = $shortcodes[0][3];
	if (!preg_match('/default_category_id=.([\\d]*)./', $attributes, $matches)) {
		return $data;
	}

	$root_category_id  = 0;
	if (!is_numeric($matches[1])) {
		return $data;
	} else if (isset($matches[1])) {
		$root_category_id  = $matches[1];
	}

	$categories = ecwid_get_categories();

	if ($root_category_id != 0) {
		$categories = _ecwid_find_category_in_horizontal_categories_tree($categories, $root_category_id);
	}

	if (!empty($categories)) {
		echo '<ul>';
		foreach ($categories as $category) {
			$url = ecwid_get_category_url(array('id' => $category->id, 'url' => $category->link));
			echo '<li><a href="' . $url . '">' . $category->name . '</a></li>';
		}
		echo '</ul>';
	}

	if (ecwid_is_paid_account()) {
		$api = new Ecwid_Api_V3(get_ecwid_store_id());

		$products = $api->get_products(array('category' => $root_category_id));

		if ($products) {
			echo '<ul>';
			foreach ($products as $product) {
				$url = ecwid_get_product_url(array('id' => $product->id, 'url' => $product->url));
				echo '<li><a href="' . $url . '">' . $product->name . '</a></li>';
			}
			echo '</ul>';
		}
	}
 	//die(var_dump($categories));
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

