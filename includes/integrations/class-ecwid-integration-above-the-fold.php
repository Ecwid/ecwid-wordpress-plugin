<?php

class Ecwid_Integration_Above_The_Fold
{
	public function __construct()
	{
		add_filter( 'abtf_jsfile_pre', array( $this, 'filter_optimized_js' ) );
	}

	public function filter_optimized_js($file) 
	{
		if ( strpos( $file, 'ecwid.com' ) !== false ) {
			return '';
		}
		
		return $file;
	}
}

$ecwid_integration_abtv = new Ecwid_Integration_Above_The_Fold();