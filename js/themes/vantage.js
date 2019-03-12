
jQuery(document).ready(function(){

	console.log( jQuery('#ec-cart-widget') );
	console.log( jQuery('#ec-cart-widget').data('fixedPosition') );

	setTimeout(function(){
		var st_bottom = parseInt(jQuery('#scroll-to-top').css('bottom')),
			st_height = parseInt(jQuery('#scroll-to-top').outerHeight(true));

		jQuery('#scroll-to-top').css('right', jQuery('.ec-minicart').css('right'));
		jQuery('.ec-minicart').css('bottom', st_bottom + st_height + 10 + 'px' );
	}, 2000);
});