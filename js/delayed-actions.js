ecwidCheckApiCache = function () {
    if (typeof ecwidCacheControlParams == 'undefined') return;

    jQuery.getJSON(
        ecwidCacheControlParams.ajax_url,
        {
            action: 'ec_check_api_cache',
        }
    );
}

ecwidGetStaticSnapshot = function () {
    if (typeof ecwidDelayedActionsParams == 'undefined') return;

    jQuery.getJSON(
        ecwidDelayedActionsParams.ajax_url,
        {
            action: 'ec_get_static_snapshot',
            snapshot_url: ecwidDelayedActionsParams.snapshot_url,
            _ajax_nonce: ecwidDelayedActionsParams.ajaxNonce,
            dynamic_css: ecwidDelayedActionsParams.cssLinkElement
        }
    );
}

jQuery(document).ready(function () {
    if (document.cookie.search("ecwid_event_is_working_beforeunload") < 0) {
        ecwidGetStaticSnapshot();
        ecwidCheckApiCache();
    }
});

jQuery(window).on('beforeunload', function () {
    ecwidGetStaticSnapshot();
    ecwidCheckApiCache();
    document.cookie = "ecwid_event_is_working_beforeunload=true";
});


if (typeof Ecwid != 'undefined') {
    Ecwid.OnAPILoaded.add(function () {
        if (typeof ecwidDelayedActionsParams == 'undefined') return;

        var css = window.ec.cssLinkElement
            && window.ec.cssLinkElement.href || '';

        ecwidDelayedActionsParams.cssLinkElement = css;
    })
};