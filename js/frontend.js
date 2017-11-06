window.ec = window.ec || {};
window.ec.config = window.ec.config || {};
window.ec.config.storefrontUrls = window.ec.config.storefrontUrls || {};

jQuery(document).ready(function() {
    jQuery('.ecwid-store-with-categories a').click(function() {jQuery(':focus').blur()});
    
    if (ecwidParams.useJsApiToOpenStorePages) {
        jQuery('a[data-ecwid-page]').click(function() {
        
            if (typeof Ecwid == 'undefined') {
                return true;
            }
            
            var page = jQuery(this).data('ecwid-page');
            if (page == 'category') {
                Ecwid.openPage('category', {id:jQuery(this).data('ecwid-category-id')});
            } else if ( page == 'product' ) {
                Ecwid.openPage('category', {id: jQuery(this).data('ecwid-product-id')});
            } else {
                Ecwid.openPage(page);
            }
            
            return false;
        });
    }
});
