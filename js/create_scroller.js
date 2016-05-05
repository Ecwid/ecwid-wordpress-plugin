jQuery(document).ready(function() {
	if (jQuery('#ecwid_product_browser_scroller').length == 0) {

		var parent = jQuery('div[id^="ecwid-store-"]');

		if (jQuery('.ecwid-shopping-cart-categories').length == 1) {
			parent = jQuery('.ecwid-shopping-cart-categories');
		}

		parent.before('<div id="ecwid_product_browser_scroller" class="ecwid-scroller-adjustment"></div>');
	}
});