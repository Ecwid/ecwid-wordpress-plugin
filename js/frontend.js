window.ec = window.ec || {};
window.ec.config = window.ec.config || {};
window.ec.config.storefrontUrls = window.ec.config.storefrontUrls || {};

jQuery(document).ready(function () {

    window.ecwidShoppingCartMakeStoreLinksUseApiCall = function ($link) {

        $link.click(function () {
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
                    Ecwid.openPage('category', { id: id });
                } else {
                    Ecwid.openPage('category', 0);
                }
            } else if (page == 'category') {
                if (ecwidParams.useJsApiToOpenStoreCategoriesPages) {
                    Ecwid.openPage('category', { id: jQuery(this).data('ecwid-category-id') });
                    jQuery(this).hide().blur().show();
                } else {
                    return;
                }
            } else if (page == 'product') {
                Ecwid.openPage('product', { id: jQuery(this).data('ecwid-product-id') });
            } else {
                Ecwid.openPage(page);
            }

            return false;
        });
    };

    ecwidShoppingCartMakeStoreLinksUseApiCall(jQuery("a[data-ecwid-page]"));
    ecwidSaveDynamicCss();
});

var ecwidSaveDynamicCss = function () {
    if (typeof Ecwid != 'undefined') {
        Ecwid.OnAPILoaded.add(function () {
            if (typeof window.ec.cssLinkElement !== 'undefined') {
                document.cookie = "ec_store_dynamic_css=" + window.ec.cssLinkElement.href;
            }
        });
    }
}

document.addEventListener('setupAfterEcwidLoaded', (e) => {
    Ecwid.OnAPILoaded.add(function () {
        ecwidSaveDynamicCss();
    });
});