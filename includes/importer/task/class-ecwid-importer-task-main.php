<?php

class Ecwid_Importer_Task_Main extends Ecwid_Importer_Task {
	
	public static $type = 'main';
	
	public function execute( Ecwid_Importer $importer, array $data ) {

		if ( $importer->get_setting( Ecwid_Importer::SETTING_DELETE_DEMO ) && Ecwid_Importer::count_ecwid_demo_products() ) {
			$importer->append_task( 
				Ecwid_Importer_Task_Delete_Products::build( Ecwid_Importer::get_ecwid_demo_products() )
			);
		}
		
		$importer->append_task( Ecwid_Importer_Task_Import_Woo_Categories::build( array() ) );
		$importer->append_task( Ecwid_Importer_Task_Import_Woo_Products::build( array() ) );
	}
}