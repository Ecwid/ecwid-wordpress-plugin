var Cart = function() {
	var cartIcon = jQuery('.ecwid-cart-icon:first'),
			cartCounter = jQuery('a', cartIcon);

	function changeState(cartObj) {
		if (cartObj.productsQuantity) {
			cartIcon.removeClass('off');
			cartCounter.attr('data-count', cartObj.productsQuantity);
		}
		else {
			cartIcon.addClass('off');
			cartCounter.attr('data-count', 0);
		}
	}

	this.init = function() {
		Ecwid.OnCartChanged.add(function(cartObj) {
			changeState(cartObj);
		});
	}
};

var x = new Cart();
x.init();

jQuery(document).ready(function() {
	if (jQuery('#wpadminbar').length > 0) {
		jQuery('.ecwid-float-icons').css('margin-top', jQuery('#wpadminbar').height() + 'px');
	}
});
