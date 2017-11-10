function ecwid_get_store_shortcode(content) {

	if (!wp.shortcode) return false;
	var found = false;
	var index = 0;

	for (var i = 0; i < ecwid_params.store_shortcodes.length; i++) {
		var candidate = false;
		while (candidate = wp.shortcode.next(ecwid_params.store_shortcodes[i], content, index)) {
	
			if (candidate && (!candidate.shortcode.attrs.named.widgets || candidate.shortcode.attrs.named.widgets.toLowerCase().indexOf('productbrowser') != -1)) {
				found = candidate;
				break;
			}
			index = found.index + 1;
		}
		
		if (found) break;
    }

	if (typeof found == 'undefined') {
		found = false;
	}

	// Workaround for the caching bug that does allow to have properly parsed attributes
	if (found) {
		var tmpfound = false;
		for (var i = 0; i < ecwid_params.store_shortcodes.length; i++) {
			var shortcode_name = ecwid_params.store_shortcodes[i];
            tmpfound = wp.shortcode.next(shortcode_name, found.content.replace('[' + shortcode_name, '[' + shortcode_name + ' timestamp="' + (new Date()).getMilliseconds() + '"'));
            if (tmpfound) {
            	break;
			}
		}
		found.shortcode.attrs = tmpfound.shortcode.attrs;
		delete found.shortcode.attrs.named.timestamp;
	}

	return found;
}