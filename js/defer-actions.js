var ecwidCheckApiCache = function () {
    if (typeof ecwidCacheControlParams == 'undefined') return;

    jQuery.getJSON(
        ecwidCacheControlParams.ajax_url,
        {
            action: 'ec_check_api_cache',
        }
    );
}

jQuery(document).ready(function () {
    if (document.cookie.search("ecwid_event_is_working_beforeunload") < 0) {
        ecwidCheckApiCache();
    }
});

jQuery(window).on('beforeunload', function () {
    ecwidCheckApiCache();
    document.cookie = "ecwid_event_is_working_beforeunload=true";
});