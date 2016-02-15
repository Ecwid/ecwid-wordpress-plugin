if ( typeof ecwid_kissmetrics.store_id != 'undefined' ) {
    _kmq.push([ 'identify', ecwid_kissmetrics.store_id ] );
}


function ecwid_kissmetrics_record(event) {
	if (typeof this.fired == 'undefined') {
			this.fired = [];
	}

	if (this.fired.indexOf(event) != -1) return;

	ecwid_kissmetrics.events.push({event: event});
	this.fired.push(event);

	ecwid_kissmetrics_flush();
}

function ecwid_kissmetrics_flush() {
	if (typeof _kmq == 'undefined') {
		return;
	}

	for (var i = 0; i < ecwid_kissmetrics.events.length; i++) {
		_kmq.push( [ 'record', ecwid_kissmetrics.events[i].event ] );
	}

	ecwid_kissmetrics.events = [];
}

jQuery(document).ready(function() {

	jQuery.getScript('https://i.kissmetrics.com/i.js', function() {
		jQuery.getScript('https://scripts.kissmetrics.com/' + ecwid_kissmetrics.key + '.2.js', function() {
			ecwid_kissmetrics_flush();
		})
	});
});