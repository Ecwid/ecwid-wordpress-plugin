switch_to_connect = function() {
	jQuery('.ecwid-landing').removeClass('register').addClass('connect');
	jQuery('.ecwid-thank-step-one').addClass('active');
	wpCookies.set('ecwid_create_store_clicked', 1);
}

hide_on_loading = '.create-store-button, .create-store-have-account-question';
invisible_on_loading = '.create-store-have-account-link';
show_on_loading = '.create-store-loading, .create-store-loading-note';

hide_on_success = '.create-store-loading, .create-store-loading-note';
show_on_success = '.create-store-success, .create-store-success-note';


jQuery(document).ready(function(){

	jQuery('.create-store-button').click(function() {

		var $context = jQuery(this).closest('.ecwid-button');
		jQuery(hide_on_loading + ', ' + invisible_on_loading, $context).fadeTo(150, .01).promise().done(function() {
			jQuery(hide_on_loading, $context).hide();
			jQuery(invisible_on_loading, $context).css('visibility', 'hidden');

			jQuery(show_on_loading, $context).fadeIn(300);
		})

		jQuery.ajax(ajaxurl + '?action=ecwid_create_store',
			{
				success: function(result) {
					var html = result;
					jQuery(hide_on_success, $context).fadeTo(150, .01).promise().done(function() {
						jQuery(hide_on_success, $context).hide();

						jQuery(show_on_success, $context).fadeIn(300);
						setTimeout(function() {
							location.href="admin.php?page=ec-store";
						}, 1000);
					})
				},
				error: function() {
					window.location.href = ecwidParams.registerLink;
				}
			}
		);
	});

});
switch_to_success = function() {

}
