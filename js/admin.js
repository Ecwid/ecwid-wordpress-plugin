	jQuery(document).ready(function() {
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
				ecwid_kissmetrics_record('Ecwid-widgets Page Viewed');
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
					'ecwid-widget-storelink': 'storePageLink'
				};
			}

			for (var i in this.widgets) {
				if (widgetElement.find('.' + i).length > 0) {
					ecwid_kissmetrics_record('sb-widget ' + this.widgets[i] + 'Added');
					break;
				}
			}
			debugger;
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

	var admin_pages = [
		{
			url: ecwid_l10n.dashboard_url,
			title: ecwid_l10n.dashboard,
			place: 'dashboard',
			km: 'Dashboard'
		},
		{
			url: ecwid_l10n.products_url,
			title: ecwid_l10n.products,
			place: 'products',
			km: 'Products'
		},
		{
			url: ecwid_l10n.orders_url,
			title: ecwid_l10n.orders,
			place: 'orders',
			km: 'Sales'
		},
	];

	if (jQuery('#ecwid-frame').length > 0) {
		if (jQuery('div.update-nag').length > 0) {
			jQuery('#superwrap').addClass('has-wp-message');
		}
		for (var i = 0; i < admin_pages.length; i++) {
			jQuery('li.toplevel_page_ecwid .wp-submenu a[href="' + admin_pages[i].url + '"]')
				.data('ecwid-menu', admin_pages[i])
				.click(function() {
					var ecwidMenu = jQuery(this).data('ecwid-menu');
					jQuery('.toplevel_page_ecwid *.current').removeClass('current');
					jQuery(this).addClass('current').closest('li').addClass('current');
					jQuery('#ecwid-frame')[0].contentWindow.postMessage(JSON.stringify({
						ecwidAppNs: "ecwid-wp-plugin",
						method: "openPage",
						data: ecwidMenu.place
					}), "*")

					ecwid_kissmetrics_record(ecwidMenu.km + ' Page Viewed');

					return false;
				});
		}
	}

		jQuery('#wp-admin-bar-ecwid-main-default a').click(function() {
			ecwid_kissmetrics_record('Top Menu Clicked');
		});
});
