<?php


class EcwidSitemapBuilder {
	var $callback;
	var $type;

	const PRIORITY_PRODUCT = 0.6;
	const PRIORITY_CATEGORY = 0.5;

	public function __construct($base_url, $callback, $api) {
		$this->callback = $callback;
		$this->base_url = $base_url;
		$this->api = $api;
	}

	public function generate() {

		$api = new Ecwid_Api_V3();

		$offset = 0;
		$limit  = 100;
		do {
			$categories = $api->get_categories(
				array(
					'offset' => $offset,
					'limit' => $limit
				)
			);


			if ($categories->items) {

				foreach ($categories->items as $item) {
					Ecwid_Store_Page::register_category($item);

					$url = Ecwid_Store_Page::get_category_url($item->id);

					call_user_func(
						$this->callback,
						$url,
						self::PRIORITY_CATEGORY,
						'weekly',
						$item
					);
				}
			}

			$offset += $limit;

		} while ($categories->count > 0);

		$offset = 0;
		do {
			$products = $api->search_products(
				array(
					'offset' => $offset,
					'limit' => $limit
				)
			);

			if ($products->items) {

				foreach ($products->items as $item) {
					Ecwid_Store_Page::register_product($item);

					$url = Ecwid_Store_Page::get_product_url($item->id);

					call_user_func(
						$this->callback,
						$url,
						self::PRIORITY_PRODUCT,
						'weekly',
						$item
					);
				}
			}

			$offset += $limit;

		} while ($products->count > 0);

		return true;
	}
}