<?php

class EcwidCatalog
{
	var $store_id = 0;
	var $store_base_url = '';

	public function __construct($store_id, $store_base_url)
	{
		$this->store_id = intval($store_id);
		$this->store_base_url = $store_base_url;	
	}

	public function warmup_store_page( $category_id )
	{
		$this->_get_data_for_category( $category_id, null );
	}
	
	public function get_product($id)
	{
		$result = $this->_get_data_for_product($id);

		if ( !$result ) {
			return '';
		}
		
		ob_start();
		$product = $result->product;
		$formats = $result->formats;
		require dirname(__FILE__) . '/html-catalog-templates/product.php';

		$return = ob_get_contents();
		ob_end_clean();
		
		return $return;
	}

	public function get_category($id)
	{
		$data = $this->_get_data_for_category( $id, @$_GET['offset'] );
		
		if ( !$data ) {
			return '';
		}
		
		$main_category = null;
		if ($id > 0) {
			$main_category = $data->main_category;
		}
		$categories = $data->categories;
		$products = $data->products;
		$formats = $data->formats;
		
		ob_start();
		require dirname(__FILE__) . '/html-catalog-templates/category.php';

		$return = ob_get_contents();
		ob_end_clean();

		return $return;
	}

	protected function _get_data_for_product( $id ) 
	{
		if ( Ecwid_Api_V3::is_available() ) {
			$api = new Ecwid_Api_V3();
			
			$product = Ecwid_Product::get_by_id( $id );
			
			$profile = $api->get_store_profile();
			
			if (!$profile) {
				return null;
			}
			
			return (object) array(
				'product' => $product,
				'formats' => @$profile->formatsAndUnits
			);
		} 
		
		return null;
	}
	
	protected function _get_data_for_category( $id, $offset = 0 )
	{
		if ( Ecwid_Api_V3::is_available() ) {
			$api = new Ecwid_Api_V3();
			
			$main_category = null;
			if ($id > 0) {
				$main_category = $api->get_category( $id );
			}

			$get_categories_params = array(
				'parent' => $id
			);
			if ($offset && $offset > 0) {
				$get_categories_params['offset'] = $offset;
			}
			$categories = $api->get_categories( $get_categories_params );
			
			$get_products_params = array(
				'category' => $id
			);
			if ($offset) {
				$get_products_params['offset'] = $offset;
			}
			$products = $api->search_products( $get_products_params );
			
			$profile = $api->get_store_profile();
			
			if ( is_null( $profile ) || !isset( $categories->items ) || !isset( $products->items ) ) {
				return null;
			}
			
			return (object) array(
				'main_category' => $main_category,
				'categories' => $categories->items,
				'products' => $products->items,
				'formats' => @$profile->formatsAndUnits
			);
		} 
		
		return null;
	}
	
	public function parse_escaped_fragment($escaped_fragment)
	{
		$fragment = urldecode($escaped_fragment);
		$return = array();

		if (preg_match('/^(\/~\/)([a-z]+)\/(.*)$/', $fragment, $matches)) {
			parse_str($matches[3], $return);
			$return['mode'] = $matches[2];
		} elseif (preg_match('!.*/(p|c)/([0-9]*)!', $fragment, $matches)) {
			if (count($matches) == 3 && in_array($matches[1], array('p', 'c'))) {
				$return  = array(
					'mode' => 'p' == $matches[1] ? 'product' : 'category',
					'id' => $matches[2]
				);
			}
		}

		return $return;
	}
}
