<?php

function show_ecwid($params) {
	$store_id = $params['store_id'];
	if (empty($store_id)) {
	  $store_id = '1003'; //demo mode
	}
		
	$list_of_views = $params['list_of_views'];
	
	foreach ($list_of_views as $k=>$v) {
		if (!in_array($v, array('list','grid','table'))) unset($list_of_views[$k]);
	}
	
	if ((!is_array($list_of_views)) || empty($list_of_views)) {
		$list_of_views = array('list','grid','table');
	}

	$ecwid_pb_categoriesperrow = $params['ecwid_pb_categoriesperrow'];
	if (empty($ecwid_pb_categoriesperrow)) {
		$ecwid_pb_categoriesperrow = 3;
	}
	$ecwid_pb_productspercolumn_grid = $params['ecwid_pb_productspercolumn_grid'];
	if (empty($ecwid_pb_productspercolumn_grid)) {
		$ecwid_pb_productspercolumn_grid = 3;
	}
	$ecwid_pb_productsperrow_grid = $params['ecwid_pb_productsperrow_grid'];
	if (empty($ecwid_pb_productsperrow_grid)) {
		$ecwid_pb_productsperrow_grid = 3;
	}
	$ecwid_pb_productsperpage_list = $params['ecwid_pb_productsperpage_list'];
	if (empty($ecwid_pb_productsperpage_list)) {
		$ecwid_pb_productsperpage_list = 10;
	}
	$ecwid_pb_productsperpage_table = $params['ecwid_pb_productsperpage_table'];
	if (empty($ecwid_pb_productsperpage_table)) {
		$ecwid_pb_productsperpage_table = 20;
	}
	$ecwid_pb_defaultview = $params['ecwid_pb_defaultview'];
	if (empty($ecwid_pb_defaultview) || !in_array($ecwid_pb_defaultview, $list_of_views)) {
		$ecwid_pb_defaultview = 'grid';
	}
	$ecwid_pb_searchview = $params['ecwid_pb_searchview'];
	if (empty($ecwid_pb_searchview) || !in_array($ecwid_pb_searchview, $list_of_views)) {
		$ecwid_pb_searchview = 'list';
	}
	$ecwid_enable_html_mode = $params['ecwid_enable_html_mode'];
	if (empty($ecwid_enable_html_mode)) {
		$ecwid_enable_html_mode = false;
	}

	$ecwid_com = "app.ecwid.com";


	$ecwid_default_category_id = $params['ecwid_default_category_id'];
	
	$ecwid_show_seo_catalog = $params['ecwid_show_seo_catalog'];
	if (empty($ecwid_show_seo_catalog)) {
		$ecwid_show_seo_catalog = false;
	}

 	$ecwid_mobile_catalog_link = $params['ecwid_mobile_catalog_link'];
	if (empty($ecwid_mobile_catalog_link)) {
		$ecwid_mobile_catalog_link = "http://$ecwid_com/jsp/$store_id/catalog";
	}

  $html_catalog = '';
	if ($ecwid_show_seo_catalog) {
    if (!empty($_GET['ecwid_product_id'])) {
      $ecwid_open_product = '<script>document.location.hash = "ecwid:category=0&mode=product&product='. intval($_GET['ecwid_product_id']) .'";</script>';
     } elseif (!empty($_GET['ecwid_category_id'])) {
       $ecwid_default_category_id = intval($_GET['ecwid_category_id']);
     }
		$html_catalog = show_ecwid_catalog($store_id);
	}
	
	if (empty($html_catalog)) {
		$html_catalog = "Your browser does not support JavaScript.<a href=\"{$ecwid_mobile_catalog_link}\">HTML version of this store</a>";
	}


	if (empty($ecwid_default_category_id)) {
		$ecwid_default_category_str = '';
	} else {
		$ecwid_default_category_str = ',"defaultCategoryId='. $ecwid_default_category_id .'"';
	}

	$ecwid_is_secure_page = $params['ecwid_is_secure_page'];
	if (empty ($ecwid_is_secure_page)) {
		$ecwid_is_secure_page = false;
	}

	$protocol = "http";
	if ($ecwid_is_secure_page) {
		$protocol = "https";
	}

	$integration_code = <<<EOT
<div>
<script type="text/javascript" src="$protocol://$ecwid_com/script.js?$store_id"></script>
<script type="text/javascript"> xProductBrowser("categoriesPerRow=$ecwid_pb_categoriesperrow","views=grid($ecwid_pb_productspercolumn_grid,$ecwid_pb_productsperrow_grid) list($ecwid_pb_productsperpage_list) table($ecwid_pb_productsperpage_table)","categoryView=$ecwid_pb_defaultview","searchView=$ecwid_pb_searchview","style="$ecwid_default_category_str);</script>
</div>
<noscript>$html_catalog</noscript>
$ecwid_open_product
EOT;
  
	return $integration_code;
}

