<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Divi extends Ecwid_Theme_Base
{
	protected $name = 'Divi';

	public function __construct()
	{
		parent::__construct();

		add_filter( Ecwid_Nav_Menus::FILTER_USE_JS_API_FOR_CATS_MENU, array( $this, 'filter_use_js_api_for_cats_menu' ) );

		if( $this->is_wireframe_view() ) {
			add_filter( 'ecwid_scriptjs_code', '__return_false' );
		}
	}

	public function is_wireframe_view()
	{
		return isset( $_REQUEST['et_fb'] ) && isset( $_REQUEST['et_bfb'] );
	}

	public function is_visual_views()
	{
		// $_POST['is_fb_preview']
		// $_POST['shortcode']
		// $_GET['et_pb_preview']
		// $_GET['iframe_id']
		return isset( $_REQUEST['et_pb_preview'] );
	}
}

return new Ecwid_Divi();