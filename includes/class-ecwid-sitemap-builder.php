<?php
class EcwidSitemapBuilder {
	public $callback;
	public $type;
	public $base_url;

	const PRIORITY_PRODUCT  = 0.6;
	const PRIORITY_CATEGORY = 0.5;

	public function __construct( $base_url, $callback ) {
		$this->callback = $callback;
		$this->base_url = $base_url;
	}

	public function generate() {

		$api = new Ecwid_Api_V3();

		$stats = $api->get_store_update_stats();

		$offset = 0;
		$limit  = 100;
		do {
			$categories = $api->get_categories(
				array(
					'offset' => $offset,
					'limit'  => $limit,
				)
			);

			if ( empty( $categories ) ) {
				break;
			}

			if ( $categories->items ) {

				foreach ( $categories->items as $item ) {

					$url = $item->url;

					$item->updated = $stats->categoriesUpdated; //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

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

		} while ( $categories->count > 0 );

		$offset = 0;
		do {
			$products = $api->search_products(
				array(
					'offset' => $offset,
					'limit'  => $limit,
				)
			);

			if ( empty( $products ) ) {
				break;
			}

			if ( $products->items ) {

				foreach ( $products->items as $item ) {
					if ( $item->enabled ) {

						$url = $item->url;

						call_user_func(
							$this->callback,
							$url,
							self::PRIORITY_PRODUCT,
							'weekly',
							$item
						);
					}
				}
			}

			$offset += $limit;

		} while ( $products->count > 0 );

		return true;
	}
}