function show_ecwid_catalog($ecwid_store_id) {
  include_once "ecwid_product_api.php";
	$ecwid_store_id = intval($ecwid_store_id);
	$api = new EcwidProductApi($ecwid_store_id);

	$ecwid_category_id = intval($_GET['ecwid_category_id']);
	$ecwid_product_id = intval($_GET['ecwid_product_id']);

	if (!empty($ecwid_product_id)) {
		$params = array(
			array("alias" => "p", "action" => "product", "params" => array("id" => $ecwid_product_id)),
			array("alias" => "pf", "action" => "profile")
		);
		$batch_result = $api->get_batch_request($params);
		$product = $batch_result["p"];
		$profile = $batch_result["pf"];
	}
	else {
		if (empty($ecwid_category_id)) {
			$ecwid_category_id = 0;
		}
		$params = array(
			array("alias" => "c", "action" => "categories", "params" => array("parent" => $ecwid_category_id)),
			array("alias" => "p", "action" => "products", "params" => array("category" => $ecwid_category_id)),
			array("alias" => "pf", "action" => "profile")
		);

		$batch_result = $api->get_batch_request($params);

		$categories = $batch_result["c"];
		$products = $batch_result["p"];
		$profile = $batch_result["pf"];
	}
	$html = '';

	if (is_array($product)) {
		$html = "<div class='hproduct'>";
		$html .= "<div class='ecwid_catalog_product_image photo'><img src='" . $product["thumbnailUrl"] . "'/></div>";
		$html .= "<div class='ecwid_catalog_product_name fn'>" . htmlentities($product["name"]) . "</div>";
		$html .= "<div class='ecwid_catalog_product_price price'>Price: " . $product["price"] . "&nbsp;" . $profile["currency"] . "</div>";
		$html .= "<div class='ecwid_catalog_product_description description'>" . $product["description"] . "</div>";
		$html .= "</div>";
	} else {
		if (is_array($categories)) {
			foreach ($categories as $category) {
				$category_url = ecwid_internal_construct_url($category["url"], array("ecwid_category_id" => $category["id"]));
				$category_name = $category["name"];
				$html .= "<div class='ecwid_catalog_category_name'><a href='" . $category_url . "'>" . $category_name . "</a><br /></div>";
			}
		}

		if (is_array($products)) {
			foreach ($products as $product) {
				$product_url = ecwid_internal_construct_url($product["url"], array("ecwid_product_id" => $product["id"]));
				$product_name = $product["name"];
				$product_price = $product["price"] . "&nbsp;" . $profile["currency"];
				$html .= "<div>";
				$html .= "<span class='ecwid_product_name'><a href='" . $product_url . "'>" . $product_name . "</a></span>";
				$html .= "&nbsp;&nbsp;<span class='ecwid_product_price'>" . $product_price . "</span>";
				$html .= "</div>";
			}
		}

	}
	return $html;
}

function ecwid_is_api_enabled($ecwid_store_id) {
	$ecwid_store_id = intval($ecwid_store_id);
	$api = new EcwidProductApi($ecwid_store_id);
  return $api->is_api_enabled();
}

function ecwid_internal_construct_url($url_with_anchor, $additional_get_params) {
	$is_https = ($_SERVER["HTTPS"] == "on");
	$port = $_SERVER["SERVER_PORT"];
	$host = $_SERVER["HTTP_HOST"];
	$path = $_SERVER["SCRIPT_NAME"];
	// extract anchor
	$url_fragments = parse_url($url_with_anchor);
	$anchor = $url_fragments["fragment"];
	// get params
	$get_params = $_GET;
	unset ($get_params["ecwid_category_id"]);
	unset ($get_params["ecwid_product_id"]);
	$get_params = array_merge($get_params, $additional_get_params);

	// construct url

	// add schema
	$base_url = "";
	if (!$is_https) {
		$base_url = "http://";
	} else {
		$base_url = "https://";
	}

	// add hostname
	$base_url .= $host;

	// add port (if needed)
	if (($is_https && ($port != 443)) || (!$is_https && ($port != 80))) {
		$base_url .= ":" . $port;
	}

	// add path
	$base_url .= $path;

		// add GET parameters
	if (count($get_params) > 0) {
		$base_url .= "?";
		$is_first = true;
		foreach ($get_params as $key => $value) {
			if (!$is_first) {
				$base_url .= "&";
			}
			$base_url .= $key . "=" . $value;
			$is_first = false;
		}
	}

	// add url anchor (if needed)
	if ($anchor != "") {
		$base_url .= "#" . $anchor;
	}

	return $base_url;
}

?>
