jQuery(document).ready(function() {
	jQuery('#ecwid-sync-products').click(function() {
		jQuery('#ecwid-sync-products-success, #ecwid-sync-products-error').hide();
		jQuery('#ecwid-sync-products-inprogress').show();
		jQuery.get('admin-ajax.php?action=ecwid_sync_products', {}, function($msg) {
			jQuery('#ecwid-sync-products-inprogress').hide();
			if ($msg == 'OK') {
				jQuery('#ecwid-sync-products-success').show();
			} else {
				jQuery('#ecwid-sync-products-error').show();
			}
		});
	});

});