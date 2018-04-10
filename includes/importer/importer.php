<?php

if (version_compare( phpversion(), '5.3', '>' ) ) {
	$oauth = new Ecwid_OAuth();
	
	if ( $oauth->has_scope( Ecwid_OAuth::SCOPE_READ_CATALOG ) ) {
		require __DIR__ . '/class-ecwid-import.php';
		new Ecwid_Import();
	}
}