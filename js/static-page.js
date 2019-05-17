var EcwidStaticPageLoader = {
    isTouchDevice: false,
    staticId: null,
    dynamicId: null,
    defaultCategoryId: null,

    find: function (selector) {
        return document.querySelector(selector);
    },

    findAll: function (selector) {
        return document.querySelectorAll(selector);
    },

    isVisible: function (elem) {
        return !!(elem.offsetWidth || elem.offsetHeight || elem.getClientRects().length);
    },

    forEach: function (elements, fn) {
        return Array.prototype.forEach.call(elements, fn);
    },

    isRootCaregory: function () {
        return window.location.hash === '' || window.location.hash.indexOf("#!/c/0/") !== -1;
    },

    onDocumentReady: function (fn) {
        if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading") {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    },

    processStaticHomePage: function (staticId, dynamicId, defaultCategoryId) {
        this.staticId = staticId;
        this.dynamicId = dynamicId;
        this.defaultCategoryId = defaultCategoryId;

        this.onDocumentReady(function () {
            if (!!('ontouchstart' in window)) {
                this.isTouchDevice = true;
                document.body.classList.add('touchable');
            }

            if (!EcwidStaticPageLoader.isRootCaregory()) {
                EcwidStaticPageLoader.hideStorefront();
                EcwidStaticPageLoader.switchToDynamicMode();
                return;
            }

            EcwidStaticPageLoader.hideStorefront();
            EcwidStaticPageLoader.showStaticHtml();
            EcwidStaticPageLoader.addStaticPageHandlers();

            Ecwid.OnPageLoad.add(function (openedPage) {
                var staticHtml = EcwidStaticPageLoader.find('#' + EcwidStaticPageLoader.staticId);
                if (!EcwidStaticPageLoader.isVisible(staticHtml)) {
                    // if we've already switched to dynamic, we don't need to dispatch this event anymore
                    return;
                }
                if (openedPage.type == "CART"
                    || openedPage.type == "ORDERS"
                    || openedPage.type == "FAVORITES"
                    || openedPage.type == "SIGN_IN") {
                    // static links from bottom of the page should be processed before page load event finishes,
                    // so that pre-opening scroll didn't make the page jump
                    EcwidStaticPageLoader.switchToDynamicMode();
                }
            });

            Ecwid.OnPageLoaded.add(function (openedPage) {
                var staticHtml = EcwidStaticPageLoader.find('#' + EcwidStaticPageLoader.staticId);
                if (!EcwidStaticPageLoader.isVisible(staticHtml)) {
                    // if we've already switched to dynamic, we don't need to dispatch this event anymore
                    return;
                }
                if (openedPage.type == "CATEGORY"
                    && openedPage.categoryId == EcwidStaticPageLoader.defaultCategoryId
                    && openedPage.offset == 0
                    && openedPage.sort == 'normal') {
                    // we don't need to dispatch root category loading,
                    // since our static contents covers it for the first time
                    return;
                }
                // other than that we must show opened page in dynamic view,
                // because static view contains only root category page
                EcwidStaticPageLoader.switchToDynamicModeDeferred();
            });
        });
    },

    addStaticPageHandlers: function () {
        var categoryLinks = EcwidStaticPageLoader.findAll('#' + this.staticId + ' .grid-category__card a');
        if (categoryLinks.length > 0) {
            EcwidStaticPageLoader.forEach(categoryLinks, function (element) {
                var categoryId = element.getAttribute('data-category-id');
                EcwidStaticPageLoader.addStaticClickEvent(element, EcwidStaticPageLoader.openEcwidPage('category', {'id': categoryId}));
            });
        }

        var productLinks = EcwidStaticPageLoader.findAll('#' + this.staticId + ' .grid-product a');
        if (productLinks.length > 0) {
            EcwidStaticPageLoader.forEach(productLinks, function (element) {
                var productId = element.getAttribute('data-product-id');
                EcwidStaticPageLoader.addStaticClickEvent(element, EcwidStaticPageLoader.openEcwidPage('product', {'id': productId}));
            });
        }

        var buyNowLinks = EcwidStaticPageLoader.findAll('#' + this.staticId + ' .grid-product__buy-now');
        if (buyNowLinks.length > 0) {
            EcwidStaticPageLoader.forEach(buyNowLinks, function (element) {
                var productId = element.getAttribute('data-product-id');
                EcwidStaticPageLoader.addStaticClickEvent(element, EcwidStaticPageLoader.openEcwidPage('product', {'id': productId}));
            });
        }


        var breadcrumbsLinks = EcwidStaticPageLoader.find('#' + this.staticId + ' .ec-breadcrumbs a');
        if (breadcrumbsLinks && breadcrumbsLinks.length > 0) {
            EcwidStaticPageLoader.forEach(breadcrumbsLinks, function (element) {
                var categoryId = element.getAttribute('data-category-id');
                if (categoryId !== EcwidStaticPageLoader.defaultCategoryId) {
                    EcwidStaticPageLoader.addStaticClickEvent(element, EcwidStaticPageLoader.openEcwidPage('category', {'id': categoryId}));
                }
            });
        }

        var orderByOptions = EcwidStaticPageLoader.find('#' + this.staticId + ' .grid__sort select');
        if (orderByOptions != null) {
            orderByOptions.onchange = function (e) {
                EcwidStaticPageLoader.openEcwidPage('category', {
                    'id': EcwidStaticPageLoader.defaultCategoryId,
                    'sort': orderByOptions.value
                }).bind(this)(e);
            }
        }

        var trackOrdersLink = EcwidStaticPageLoader.findAll('#' + this.staticId + ' .footer__link--track-order');
        if (trackOrdersLink.length > 0) {
            EcwidStaticPageLoader.forEach(trackOrdersLink, function (element) {
                EcwidStaticPageLoader.addStaticClickEvent(element, EcwidStaticPageLoader.openEcwidPage('account/orders'));
            });
        }

        var favoritesLink = EcwidStaticPageLoader.findAll('#' + this.staticId + ' .footer__link--shopping-favorites');
        if (favoritesLink.length > 0) {
            EcwidStaticPageLoader.forEach(favoritesLink, function (element) {
                EcwidStaticPageLoader.addStaticClickEvent(element, EcwidStaticPageLoader.openEcwidPage('account/favorites'));
            });
        }

        var shoppingCartLink = EcwidStaticPageLoader.findAll('#' + this.staticId + ' .footer__link--shopping-cart');
        if (shoppingCartLink.length > 0) {
            EcwidStaticPageLoader.forEach(shoppingCartLink, function (element) {
                EcwidStaticPageLoader.addStaticClickEvent(element, EcwidStaticPageLoader.openEcwidPage('cart'));
            });
        }

        var signInLink = EcwidStaticPageLoader.findAll('#' + this.staticId + ' .footer__link--sigin-in');
        if (signInLink.length > 0) {
            EcwidStaticPageLoader.forEach(signInLink, function (element) {
                EcwidStaticPageLoader.addStaticClickEvent(element, EcwidStaticPageLoader.openEcwidPage('signin'));
            });
        }

        var pagerButtonLinks = EcwidStaticPageLoader.findAll('#' + this.staticId + ' .pager__button');
        if (pagerButtonLinks.length > 0) {
            EcwidStaticPageLoader.forEach(pagerButtonLinks, function (element) {
                EcwidStaticPageLoader.addStaticClickEvent(element, EcwidStaticPageLoader.openEcwidPage('category', {
                    'id': EcwidStaticPageLoader.defaultCategoryId,
                    'page': 2
                }));
            });
        }

        var pagerNumberLinks = EcwidStaticPageLoader.findAll('#' + this.staticId + ' .pager__number');
        if (pagerNumberLinks.length > 0) {
            EcwidStaticPageLoader.forEach(pagerNumberLinks, function (element) {
                var pageNumber = element.getAttribute('data-page-number');
                EcwidStaticPageLoader.addStaticClickEvent(element, EcwidStaticPageLoader.openEcwidPage('category', {
                    'id': EcwidStaticPageLoader.defaultCategoryId,
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
                var staticHtml = EcwidStaticPageLoader.find('#' + EcwidStaticPageLoader.staticId);
                if (!EcwidStaticPageLoader.isVisible(staticHtml)) {
                    // if we've already switched to dynamic, we don't need to dispatch this event anymore
                    return;
                }
                Ecwid.openPage(page, params);
            });
        }
    },

    hideStorefront: function () {
        var dynamicEl = EcwidStaticPageLoader.find('#' + this.dynamicId);
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
        var dynamicEl = EcwidStaticPageLoader.find('#' + this.dynamicId);
        // disable zero-height trick to show the storefront
        dynamicEl.style.height = '';
        dynamicEl.style.minHeight = '';
        dynamicEl.style.maxHeight = '';
    },

    hideStaticHtml: function () {
        var staticEl = EcwidStaticPageLoader.find('#' + this.staticId);
        staticEl.style.opacity = 0;
        staticEl.style.display = 'none';
    },

    showStaticHtml: function () {
        var staticEl = EcwidStaticPageLoader.find('#' + this.staticId);
        staticEl.style.opacity = 1;
    },

    switchToDynamicMode: function () {
        this.showStorefront();
        this.hideStaticHtml();
    },

    switchToDynamicModeDeferred: function () {
        // defer switching to dynamic to avoid blinking effect
        setTimeout(function () {
            EcwidStaticPageLoader.switchToDynamicMode();
        }, 0);
    }

};