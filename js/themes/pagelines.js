jQuery(document).ready(function() {
	var fixedNav = jQuery('#navbar');
	if (fixedNav.css('position') == 'fixed') {
		jQuery('#ecwid_product_browser_scroller').css({
			'position': 'relative',
			'top': -fixedNav.height()
		});
	}
});