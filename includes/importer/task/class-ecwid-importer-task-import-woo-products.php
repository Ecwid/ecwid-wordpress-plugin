<?php 

class Ecwid_Importer_Task_Import_Woo_Products extends Ecwid_Importer_Task {

	public static $type = 'import-woo-products';

	const BATCH_SIZE = 100;

	public function execute( Ecwid_Importer $importer, array $data ) {
		
		$count = wp_count_posts( self::WC_POST_TYPE_PRODUCT )->publish;
		
		$ind = 0;
		
		while ( $ind * self::BATCH_SIZE < $count ) {
			$importer->append_task(
				Ecwid_Importer_Task_Import_Woo_Products_Batch::build( 
					array(
						'start' => $ind * self::BATCH_SIZE,
						'length' => self::BATCH_SIZE 
					)
				)
			);
			$ind++;
		}
	}
}