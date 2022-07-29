<?php

require_once ECWID_PLUGIN_DIR . '/includes/widgets/class-ecwid-widget-base.php';

class Ecwid_Widget_Badge extends Ecwid_Widget_Base {

	public $url_template = 'https://dj925myfyz5v.cloudfront.net/badges/%s.png';
	public $available_badges;

	public function __construct() {
		$this->_hide_title = true;

		$widget_ops = array(
			'classname'   => 'widget_ecwid_badge',
			'description' => __( "Do you like Ecwid and want to help it grow? You can add this fancy 'Powered by Ecwid' badge on your site to show your visitors that you're a proud user of Ecwid.", 'ecwid-shopping-cart' ),
		);
		parent::__construct( 'ecwidbadge', __( 'Ecwid Badge', 'ecwid-shopping-cart' ), $widget_ops );

		$this->available_badges = array(
			'ecwid-shopping-cart-widget-5' => array(
				'name'   => 'ecwid-shopping-cart-widget-5',
				'width'  => '73',
				'height' => '20',
				'alt'    => __( 'Ecwid shopping cart widget', 'ecwid-shopping-cart' ),
			),
			'ecwid-shopping-cart-widget-6' => array(
				'name'   => 'ecwid-shopping-cart-widget-6',
				'width'  => '73',
				'height' => '20',
				'alt'    => __( 'Ecwid shopping cart widget', 'ecwid-shopping-cart' ),
			),
			'ecwid-ecommerce-solution-2'   => array(
				'name'   => 'ecwid-ecommerce-solution-2',
				'width'  => '165',
				'height' => '58',
				'alt'    => __( 'Ecwid ecommerce solution', 'ecwid-shopping-cart' ),
			),
			'ecwid-free-shopping-cart-2'   => array(
				'name'   => 'ecwid-free-shopping-cart-2',
				'width'  => '175',
				'height' => '58',
				'alt'    => __( 'Ecwid free shopping cart', 'ecwid-shopping-cart' ),
			),
			'ecwid-shopping-cart-3'        => array(
				'name'   => 'ecwid-shopping-cart-3',
				'width'  => '165',
				'height' => '56',
				'alt'    => __( 'Ecwid shopping cart', 'ecwid-shopping-cart' ),
			),
			'ecwid-ecommerce-widgets-3'    => array(
				'name'   => 'ecwid-ecommerce-widgets-3',
				'width'  => '165',
				'height' => '58',
				'alt'    => __( 'Ecwid e-commerce widgets', 'ecwid-shopping-cart' ),
			),
			'ecwid-shopping-cart-3'        => array(
				'name'   => 'ecwid-shopping-cart-3',
				'width'  => '165',
				'height' => '56',
				'alt'    => __( 'Ecwid shopping cart', 'ecwid-shopping-cart' ),
			),
			'ecwid-ecommerce-widgets-3'    => array(
				'name'   => 'ecwid-ecommerce-widgets-3',
				'width'  => '165',
				'height' => '58',
				'alt'    => __( 'Ecwid e-commerce widgets', 'ecwid-shopping-cart' ),
			),
			'ecwid-ecommerce-solution-3'   => array(
				'name'   => 'ecwid-ecommerce-solution-3',
				'width'  => '165',
				'height' => '58',
				'alt'    => __( 'Ecwid ecommerce solution', 'ecwid-shopping-cart' ),
			),
			'ecwid-free-shopping-cart-3'   => array(
				'name'   => 'ecwid-free-shopping-cart-3',
				'width'  => '175',
				'height' => '58',
				'alt'    => __( 'Ecwid free shopping cart', 'ecwid-shopping-cart' ),
			),
		);
	}

	protected function _render_widget_content( $args, $instance ) { //phpcs:ignore PSR2.Methods.MethodDeclaration.Underscore

		if ( ! isset( $instance['badge_id'] ) || ! array_key_exists( $instance['badge_id'], $this->available_badges ) ) {
			return;
		}

		$badge = $this->available_badges[ $instance['badge_id'] ];
		$url   = sprintf( $this->url_template, $badge['name'] );

		if ( ! isset( $instance['badge_id'] ) || ! array_key_exists( $instance['badge_id'], $this->available_badges ) ) {
			return;
		}

		ob_start();
		?>
		<div>
			<a target="_blank" rel="nofollow" href="http://www.ecwid.com">
				<img src="<?php echo esc_url( $url ); ?>" width="<?php echo esc_attr( $badge['width'] ); ?>" height="<?php echo esc_attr( $badge['height'] ); ?>" alt="<?php echo esc_attr( $badge['alt'] ); ?>" />
			</a>
		</div>
		<?php

		return ob_get_clean();
	}

	public function update( $new_instance, $old_instance ) {
		$instance             = $old_instance;
		$instance['badge_id'] =
			array_key_exists( $new_instance['badge_id'], $this->available_badges )
				? $new_instance['badge_id']
				: '';

		return $instance;
	}

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'badge_id' => 'ecwid-shopping-cart-widget-5' ) );

		foreach ( $this->available_badges as $id => $widget ) {
			$element_id = "badge-$id";
			$name       = $this->get_field_name( 'badge_id' );
			$checked    = '';
			if ( isset( $instance['badge_id'] ) && $instance['badge_id'] == $id ) { //phpcs:ignore Universal.Operators.StrictComparisons.LooseEqual
				$checked = 'checked="checked"';
			}
			$url = sprintf( $this->url_template, $id );
			?>
			<label class="ecwid-badge">
				<div class="checkbox">
					<input name="<?php echo esc_attr( $name ); ?>" type="radio" value="<?php echo esc_attr( $widget['name'] ); ?>" <?php echo esc_attr( $checked ); ?> />
				</div>
				<div class="image">
					<img src="<?php echo esc_url( $url ); ?>" width="<?php echo esc_attr( $widget['width'] ); ?>" height="<?php echo esc_attr( $widget['height'] ); ?>" alt="<?php echo esc_attr( $widget['alt'] ); ?>" />
				</div>
			</label>
			<?php
		}
	}
}
