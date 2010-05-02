<?php

class EcwidProductApi {
	var $store_id = '';

	var $error = '';

	var $error_code = '';

	var $ECWID_PRODUCT_API_ENDPOINT = "http://app.ecwid.com/api/v1";
	
	function __construct($store_id) {
		$this->store_id = intval($store_id);
	}

	function EcwidProductApi($store_id) {
		if(version_compare(PHP_VERSION,"5.0.0","<")) {
			$this->__construct($store_id);
		}
	}

	function internal_parse_json($json) {
    if(version_compare(PHP_VERSION,"5.2.0",">=")) {
      return json_decode($json, true);
     }
		include_once('JSON.php');
		$json_parser = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
		return $json_parser->decode($json);
	}

	function internal_fetch_url_libcurl($url) {
		if (intval($timeout) <= 0)
			$timeout = 90;
		if (!function_exists('curl_init'))
			return array("code"=>"0","data"=>"libcurl is not installed");
		$headers[] = "Content-Type: application/x-www-form-urlencoded";
		$ch = curl_init();

		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt ($ch, CURLOPT_HTTPGET, 1);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		
		$body = curl_exec ($ch);
		$errno = curl_errno ($ch);
		$error = curl_error($ch);

		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$result = array();
		if( $error ) {
			return array("code"=>"0","data"=>"libcurl error($errno): $error");
		}

		return array("code"=>$httpcode, "data"=>$body);
	}

	function process_request($url) {
		$result = $this->internal_fetch_url_libcurl($url);
		if ($result['code'] == 200) {
			$this->error = '';
			$this->error_code = '';
			$json = $result['data'];
			return $this->internal_parse_json($json);
		} else {
			$this->error = $result['data'];
			$this->error_code = $result['code'];
			return false;
		}
	}

	function get_all_categories() {
		$api_url = $this->ECWID_PRODUCT_API_ENDPOINT . "/" . $this->store_id . "/categories";
		$categories = $this->process_request($api_url);
		return $categories;
	}

	function get_subcategories_by_id($parent_category_id = 0) {
		$parent_category_id = intval($parent_category_id);
		$api_url = $this->ECWID_PRODUCT_API_ENDPOINT . "/" . $this->store_id . "/categories?parent=" .
				$parent_category_id;
		$categories = $this->process_request($api_url);
		return $categories;
	}

	function get_all_products() {
		$api_url = $this->ECWID_PRODUCT_API_ENDPOINT . "/" . $this->store_id . "/products";
		$products = $this->process_request($api_url);
		return $products;
	}


	function get_products_by_category_id($category_id = 0) {
		$category_id = intval($category_id);
		$api_url = $this->ECWID_PRODUCT_API_ENDPOINT . "/" . $this->store_id . "/products?category=" . $category_id;
		$products = $this->process_request($api_url);
		return $products;
	}

	function get_product($product_id) {
		$product_id = intval($product_id);
		$api_url = $this->ECWID_PRODUCT_API_ENDPOINT . "/" . $this->store_id . "/product?id=" . $product_id;
		$product = $this->process_request($api_url);
		return $product;
	}

	function get_batch_request($params) {
		if (!is_array($params)) {
			return false;
		} else {
			$api_url = '';
			foreach ($params as $param) {
				$alias = $param["alias"];
				$action = $param["action"];
				$action_params = $param["params"];
				if (!empty($api_url))
					$api_url .= "&";

				$api_url .= ($alias . "=" . $action);

					// if there are the parameters - add it to url
				if (is_array($action_params)) {
					$action_param_str = "?";
					$is_first = true;
					foreach ($action_params as $action_param_name => $action_param_value) {
						if (!$is_first) {
							$action_param_str .= "&";
						}
						$action_param_str .= $action_param_name . "=" . $action_param_value;
						$is_first = false;
					}
					$action_param_str = urlencode($action_param_str);
					$api_url .= $action_param_str;
				}
			}
			
			$api_url =  $this->ECWID_PRODUCT_API_ENDPOINT . "/" . $this->store_id . "/batch?". $api_url;
			$data = $this->process_request($api_url);
			return $data;
		}
	}

	function get_random_products($count) {
	  $count = intval($count);
		$api_url = $this->ECWID_PRODUCT_API_ENDPOINT . "/" . $this->store_id . "/random_products?count=" . $count;
		$random_products = $this->process_request($api_url);
		return $random_products;
	}
	
	function get_profile() {
		$api_url = $this->ECWID_PRODUCT_API_ENDPOINT . "/" . $this->store_id . "/profile";
		$profile = $this->process_request($api_url);
		return $profile;
	}

  function is_api_enabled() {
    // quick and lightweight request
    $api_url = $this->ECWID_PRODUCT_API_ENDPOINT . "/" . $this->store_id . "/profile";
    $this->process_request($api_url);
    if ($this->error_code === '') {
      return true;
    } else {
      return false;
    }
  }
}

?>
