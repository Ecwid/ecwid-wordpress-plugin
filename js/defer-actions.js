ecwidCheckApiCache = function () {
    if (typeof ecwidCacheControlParams == 'undefined') return;

    jQuery.getJSON(
        ecwidCacheControlParams.ajax_url,
        {
            action: 'ec_check_api_cache',
        }
    );
}

var isNeedGetStaticSnapshot = true;
ecwidGetStaticSnapshot = function () {
    if (typeof ecwidDeferActionsParams == 'undefined') return;

    if (!isNeedGetStaticSnapshot) return;

    jQuery.getJSON(
        ecwidDeferActionsParams.ajax_url,
        {
            action: 'ec_get_static_snapshot',
            snapshot_url: ecwidDeferActionsParams.snapshot_url,
            dynamic_css: ecwidDeferActionsParams.cssLinkElement,
            _ajax_nonce: ecwidDeferActionsParams.ajaxNonce
        },
        function () {
            isNeedGetStaticSnapshot = false;
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
        if (typeof ecwidDeferActionsParams == 'undefined') return;

        ecwidGetStaticSnapshot();

        var css = window.ec.cssLinkElement
            && window.ec.cssLinkElement.href || '';

        ecwidDeferActionsParams.cssLinkElement = css;
    })
};