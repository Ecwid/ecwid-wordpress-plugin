jQuery('document').ready(function () {
    jQuery('.ecwid-popup').on('click', function (e) {
        var $popup = jQuery('.ecwid-popup-window', this);

        if (!$popup.is(e.target) && $popup.has(e.target).length === 0) {
            jQuery(this).removeClass('open');
            jQuery('body').removeClass('ecwid-popup-open');
        }
    });

    jQuery('.ecwid-popup .btn-close').on('click', function () {
        jQuery(this).closest('.ecwid-popup').removeClass('open');
        jQuery('body').removeClass('ecwid-popup-open');
    });

    jQuery('.ec-store-open-feedback-popup').on('click', function () {
        jQuery('.ecwid-popup-woo-import-feedback').addClass('open');
        jQuery('body').addClass('ecwid-popup-open');

        return false;
    });
});