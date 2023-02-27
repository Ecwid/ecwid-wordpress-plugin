(function () {
    var isTouchDevice = false;
    var staticId = null;
    var staticContentClass = 'static-content';
    var dynamicId = null;
    var ecwidPageOpened = false;
    var autoSwitchStaticToDynamicWhenReady = false;
    var autoSwitchStaticToDynamicWhenReadyDefault = false;
    var invisibleDynamicContainerStyle = "display: block !important; height: 0 !important; max-height: 0 !important; min-height: 0 !important; overflow-y: auto !important; margin: 0 !important; padding: 0 !important;";
    var mainCategoryId = 0;
    var initialCategoryOffset = 0;

    function find(selector) {
        return document.querySelector(selector);
    }

    function isDynamicMode() {
        function isVisible(elem) {
            return !!(elem.offsetWidth || elem.offsetHeight || elem.getClientRects().length);
        }

        var staticHtml = find('#' + staticId);
        return !staticHtml || !isVisible(staticHtml);
    }

    function isRootCategory() {
        var urlHash = window.location.hash;
        return urlHash === '' || (urlHash.indexOf("#!/c/0/") === -1 && urlHash.indexOf("#!//c/0/") === -1);
    }

    function isHashbangPage() {
        var urlHash = window.location.hash;
        return urlHash !== '' && urlHash.indexOf("#!/") >= 0
    }

    function loadScriptJs(onScriptJsLoadedCallback) {
        var scriptJs = document.createElement('script');
        scriptJs.src = ec.storefront.staticPages.lazyLoading.scriptJsLink;
        if (scriptJs.readyState) {
            scriptJs.onreadystatechange = function () {
                if (scriptJs.readyState === 'loaded' || scriptJs.readyState === 'complete') {
                    onScriptJsLoadedCallback();
                }
            };
        } else {
            scriptJs.onload = onScriptJsLoadedCallback;
        }
        var dynamicEl = find("#" + ec.storefront.staticPages.dynamicContainerID);
        dynamicEl.appendChild(scriptJs);
    };

    function processStaticHomePage() {

        window.ec = window.ec || {};
        window.ec.storefront = window.ec.storefront || {};
        window.ec.storefront.staticPages = window.ec.storefront.staticPages || {};

        window.ec.storefront.staticPages.switchStaticToDynamic = switchToDynamicMode;

        function onDocumentReady(fn) {
            if (document.attachEvent ? document.readyState === "complete" : document.readyState !== "loading") {
                fn();
            } else {
                document.addEventListener('DOMContentLoaded', fn);
            }
        }

        onDocumentReady(function () {
            var staticStorefrontEnabled = window.ec.storefront.staticPages.staticStorefrontEnabled || false;

            if (staticStorefrontEnabled !== true) {
                return;
            }

            staticId = ec.storefront.staticPages.staticContainerID;
            dynamicId = ec.storefront.staticPages.dynamicContainerID;
            if (!staticId || !document.querySelector("#" + staticId)) {
                if (!!console) {
                    console.warn("Static storefront is enabled, but no staticContainerID is provided or container is not present");
                }
                return;
            }
            if (!dynamicId || !document.querySelector("#" + dynamicId)) {
                if (!!console) {
                    console.warn("Static storefront is enabled, but no dynamicContainerID is provided or container is not present");
                }
                return;
            }

            if (window.location.hash.indexOf("#!/c/0") !== -1) {
                var element = document.querySelector("#" + staticId);
                element.scrollIntoView(true);
            }

            if (!!('ontouchstart' in window)) {
                isTouchDevice = true;
                document.body.classList.add('touchable');
            }

            var needDisableLazyLoading = false;
            if (ecwidLoaded() || isHashbangPage()) {
                needDisableLazyLoading = true;
            }

            if (needDisableLazyLoading && typeof ec.storefront.staticPages.lazyLoading !== "undefined") {
                var onScriptJsLoadedCallback = function () {
                    xProductBrowser.apply(this, ec.storefront.staticPages.lazyLoading.xProductBrowserArguments);
                }

                loadScriptJs(onScriptJsLoadedCallback);
            }

            if (!needDisableLazyLoading && typeof ec.storefront.staticPages.lazyLoading !== "undefined") {
                if (typeof ec.storefront.staticPages.lazyLoading.scriptJsLink === "undefined") {
                    if (!!console) {
                        console.warn("Storefront lazy loading is enabled, but no scriptJsLink is provided");
                    }
                    return;
                }
                if (typeof ec.storefront.staticPages.lazyLoading.xProductBrowserArguments === "undefined") {
                    if (!!console) {
                        console.warn("Storefront lazy loading is enabled, but no xProductBrowser arguments are provided");
                    }
                    return;
                }

                toggleLazyLoadingEvents(true);

                var lazyLoading = true;

                function lazyLoadingEventHandler() {
                    if (typeof lazyLoading === 'undefined') {
                        if (!!console) {
                            console.warn("Unable to fetch script.js outside of lazy loading mode");
                        }
                    } else {
                        toggleLazyLoadingEvents(false);
                        loadScriptJs(lazyInitDynamic);
                    }
                }

                var lazyInitDynamic = function () {
                    xProductBrowser.apply(this, ec.storefront.staticPages.lazyLoading.xProductBrowserArguments);

                    setupEcwidWhenLoaded();
                }

                function toggleLazyLoadingEvents(add) {
                    var staticDivEventsForLazyLoading = ['mousedown', 'mouseup', 'mousemove', 'contextmenu', 'keydown', 'keyup'];
                    var staticDivTouchEventsForLazyLoading = ['touchstart', 'touchend', 'touchcancel', 'touchmove'];

                    var toggleEvent = function (el, add, event) {
                        if (add) {
                            el.addEventListener(event, lazyLoadingEventHandler, { passive: true });
                        } else {
                            el.removeEventListener(event, lazyLoadingEventHandler);
                        }
                    }

                    //var staticEl = find('#' + staticId);
                    if (isTouchDevice) {
                        staticDivTouchEventsForLazyLoading.forEach(
                            function applyEvent(event) {
                                toggleEvent(document, add, event);
                            }
                        );
                    } else {
                        staticDivEventsForLazyLoading.forEach(
                            function applyEvent(event) {
                                toggleEvent(document, add, event);
                            }
                        );
                    }
                }
            }

            var mainCategoryIdFromConfig = ec.storefront.staticPages.mainCategoryId;
            if (mainCategoryIdFromConfig) {
                mainCategoryId = mainCategoryIdFromConfig;
            }


            var autoSwitchStaticToDynamicWhenReadyFromConfig = ec.storefront.staticPages.autoSwitchStaticToDynamicWhenReady;
            if (autoSwitchStaticToDynamicWhenReadyFromConfig) {
                autoSwitchStaticToDynamicWhenReady = autoSwitchStaticToDynamicWhenReadyFromConfig;
            } else {
                autoSwitchStaticToDynamicWhenReady = autoSwitchStaticToDynamicWhenReadyDefault;
            }

            if (ec.storefront.staticPages.initialCategoryOffset) {
                initialCategoryOffset = ec.storefront.staticPages.initialCategoryOffset;
            }

            hideStorefront();
            showStaticHtml();

            window.ec.config = window.ec.config || {};

            if (!isRootCategory()) {
                hideStorefront();
                switchToDynamicMode();
                return;
            }

            if (!autoSwitchStaticToDynamicWhenReady) {
                addStaticPageHandlers();
            }

            function setupAfterEcwidLoaded() {

                const event = document.createEvent('Event');
                event.initEvent('setupAfterEcwidLoaded', true, false);
                document.dispatchEvent(event);

                var cartWidgets = document.getElementsByClassName('ec-cart-widget');
                if (cartWidgets.length > 0) {
                    Ecwid.init();
                }

                if (!window.needLoadEcwidAsync && typeof Ecwid._onComplete !== undefined) {
                    Ecwid._onComplete();
                }

                // if a store is opened for a client, then the storeClosed won't be true
                // if a store is opened for a client and we've uploaded a closed banner, then we check it in dynamic
                Ecwid.OnAPILoaded.add(function () {
                    if (isDynamicMode()) {
                        // if we've already switched to dynamic, we don't need to dispatch this event anymore
                        return;
                    }
                    var storeClosed = window.ecwid_initial_data.data.storeClosed;
                    var storeClosedWrapper = document.querySelectorAll('.ecwid-maintenance-wrapper');
                    var storeNotClosedAndWrapperExists = !storeClosed && storeClosedWrapper.length > 0;

                    if (storeClosed
                        || storeNotClosedAndWrapperExists
                        || hasEcwidMessages()
                    ) {
                        switchToDynamicMode();
                    }
                });

                Ecwid.OnPageLoad.add(function (openedPage) {
                    if (isDynamicMode()) {
                        // if we've already switched to dynamic, we don't need to dispatch this event anymore
                        return;
                    }
                    if (openedPage.type === "CART"
                        || openedPage.type === "ORDERS"
                        || openedPage.type === "FAVORITES"
                        || openedPage.type === "SIGN_IN"
                        || openedPage.type === "RESET_PASSWORD"
                        || hasEcwidMessages()
                    ) {
                        // static links from bottom of the page should be processed before page load event finishes,
                        // so self pre-opening scroll didn't make the page jump
                        switchToDynamicMode();
                    }
                });

                addOnPageLoadedCallback(function (openedPage) {
                    if (isDynamicMode()) {
                        // if we've already switched to dynamic, we don't need to dispatch this event anymore
                        return;
                    }

                    if (autoSwitchStaticToDynamicWhenReady) {
                        switchToDynamicWhenReadyWithRetries(10);
                        return;
                    }

                    if (!ecwidPageOpened
                        && openedPage.type === "CATEGORY"
                        && openedPage.categoryId === mainCategoryId
                        && openedPage.offset === initialCategoryOffset) {

                        // we don't need to dispatch root category loading,
                        // since our static contents covers it for the first time
                        return;
                    }
                    // other than self we must show opened page in dynamic view,
                    // because static view contains only root category page
                    switchToDynamicMode();
                }, 0);
            }

            function switchToDynamicWhenReadyWithRetries(retry) {
                if (retry <= 0) {
                    switchToDynamicMode();
                    return;
                }

                var allImagesLoaded = allImagesLoadedInDynamicMarkup();
                if (!allImagesLoaded) {
                    setTimeout(function () {
                        switchToDynamicWhenReadyWithRetries(retry - 1);
                    }, 100);
                    return
                }

                switchToDynamicMode();
            }

            function allImagesLoadedInDynamicMarkup() {
                if (!dynamicId) {
                    return true;
                }

                try {
                    var firstNotLoadedCategory = document.querySelector('#' + dynamicId + ' .grid-category--loading');
                    if (firstNotLoadedCategory != null) {
                        return false;
                    }

                    var firstNotLoadedProduct = document.querySelector('#' + dynamicId + ' .grid-product--loading');
                    if (firstNotLoadedProduct != null) {
                        return false;
                    }
                } catch (e) {
                }

                return true;
            }

            function ecwidLoaded() {
                return typeof Ecwid !== "undefined" && !!Ecwid.OnAPILoaded && !!Ecwid.OnAPILoaded.add;
            }

            function hasEcwidMessages() {
                // If the merchant has at least one custom label, then switch to dynamics regardless of the page type or click
                return !!window.ecwidMessages && Object.keys(window.ecwidMessages).length > 0;
            }

            var setupEcwidWhenLoaded = function () {
                if (ecwidLoaded()) {
                    setupAfterEcwidLoaded();
                } else {
                    var setupIntervalId = setInterval(function () {
                        if (ecwidLoaded()) {
                            setupAfterEcwidLoaded();
                            clearInterval(setupIntervalId);
                        }
                    }, 100);
                }
            };

            if (typeof lazyLoading === 'undefined') {
                // Follow legacy dynamic store initialization flow
                setupEcwidWhenLoaded();
            }
        });
    }

    function addStaticPageHandlers() {
        function addClickHandlers(selector, elementProcessor) {
            var elements = document.querySelectorAll(selector);
            for (var i = 0; i < elements.length; i++) {
                elementProcessor(elements[i]);
            }
        }

        addClickHandlers('#' + staticId + ' .ec-breadcrumbs a', function (element) {
            var categoryId = element.getAttribute('categoryId');
            if (categoryId !== mainCategoryId) {
                addStaticClickEvent(element, openEcwidPage('category', { 'id': categoryId }));
            }
        });

        var orderByOptions = document.querySelector('#' + staticId + ' .grid__sort select');
        if (!!orderByOptions) {
            orderByOptions.addEventListener('change', function (event) {
                openEcwidPage('category', {
                    'id': mainCategoryId,
                    'sort': orderByOptions.value
                })(event);
            });
        }

        addClickHandlers('#' + staticId + ' .grid__sort .grid-sort__item--filter', function (element) {
            addStaticClickEvent(element, function () {
                addOnPageLoadedCallback(function () {
                    if (isDynamicMode()) {
                        return;
                    }
                    switchToDynamicMode();
                    Ecwid.showProductFilters();
                }, 0);
            });
        });

        addClickHandlers('#' + staticId + ' .grid-category__card a', function (element) {
            var categoryId = element.getAttribute('data-category-id');
            addStaticClickEvent(element, openEcwidPage('category', { 'id': categoryId }));
        });

        addClickHandlers('#' + staticId + ' .grid-product:not(.grid-product--view-all) a:not(.open-external-url)', function (element) {
            var productId = element.getAttribute('data-product-id');
            addStaticClickEvent(element, openEcwidPage('product', { 'id': productId }));
        });

        addClickHandlers('#' + staticId + ' .grid-product:not(.grid-product--view-all) .grid-product__wrap[data-product-id]', function (element) {
            var productId = element.getAttribute('data-product-id');
            addStaticClickEvent(element, openEcwidPage('product', { 'id': productId }));
        });

        addClickHandlers('#' + staticId + ' .grid-product--view-all a', function (element) {
            var categoryId = element.getAttribute('data-category-id');
            addStaticClickEvent(element, openEcwidPage('category', { 'id': categoryId }));
        })

        addClickHandlers('#' + staticId + ' .grid-product__buy-now', function (element) {
            var productId = element.getAttribute('data-product-id');
            addStaticClickEvent(element, openEcwidPage('product', { 'id': productId }));
        });

        addClickHandlers('#' + staticId + ' .footer__link--gift-card', function (element) {
            var productId = element.getAttribute('data-product-id');
            addStaticClickEvent(element, openEcwidPage('product', { 'id': productId }));
        });

        addClickHandlers('#' + staticId + ' .footer__link--all-products', function (element) {
            addStaticClickEvent(element, openEcwidPage('search'));
        });

        addClickHandlers('#' + staticId + ' .footer__link--track-order', function (element) {
            addStaticClickEvent(element, openEcwidPage('account/orders'));
        });

        addClickHandlers('#' + staticId + ' .footer__link--shopping-favorites', function (element) {
            addStaticClickEvent(element, openEcwidPage('account/favorites'));
        });

        addClickHandlers('#' + staticId + ' .footer__link--shopping-cart', function (element) {
            addStaticClickEvent(element, openEcwidPage('cart'));
        });

        addClickHandlers('#' + staticId + ' .footer__link--sigin-in', function (element) {
            addStaticClickEvent(element, openEcwidPage('signin'));
        });

        addClickHandlers('#' + staticId + ' .footer__link--my-account', function (element) {
            addStaticClickEvent(element, openEcwidPage('account/settings'));
        });

        addClickHandlers('#' + staticId + ' .pager__button', function (element) {
            var pageNumber = element.getAttribute('data-page-number') || 2;
            addStaticClickEvent(element, openEcwidPage('category', {
                'id': mainCategoryId,
                'page': pageNumber
            }));
        });

        addClickHandlers('#' + staticId + ' .pager__number', function (element) {
            var pageNumber = element.getAttribute('data-page-number');
            addStaticClickEvent(element, openEcwidPage('category', {
                'id': mainCategoryId,
                'page': pageNumber
            }));
        });

        addClickHandlers('#' + staticId + ' .open-external-url', function (element) {
            addStaticClickEvent(element, function (e) {
                e.stopPropagation();
            });
        });
    }

    function addStaticClickEvent(el, callback) {
        var x = 0,
            y = 0,
            dx = 0,
            dy = 0,
            isTap = false;

        if (isTouchDevice) {
            el.addEventListener('touchstart', function (e) {
                isTap = true;
                x = e.touches[0].clientX;
                y = e.touches[0].clientY;
                dx = 0;
                dy = 0;
            }, { passive: true });
            el.addEventListener('touchmove', function (e) {
                dx = e.changedTouches[0].clientX - x;
                dy = e.changedTouches[0].clientY - y;
            }, { passive: true });
            el.addEventListener('touchend', function (e) {
                if (isTap && Math.abs(dx) < 10 && Math.abs(dy) < 10) {
                    callback(e);
                }
            });
        }

        el.addEventListener('click', function (e) {
            if (!isTap) {
                callback(e);
            } else {
                isTap = false;
            }
        });
    }

    function openEcwidPage(page, params) {
        return function (e) {
            if (isCtrlClickOnProductEvent(page, e)) {
                // In case product element in grid was clicked with ctrl/meta key, do not invoke e.preventDefault()
                // and do nothing. Event will be handled and processed by default ctrl/meta + click handler on
                // underlying <a> element. New background tab with product page will be opened.
                return;
            }

            e.preventDefault();
            // we must wait for Ecwid first page to be ready before changing it
            addOnPageLoadedCallback(function () {
                if (isDynamicMode() && ecwidPageOpened) {
                    // if we've already switched to dynamic, we don't need to dispatch this event anymore
                    return;
                }
                var onClickCallback = window.ec.storefront.staticPages.onClickCallback;
                if (!autoSwitchStaticToDynamicWhenReady && onClickCallback) {
                    onClickCallback();
                }
                ecwidPageOpened = true;
                var element = find('#' + staticId + " .ec-wrapper");
                if (!!element) {
                    elementsClassListAction([element], function (list) {
                        list.add("ec-wrapper--transition");
                    });
                }
                Ecwid.openPage(page, params);
            }, 0);
        }
    }

    function isCtrlClickOnProductEvent(page, event) {
        return page === 'product' && (event.ctrlKey || event.metaKey)
    }

    function addOnPageLoadedCallback(callback, attempt) {
        // let's wait for the Ecwid environment to be loaded for up to 2000 milliseconds
        if (attempt >= 40) {
            if (!!console) {
                console.warn("failed to add Ecwid.OnPageLoaded callback");
            }
            return;
        }

        if (typeof (Ecwid) == 'object' && typeof (Ecwid.OnPageLoaded) == 'object') {
            Ecwid.OnPageLoaded.add(callback);
        } else {
            setTimeout(function () {
                addOnPageLoadedCallback(callback, attempt + 1);
            }, 50);
        }
    }

    function hideStorefront() {
        var dynamicEl = find('#' + dynamicId);
        var currentStyleAttribute = dynamicEl.getAttribute("style") || "";

        dynamicEl.setAttribute("style", currentStyleAttribute + invisibleDynamicContainerStyle);
    }

    function showStorefront() {
        var dynamicEl = find('#' + dynamicId);
        // disable zero-height trick to show the storefront
        dynamicEl.style.height = "";
        dynamicEl.style.maxHeight = "";
        dynamicEl.style.minHeight = "";
        dynamicEl.style.overflowY = "";
        dynamicEl.style.margin = "";
        dynamicEl.style.padding = "";
        dynamicEl.style.display = "block";
    }

    function hideStaticHtml() {
        var staticEl = find('#' + staticId);
        if (!!staticEl) {
            staticEl.style.opacity = 0;
            staticEl.style.display = 'none';
        }
    }

    function showStaticHtml() {
        var element = find('#' + staticId + " ." + staticContentClass);
        if (!!element) {
            element.style.opacity = 1;
        }
    }

    function switchToDynamicMode() {
        requestAnimationFrame(function () {
            removeClassAnimationForAutoSwitchToDynamic();
            showStorefront();
            hideStaticHtml();

            var staticEl = find('#' + staticId);
            if (staticEl && staticEl.parentNode) {
                staticEl.parentNode.removeChild(staticEl);
            }
            var switchToDynamicCallback = window.ec.storefront.staticPages.switchToDynamicCallback;

            var element = find('#' + staticId + " .ec-wrapper");
            if (!!element) {
                elementsClassListAction([element], function (list) {
                    list.remove("ec-wrapper--transition");
                });
            }
            if (!autoSwitchStaticToDynamicWhenReady && switchToDynamicCallback) {
                switchToDynamicCallback();
            }
        });
    }

    function removeClassAnimationForAutoSwitchToDynamic() {
        if (!autoSwitchStaticToDynamicWhenReady) {
            return;
        }
        var wrapers = document.querySelectorAll('.ec-wrapper--animated-transitions');
        var arrWrapers = Array.prototype.slice.call(wrapers);
        elementsClassListAction(arrWrapers, function (list) {
            list.remove('ec-wrapper--animated-transitions');
        });
    }

    function elementsClassListAction(elements, callback) {
        if (!(Array.isArray(elements))) {
            return;
        }
        for (var key in elements) {
            var list = elements[key].classList;
            if (typeof list != 'undefined') {
                callback(list);
            }
        }
    }

    processStaticHomePage();

    function forceDynamicLoadingIfRequired() {
        if (typeof ec.storefront.staticPages.lazyLoading === "undefined") {
            if (!!console) {
                console.warn("Storefront lazy loading is not enabled to switch in dynamic mode");
            }
            return;
        }
        if (typeof ec.storefront.staticPages.lazyLoading.scriptJsLink === "undefined") {
            if (!!console) {
                console.warn("No scriptJsLink is provided to switch in dynamic mode");
            }
            return;
        }
        if (typeof ec.storefront.staticPages.lazyLoading.xProductBrowserArguments === "undefined") {
            if (!!console) {
                console.warn("No xProductBrowser arguments are provided to switch in dynamic mode");
            }
            return;
        }

        var staticContainer = document.getElementById(ec.storefront.staticPages.staticContainerID);

        var rootCategory = isRootCategory();
        if (!rootCategory) {
            while (staticContainer.lastChild) {
                staticContainer.lastChild.remove();
            }

            var onScriptJsLoadedCallback = function () {
                xProductBrowser.apply(this, ec.storefront.staticPages.lazyLoading.xProductBrowserArguments);
            }

            loadScriptJs(onScriptJsLoadedCallback);
        } else {
            staticContainer.style.height = "";
            staticContainer.style.maxHeight = "";
            staticContainer.style.minHeight = "";
            staticContainer.style.overflowY = "";
        }
    }

    window.ec = window.ec || {};
    window.ec.storefront = window.ec.storefront || {};
    window.ec.storefront.staticPages = window.ec.storefront.staticPages || {};
    window.ec.storefront.staticPages.forceDynamicLoadingIfRequired = forceDynamicLoadingIfRequired;
})();