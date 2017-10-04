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
		
		if (ecwid_floating_shopping_cart_options.move_to_body == 1) {
            jQuery(document).ready(function() {
                 jQuery('body').append(jQuery('.ecwid-float-icons'));
            });			
		}
	}
};

if ( typeof ecwid_floating_shopping_cart == 'undefined' && typeof Ecwid != 'undefined' ) {
    var ecwid_floating_shopping_cart = new EcwidFloatingShoppingCart();
    ecwid_floating_shopping_cart.init();
}