jQuery(document).ready(function() {
	
	var is_safari = navigator.userAgent.indexOf('Chrome') == -1 && navigator.userAgent.indexOf("Safari") > -1;
	
	wpCookies.set('ecwid_is_safari', is_safari);

	window.ecwidOpenAdminPage = function(place) {
		jQuery('#ecwid-frame')[0].contentWindow.postMessage(JSON.stringify({
			ecwidAppNs: "ecwid-wp-plugin",
			method: "openPage",
			data: place
		}), "*")
	}

	jQuery('#hide-vote-message').click(function() {
		jQuery('#hide-vote-message').addClass('hiding');
		jQuery.getJSON(
			'admin-ajax.php',
			{ action:'ecwid_hide_vote_message' }, 
			function(data) {
				jQuery('#hide-vote-message').removeClass('hiding')
						.closest('div.update-nag, div.updated.fade').fadeOut();
			}
		);
	});

	jQuery('a.ecwid-message-hide').click(function() {

		var a = this;
		jQuery(a).css('cursor', 'wait');
		jQuery.getJSON(
			'admin-ajax.php',
			{
				action: 'ecwid_hide_message',
				message: a.name
			},
			function(data) {
				jQuery(a).closest('.ecwid-message').fadeOut();
			}
		);
		
		return false;
	});

	if (location.href.match(/wp-admin\/widgets.php/) || location.href.match(/wp-admin\/customize.php/)) {
		jQuery('div[id^="widget-"]').filter('div[id*="_ecwid"]').each(function(idx, el) {
			if (location.href.match(/wp-admin\/widgets.php\?from-ecwid=/) && el.id.match(/__i__/)) {
				if (jQuery('.ecwid-widget').length > 0) {
					jQuery(el).insertAfter(jQuery('.ecwid-widget:last'));
				} else {
					jQuery(el).prependTo(jQuery('#widget-list'));
				}
				jQuery('.widget-top', el).addClass('ecwid-widget-highlighted');
			}

			var classname = el.id.match(/ecwid(.*)-__i__/);
			if (classname) {
				classname = 'ecwid-widget-' + classname[1];
				jQuery(el).addClass('ecwid-widget')
					.find('.widget-top')
					.addClass(classname);
			}

		});

		jQuery(document).on('widget-added', function(event, widgetElement) {
			if (typeof this.widgets == 'undefined') {
				this.widgets = {
					'ecwid-widget-badge': 'ecwidBadge',
					'ecwid-widget-search': 'productSearch',
					'ecwid-widget-recentlyviewed': 'recentlyViewedProducts',
					'ecwid-widget-minicart': 'shoppingCart',
					'ecwid-widget-minicart_miniview': 'miniShoppingCart',
					'ecwid-widget-vcategories': 'storeCategories',
					'ecwid-widget-storelink': 'storePageLink',
					'ecwid-widget-floatingshoppingcart': 'floatingShoppingCart',
					'ecwid-widget-vcategorieslist': 'storeRootCategories',
					'ecwid-widget-nsfminicart': 'shoppingCart',
				};
			}

			jQuery('input[value=ecwidvcategorieslist]').closest('.widget').each(function(idx, el) {
				prepareVerticalCategoriesWidget(el);
			});
		});
	}

	if (location.href.match(/wp-admin\/widgets.php/)) {
		jQuery('input[value=ecwidvcategorieslist]').closest('.widget').each(function(idx, el) {
			prepareVerticalCategoriesWidget(el);
		});
	}

	jQuery('.drop-down').each(function(idx, el) {
		jQuery(el).find('>span').click(function (e) {
			jQuery(e.target).closest('.drop-down').addClass('hover');

			jQuery(window).bind('click.ecwidDropDown', function(e) {
				if (jQuery(e.target).closest('.drop-down')[0] != el) {
					jQuery(window).unbind('.ecwidDropDown');
					jQuery(el).removeClass('hover');
				}
			});
		})
	});

	jQuery('#ecwid-connect-no-oauth').click(function() {
		if (jQuery('#ecwid-store-id').val()) {
			location.href = this.href + '&force_store_id=' + jQuery('#ecwid-store-id').val();
		}
		return false;
	});

	jQuery('#ecwid-get-mobile-app').click(function() {
		ecwidOpenAdminPage('mobile');

		return false;
	});

	if (document.location.hash == 'mobile') {
		ecwidOpenAdminPage('mobile');
	}
	
});

prepareVerticalCategoriesWidget = function(element) {

	element = jQuery(element);

	if (element.data('vcategoriesInitialized')) return;

	if (jQuery('input.widget-id', element).val() == 'ecwidvcategorieslist-__i__') return;

	resetCache = function(callback) {
		jQuery.getJSON(
				'admin-ajax.php',
				{
					action: 'ecwid_reset_categories_cache'
				},
				callback
		);
	}

	$target = jQuery('<p class="ecwid-cats-reset-cache">').appendTo(jQuery('.ecwid-reset-categories-cache-block', element));

	jQuery('<span>')
			.text(ecwid_params.cache_updated)
			.addClass('ecwid-reset-categories-cache-updated')
			.appendTo($target);

	var a = jQuery('<a>')
			.text(ecwid_params.reset_cats_cache)
			.attr('href', 'javascript:void(0);')
			.addClass('ecwid-reset-categories-cache')
			.appendTo($target);
	a.click(function() {

		var that = this;
		jQuery(that).css('cursor', 'wait');
		resetCache(function() {
			jQuery(that).fadeOut(100, function() {
				jQuery(that).prev('.ecwid-reset-categories-cache-updated').fadeIn(100, function() {
					setTimeout(function () {
						jQuery(that).prev('.ecwid-reset-categories-cache-updated').fadeOut(500, function () {
							jQuery(that).fadeIn(500);
						})
					}, 4000);
				});
			});

			jQuery(that).css('cursor', 'pointer');
		});
	});

    if (jQuery('#ecwid-frame').length > 0) {
        if (jQuery('div.update-nag').length > 0) {
            jQuery('#ecwid-frame').addClass('has-wp-message');
        }
    }
    
	element.data('vcategoriesInitialized', true);
}