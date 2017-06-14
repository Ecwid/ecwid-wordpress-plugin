<?php

class EcwidCatalog
{
	var $store_id = 0;
	var $store_base_url = '';
	var $ecwid_api = null;

	public function __construct($store_id, $store_base_url)
	{
		$this->store_id = intval($store_id);
		$this->store_base_url = $store_base_url;	
		$this->ecwid_api = new EcwidProductApi($this->store_id);
	}

	public function get_product($id)
	{
		$result = $this->_get_data_for_product($id);
		
		ob_start();
		$product = $result->product;
		$formats = $result->formats;
		require __DIR__ . '/html-catalog-templates/product.php';

		$return = ob_get_contents();
		ob_end_clean();
		
		return $return;
	}

	public function get_category($id)
	{
		$data = $this->_get_data_for_category( $id, @$_GET['offset'] );
		
		$main_category = null;
		if ($id > 0) {
			$main_category = $data->main_category;
		}
		$categories = $data->categories;
		$products = $data->products;
		$formats = $data->formats;
		
		ob_start();
		require __DIR__ . '/html-catalog-templates/category.php';

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
			
			return (object) array(
				'product' => $product,
				'formats' => $profile->formatsAndUnits
			);
		} else {

			$params = array
			(
				array("alias" => "product", "action" => "product", "params" => array("id" => $id)),
				array("alias" => "formats", "action" => "profile")
			);
			
			$batch_result = $this->_get_apiv1_batch_result( $params );
			
			return $batch_result;
		}
		
	}
	
	protected function _get_data_for_category( $id, $offset )
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
			if ($offset) {
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
			
			return (object) array(
				'main_category' => $main_category,
				'categories' => $categories->items,
				'products' => $products->items,
				'formats' => $profile->formatsAndUnits
			);
		} else {
			$params = array
			(
				array("alias" => "categories", "action" => "categories", "params" => array("parent" => $id)),
				array("alias" => "products", "action" => "products", "params" => array("category" => $id)),
				array("alias" => "formats", "action" => "profile")
			);
			if ($id > 0) {
				$params[] = array('alias' => 'main_category', "action" => "category", "params" => array("id" => $id));
			}
			
			$batch_result = $this->_get_apiv1_batch_result( $params );
			if ( !isset($batch_result->main_category) ) {
				$batch_result->main_category = null;
			}
			
			return $batch_result;
		}
	}
	
	protected function _get_apiv1_batch_result($params) {
		$batch_result = $this->ecwid_api->get_batch_request($params);
		if ( is_array( $batch_result ) ) {
			$batch_result = $this->ecwid_api->get_batch_request($params);
		}
	
		return $batch_result;
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
