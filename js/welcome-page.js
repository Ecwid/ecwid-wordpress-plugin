jQuery(document).ready(function(){

	jQuery('.ec-create-store-button').on('click', function() {
		
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
						location.href="admin.php?page=ec-store&ec-store-page=complete-registration";
					}, 1000);
				},
				error: function(error) {
					if( error.status == '409' ) {
						location.href = 'admin-post.php?action=ec_connect';
					} else {
						location.href = ecwidParams.registerLink;
					}
				}
			}
		);
	});

});