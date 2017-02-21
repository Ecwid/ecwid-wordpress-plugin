<?php
class Ecwid_Widget_Vertical_Categories_List extends WP_Widget {

	/**
	 * Sets up a new Meta widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array('classname' => 'widget_ecwid_vcategories_list', 'description' => __( 'Adds root categories list to the sidebar to let your customers navigate the store.', 'ecwid-shopping-cart' ) );
		parent::__construct( 'ecwidvcategorieslist', __('Store Root Categories', 'ecwid-shopping-cart' ), $widget_ops);
	}

	public function widget( $args, $instance ) {

		$categories = ecwid_get_categories();

		if (empty($categories)) return;

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', empty($instance['title']) ? __( 'Browse by Category', 'ecwid-shopping-cart' ) : $instance['title'], $instance, $this->id_base );

		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		echo '<ul>';

		foreach ($categories as $category) {
			echo '<li>';
			echo '<a href="' . Ecwid_Store_Page::get_category_url( $category->id ) . '">' . $category->name . '</a>';
			echo '</li>';
		}

		echo '</ul>';
		echo $args['after_widget'];
	}

	/**
	 * Handles updating settings for the current Meta widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = sanitize_text_field( $new_instance['title'] );

		return $instance;
	}

	/**
	 * Outputs the settings form for the Meta widget.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => __( 'Browse by Category', 'ecwid-shopping-cart' ) ) );
		$title = sanitize_text_field( $instance['title'] );
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<div class="ecwid-reset-categories-cache-block"></div>
		<?php
	}
}
