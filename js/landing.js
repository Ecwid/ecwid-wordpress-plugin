switch_to_connect = function() {
	jQuery('.ecwid-landing').removeClass('register').addClass('connect');
	jQuery('.ecwid-thank-step-one').addClass('active');
	wpCookies.set('ecwid_create_store_clicked', 1);
}