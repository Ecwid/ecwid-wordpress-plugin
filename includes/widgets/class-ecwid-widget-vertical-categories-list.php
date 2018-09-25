<?php

require_once ECWID_PLUGIN_DIR . '/includes/widgets/class-ecwid-widget-base.php';

class Ecwid_Widget_Vertical_Categories_List extends Ecwid_Widget_Base {

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

	public function _render_widget_content( $args, $instance ) {

	    $api = new Ecwid_Api_V3();
	    
		$result = $api->get_categories(array( 'parent' => 0 ) );

		if ( !$result || empty( $result->items ) ) return "";

		$categories = $result->items;
		usort( $categories, Ecwid_Category::usort_callback() );
        
		$html = '<ul>';
		
		foreach ($categories as $category) {
		    $category = Ecwid_Category::get_by_id( $category->id );
			$html .= '<li>';
			$html .= '<a href="' . $category->link 
                . '" data-ecwid-page="category" data-ecwid-category-id="' . $category->id . '">' 
                . $category->name 
                . '</a>';
			$html .= '</li>';
		}

		$html .= '</ul>';
		
		return $html;
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
