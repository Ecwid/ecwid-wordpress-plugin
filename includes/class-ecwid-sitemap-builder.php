<?php
class EcwidSitemapBuilder {
	public $callback;
	public $type;
	public $base_url;
	public $unlimited;

	const PRIORITY_PRODUCT  = 0.6;
	const PRIORITY_CATEGORY = 0.5;

	const API_LIMIT           = 100;
	const SITEMAP_ITEMS_LIMIT = 5000;

	public function __construct( $base_url, $callback, $unlimited = false ) {
		$this->callback  = $callback;
		$this->base_url  = $base_url;
		$this->unlimited = apply_filters( 'ecwid_sitemap_builder_set_unlimited', $unlimited );
	}

	public function generate( $page_num ) {
		$api = new Ecwid_Api_V3();

		$stats = $api->get_store_update_stats();

		$offset = 0;
		$limit  = self::API_LIMIT;
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

						$item->updated = gmdate( 'c', strtotime( $stats->categoriesUpdated ) ); //phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

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

		if ( $this->unlimited ) {
			$sitemap_items_limit = self::get_products_total();
		} else {
			$sitemap_items_limit = self::SITEMAP_ITEMS_LIMIT;
		}

		$count  = 0;
		$limit  = self::API_LIMIT;
		$offset = 1 === $page_num ? 0 : $sitemap_items_limit * ( $page_num - 1 );

		if ( $limit > $sitemap_items_limit ) {
			$limit = $sitemap_items_limit;
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

						if ( ! empty( $item->updated ) ) {
							$item->updated = gmdate( 'c', strtotime( $item->updated ) );
						}

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
			}//end if

			if ( $products->count !== $limit ) {
				break;
			}

			$offset += $limit;
		} while ( $count < $sitemap_items_limit );

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
		$unlimited = apply_filters( 'ecwid_sitemap_builder_set_unlimited', false );
		if ( $unlimited ) {
			return 1;
		}

		return ceil( self::get_products_total() / self::SITEMAP_ITEMS_LIMIT );
	}
}
