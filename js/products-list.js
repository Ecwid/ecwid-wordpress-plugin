jQuery.widget('ecwid.productsList', {

	_create: function() {

		this.products = {};
		this.container = null;
		this._prefix = 'ecwid-productsList';
		this.sort = [];
		this.options = {
			max: 3,
			debug: false,
			prependNew: false
		};


		this.element.addClass(this._prefix);
		this._removeInitialContent();
		this.container = jQuery('<ul>').appendTo(this.element);
		this._initFromHtmlData();
		this._readSingleProducts();
		this._onWindowResize();
		this._render();

		var self = this;
		jQuery(window).resize(
			ecwid_debounce(
				function() {
					self._onWindowResize();
				}
			, 200)
		);

		if (typeof wp_ecwid_products_list_vars != 'undefined') {
			this.ajax_url = wp_ecwid_products_list_vars.ajax_url;
			this.is_api_available = wp_ecwid_products_list_vars.is_api_available;
        }
	},

	_render: function() {
		var toShow = this._getProductsToShow();

		for (var i = 0; i < toShow.length; i++) {
			this._showProduct(this.products[toShow[i]]);
		}

		for (var id in this.products) {
			if (toShow.indexOf(id) == -1) {
				this._hideProduct(this.products[id]);
			}
		}

		if (toShow.length > 0) {
			jQuery('.show-if-empty', this.el).hide();
		}
	},

	_setOption: function(key, value) {
		this._super(key, value);
		if (key == 'max') {
			this.refresh();
		}
	},

	_getProductClass: function(id) {
		return this._prefix + '-product-' + id;
	},

	_getProductElement: function(id) {
		return this.container.find('.' + this._getProductClass(id));
	},

	_showProduct: function(product) {
		var existing = this._getProductElement(product.id);

		if (existing.length == 0) {
			this._buildProductElement(product);
		}

		this._fillProductElement(product);

		var el = this._getProductElement(product.id)
				.addClass('show')
				.removeClass('hide');
		
		if (this.options.prependNew) {
			el.prependTo(this.container);
		} else {
			el.appendTo(this.container);
		}
	},

	_hideProduct: function(product) {
		this._getProductElement(product.id)
			.addClass('hide')
			.removeClass('show');
	},

	_buildProductElement: function(product) {
		var container = jQuery('<li class="' + this._getProductClass(product.id) + '">').appendTo(this.container);

		if (product.link != '') {
			container = jQuery('<a>')
					.appendTo(container);
		}
		if (product.image) {
			jQuery('<div class="' + this._prefix + '-image">').append('<img>').appendTo(container);
		} else {
			jQuery('<div class="' + this._prefix + '-image ecwid-noimage">').appendTo(container);
		}
		jQuery('<div class="' + this._prefix + '-name">').appendTo(container);
		jQuery('<div class="' + this._prefix + '-price ecwid-productBrowser-price">').appendTo(container);
	},

	_fillProductElement: function(product) {
		var container = jQuery('.'+ this._getProductClass(product.id), this.el);

		if (product.link != '') {
			
			var a = jQuery('a', container)
					.attr('href', product.link)
					.attr('title', product.name)
					.data('ecwid-page', 'product')
					.data('ecwid-product-id', product.id);
			
			if (typeof window.ecwidShoppingCartMakeStoreLinksUseApiCall != 'undefined') {
                ecwidShoppingCartMakeStoreLinksUseApiCall(a);
			}
		}
		if (product.image) {
			jQuery('.' + this._prefix + '-image img', container).attr('src', product.image);
		}

		jQuery('.' + this._prefix + '-name', container).text(product.name);
		
		price = product.defaultDisplayedPrice ? product.defaultDisplayedPrice : product.price;
		jQuery('.' + this._prefix + '-price.ecwid-productBrowser-price', container).text(product.price);

	},

	_initFromHtmlData: function() {
		for (var option_name in this.options) {
			var data_name = 'ecwid-' + option_name;
			if (typeof(this.element.data(data_name)) != 'undefined') {
				this._setOption(option_name, this.element.data(data_name));
			}
		}
	},

	_removeInitialContent: function() {
		this.originalContentContainer = jQuery('<div class="ecwid-initial-productsList-content">')
				.data('generatedProductsList', this)
				.append(this.element.find('>*'))
				.insertAfter(this.element);
	},

	_readSingleProducts: function() {

		var self = this;
		var singleProductLoaded = function (container) {
			return jQuery('.ecwid-title', container).text() != '';
		}

		jQuery('.ecwid-SingleProduct', this.originalContentContainer).each(function(idx, el) {
			var interval = setInterval(
					function() {
						if (singleProductLoaded(el)) {
							clearInterval(interval);
							self._readSingleProduct(el);
						}
					},
					500
			);
		});
	},

	_readSingleProduct: function(singleProductContainer) {

		var forced_image = jQuery('div[itemprop=image]', singleProductContainer).data('force-image');
		var product = {
			name: jQuery('.ecwid-title', singleProductContainer).text(),
			image: forced_image ? forced_image : jQuery('.ecwid-SingleProduct-picture img', singleProductContainer).attr('src'),
			id: jQuery(singleProductContainer).data('single-product-id'),
			link: jQuery(singleProductContainer).data('single-product-link'),
		}
		if (jQuery('.ecwid-productBrowser-price .gwt-HTML', singleProductContainer).length > 0) {
			product.price = jQuery('.ecwid-productBrowser-price .gwt-HTML', singleProductContainer).text();
		} else {
			product.price = jQuery('.ecwid-price', singleProductContainer).text();
		}
		this.addProduct(product, true);
	},

	_updateFromServer: function(id) {

		var that = this;
		if (!this.products[id]) return false;
		jQuery.getJSON(
			wp_ecwid_products_list_vars.ajax_url,
			{
				'action': 'ecwid_get_product_info',
				'id': id
			},
			function(data, result) {
				if (result == 'success') {
					that.products[id] = jQuery.extend(
							that.products[id], {
								image: data.imageUrl
							}
					);

					that._render();
				}
			}
		);
	},

	_getProductsToShow: function() {
		return this.sort.slice(0, this.option('max'));
	},

	_addToSort: function(id) {
		this.sort.push(id.toString());
	},

	_triggerError: function(message) {
		message = 'ecwid.productsList ' + message;
		if (this.options.debug) {
			alert(message);
		}
		console.log(message);
	},

	_destroy: function() {
		this.element.removeClass('.' + this._prefix).find('>*').remove();
		this.element.append(this.originalContentContainer.find('>*'));
		this.originalContentContainer.data('generatedProductsList', null);
		this.originalContentContainer = null;
		this._superApply(arguments);
	},

	refresh: function() {
		this._render();
	},

	addProduct: function(product, forceRender) {
		if (typeof(product.id) == 'undefined') {
			this._triggerError('addProduct error: product must have id');
		}

		if (typeof this.products[product.id] != 'undefined') {
			return;
		}

		this.products[product.id] = jQuery.extend(
				{}, {
					id: 0,
					name: 'no name',
					image: '',
					link: '',
					price: '',
					toString: function() {return this.name;}
				},
				product
		);

		this._addToSort(product.id);

		if (forceRender) {
			this._render();
		}
	},

	_onWindowResize: function() {
		if (this.element.width() < 150) {
			this.element.addClass('width-s').removeClass('width-m width-l');
		} else if (this.element.width() < 300) {
			this.element.addClass('width-m').removeClass('width-s width-l');
		} else {
			this.element.addClass('width-l').removeClass('width-s width-m');
		}
	}
});


// Debounce function from http://unscriptable.com/2009/03/20/debouncing-javascript-methods/
var ecwid_debounce = function (func, threshold, execAsap) {

	var timeout;

	return function debounced () {
		var obj = this, args = arguments;
		function delayed () {
			if (!execAsap) {
				func.apply(obj, args);
			}
			timeout = null;
		};

		if (timeout)
			clearTimeout(timeout);
		else if (execAsap)
			func.apply(obj, args);

		timeout = setTimeout(delayed, threshold || 100);
	};

}

jQuery('.ecwid-productsList').trigger('ecwidOnWindowResize');
