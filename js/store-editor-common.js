function ecwid_get_store_shortcode(content) {

	if (!wp.shortcode) return false;
	var found = false;
	var index = 0;

	while (found = wp.shortcode.next(ecwid_params.store_shortcode, content, index)) {

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
		var tmpfound = wp.shortcode.next(ecwid_params.store_shortcode, found.content.replace('[' + ecwid_params.store_shortcode, '[' + ecwid_params.store_shortcode+ ' timestamp="' + (new Date()).getMilliseconds() + '"'));
		found.shortcode.attrs = tmpfound.shortcode.attrs;
		delete found.shortcode.attrs.named.timestamp;
	}

	return found;
}