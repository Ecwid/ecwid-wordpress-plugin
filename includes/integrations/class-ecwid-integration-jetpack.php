<?php

class Ecwid_Integration_Jetpack {
	public $post_types = array( 'jetpack-portfolio', 'jetpack-testimonial' );

	public function __construct() {
		add_filter( 'ecwid_seo_allowed_post_types', array( $this, 'add_post_types' ), 10, 1 );
	}

	public function add_post_types( $types ) {
		$types = array_merge( $types, $this->post_types );

		return $types;
	}
}

new Ecwid_Integration_Jetpack();
