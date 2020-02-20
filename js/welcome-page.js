jQuery(document).ready(function(){

	jQuery('.ec-create-store-button').click(function() {
		
	    if (ecwidParams.isWL) {
	        location.href = ecwidParams.registerLink;
	        return;
        }

        jQuery('.ec-create-store-button').addClass('btn--loading');
        jQuery('.ec-connect-store').addClass('disabled');

		jQuery.ajax(ajaxurl + '?action=ecwid_create_store',
			{
				success: function(result) {
        			jQuery('.ec-create-store-note').hide();
        			jQuery('.ec-create-store-success-note').show();
					
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