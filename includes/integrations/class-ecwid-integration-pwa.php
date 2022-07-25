<?php

class Ecwid_Integration_PWA {

	public $cache_prefix = 'ec-store';

	public function __construct() {
		if ( ! class_exists( 'WP_Service_Worker_Caching_Routes' ) ) {
			return;
		}

		$this->_register_routes();
	}

	protected function _register_routes() {

		$routes = $this->_get_routes();

		if ( ! is_array( $routes ) && empty( $routes ) ) {
			return;
		}

		foreach ( $routes as $route ) {
			wp_register_service_worker_caching_route(
				$route['pattern'],
				array(
					'strategy'  => $route['strategy'],
					'cacheName' => sprintf( '%s: %s', $this->cache_prefix, $route['cache_name'] ),
					'plugins'   => array(
						'expiration' => $route['expiration'],
					),
				)
			);
		}
	}

	protected function _get_routes() {
		$store_id = get_ecwid_store_id();

		$stale_while_revalidate = array(
			'd1q3axnfhmyveb.cloudfront.net',
			'd1q3axnfhmyveb.cloudfront.net',
			'd34ikvsdm2rlij.cloudfront.net',
			'd1q3axnfhmyveb.cloudfront.net',
			'categories.js\?ownerid=' . $store_id,
			'd1oxsl77a1kjht.cloudfront.net',
			'data.js\?ownerid=' . $store_id,
		);

		$plugin_dir_name = dirname( ECWID_PLUGIN_BASENAME );

		$routes = array(

			array(
				'pattern'    => '.*(?:' . implode( '|', $stale_while_revalidate ) . ').*$',
				'cache_name' => 'stale-while-revalidate',
				'strategy'   => WP_Service_Worker_Caching_Routes::STRATEGY_STALE_WHILE_REVALIDATE,
				'expiration' => array(
					'maxEntries'    => 100,
					'maxAgeSeconds' => 60 * 60 * 24 * 30,
				),
			),

			array(
				'pattern'    => '.*(?:png|gif|jpg|svg)$',
				'cache_name' => 'images-cache',
				'strategy'   => WP_Service_Worker_Caching_Routes::STRATEGY_STALE_WHILE_REVALIDATE,
				'expiration' => array(
					'maxEntries'        => 100,
					'maxAgeSeconds'     => 60 * 60 * 24 * 30,
					'purgeOnQuotaError' => true,
				),
			),

			array(
				'pattern'    => '.*(?:ttf|woff|woff2)$',
				'cache_name' => 'fonts-cache',
				'strategy'   => WP_Service_Worker_Caching_Routes::STRATEGY_STALE_WHILE_REVALIDATE,
				'expiration' => array(
					'maxAgeSeconds' => 60 * 60 * 24 * 365,
				),
			),

			array(
				'pattern'    => '.*\/' . $plugin_dir_name . '\/.*(?:css|js).*$',
				'cache_name' => 'plugin-cache',
				'strategy'   => WP_Service_Worker_Caching_Routes::STRATEGY_STALE_WHILE_REVALIDATE,
				'expiration' => array(
					'maxAgeSeconds' => 60 * 60 * 24 * 30,
				),
			),

			array(
				'pattern'    => '.*script.js\?' . $store_id . '.*$',
				'cache_name' => 'network-first',
				'strategy'   => WP_Service_Worker_Caching_Routes::STRATEGY_NETWORK_FIRST,
				'expiration' => array(
					'maxAgeSeconds' => 60 * 60 * 24 * 30,
				),
			),

		);

		return $routes;
	}

}

$ecwid_integration_pwa = new Ecwid_Integration_PWA();
