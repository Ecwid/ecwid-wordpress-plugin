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
                if (!ecwidParams.useJsApiToOpenStoreCategoriesPages) {
                    return;
                }
                
                var id = jQuery('[data-ecwid-default-category-id]').data('ecwid-default-category-id');
                if (id) {
                    Ecwid.openPage('category', {id:id});
                } else {
                    Ecwid.openPage('category', 0);
                }
            } else if (page == 'category' ) {
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
        Ecwid.OnAPILoaded.add(function () {

            var font = window.ec.config.chameleonDefaults
                && window.ec.config.chameleonDefaults.font
                && window.ec.config.chameleonDefaults.font['font-family'] || '';
            document.cookie = "ec_store_chameleon_font=" + font;
        })
    };

    if ( ecwidParams.trackPublicPage ) {
        if ( typeof Ecwid != 'undefined' ) {
            Ecwid.OnAPILoaded.add(function () {

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
                    }
                }
            });
        } else {
            (function(w, d, analyticsJsUrl, trackerName) {
                w['HeapAnalyticsObject'] = trackerName;
                w[trackerName] = w[trackerName] || function() {
                        (w[trackerName].q = w[trackerName].q || []).push(arguments)
                    }, w[trackerName].l = 1 * new Date();
                var analyticsScript = d.createElement('script');
                analyticsScript.async = true;
                analyticsScript.src = analyticsJsUrl;

                var firstScript = d.getElementsByTagName('script')[0];
                var maxAttempts = 50;
                var interval = setInterval(function() {
                    if (/loaded|complete/.test(d.readyState) || (0 === maxAttempts--)) {
                        firstScript.parentNode.insertBefore(analyticsScript, firstScript);
                        clearInterval(interval)
                    }
                }, 100);
            })(window, document, 'https://ecomm.events/i.js', "eca");
            eca('send', {'eventName': 'PAGE_VIEW', entityType: 'PAGE', 'storeId': ecwidParams.storeId});
        }
    } 
    
});
