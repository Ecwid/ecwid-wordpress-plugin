<?php

require_once dirname(__FILE__) . '/class-ecwid-widget-products-base.php';

class Ecwid_Widget_Random_Products extends Ecwid_Widget_Products_List_Base {

	public function __construct() {
		$this->_init(
			__('Random Products', 'ecwid-shopping-cart'),
			__('Displays a list of random products.', 'ecwid-shopping-cart')
		);

		parent::__construct();
	}

	protected function _get_products() {
		$api = new Ecwid_Api_V3();

		$sorts = array(
			'PRICE_ASC',
			'ADDED_TIME_ASC',
			'UPDATED_TIME_ASC',
			'NAME_ASC'
		);

		$total = EcwidPlatform::get_from_products_cache('ecwid_total_products');
		
		$offset = 0;
		if ($total > 0) {
			$max = $total / 100;
			$offset = rand(0, $max - 1) * 100;
		}
		
		
		$found = $api->search_products(array(
			'sortBy' => $sorts[rand(0, 3)],
			'offset' => $offset
		));
		
		$items = $found->items;
		
		$result = array();
		for ($i = 0; $i < $this->_instance['number_of_products']; $i++) {
			
			if (count($items) == 0) {
				break;
			}
			
			$ind = rand(0, count($items) - 1);
			
			$result[] = $items[$ind];
			unset($items[$ind]);
			$items = array_values($items);
		}
		
		return $result;
	}
}
