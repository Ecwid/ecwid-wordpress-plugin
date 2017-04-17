<?php
class Ecwid_Widget_Floating_Shopping_Cart extends WP_Widget {
	static $was_enqueued = false;

	static protected $positions = array(
		'topright',
		'bottomright'
	);

	const OPTION_DISPLAY_POSITION = 'ecwid_floating_shopping_cart_mode';

	static protected $default_position = 'bottomright';

	public function __construct() {
		$widget_ops = array('classname' => 'widget_ecwid_floating_shopping_cart', 'description' => __("Adds a shopping cart widget to the top right corner of your site.", 'ecwid-shopping-cart') );
		parent::__construct('ecwidfloatingshoppingcart', __('Shopping Cart (Floating)', 'ecwid-shopping-cart'), $widget_ops);

		add_action('init', array($this, 'init'));
	}

	public function init() {
		if ( is_active_widget(false, false, $this->id_base, true ) || get_option(self::OPTION_DISPLAY_POSITION) ) {
			add_filter( 'body_class', array($this, 'body_class' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_footer', array( $this, 'render' ) );
		}
	}

	public function enqueue_scripts() {
		if (self::$was_enqueued) return;

		wp_enqueue_script('ecwid-floating-shopping-cart', ECWID_PLUGIN_URL . '/js/floating-shopping-cart.js', array('jquery'), get_option('ecwid_plugin_version'), true);
		wp_enqueue_style('ecwid-floating-shopping-cart', ECWID_PLUGIN_URL . 'css/floating-shopping-cart.css', array(), get_option('ecwid_plugin_version'));
	}

	public function body_class($classes) {
		$classes[] = 'ecwid-floating-shopping-cart';

		return $classes;
	}

	public function widget($args, $instance) {
	}

	public function render() {

		$position = get_option(self::OPTION_DISPLAY_POSITION);

		if (!$position) {
			$options = get_option('widget_ecwidfloatingshoppingcart');

			if ( is_array( $options ) ) {
				foreach ( $options as $key => $option ) {
					if (is_array($option) && isset($option['position'])) {
						$position = $option['position'];
						break;
					}
				}
			}
		}

		if (!$position) {
			return;
		}
		$position = in_array($position, self::$positions) ? $position : self::$default_position;

		if ( Ecwid_Store_Page::is_store_page() ) {
			$cart_url = '#!/~/cart';
		} else {
			$cart_url = Ecwid_Store_Page::get_cart_url();
		}

		echo '<!-- noptimize -->';
		echo ecwid_get_scriptjs_code();
		echo
		<<<HTML
		 <div class="ecwid-float-icons position-$position" ondragstart="return false">
            <div class="ecwid-cart-icon off">
                <a href="$cart_url" data-count="0">
                    <svg width="20" height="26" viewBox="0 0 20 26" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><path d="M.5 6.5v14.81c0 2.255 1.79 4.084 4 4.084h11c2.21 0 4-1.83 4-4.085V6.5H.5zM6 10.585c.552 0 1-.457 1-1.02C7 9 6.552 8.542 6 8.542S5 9 5 9.563c0 .565.448 1.022 1 1.022zm8 0c.552 0 1-.457 1-1.02 0-.565-.448-1.022-1-1.022S13 9 13 9.563c0 .565.448 1.022 1 1.022z" stroke="#439CA0"/><path d="M14.5 6h-1V4.582c0-1.97-1.57-3.575-3.5-3.575S6.5 2.61 6.5 4.582V6h-1V4.582C5.5 2.048 7.52-.014 10-.014c2.482 0 4.5 2.062 4.5 4.596V6z" fill="#439CA0"/></g></svg>
                    <div id="ecwid-cart"><script type="text/javascript"> xMinicart("style="); </script></div>
                </a>
            </div>
    </div>
	<script type="text/javascript">
		if (window.EcwidFloatingShoppingCart) {
		    var ecwid_floating_shopping_cart = new EcwidFloatingShoppingCart();
		    ecwid_floating_shopping_cart.init();
		}
	</script>
HTML;
		echo '<!-- /noptimize -->';
	}

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['position'] = in_array( $new_instance['position'], self::$positions )
			? $new_instance['position']
			: self::$default_position;

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array('position' => self::$default_position ) );

		$position = $instance['position'];

		echo '<p>' . __('Position', 'ecwid-shopping-cart') . ':</p>';
		echo '<p><label><input type="radio" name="' . $this->get_field_name('position') . '" value="bottomright"'
			. ($position == 'bottomright' ? ' checked="checked"' : '') . '/>'
			. __('Bottom right', 'ecwid-shopping-cart')
			. '</label></p>';

		echo '<p><label><input type="radio" name="' . $this->get_field_name('position') . '" value="topright"'
			. ($position == 'topright' ? ' checked="checked"' : '') . '/>'
			. __('Top right', 'ecwid-shopping-cart')
			. '</label></p>';

	}

}
