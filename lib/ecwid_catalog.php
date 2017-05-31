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

		$params = array 
		(
			array("alias" => "p", "action" => "product", "params" => array("id" => $id)),
			array("alias" => "pf", "action" => "profile")
		);

		$batch_result = $this->ecwid_api->get_batch_request($params);

		$product = $batch_result["p"];
		$profile = $batch_result["pf"];

		$return = $this->_l('');
		
		if (is_array($product)) 
		{
		
			$return .= $this->_l('<div itemscope itemtype="http://schema.org/Product">', 1);
			$return .= $this->_l('<h1 class="ecwid_catalog_product_name" itemprop="name">' . EcwidPlatform::esc_html($product["name"]) . '</h1>');
			$return .= $this->_l('<p class="ecwid_catalog_product_sku" itemprop="sku">' . EcwidPlatform::esc_html($product["sku"]) . '</p>');
			
			if (!empty($product["thumbnailUrl"])) 
			{
				$return .= $this->_l('<div class="ecwid_catalog_product_image">', 1);
				$return .= $this->_l(
					sprintf(
						'<img itemprop="image" src="%s" alt="%s" />',
						EcwidPlatform::esc_attr($product['originalImageUrl']),
						EcwidPlatform::esc_attr($product['name'] . ' ' . $product['sku'])
					)
				);
				$return .= $this->_l('</div>', -1);
			}

			if(isset($product['categories']) && is_array($product["categories"]))
			{
				foreach ($product["categories"] as $ecwid_category) 
				{
					if($ecwid_category["defaultCategory"] == true)
					{
						$return .= $this->_l('<div class="ecwid_catalog_product_category">' . EcwidPlatform::esc_html($ecwid_category['name']) . '</div>');
					}
				}
			}
			
			$return .= $this->_l('<div class="ecwid_catalog_product_price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">', 1);
			$return .=  $this->_l(EcwidPlatform::get_price_label() . ': <span itemprop="price">' . EcwidPlatform::esc_html($product["price"]) . '</span>');

			$return .= $this->_l('<span itemprop="priceCurrency">' . EcwidPlatform::esc_html($profile['currency']) . '</span>');
			if (!isset($product['quantity']) || (isset($product['quantity']) && $product['quantity'] > 0)) {
				$return .= $this->_l('<link itemprop="availability" href="http://schema.org/InStock" />In stock');
			}
			$return .= $this->_l('</div>', -1);

			$return .= $this->_l('<div class="ecwid_catalog_product_description" itemprop="description">', 1);
			$return .= $this->_l($product['description']);
			$return .= $this->_l('</div>', -1);

			if (is_array($product['attributes']) && !empty($product['attributes'])) {

				foreach ($product['attributes'] as $attribute) {
					if (trim($attribute['value']) != '') {
						$return .= $this->_l('<div class="ecwid_catalog_product_attribute">', 1);

						$attr_string = EcwidPlatform::esc_html($attribute['name']) . ':';

						if (isset($attribute['internalName']) && $attribute['internalName'] == 'Brand') {
							$attr_string .= '<span itemprop="brand">' . EcwidPlatform::esc_html($attribute['value']) . '</span>';
						} else {
							$attr_string .= EcwidPlatform::esc_html($attribute['value']);
						}

						$return .= $this->_l($attr_string);
						$return .= $this->_l('</div>', -1);
					}
				}
			}

			if (is_array($product["options"]))
			{
				$allowed_types = array('TEXTFIELD', 'DATE', 'TEXTAREA', 'SELECT', 'RADIO', 'CHECKBOX');
				foreach($product["options"] as $product_options)
				{
					if (!in_array($product_options['type'], $allowed_types)) continue;

					$return .= $this->_l('<div class="ecwid_catalog_product_options">', 1);
					$return .=$this->_l('<span>' . EcwidPlatform::esc_html($product_options["name"]) . '</span>');

					if($product_options["type"] == "TEXTFIELD" || $product_options["type"] == "DATE")
					{
						$return .=$this->_l('<input type="text" size="40" name="'. EcwidPlatform::esc_attr($product_options["name"]) . '">');
					}
					   if($product_options["type"] == "TEXTAREA")
					{
						 $return .=$this->_l('<textarea name="' . EcwidPlatform::esc_attr($product_options["name"]) . '"></textarea>');
					}
					if ($product_options["type"] == "SELECT")
					{
						$return .= $this->_l('<select name="'. $product_options["name"].'">', 1);
						foreach ($product_options["choices"] as $options_param) 
						{ 
							$return .= $this->_l(
								sprintf(
									'<option value="%s">%s (%s)</option>',
									EcwidPlatform::esc_attr($options_param['text']),
									EcwidPlatform::esc_html($options_param['text']),
									EcwidPlatform::esc_html($options_param['priceModifier'])
								)
							);
						}
						$return .= $this->_l('</select>', -1);
					}
					if($product_options["type"] == "RADIO")
					{
						foreach ($product_options["choices"] as $options_param) 
						{
							$return .= $this->_l(
								sprintf(
									'<input type="radio" name="%s" value="%s" />%s (%s)',
									EcwidPlatform::esc_attr($product_options['name']),
									EcwidPlatform::esc_attr($options_param['text']),
									EcwidPlatform::esc_html($options_param['text']),
									EcwidPlatform::esc_html($options_param['priceModifier'])
								)
							);
						}
					}
					if($product_options["type"] == "CHECKBOX")
					{
						foreach ($product_options["choices"] as $options_param)
						{
							$return .= $this->_l(
								sprintf(
									'<input type="checkbox" name="%s" value="%s" />%s (%s)',
									EcwidPlatform::esc_attr($product_options['name']),
									EcwidPlatform::esc_attr($options_param['text']),
									EcwidPlatform::esc_html($options_param['text']),
									EcwidPlatform::esc_html($options_param['priceModifier'])
								)
							);
						 }
					}

					$return .= $this->_l('</div>', -1);
				}
			}				
						
			if (is_array($product["galleryImages"])) 
			{
				foreach ($product["galleryImages"] as $galleryimage) 
				{
					if (empty($galleryimage["alt"]))  $galleryimage["alt"] = htmlspecialchars($product["name"]);
					$return .= $this->_l(
						sprintf(
							'<img src="%s" alt="%s" title="%s" />',
							EcwidPlatform::esc_attr($galleryimage['url']),
							EcwidPlatform::esc_attr($galleryimage['alt']),
							EcwidPlatform::esc_attr($galleryimage['alt'])
						)
					);
				}
			}

			$return .= $this->_l("</div>", -1);
		}

		return $return;
	}

	public function get_category($id)
	{
		$params = array
		(
			array("alias" => "c", "action" => "categories", "params" => array("parent" => $id)),
			array("alias" => "p", "action" => "products", "params" => array("category" => $id)),
			array("alias" => "pf", "action" => "profile")
		);
		if ($id > 0) {
			$params[] = array('alias' => 'category', "action" => "category", "params" => array("id" => $id));
		}

		$batch_result = $this->ecwid_api->get_batch_request($params);

		$category	 = $id > 0 ? $batch_result['category'] : null;
		$categories = $batch_result["c"];
		$products   = $batch_result["p"];
		$profile	= $batch_result["pf"];

		$return = $this->_l('');

		if (!is_null($category)) {
			$return .= $this->_l('<h1>' . EcwidPlatform::esc_html($category['name']) . '</h1>');
			$return .= $this->_l('<div>' . $category['description'] . '</div>');
		}

		if (is_array($categories)) 
		{
			foreach ($categories as $category) 
			{
				$category_url = Ecwid_Store_Page::get_category_url( $category['id'] );

				$category_name = $category["name"];
				$return .= $this->_l('<div class="ecwid_catalog_category_name">', 1);
				$return .= $this->_l('<a href="' . EcwidPlatform::esc_attr($category_url) . '">' . EcwidPlatform::esc_html($category_name) . '</a>');
				$return .= $this->_l('</div>', -1);
			}
		}

		if (is_array($products)) 
		{
			foreach ($products as $product) 
			{

				$product_url = Ecwid_Store_Page::get_product_url( $product['id'] );

				$product_name = $product['name'];
				$product_price = $product['price'] . ' ' . $profile['currency'];
				$return .= $this->_l('<div>', 1);
				$return .= $this->_l('<span class="ecwid_product_name">', 1);
				$return .= $this->_l('<a href="' . EcwidPlatform::esc_attr($product_url) . '">' . EcwidPlatform::esc_html($product_name) . '</a>');
				$return .= $this->_l('</span>', -1);
				$return .= $this->_l('<span class="ecwid_product_price">' . EcwidPlatform::esc_html($product_price) . '</span>');
				$return .= $this->_l('</div>', -1);
			}
		}

		return $return;
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

	public function get_category_name($id)
	{
		$category = $this->ecwid_api->get_category($id);

		$result = '';
		if (is_array($category) && isset($category['name'])) { 
			$result = $category['name'];
		}

		return $result;
	}

	public function get_product_name($id)
	{
		$product = $this->ecwid_api->get_product($id);
				
		$result = '';
		if (is_array($product) && isset($product['name'])) {
			$result = $product['name'];
		}

		return $result;
	}


	public function get_category_description($id)
	{
			$category = $this->ecwid_api->get_category($id);

			$result = '';
			if (is_array($category) && isset($category['description'])) {
					$result = $category['description'];
			}

			return $result;
	}

	public function get_product_description($id)
	{
			$product = $this->ecwid_api->get_product($id);

			$result = '';
			if (is_array($product) && isset($product['description'])) {
					$result = $product['description'];
			}

			return $result;
	}

	public function get_product_url($product)
	{
		if (is_numeric($product) && $this->ecwid_api->is_api_enabled()) {
			$product = $this->ecwid_api->get_product($product);
		}

		return $this->get_entity_url($product, 'p');
	}

	public function get_category_url($category)
	{
		if (is_numeric($category) && $this->ecwid_api->is_api_enabled()) {
			$category = $this->ecwid_api->get_category($category);
		}

		return $this->get_entity_url($category, 'c');
	}

	protected function get_entity_url($entity, $type) {

		$link = $this->store_base_url;

		if (is_numeric($entity)) {
			return $link . '#!/' . $type . '/' . $entity;
		} elseif (is_array($entity) && isset($entity['url'])) {
			$link .= substr($entity['url'], strpos($entity['url'], '#'));
		}

		return $link;

	}

	/*
	 * A helper function to produce indented html output. 
	 * Indent change need to be 1 for opening tag lines and -1 for closing tag lines. 
	 * Regular lines should omit the second parameter.
	 * Example:
	 * _l('<parent-tag>', 1);
	 * _l('<content-tag>content</content-tag>');
	 * _l('</parent-tag>', -1)
	 * 
	 */
	protected function _l($code, $indent_change = 0)
	{
		static $indent = 0;

		if ($indent_change < 0) $indent -= 1;
		$str = str_repeat('    ', $indent) . $code . "\n";
		if ($indent_change > 0) $indent += 1;

		return $str;
	}
}
