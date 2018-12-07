<?php 

class Ecwid_Importer_Task_Import_Woo_Categories extends Ecwid_Importer_Task {

	public static $type = 'import-woo-categories';

	public function execute( Ecwid_Importer $importer, array $data ) {
		
		if ( !ecwid_is_paid_account() ) {
			return $this->_result_nothing();
		}
		$categories = $importer->gather_categories();

		foreach ( @$categories as $category ) {
			$tasks[] = Ecwid_Importer_Task_Create_Category::build( $category );
			if ( $category['has_image'] ) {
				$tasks[] = Ecwid_Importer_Task_Upload_Category_Image::build( $category );
			}
		}

		return $this->_result_success();
	}
}