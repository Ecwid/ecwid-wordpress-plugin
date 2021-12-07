<?php

abstract class Ecwid_Importer_Task_Product_Base extends Ecwid_Importer_Task {
	protected $_woo_product_id;
	protected $_ecwid_product_id;
	
	public function get_woo_id() {
		return $this->_woo_product_id;
	}
	
	public function get_ecwid_id() {
		return $this->_ecwid_product_id;
	}
	
	public function get_product_name() {
		if ( !$this->get_woo_id() ) return null;
		
		$product = wc_get_product( $this->get_woo_id() );
		
		return $product->get_title();
	}
		
	public function get_woo_link() {
		if ( !$this->get_woo_id() ) return null;
		
		return admin_url( 'post.php?post=' . $this->get_woo_id() . '&action=edit' );
	}
	
	public function get_ecwid_link() {
		if ( !$this->get_ecwid_id() ) return null;

		$url = 'admin.php?page=' . Ecwid_Admin::ADMIN_SLUG . '&ec-store-page=';
		
		$url .= rawurlencode( 'product:mode=edit&id=' . $this->get_ecwid_id() );
		
		return admin_url( $url );
	}
}