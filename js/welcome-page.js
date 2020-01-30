jQuery(document).ready(function(){

	jQuery('.create-store-button').click(function() {
		var hide_on_loading = '.create-store-button, .create-store-note',
			show_on_loading = '.create-store-loading, .create-store-loading-note',
			show_on_success = '.create-store-success, .create-store-success-note';


	    if (ecwidParams.isWL) {
	        location.href = ecwidParams.registerLink;
	        return;
        }

        jQuery(hide_on_loading).hide();
        jQuery(show_on_loading).show();

		jQuery.ajax(ajaxurl + '?action=ecwid_create_store',
			{
				success: function(result) {
					jQuery(show_on_loading).hide();
        			jQuery(show_on_success).show();
					
					setTimeout(function() {
						location.href="admin.php?page=ec-store";
					}, 1000);
				},
				error: function() {
					window.location.href = ecwidParams.registerLink;
				}
			}
		);
	});

});