window.ec = window.ec || {};
window.ec.config = window.ec.config || {};
window.ec.config.storefrontUrls = window.ec.config.storefrontUrls || {};

jQuery(document).ready(function() {
    jQuery('.ecwid-store-with-categories a').click(function() {jQuery(':focus').blur()});
    
    window.ecwidShoppingCartMakeStoreLinksUseApiCall = function($link) {

        $link.click(function() {
            if (typeof Ecwid == 'undefined') {
                return true;
            }

            var page = jQuery(this).data('ecwid-page');
            if (page == '/') {
                var id = jQuery('[data-ecwid-default-category-id]').data('ecwid-default-category-id');
                if (id) {
                    Ecwid.openPage('category', {id:id});
                } else {
                    Ecwid.openPage('category', 0);
                }
            } if (page == 'category') {
                Ecwid.openPage('category', {id:jQuery(this).data('ecwid-category-id')});
            } else if ( page == 'product' ) {
                Ecwid.openPage('product', {id: jQuery(this).data('ecwid-product-id')});
            } else {
                Ecwid.openPage(page);
            }

            jQuery(this).parents('ul.sub-menu.focus').removeClass('focus').blur().parents('li.menu-item.focus').removeClass('focus').blur();

            return false;
        });
    };
    
    
    if (ecwidParams.useJsApiToOpenStorePages) {
        ecwidShoppingCartMakeStoreLinksUseApiCall(jQuery("a[data-ecwid-page]"));
    }
});
