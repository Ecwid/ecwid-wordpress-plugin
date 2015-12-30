(function() {

if (typeof jQuery == 'undefined') {
	console.warn('recently-viewed-common.js requires jquery');
}

if (typeof Ecwid == 'undefined') {
	console.warn('recently-viewed-common.js must be included after Ecwid object initialization');
	return;
}

if (typeof wpCookies == 'undefined') {
	console.warn('recently-viewed-common.js requires utils');
}

if (wpCookies.get('test_ecwid_shopping_cart_recently_products_cookie') != 'test_ecwid_shopping_cart_cookie_value') {
	wpCookies.set('test_ecwid_shopping_cart_recently_products_cookie', 'test_ecwid_shopping_cart_cookie_value', '', '/');
	console.warn('recently-viewed-common.js requires enabled cookies');
}

Ecwid.OnPageLoaded.add(function(page) {
	if (page.type == 'PRODUCT') {
		var productInfo = fetchProductInfo(page.productId);
		saveProductToCookies(productInfo);
	}
});

var fetchProductInfo = function(productId) {
	var product = {};

	product.id = productId;
	product.link = window.location.href;

	return product;
}

var saveProductToCookies = function(product) {
	var cookieName = 'ecwid-shopping-cart-recently-viewed';

	var cookie = JSON.parse(wpCookies.get(cookieName));

	if (cookie == null || typeof(cookie) != 'object') {
		cookie = {last: 0, products: []};
	}

	var expires = new Date;
	expires.setMonth(expires.getMonth() + 1);

	var src = jQuery('script[src*="app.ecwid.com/script.js?"]').attr('src');
	var re = /app.ecwid.com\/script.js\?(\d*)/;
	cookie.store_id = src.match(re)[1];

	for (var i = 0; i < cookie.products.length; i++) {
		if (cookie.products[i].id == product.id) {
			cookie.products.splice(i, 1);
		}
	}

	cookie.products.unshift({
		id: product.id,
		link: product.link
	});

	wpCookies.set(cookieName, JSON.stringify(cookie), expires.toUTCString(), '/' );

}

})();