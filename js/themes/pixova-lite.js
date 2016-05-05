jQuery(document).ready(function() {
	var fixedNav = jQuery('#header-wrap');
	if (fixedNav.css('position') == 'fixed') {
		jQuery('#ecwid_product_browser_scroller').css({
			'position': 'relative',
			'top': -fixedNav.height() - jQuery('.ecwid-shopping-cart-categories').height()
		});
	}
});