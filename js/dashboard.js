jQuery(window).resize(function() {
	if (jQuery(this).width() < 768) {
		jQuery('.ecwid-admin').addClass('width-smaller');
		var head =
		jQuery('.ecwid-dashboard .box .head');
		/*
		head.addClass('drop-down');
		head.find('h2').addClass('drop-down-head');
		head.find('ul').addClass('drop-down-content open').removeClass('head-links');*/
	} else {
		jQuery('.ecwid-admin').removeClass('width-smaller');
	}
}).trigger('resize');

show_reconnect = function() {
	jQuery('<div class="ecwid-popup"></div>').load(
		'admin-post.php?action=ecwid_show_reconnect',
		'',
		function() {
			jQuery('.ecwid-popup .close').click(function() {
				jQuery(this).closest('.ecwid-popup').remove();
			});
		}
	).appendTo('body');

}


function ecwidSetPopupCentering(iframeSelector) {
	if (!iframeSelector) {
		if (console) console.log("Selector should be set");
		return;
	}
	var iframeElement = document.querySelector(iframeSelector);
	if (iframeElement === null) {
		if (console) console.log("Element not found by selector " + iframeSelector);
		return;
	}
	window.addEventListener('scroll', function(e) {
		sendCenteringSettings(iframeElement);
	});
	window.addEventListener('resize', function(e) {
		sendCenteringSettings(iframeElement);
	});
	sendCenteringSettings(iframeElement);
	function sendCenteringSettings(iframeElement) {
		var scrollTop = window.pageYOffset || document.documentElement.scrollTop;
		var clientHeight = window.innerHeight;
		var data = {
			parentWindowVisibleHeight: clientHeight,
			visibleHeightAboveIframe: iframeElement.getBoundingClientRect().top
		};
		iframeElement.contentWindow.postMessage(JSON.stringify({data: data, method: 'setupPopupCentering'}), '*');
	}
}
