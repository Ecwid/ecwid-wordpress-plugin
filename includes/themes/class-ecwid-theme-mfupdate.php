<?php

require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_MFUpdate extends Ecwid_Theme_Base
{
	protected $name = 'MFUpdate';

	public function __construct()
	{
		parent::__construct();

		add_filter( 'ecwid_shortcode_content', array( $this, 'ecwid_shortcode_content' ) );
	}

	public function ecwid_shortcode_content($content) {

		$content .= <<<HTML
<script type="text/javascript">
Ecwid.OnPageLoaded.add( function() {
	if (jQuery('#container').data() && jQuery('#container').data().isotope) {
		jQuery('#container').data().isotope.reLayout();
	}
}
);</script>
HTML;

		return $content;
	}
}

return new Ecwid_Theme_MFUpdate();