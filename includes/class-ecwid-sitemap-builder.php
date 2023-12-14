<?php
class EcwidSitemapBuilder {
	public $callback;
	public $type;
	public $base_url;

	const PRIORITY_PRODUCT  = 0.6;
	const PRIORITY_CATEGORY = 0.5;

	const API_MAX_LIMIT       = 100;
	const SITEMAP_ITEMS_LIMIT = 5000;

	public function __construct( $base_url, $callback ) {
		$this->callback = $callback;
		$this->base_url = $base_url;
	}

	public function generate( $page_num ) {
		$api = new Ecwid_Api_V3();

		$stats = $api->get_store_update_stats();

		$offset = 0;
		$limit  = self::API_MAX_LIMIT;
		if ( $page_num === 1 ) {
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
		}//end if

		$count  = 0;
		$limit  = self::API_MAX_LIMIT;
		$offset = 1 === $page_num ? 0 : self::SITEMAP_ITEMS_LIMIT * ( $page_num - 1 );

		if ( $limit > self::SITEMAP_ITEMS_LIMIT ) {
			$limit = self::SITEMAP_ITEMS_LIMIT;
		}

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
					++$count;
				}
			}

			if ( $products->count !== $limit ) {
				break;
			}

			$offset += $limit;
			if ( self::SITEMAP_ITEMS_LIMIT > self::API_MAX_LIMIT ) {
				$limit = self::SITEMAP_ITEMS_LIMIT - $limit;
			}
		} while ( $count < self::SITEMAP_ITEMS_LIMIT );

		return true;
	}

	public static function get_products_total() {
		$api      = new Ecwid_Api_V3();
		$products = $api->search_products( array( 'baseUrl' => '' ) );

		$total = 0;

		if ( ! empty( $products ) ) {
			$total += $products->total;
		}

		return $total;
	}

	public static function get_num_pages() {
		return ceil( self::get_products_total() / self::SITEMAP_ITEMS_LIMIT );
	}
}
