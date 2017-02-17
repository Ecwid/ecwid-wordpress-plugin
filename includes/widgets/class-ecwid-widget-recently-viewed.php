<?php
class Ecwid_Widget_Recently_Viewed extends WP_Widget {

	var $max = 10;
	var $min = 1;
	var $default = 3;

	function __construct() {
		$widget_ops = array('classname' => 'widget_ecwid_recently_viewed', 'description' => __('Displays a list of products recently viewed by the customer to easily return to the products they saw in your shop.', 'ecwid-shopping-cart'));
		parent::__construct('ecwidrecentlyviewed', __('Recently Viewed Products', 'ecwid-shopping-cart'), $widget_ops);
		$recently_viewed = false;
		if (isset($_COOKIE['ecwid-shopping-cart-recently-viewed'])) {
			$recently_viewed = json_decode(stripslashes($_COOKIE['ecwid-shopping-cart-recently-viewed']));
		}

		if ($recently_viewed && $recently_viewed->store_id != get_ecwid_store_id() && !is_admin()) {
			setcookie('ecwid-shopping-cart-recently-viewed', null, strtotime('-1 day'));
		}

		add_action( 'wp_enqueue_scripts', array($this, 'enqueue' ) );
	}

	function enqueue() {
		if ( is_active_widget( false, false, $this->id_base ) ) {
			wp_enqueue_script('ecwid-recently-viewed-js', ECWID_PLUGIN_URL . 'js/recently-viewed.js', array('ecwid-products-list-js', 'utils'), get_option('ecwid_plugin_version'));
			wp_enqueue_style('ecwid-products-list-css');
			wp_enqueue_style('ecwid-recently-viewed-css', ECWID_PLUGIN_URL . 'css/recently-viewed.css', array(), get_option('ecwid_plugin_version'));
		}
	}

	function widget($args, $instance) {

		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);

		echo $before_widget;

		if ( $title )
			echo $before_title . $title . $after_title;

		echo '<!-- noptimize -->' . ecwid_get_scriptjs_code() . '<!-- /noptimize -->';

		$recently_viewed = false;
		if (isset($_COOKIE['ecwid-shopping-cart-recently-viewed'])) {
			$recently_viewed = json_decode($_COOKIE['ecwid-shopping-cart-recently-viewed']);
		}
		$recently_viewed = json_decode(stripslashes(@$_COOKIE['ecwid-shopping-cart-recently-viewed']));

		if ($recently_viewed && $recently_viewed->store_id != get_ecwid_store_id()) {
			$recently_viewed = null;
		}

		echo '<div class="ecwid-recently-viewed-products" data-ecwid-max="' . $instance['number_of_products'] . '">';


		$api = false;
		if (ecwid_is_api_enabled()) {
			$api = ecwid_new_product_api();
		}

		$counter = 0;
		$ids = array();
		if ($recently_viewed && isset($recently_viewed->products)) {

			for ($i = count($recently_viewed->products) - 1; $i >= 0; $i--) {
				$product = $recently_viewed->products[$i];

				$counter++;
				if (isset($product->id) && isset($product->link)) {
					$ids[] = $product->id;
					$hide = $counter > $instance['number_of_products'] ? ' hidden' : '';

					$force_image = '';
					if ($api) {
						$product_https = $api->get_product_https($product->id);
						if ( isset( $product_https['imageUrl'] ) ) {
							$force_image = $product_https['imageUrl'];
						}
					}

					$name = isset($product_https) ? $product_https['name']: '';

					echo <<<HTML
	<a class="product$hide" href="$product->link" alt="$name" title="$name">
		<div class="ecwid ecwid-SingleProduct ecwid-Product ecwid-Product-$product->id" data-single-product-link="$product->link" itemscope itemtype="http://schema.org/Product" data-single-product-id="$product->id">
			<div itemprop="image" data-force-image="$force_image"></div>
			<div class="ecwid-title" itemprop="name"></div>
			<div itemtype="http://schema.org/Offer" itemscope itemprop="offers"><div class="ecwid-productBrowser-price ecwid-price" itemprop="price"></div></div>
		</div>

		<!-- noptimize --><script type="text/javascript">xSingleProduct();</script><!-- /noptimize -->
	</a>
HTML;
				}
			}
		} else {
			echo <<<HTML
<script type="text/javascript">
jQuery(document).ready(function() {
  wpCookies.remove('ecwid-shopping-cart-recently-viewed');
  recently_viewed = {products: []};
});
</script>
HTML;
		}
		$ids_string = '';
		if (!empty($ids)) {
			$ids_string = implode(',', $ids);
		}

		echo <<<HTML
<script type="text/javascript">
<!--
jQuery(document).ready(function() {
	jQuery('#$this->id .ecwid-recently-viewed-products').recentlyViewedProducts();
});
-->
</script>
HTML;

		echo "</div>";

		$store_link_message = empty($instance['store_link_title']) ? __('You have not viewed any product yet. Open store.', 'ecwid-shopping-cart') : $instance['store_link_title'];

		$page_id = Ecwid_Store_Page::get_current_store_page_id();
		$post = get_post($page_id);

		if (empty($recently_viewed->products)) {
			echo '<a class="show-if-empty" href="' . Ecwid_Store_Page::get_store_url() . '">' . $store_link_message . '</a>';
		}

		echo $after_widget;
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags(stripslashes($new_instance['title']));
		$instance['store_link_title'] = strip_tags(stripslashes($new_instance['store_link_title']));
		$num = intval($new_instance['number_of_products']);
		if ($num > $this->max || $num < $this->min) {
			$num = $this->default;
		}
		$instance['number_of_products'] = intval($new_instance['number_of_products']);

		return $instance;
	}

	function form($instance){

		$instance = wp_parse_args( (array) $instance,
			array(
				'title' => __('Recently Viewed Products', 'ecwid-shopping-cart'),
				'store_link_title' => __('You have not viewed any product yet. Open store.', 'ecwid-shopping-cart'),
				'number_of_products' => 3
			)
		);

		$title = htmlspecialchars($instance['title']);
		$store_link_title = htmlspecialchars($instance['store_link_title']);
		$number_of_products = $instance['number_of_products'];
		if ($number_of_products)

			echo '<p><label for="' . $this->get_field_name('title') . '">' . __('Title') . ': <input style="width:100%;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';
		echo '<p><label for="' . $this->get_field_name('store_link_title') . '">' . __('Store Link Title', 'ecwid-shopping-cart') . ': <input style="width:100%;" id="' . $this->get_field_id('store_link_title') . '" name="' . $this->get_field_name('store_link_title') . '" type="text" value="' . $store_link_title . '" /></label></p>';
		echo '<p><label for="' . $this->get_field_name('number_of_products') . '">' . __( 'Number of products to show', 'ecwid-shopping-cart' ) . ': <input style="width:100%;" id="' . $this->get_field_id('number_of_products') . '" name="' . $this->get_field_name('number_of_products') . '" type="number" min="' . $this->min . '" max="' . $this->max . '" value="' . $number_of_products . '" /></label></p>';
	}

	function is_valid_number_of_products($num) {
		return is_numeric($num) && $num <= $this->max && $num >= $this->min;
	}
}
