(function($) {

function doDefaultLayout()
{
	$('.ecwid-shopping-cart-search .ecwid-SearchPanel-button').text('');

	$('.ecwid-minicart-mini-rolloverContainer').show();
	$('.ecwid-shopping-cart-minicart')
			.css({
					'top': '2px'
			})
			.show();

	var topElement = $('.ecwid-shopping-cart-categories');
	if (topElement.length == 0) {
		topElement = $('.ecwid-shopping-cart-product-browser')
	}
	if (topElement.length > 0) {
		$('.ecwid-productBrowser-auth-mini').css({
			'position': 'absolute',
			'top': topElement.prop('offsetTop') - 50
		});

		$('.ecwid-shopping-cart-search').css({
			'position': 'absolute',
			'top': topElement.prop('offsetTop') - 50 + 8
	  });

		if (navigator.userAgent.match(/firefox/i)) {
			$('.ecwid-SearchPanel-button').css('right', '3px');
		}
	}

	$('.ecwid-shopping-cart-minicart').css(
		'margin-right', $('.ecwid-minicart-mini-rolloverContainer').width() - $('.ecwid-shopping-cart-minicart').width()
	);

	if ($('.ecwid-search-placeholder').length == 0) {
		$('.ecwid-shopping-cart .ecwid-shopping-cart-search .ecwid-SearchPanel').after('<div class="ecwid-search-placeholder"></div>');
	}

	$('.ecwid-search-placeholder').click(function() {
		$('body').addClass('ecwid-search-open');
		$('.ecwid-shopping-cart-search .ecwid-SearchPanel-field').focus();
	});
}

$('body').click(function(e) {
	if ($('.ecwid-shopping-cart-search').has(e.target).length == 0) {
		$(this).removeClass('ecwid-search-open');
	}
});

function doMobileLayout()
{
	$('.ecwid-minicart-mini-rolloverContainer').hide();
	$('.ecwid-shopping-cart-minicart').hide();
	$('.ecwid-productBrowser-auth-mini').css({
		'position': 'inherit',
		'top': 'auto'
	});
	$('.ecwid-shopping-cart-search').css({
		'position': 'absolute',
		'top': $('.ecwid-productBrowser').prop('offsetTop') - 50 + 8
	});
}
if (typeof Ecwid != 'undefined') {
	Ecwid.OnPageLoaded.add(function(args) {
		if ($(window).width() < 650) {
			doMobileLayout();
		} else {
			doDefaultLayout();
		}
	});
}

$(window).resize(function() {
	if ($(window).width() < 650) {
		doMobileLayout();
	} else {
		doDefaultLayout();
	}
});

})(jQuery);
/*});*/


