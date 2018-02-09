<?php

if (version_compare( phpversion(), '5.3', '<' ) ) {
	
	add_action( 'admin_menu', 'ecwid_importer_admin_menu' );
	
	function ecwid_importer_admin_menu()
	{
		add_submenu_page(
			'',
			sprintf(__('Import', 'ecwid-shopping-cart')),
			sprintf(__('Import', 'ecwid-shopping-cart')),
			'manage_options',
			'ec-store-import',
			'ecwid_show_php_error',
			''
		);
	}

	function ecwid_show_php_error()
	{
		echo '<div>This feature requires php 5.3+. Please, consider upgrading you server software</div>';
	}
} else {
	require __DIR__ . '/class-ecwid-import.php';
	new Ecwid_Import();
}