jQuery(document).ready(function() {
    $popup = jQuery('#ecwid-product-popup-content');

    jQuery('.media-menu-item', $popup).click(function() {
        jQuery('.media-menu .media-menu-item', $popup).removeClass('active');
        jQuery(this).addClass('active');

        jQuery('.media-modal-content', $popup).attr('data-active-dialog', jQuery(this).attr('data-content'));
        jQuery('.media-menu').removeClass('visible');
        return false;
    });

    jQuery('h1', $popup).click(function() {
        jQuery('.media-menu').toggleClass('visible');
    })
});
/*
 * Handles media modal menus
 */
