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

	if (location.href.match(/wp-admin\/widgets.php/)) {
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
		debugger;
		if (jQuery('#ecwid-store-id').val()) {
			location.href = this.href + '&force_store_id=' + jQuery('#ecwid-store-id').val();
		}
		return false;
	});
});
