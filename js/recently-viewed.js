wpCookies.set('test_ecwid_shopping_cart_recently_products_cookie', 'test_ecwid_shopping_cart_cookie_value', { path: '/' });
if (wpCookies.get('test_ecwid_shopping_cart_recently_products_cookie') != 'test_ecwid_shopping_cart_cookie_value') {
	// Cookies do not work, we do nothing
	exit;
}

jQuery.widget('ecwid.recentlyViewedProducts', jQuery.ecwid.productsList, {

	_create: function() {
		this._superApply(arguments);
		this.options.prependNew = true;

		if (typeof Ecwid == 'undefined') return;

		var self = this;
		Ecwid.OnPageLoaded.add(
			function(page) {

				if (page.type == 'PRODUCT' && jQuery('.ecwid-productBrowser-details').length > 0) {
					var product = {
						id: page.productId.toString(),
						name: page.name
					}

					setTimeout(function() {
						self.addViewedProduct(product);
					}, 500);
				} else {
					self.refresh();
				}
			}
		);
	},

	addViewedProduct: function(product) {
		product.image = jQuery('.ecwid-productBrowser-gallery-image .gwt-Image').attr('src');
		product.link = window.location.href;
		product.name = jQuery('.ecwid-productBrowser-head').text();
		if (jQuery('.ecwid-productBrowser-price .ecwid-productBrowser-price-value').length > 0) {
			product.price = jQuery('.ecwid-productBrowser-details-rightPanel .ecwid-productBrowser-price .ecwid-productBrowser-price-value').text();
		} else {
			product.price = jQuery('.ecwid-productBrowser-details-rightPanel .ecwid-productBrowser-price').text();
		}

		if (typeof this.products[product.id] == 'undefined') {
			this.addProduct(product);
			if (this.is_api_available) {
				this._updateFromServer(product.id);
			}
		} else {
			this.sort.splice(this.sort.indexOf(product.id), 1);
			this._addToSort(product.id);
		}

		this._render();
	},

	render: function() {
		this._superApply(arguments);
		jQuery('.show-if-empty', this.el).hide();
	},

	_getProductsToShow: function() {
		// copy array using slice
		var sort = this.sort.slice();


		if (jQuery('.ecwid-productBrowser-ProductPage').length > 0) {
			var currentProductId = jQuery('.ecwid-productBrowser-ProductPage').attr('class').match(/ecwid-productBrowser-ProductPage-(\d+)/);

			if (sort.length > 1 && sort.indexOf(currentProductId[1]) != -1) {
				sort.splice(
						sort.indexOf(
								currentProductId[1]
						), 1
				);
			}
		}

		return sort.reverse().slice(0, this.option('max')).reverse();
	}
});
