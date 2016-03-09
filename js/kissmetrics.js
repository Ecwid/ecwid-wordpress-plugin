if ( typeof ecwid_kissmetrics.store_id != 'undefined' ) {
    _kmq.push([ 'identify', ecwid_kissmetrics.store_id ] );
}

function ecwid_kissmetrics_set(nameValue) {
	ecwid_kissmetrics.items.push({'property' : nameValue });

	ecwid_kissmetrics_flush();
}

function ecwid_kissmetrics_record(event) {
	if (typeof this.fired == 'undefined') {
			this.fired = [];
	}

	event = 'wp-plugin ' + event;

	if (this.fired.indexOf(event) != -1) return;

	ecwid_kissmetrics.items.push({event: event});
	this.fired.push(event);

	ecwid_kissmetrics_flush();
}

function ecwid_kissmetrics_flush() {
	if (typeof _kmq == 'undefined') {
		return;
	}

	for (var i = 0; i < ecwid_kissmetrics.items.length; i++) {
		var item = ecwid_kissmetrics.items[i];
		if (typeof item.event != 'undefined') {
			_kmq.push(['record', ecwid_kissmetrics.items[i].event]);
		} else if (typeof item.property != 'undefined') {
			_kmq.push( [ 'set', ecwid_kissmetrics.items[i].property ] );
		}
	}

	ecwid_kissmetrics.items = [];
}

jQuery(document).ready(function() {

	jQuery.getScript('https://i.kissmetrics.com/i.js', function() {
		jQuery.getScript('https://scripts.kissmetrics.com/' + ecwid_kissmetrics.key + '.2.js', function() {
			ecwid_kissmetrics_flush();
		})
	});
});