function ecwid_get_store_shortcode(content) {
	var found = false;
	var index = 0;

	while (found = wp.shortcode.next('ecwid', content, index)) {

		if (found && (!found.shortcode.attrs.named.widgets || found.shortcode.attrs.named.widgets.toLowerCase().indexOf('productbrowser') != -1)) {
			break;
		}
		index = found.index + 1;
	}

	if (typeof found == 'undefined') {
		found = false;
	}

	// Workaround for the caching bug that does allow to have properly parsed attributes
	if (found) {
		var tmpfound = wp.shortcode.next('ecwid', found.content.replace('[ecwid', '[ecwid timestamp="' + (new Date()).getMilliseconds() + '"'));
		found.shortcode.attrs = tmpfound.shortcode.attrs;
		delete found.shortcode.attrs.named.timestamp;
	}

	return found;
}