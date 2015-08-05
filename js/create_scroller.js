jQuery(document).ready(function() {
	if (jQuery('#ecwid_product_browser_scroller').length == 0) {
		jQuery('div[id^="ecwid-store-"]').before('<div id="ecwid_product_browser_scroller" class="ecwid-scroller-adjustment"></div>');
	}
});