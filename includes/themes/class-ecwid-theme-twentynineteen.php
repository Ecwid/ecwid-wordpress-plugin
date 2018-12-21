<?php
require_once ECWID_THEMES_DIR . '/class-ecwid-theme-base.php';

class Ecwid_Theme_2019 extends Ecwid_Theme_Base {

	protected $name = 'twentynineteen';
	
	public function __construct() {
		parent::__construct();
		
		add_filter( 'ecwid_shortcode_content', array( $this, 'filter_shortcode_content' ) ); 
	}
	
	public function filter_shortcode_content( $content ) {
		return '<div>' . $content . '</div>';
	}
}

return new Ecwid_Theme_2019();