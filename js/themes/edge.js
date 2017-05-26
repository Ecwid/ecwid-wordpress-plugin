jQuery(document).ready(function() {
    var fixedNav = jQuery('#sticky_header');
    jQuery('#ecwid_product_browser_scroller').css({
        'position': 'relative',
        'top': -fixedNav.height() - jQuery('.ecwid-shopping-cart-categories').height()
    });
});