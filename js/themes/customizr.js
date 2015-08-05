jQuery(document).ready(function() {

	var fixedNav = jQuery('header.tc-header'),
			ecwid_pb_scroller = jQuery('#ecwid_product_browser_scroller');

	ecwid_pb_scroller.css('top', (- fixedNav.height()) + 'px');
	
	jQuery(window).scroll(function() {
		ecwid_pb_scroller.css('top', (- fixedNav.height()) + 'px');
	});
});