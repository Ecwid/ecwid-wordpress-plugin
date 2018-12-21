<?php

class Ecwid_Integration_Divibuilder {
	public function __construct() {
		if ( is_admin() ) {
			add_action( 'admin_init', 'ecwid_create_divi_module' );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_style' ) );
		} else {
			add_action( 'wp', 'ecwid_create_divi_module' );
		}
	}

	public function enqueue_style() {
		wp_enqueue_style('ecwid-divi', ECWID_PLUGIN_URL . '/css/divibuilder.css' );
	}
}

new Ecwid_Integration_Divibuilder();

function ecwid_create_divi_module() {

	if ( class_exists( 'ET_Builder_Module' ) && ! class_exists( 'ET_Builder_Module_Ecwid' ) ) {
		class ET_Builder_Module_Ecwid extends ET_Builder_Module {
			function init() {
				$this->name            = sprintf( __( '%s Store', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() );
				$this->slug            = 'et_pb_ecwid';
				$this->use_row_content = TRUE;
				$this->decode_entities = TRUE;

				$this->whitelisted_fields = array(
					'raw_content',
					'admin_label',
					'module_id',
					'module_class',
				);
			}

			function get_fields() {
				$fields = array(
					'raw_content'  => array(
						'label'           => __( 'Content', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'basic_option',
						'description'     => __( 'Here you can create the content that will be used within the module.', 'et_builder' ),
						'default'         => '[' . Ecwid_Shortcode_Base::get_current_store_shortcode_name() .' widgets="productbrowser" default_category_id="0"]'

					),
					'admin_label'  => array(
						'label'       => __( 'Admin Label', 'et_builder' ),
						'type'        => 'text',
						'description' => __( 'This will change the label of the module in the builder for easy identification.', 'et_builder' ),
					),
					'module_id'    => array(
						'label'           => __( 'CSS ID', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'configuration',
						'description'     => __( 'Enter an optional CSS ID to be used for this module. An ID can be used to create custom CSS styling, or to create links to particular sections of your page.', 'et_builder' ),
					),
					'module_class' => array(
						'label'           => __( 'CSS Class', 'et_builder' ),
						'type'            => 'text',
						'option_category' => 'configuration',
						'description'     => __( 'Enter optional CSS classes to be used for this module. A CSS class can be used to create custom CSS styling. You can add multiple classes, separated with a space.', 'et_builder' ),
					),
				);

				return $fields;
			}

			function shortcode_callback( $atts, $content = NULL, $function_name ) {

				$module_id    = $this->shortcode_atts['module_id'];
				$module_class = $this->shortcode_atts['module_class'];

				$module_class = ET_Builder_Element::add_module_order_class( $module_class, $function_name );

				$this->shortcode_content = et_builder_replace_code_content_entities( $this->shortcode_content );

				$output = sprintf(
					'<div%2$s class="et_pb_ecwid et_pb_module%3$s">
					%1$s
				</div> <!-- .et_pb_ecwid -->',
					$this->shortcode_content,
					( '' !== $module_id ? sprintf( ' id="%1$s"', esc_attr( $module_id ) ) : '' ),
					( '' !== $module_class ? sprintf( ' %1$s', esc_attr( $module_class ) ) : '' )
				);

				return $output;
			}
		}

		new ET_Builder_Module_Ecwid;

	}
}