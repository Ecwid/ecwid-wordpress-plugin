switch_to_connect = function() {
	jQuery('.ecwid-landing').removeClass('register').addClass('connect');
	jQuery('.ecwid-thank-step-one').addClass('active');
	wpCookies.set('ecwid_create_store_clicked', 1);
}

hide_on_loading = '.create-store-button, .create-store-have-account-question';
invisible_on_loading = '.create-store-have-account-link';
show_on_loading = '.create-store-loading, .create-store-loading-note';

hide_on_success = '.create-store-loading';
invisible_on_success = '.create-store-loading-note';
show_on_success = '.create-store-success';


jQuery(document).ready(function(){

	jQuery('.create-store-button').click(function() {

		jQuery(hide_on_loading + ', ' + invisible_on_loading).fadeTo(150, .01).promise().done(function() {
			jQuery(hide_on_loading).hide();
			jQuery(invisible_on_loading).css('visibility', 'hidden');

			jQuery(show_on_loading).fadeIn(300);
		})

		jQuery.ajax(ajaxurl + '?action=ecwid_create_store',
			{
				success: function(result) {
					debugger;
					var html = result;
					jQuery(hide_on_success + ', ' + invisible_on_success).fadeTo(150, .01).promise().done(function() {
						jQuery(hide_on_success).hide();
						jQuery(invisible_on_success).css('visibility', 'hidden');

						jQuery(show_on_success).fadeIn(300);
						setTimeout(function() {
							jQuery('#wpbody-content').html(html);
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
