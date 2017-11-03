jQuery('document').ready(function() {
    jQuery('.ecwid-popup').click(function(e) {
        var $popup = jQuery('.ecwid-popup-window', this);

        if (!$popup.is(e.target) && $popup.has(e.target).length === 0) {
            jQuery(this).removeClass('open');
            jQuery('body').removeClass('ecwid-popup-open');            
        }
    });
    
    jQuery('.ecwid-popup .btn-close').click(function() {
        jQuery(this).closest('.ecwid-popup').removeClass('open');
        jQuery('body').removeClass('ecwid-popup-open');
    });
});