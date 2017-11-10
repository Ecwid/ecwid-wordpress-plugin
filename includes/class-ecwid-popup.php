<?php

if ( !defined( 'ECWID_POPUP_TEMPLATES_DIR' ) ) {
	define ( 'ECWID_POPUP_TEMPLATES_DIR', ECWID_PLUGIN_DIR . 'templates/popup/' );
}
abstract class Ecwid_Popup
{
	public static $popups = array();
	
	protected $_class = '';
	
	public static function add_popup( $popup ) {
		
		if ( current_filter() != 'current_screen' ) {
			if (WP_DEBUG) {
				wp_die( 'Called add_popup not from admin_init action' );
			}
		}
		
		self::$popups[] = $popup;
		
		$popup->_init();
	}
	
	protected function _init()
	{
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'admin_footer', array( $this, 'render' ) );
	}
	
	public function enqueue_scripts()
	{
		wp_enqueue_script( 'ecwid-popup', ECWID_PLUGIN_URL . '/js/popup.js', array( 'jquery' ), get_option('ecwid_plugin_version') );
		wp_enqueue_style( 'ecwid-popup', ECWID_PLUGIN_URL . '/css/popup.css', array(), get_option('ecwid_plugin_version') );
	}
	
	public function render() {
		require( ECWID_POPUP_TEMPLATES_DIR . 'popup.php' );
	}
	
	protected function _render_header() {
		require( ECWID_POPUP_TEMPLATES_DIR . 'header.php' );			
	}
	
	protected function _render_footer() {
		require( ECWID_POPUP_TEMPLATES_DIR . 'footer.php' );
	}
	
	abstract protected function _get_footer_buttons();

	abstract protected function _get_header();

	abstract protected function _render_body();
}
