<?php

class Ecwid_Widget_Products_List_Base extends WP_Widget {

	protected $_max = 10;
	protected $_min = 1;
	protected $_default = 3;
	
	protected $_title;
	protected $_widget_name;
	protected $_class_name;
	protected $_description;
	
	public function __construct() {
		
		$id_base = str_replace('_', '', $this->_widget_name);
		$classname = 'widget_' . $this->_widget_name;

		$widget_ops = array('classname' => $classname, 'description' => $this->_description);
		
		parent::__construct($id_base, $this->_title, $widget_ops);

		add_action( 'wp_enqueue_scripts', array($this, 'enqueue' ) );
	}
	
	protected function _init( $title, $description, $widget_name = null, $class_name = null )
	{
		$this->_title = $title;
		$this->_description = $description;
		if ( is_null ( $widget_name ) ) {
			$widget_name = strtolower( $widget_name );
			$widget_name = preg_replace('![^a-z0-9_\s-]!', '', $widget_name);
			$widget_name = preg_replace('![\s-]+!', '_', $widget_name);
		}
		
		$this->_widget_name = $widget_name;
		
		if (is_null($class_name)) {
			$class_name = str_replace('_', '', $this->_widget_name);
		}
		$this->_class_name = $class_name;
	}

	public function _enqueue() {
		if ( is_active_widget( false, false, $this->id_base ) ) {
			wp_enqueue_style('ecwid-products-list-css');
		}
	}

	function widget($args, $instance) {

		$this->_args = $args;
		$this->_instance = $instance;
		
		// for the sake of phpstorm sanity about undefined vars
		$before_widget = $before_title = $after_title = $after_widget = '';
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? '&nbsp;' : $instance['title']);
		
		echo $before_widget;

		if ( $title ) {
			echo $before_title . $title . $after_title;
		}
		
		echo '<!-- noptimize -->' . ecwid_get_scriptjs_code() . '<!-- /noptimize -->';

		echo '<div class="' . $this->_class_name . '" data-ecwid-max="' . $instance['number_of_products'] . '">';

		$counter = 1;
		$ids = array();
		
		$this->print_widget_content($instance);
		echo '</div>';
		
		echo $after_widget;
	}
	
	protected function print_widget_content()
	{
		$products = $this->_get_products();
		
		if ($products) {
			$this->_print_products($products);
		} else {
			$this->_print_no_products();
		}
	}
	
	protected function _print_no_products()
	{
		$store_link_message = empty($instance['store_link_title']) ? __('You have not viewed any product yet. Open store.', 'ecwid-shopping-cart') : $instance['store_link_title'];
		echo '<a class="show-if-empty" href="' . Ecwid_Store_Page::get_store_url() . '">' . $store_link_message . '</a>';
	}
	
	protected function _print_products($products) 
	{
		$next = 1;
		foreach ($products as $product) {
			
			$product = Ecwid_Product::get_by_id($product->id);
			
			$force_image = '';
			if ( isset( $product->imageUrl ) && strpos( $product->imageUrl, 'https://' ) == 0 ) {
				$force_image = $product->imageUrl;
			}

			$name = esc_html($product->name);

			echo <<<HTML
		<a class="product" href="$product->link" alt="$name" title="$name">
			<div class="ecwid ecwid-SingleProduct ecwid-Product ecwid-Product-$product->id" data-single-product-link="$product->link" itemscope itemtype="http://schema.org/Product" data-single-product-id="$product->id">
				<div itemprop="image" data-force-image="$force_image"></div>
				<div class="ecwid-title" itemprop="name"></div>
				<div itemtype="http://schema.org/Offer" itemscope itemprop="offers"><div class="ecwid-productBrowser-price ecwid-price" itemprop="price"></div></div>
			</div>
			<!-- noptimize --><script type="text/javascript">xSingleProduct();</script><!-- /noptimize -->
		</a>
HTML;
			$next++;
		}
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
		if ($number_of_products) {
			echo '<p><label for="' . $this->get_field_name('title') . '">' . __('Title') . ': <input style="width:100%;" id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" type="text" value="' . $title . '" /></label></p>';
		}

		echo '<p><label for="' . $this->get_field_name('store_link_title') . '">' . __('Store Link Title', 'ecwid-shopping-cart') . ': <input style="width:100%;" id="' . $this->get_field_id('store_link_title') . '" name="' . $this->get_field_name('store_link_title') . '" type="text" value="' . $store_link_title . '" /></label></p>';
		echo '<p><label for="' . $this->get_field_name('number_of_products') . '">' . __( 'Number of products to show', 'ecwid-shopping-cart' ) . ': <input style="width:100%;" id="' . $this->get_field_id('number_of_products') . '" name="' . $this->get_field_name('number_of_products') . '" type="number" min="' . $this->min . '" max="' . $this->max . '" value="' . $number_of_products . '" /></label></p>';
	}

	function is_valid_number_of_products($num) {
		return is_numeric($num) && $num <= $this->max && $num >= $this->min;
	}
}
