<?php

class Ecwid_Gutenberg_Block_Category_Page extends Ecwid_Gutenberg_Block_Store {
	protected $_name = 'category-page';

	public function get_block_name() {
		return Ecwid_Gutenberg_Block_Base::get_block_name();
	}
}