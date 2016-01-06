if ( typeof ecwid_kissmetrics.store_id != 'undefined' ) {
    _kmq.push([ 'identify', ecwid_kissmetrics.store_id ] );
}

for (var i = 0; i < ecwid_kissmetrics.events.length; i++) {
    _kmq.push( [ 'record', ecwid_kissmetrics.events[i].event ] );
}

function ecwid_kissmetrics_record(event) {
    if (typeof this.fired == 'undefined') {
        this.fired = [];
    }

    if (this.fired.indexOf(event) != -1) return;

    _kmq.push( [ 'record', 'wp-plugin ' + event ] );
    this.fired.push(event);
}