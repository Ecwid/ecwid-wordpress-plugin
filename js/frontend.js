window.ec = window.ec || {};
window.ec.config = window.ec.config || {};
window.ec.config.storefrontUrls = window.ec.config.storefrontUrls || {};

jQuery(document).ready(function() {
    
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
            } if (page == 'category' ) {
                if (ecwidParams.useJsApiToOpenStoreCategoriesPages) {
                    Ecwid.openPage('category', {id:jQuery(this).data('ecwid-category-id')});
                    jQuery(this).hide().blur().show();
                } else {
                    return;
                }
            } else if ( page == 'product' ) {
                Ecwid.openPage('product', {id: jQuery(this).data('ecwid-product-id')});
            } else {
                Ecwid.openPage(page);
            }
            
            return false;
        });
    };
    
    ecwidShoppingCartMakeStoreLinksUseApiCall(jQuery("a[data-ecwid-page]"));
    
    
    if ( jQuery('#ecwid-main-page-title').length > 0 ) {
        Ecwid.OnPageLoaded.add(function(page) {
            var isCategory = page.type == 'CATEGORY';
            var isProduct = page.type == 'PRODUCT';
            
            if ( !isCategory && !isProduct ) return;
            
            var el = jQuery('#ecwid-main-page-title');
            var format = el.attr('data-format');
            var titlePlaceholder = el.attr('data-title-placeholder');
            
            var newTitle = jQuery('title').html();
            if ( !newTitle ) {
                newTitle = "";
            }
            if ( isCategory && page.categoryId == 0 || newTitle.length == 0 ) {
                var title = el.attr('data-original-title');
            } else {
                var title = format.replace( titlePlaceholder, jQuery('title').html() );
            }
            
            el.html( title );
        });
    }
});
