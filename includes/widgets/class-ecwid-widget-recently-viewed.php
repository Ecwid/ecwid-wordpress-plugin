<?php

require_once dirname(__FILE__) . '/class-ecwid-widget-products-base.php';

class Ecwid_Widget_Recently_Viewed extends Ecwid_Widget_Products_List_Base {
	
	protected $_widget_class = 'recentlyViewedProducts';
	
	public function __construct() {
		$this->_init(
			__('Recently Viewed Products', 'ecwid-shopping-cart'),
			__('Displays a list of products recently viewed by the customer to easily return to the products they saw in your shop.', 'ecwid-shopping-cart'),
			'ecwid_recently_viewed',
			'ecwid-recently-viewed-products'
		);
		
		parent::__construct();

		$recently_viewed = false;
		if (isset($_COOKIE['ecwid-shopping-cart-recently-viewed'])) {
			$recently_viewed = json_decode(stripslashes($_COOKIE['ecwid-shopping-cart-recently-viewed']));
		}

		if ($recently_viewed && $recently_viewed->store_id != get_ecwid_store_id() && !is_admin()) {
			setcookie('ecwid-shopping-cart-recently-viewed', null, strtotime('-1 day'));
		}
	}
	
	public function enqueue() {
		parent::enqueue();
		
		if ( is_active_widget( false, false, $this->id_base ) ) {
			wp_enqueue_script($this->_widget_name, ECWID_PLUGIN_URL . 'js/recently-viewed.js', array('ecwid-products-list-js', 'utils'), get_option('ecwid_plugin_version'));
			wp_enqueue_style($this->_widget_name, ECWID_PLUGIN_URL . 'css/recently-viewed.css', array(), get_option('ecwid_plugin_version'));
		}
	}
	
	protected function _get_products() {
		$recently_viewed = false;
		if (isset($_COOKIE['ecwid-shopping-cart-recently-viewed'])) {
			$recently_viewed = json_decode($_COOKIE['ecwid-shopping-cart-recently-viewed']);
		}
		$recently_viewed = json_decode(stripslashes(@$_COOKIE['ecwid-shopping-cart-recently-viewed']));

		if ($recently_viewed && $recently_viewed->store_id != get_ecwid_store_id()) {
			$recently_viewed = null;
		}

		if ($recently_viewed && isset($recently_viewed->products)) {
			$to_load = array();
			foreach ($recently_viewed->products as $product_data) {
				$product = Ecwid_Product::get_without_loading($product_data->id);
				if (!@$product->imageUrl) {
					$to_load[] = $product_data->id;
				}
			}
			if (!empty($to_load)) {
				Ecwid_Product::preload_by_ids($to_load);
			}
		} else {
			return null;
		}

		return array_reverse($recently_viewed->products);
	}
	
	protected function _print_no_products()
	{
		$store_link_message = empty($instance['store_link_title']) ? __('You have not viewed any product yet. Open store.', 'ecwid-shopping-cart') : $instance['store_link_title'];
		echo '<a class="show-if-empty" href="' . Ecwid_Store_Page::get_store_url() . '">' . $store_link_message . '</a>';

		echo <<<HTML
<script type="text/javascript">
jQuery(document).ready(function() {
  wpCookies.remove('ecwid-shopping-cart-recently-viewed');
  recently_viewed = {products: []};
});
</script>
HTML;
		
	}

	protected function _get_form_fields()
	{
		$fields = parent::_get_form_fields();
		
		$fields[] = array(
			'name' => 'store_link_title',
			'title' => __('Store Link Title', 'ecwid-shopping-cart'),
			'type' => 'text',
			'default' => __('You have not viewed any product yet. Open store.', 'ecwid-shopping-cart')
		);
		
		return $fields;
	}
}
