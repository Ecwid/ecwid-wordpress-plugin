<?php



if (version_compare( phpversion(), '5.6', '>=' ) ) {
	$oauth = new Ecwid_OAuth();
	
	if ( $oauth->has_scope( Ecwid_OAuth::SCOPE_READ_CATALOG ) ) {
		require __DIR__ . '/class-ecwid-import.php';
		new Ecwid_Import();
	}
	
	spl_autoload_register(
		function( $classname ) {
			if ( strpos( $classname, 'Ecwid_Importer_Task') === 0 ) {
				
				$task = substr( $classname, 20 );
				$task = str_replace( '_', '-', $task );
				$task = strtolower( $task );
				require_once ECWID_PLUGIN_DIR . '/includes/importer/task/class-ecwid-importer-task-' . $task . '.php';
			}
			
		}	
	);
}