<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_Envision extends Ecwid_Theme_Base
{
	protected $name = 'Envision';

	public function __construct()
	{
		parent::__construct();

		add_filter( 'ecwid_page_has_product_browser', array( $this, 'has_product_browser' ) );
	}

	public function has_product_browser( $value )
	{
		if ( $value ) {
			return $value;
		}

		$meta = serialize(get_post_meta( get_the_ID(), 'env_composer' ));
		//die(print_r(serialize($meta)));

		// not exactly the intended usage, but quite simple and still works
		// $meta is a serialized array that has the actual content
		// a right way is to walk through the structure and run has_shortcode against all fields
		$result = ecwid_content_has_productbrowser($meta);

		return $result;
	}
}

return new Ecwid_Theme_Envision();