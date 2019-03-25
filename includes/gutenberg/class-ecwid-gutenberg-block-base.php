<?php

abstract class Ecwid_Gutenberg_Block_Base {
	
	protected $_name;
	protected $_block_prefix = 'ec-store';
	protected $_editor_script = 'ecwid-gutenberg-store';

	abstract public function render_callback( $params );

	public function __construct() {
	}
	
	public function register() {
		
		register_block_type( $this->get_block_name(), 
			array(
				'editor_script' => $this->_editor_script,
				'render_callback' => array( $this, 'render_callback' ),
			)
		);
	}	
	
	protected function _get_common_block_params()
	{
		return array(
			'blockName' => $this->get_block_name()
		);
	}
	
	public function get_block_name() {
		return $this->_block_prefix . '/' . $this->_name;
	}
	
	public function get_params() {
		return array();
	}
	
}