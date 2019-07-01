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

    if ( typeof Ecwid != 'undefined' ) {
        Ecwid.OnAPILoaded.add(function() {
            
            var font = window.ec.config.chameleonDefaults
                && window.ec.config.chameleonDefaults.font
                && window.ec.config.chameleonDefaults.font['font-family'] || '';
            document.cookie = "ec_store_chameleon_font=" + font;

            var tracker = window['eca'];

            if (tracker) {
                var noTracking = false;
                var noTrackingWidgets = ['ProductBrowser', 'Product', 'SingleProduct'];
                var initializedWidgets = Ecwid.getInitializedWidgets();
                for (var i = 0; i < noTrackingWidgets.length; i++) {
                    if (initializedWidgets.indexOf(noTrackingWidgets[i]) != -1) {
                        noTracking = true;
                        break;
                    }
                }

                if (!noTracking) {
                    tracker('send', {'eventName': 'PAGE_VIEW', entityType: 'PAGE', 'storeId': Ecwid.getOwnerId()});
                    console.log('tracked');
                }
            }
        });
    }
    
});
