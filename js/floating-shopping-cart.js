var EcwidFloatingShoppingCart = function() {
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

if ( typeof ecwid_floating_shopping_cart == 'undefined' ) {
    var ecwid_floating_shopping_cart = new EcwidFloatingShoppingCart();
    ecwid_floating_shopping_cart.init();
}