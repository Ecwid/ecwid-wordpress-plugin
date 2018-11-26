<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_Boundless extends Ecwid_Theme_Base
{
	protected $name = 'Boundless';

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

		$meta = get_post_meta( get_the_ID(), '_witty_builder_data' );

		if ( is_array( $meta ) ) {
			$meta = serialize( $meta );

			// not exactly the intended usage, but quite simple and still works
			// $meta is a serialized array that has the actual content
			// a right way is to walk through the structure and run has_shortcode against all fields
			$result = ecwid_content_has_productbrowser($meta);
		}

		return $result;
	}
}

return new Ecwid_Theme_Boundless();