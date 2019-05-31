<?php

class Ecwid_Integration_PWA
{
	public $cache_prefix = 'ec-store';

	public function __construct() {
		$this->register_caching_route();
	}

	public function register_caching_route() {

		$store_id = get_ecwid_store_id();

		$stale_while_revalidate = array(
			'd1q3axnfhmyveb.cloudfront.net',
			'd1q3axnfhmyveb.cloudfront.net',
			'd34ikvsdm2rlij.cloudfront.net',
			'd1q3axnfhmyveb.cloudfront.net',
			'categories.js\?ownerid=' . $store_id,
			'd3j0zfs7paavns.cloudfront.net',
			'data.js\?ownerid=' . $store_id
		);

		wp_register_service_worker_caching_route(
			'.*(?:' . implode( '|', $stale_while_revalidate ) . ').*$',
			array(
				'strategy'  => WP_Service_Worker_Caching_Routes::STRATEGY_STALE_WHILE_REVALIDATE,
				'cacheName' => $this->cache_prefix . ': stale-while-revalidate',
				'plugins'   => array(
					'expiration'        => array(
						'maxEntries'    => 100,
						'maxAgeSeconds' => 60 * 60 * 24 * 30,
					)
				)
			)
		);



		wp_register_service_worker_caching_route(
			'.*(?:png|gif|jpg|svg)$',
			array(
				'strategy'  => WP_Service_Worker_Caching_Routes::STRATEGY_STALE_WHILE_REVALIDATE,
				'cacheName' => $this->cache_prefix . ': images-cache',
				'plugins'   => array(
					'expiration'        => array(
						'maxEntries'    => 100,
						'maxAgeSeconds' => 60 * 60 * 24 * 30,
						'purgeOnQuotaError' => true
					)
				)
			)
		);


		wp_register_service_worker_caching_route(
			'.*(?:ttf|woff|woff2)$',
			array(
				'strategy'  => WP_Service_Worker_Caching_Routes::STRATEGY_STALE_WHILE_REVALIDATE,
				'cacheName' => $this->cache_prefix . ': fonts-cache',
				'plugins'   => array(
					'expiration'        => array(
						'maxAgeSeconds' => 60 * 60 * 24 * 365,
					)
				)
			)
		);


		$plugin_dir_name = dirname( ECWID_PLUGIN_BASENAME );

		wp_register_service_worker_caching_route(
			'.*\/' . $plugin_dir_name . '\/.*(?:css|js).*$',
			array(
				'strategy'  => WP_Service_Worker_Caching_Routes::STRATEGY_STALE_WHILE_REVALIDATE,
				'cacheName' => $this->cache_prefix . ': plugin-cache',
				'plugins'   => array(
					'expiration'        => array(
						'maxAgeSeconds' => 60 * 60 * 24 * 30,
					)
				)
			)
		);


		wp_register_service_worker_caching_route(
			'\/(\?lang=.*\&from_admin)?$',
			array(
				'strategy'  => WP_Service_Worker_Caching_Routes::STRATEGY_NETWORK_FIRST,
				'cacheName' => $this->cache_prefix . ': home-page',
				'plugins'   => array(
					'expiration'        => array(
						'maxAgeSeconds' => 60 * 60 * 24 * 30,
					)
				)
			)
		);

		$store_id = get_ecwid_store_id();
		wp_register_service_worker_caching_route(
			'.*script.js\?' . $store_id . '.*$',
			array(
				'strategy'  => WP_Service_Worker_Caching_Routes::STRATEGY_NETWORK_FIRST,
				'cacheName' => $this->cache_prefix . ': network-first',
				'plugins'   => array(
					'expiration'        => array(
						'maxAgeSeconds' => 60 * 60 * 24 * 30,
					)
				)
			)
		);
	}

}

$ecwid_integration_pwa = new Ecwid_Integration_PWA();