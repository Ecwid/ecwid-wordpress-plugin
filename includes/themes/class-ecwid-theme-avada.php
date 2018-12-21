<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_Avada extends Ecwid_Theme_Base
{
	protected $name = 'Avada';

	public function __construct()
	{
		parent::__construct();

		add_filter( Ecwid_Nav_Menus::FILTER_USE_JS_API_FOR_CATS_MENU, array( $this, 'filter_use_js_api_for_cats_menu' ) );
		add_filter( 'ecwid_shortcode_content', array( $this, 'shortcode_content' ) );
	}
	
	public function shortcode_content( $content )
	{
		$content .= <<<HTML
<script type="text/javascript">
Ecwid.OnPageLoaded.add( function() {
    if (typeof niceScrollReInit == 'function') {
		niceScrollReInit();
	}
}
);</script>
HTML;
		return $content;
	}
}

return new Ecwid_Theme_Avada();