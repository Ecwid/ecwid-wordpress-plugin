function onDocumentReady(fn) {
    if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading") {
        fn();
    } else {
        document.addEventListener('DOMContentLoaded', fn);
    }
}

function find(selector) {
    return document.querySelector(selector);
}

function findAll(selector) {
    return document.querySelectorAll(selector);
}

function isVisible(elem) {
    return !!(elem.offsetWidth || elem.offsetHeight || elem.getClientRects().length);
}

function forEach(elements, fn) {
    return Array.prototype.forEach.call(elements, fn);
}

var StaticPageLoader = {
    isTouchDevice: false,
    staticId: null,
    dynamicId: null,

    isRootCategory: function () {
        return window.location.hash === '' || window.location.hash.indexOf("#!/c/0/") !== -1;
    },

    processStaticHomePage: function (staticId, dynamicId) {
        this.staticId = staticId;
        this.dynamicId = dynamicId;

        onDocumentReady(function () {
            if (!!('ontouchstart' in window)) {
                this.isTouchDevice = true;
                document.body.classList.add('touchable');
            }

            if (!StaticPageLoader.isRootCategory()) {
                StaticPageLoader.hideStorefront();
                StaticPageLoader.switchToDynamicMode();
                return;
            }

            StaticPageLoader.hideStorefront();
            StaticPageLoader.showStaticHtml();
            StaticPageLoader.addStaticPageHandlers();

            Ecwid.OnPageLoad.add(function (openedPage) {
                var staticHtml = find('#' + StaticPageLoader.staticId);
                if (!isVisible(staticHtml)) {
                    // if we've already switched to dynamic, we don't need to dispatch this event anymore
                    return;
                }
                if (openedPage.type == "CART"
                    || openedPage.type == "ORDERS"
                    || openedPage.type == "FAVORITES"
                    || openedPage.type == "SIGN_IN") {
                    // static links from bottom of the page should be processed before page load event finishes,
                    // so that pre-opening scroll didn't make the page jump
                    StaticPageLoader.switchToDynamicMode();
                }
            });

            Ecwid.OnPageLoaded.add(function (openedPage) {
                var staticHtml = find('#' + StaticPageLoader.staticId);
                if (!isVisible(staticHtml)) {
                    // if we've already switched to dynamic, we don't need to dispatch this event anymore
                    return;
                }
                if (openedPage.type == "CATEGORY" && openedPage.categoryId == 0) {
                    // we don't need to dispatch root category loading,
                    // since our static contents covers it for the first time
                    return;
                }
                // other than that we must show opened page in dynamic view,
                // because static view contains only root category page
                StaticPageLoader.switchToDynamicModeDeferred();
            });
        });
    },

    addStaticPageHandlers: function () {
        var categoryLinks = findAll('#' + this.staticId + ' .grid-category__card a');
        if (categoryLinks.length > 0) {
            forEach(categoryLinks, function (element) {
                var categoryId = element.getAttribute('data-category-id');
                StaticPageLoader.addStaticClickEvent(element, StaticPageLoader.openEcwidPage('category', {'id': categoryId}));
            });
        }

        var productLinks = findAll('#' + this.staticId + ' .grid-product a');
        if (productLinks.length > 0) {
            forEach(productLinks, function (element) {
                var productId = element.getAttribute('data-product-id');
                StaticPageLoader.addStaticClickEvent(element, StaticPageLoader.openEcwidPage('product', {'id': productId}));
            });
        }

        var buyNowLinks = findAll('#' + this.staticId + ' .grid-product__buy-now');
        if (buyNowLinks.length > 0) {
            forEach(buyNowLinks, function (element) {
                var productId = element.getAttribute('data-product-id');
                StaticPageLoader.addStaticClickEvent(element, StaticPageLoader.openEcwidPage('product', {'id': productId}));
            });
        }

        var trackOrdersLink = findAll('#' + this.staticId + ' .footer__link--track-order');
        if (trackOrdersLink.length > 0) {
            forEach(trackOrdersLink, function (element) {
                StaticPageLoader.addStaticClickEvent(element, StaticPageLoader.openEcwidPage('account/orders'));
            });
        }

        var favoritesLink = findAll('#' + this.staticId + ' .footer__link--shopping-favorites');
        if (favoritesLink.length > 0) {
            forEach(favoritesLink, function (element) {
                StaticPageLoader.addStaticClickEvent(element, StaticPageLoader.openEcwidPage('account/favorites'));
            });
        }

        var shoppingCartLink = findAll('#' + this.staticId + ' .footer__link--shopping-cart');
        if (shoppingCartLink.length > 0) {
            forEach(shoppingCartLink, function (element) {
                StaticPageLoader.addStaticClickEvent(element, StaticPageLoader.openEcwidPage('cart'));
            });
        }

        var signInLink = findAll('#' + this.staticId + ' .footer__link--sigin-in');
        if (signInLink.length > 0) {
            forEach(signInLink, function (element) {
                StaticPageLoader.addStaticClickEvent(element, StaticPageLoader.openEcwidPage('signin'));
            });
        }

        var pagerButtonLinks = findAll('#' + this.staticId + ' .pager__button');
        if (pagerButtonLinks.length > 0) {
            forEach(pagerButtonLinks, function (element) {
                StaticPageLoader.addStaticClickEvent(element, StaticPageLoader.openEcwidPage('category', {
                    'id': 0,
                    'page': 2
                }));
            });
        }

        var pagerNumberLinks = findAll('#' + this.staticId + ' .pager__number');
        if (pagerNumberLinks.length > 0) {
            forEach(pagerNumberLinks, function (element) {
                var pageNumber = element.getAttribute('data-page-number');
                StaticPageLoader.addStaticClickEvent(element, StaticPageLoader.openEcwidPage('category', {
                    'id': 0,
                    'page': pageNumber
                }));
            });
        }
    },

    addStaticClickEvent: function (el, callback) {
        var x = 0,
            y = 0,
            dx = 0,
            dy = 0,
            isTap = false;

        if (this.isTouchDevice) {
            el.addEventListener('touchstart', function (e) {
                isTap = true;
                x = e.originalEvent.touches[0].clientX;
                y = e.originalEvent.touches[0].clientY;
                dx = 0;
                dy = 0;
            }).addEventListener('touchmove', function (e) {
                dx = e.originalEvent.changedTouches[0].clientX - x;
                dy = e.originalEvent.changedTouches[0].clientY - y;
            }).addEventListener('touchend', function (e) {
                if (isTap && Math.abs(dx) < 10 && Math.abs(dy) < 10) {
                    callback.bind(this)(e);
                }
            });
        }

        el.addEventListener('click', function (e) {
            if (!isTap) {
                callback.bind(this)(e);
            }
            else {
                isTap = false;
            }
        });
    },

    openEcwidPage: function (page, params) {
        return function (e) {
            e.preventDefault();
            // we must wait for Ecwid first page to be ready before changing it
            Ecwid.OnPageLoaded.add(function () {
                var staticHtml = find('#' + StaticPageLoader.staticId);
                if (!isVisible(staticHtml)) {
                    // if we've already switched to dynamic, we don't need to dispatch this event anymore
                    return;
                }
                Ecwid.openPage(page, params);
            });
        }
    },

    hideStorefront: function () {
        var dynamicEl = find('#' + this.dynamicId);
        // the dynamic div container must be visible while loading Ecwid,
        // so that the scripts could calculate available container width,
        // therefore we ensure the element is visible and hide it via zero-height trick
        dynamicEl.style.display = 'block';
        dynamicEl.style.overflowY = 'auto';
        dynamicEl.style.height = '0';
        dynamicEl.style.minHeight = '0';
        dynamicEl.style.maxHeight = '0';
    },

    showStorefront: function () {
        var dynamicEl = find('#' + this.dynamicId);
        // disable zero-height trick to show the storefront
        dynamicEl.style.height = '';
        dynamicEl.style.minHeight = '';
        dynamicEl.style.maxHeight = '';
    },

    hideStaticHtml: function () {
        var staticEl = find('#' + this.staticId);
        staticEl.style.opacity = 0;
        staticEl.style.display = 'none';
    },

    showStaticHtml: function () {
        var staticEl = find('#' + this.staticId);
        staticEl.style.opacity = 1;
    },

    switchToDynamicMode: function () {
        this.showStorefront();
        this.hideStaticHtml();
    },

    switchToDynamicModeDeferred: function () {
        // defer switching to dynamic to avoid blinking effect
        setTimeout(function () {
            StaticPageLoader.switchToDynamicMode();
        }, 0);
    }
};