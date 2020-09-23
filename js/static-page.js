(function () {
    var isTouchDevice = false;
    var staticId = null;
    var staticContentClass = 'static-content';
    var dynamicId = null;
    var ecwidPageOpened = false;
    var autoSwitchStaticToDynamicWhenReady = false;
    var autoSwitchStaticToDynamicWhenReadyDefault = false;
    var invisibleDynamicContainerStyle = "display: block !important; height: 0 !important; max-height: 0 !important; min-height: 0 !important; overflow-y: auto !important;";
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

    function processStaticHomePage() {

        window.ec = window.ec || {};
        window.ec.storefront = window.ec.storefront || {};
        window.ec.storefront.staticPages = window.ec.storefront.staticPages || {};


        window.ec.storefront.staticPages.switchStaticToDynamic = switchToDynamicMode;

        function isRootCategory() {
            return window.location.hash === '' || window.location.hash.indexOf("#!/c/0/") !== -1;
        }

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
            window.ec.config.navigation_scrolling = "DISABLED";
            if (!!('ontouchstart' in window)) {
                isTouchDevice = true;
                document.body.classList.add('touchable');
            }

            if (!isRootCategory()) {
                hideStorefront();
                switchToDynamicMode();
                return;
            }

            addStaticPageHandlers();

            function setupAfterEcwidLoaded() {

                // если магазин не закрыт для клиента, то в storeClosed не будет true
                // если магазин не закрыт для клиента и мы загрузили закрытую плашку проверим это в динамике
                Ecwid.OnAPILoaded.add(function () {
                    var storeClosed = window.ecwid_initial_data.data.storeClosed;
                    var storeClosedWrapper = document.querySelectorAll('.ecwid-maintenance-wrapper');
                    var storeNotClosedAndWrapperExists = !storeClosed && storeClosedWrapper.length > 0;

                    if (!isDynamicMode() && (storeClosed || storeNotClosedAndWrapperExists)) {
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
                        || openedPage.type === "RESET_PASSWORD") {
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
                return typeof Ecwid != 'undefined' && !!Ecwid && !!Ecwid.OnAPILoaded && !!Ecwid.OnAPILoaded.add;
            }

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
                addStaticClickEvent(element, openEcwidPage('category', {'id': categoryId}));
            }
        });

        var orderByOptions = document.querySelector('#' + staticId + ' .grid__sort select');
        if (!!orderByOptions) {
            orderByOptions.addEventListener("change", function (event) {
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
            addStaticClickEvent(element, openEcwidPage('category', {'id': categoryId}));
        });

        addClickHandlers('#' + staticId + ' .grid-product:not(.grid-product--view-all) a', function (element) {
            var productId = element.getAttribute('data-product-id');
            addStaticClickEvent(element, openEcwidPage('product', {'id': productId}));
        });

        addClickHandlers('#' + staticId + ' .grid-product:not(.grid-product--view-all) .grid-product__wrap[data-product-id]', function (element) {
            var productId = element.getAttribute('data-product-id');
            addStaticClickEvent(element, openEcwidPage('product', {'id': productId}));
        });

        addClickHandlers('#' + staticId + ' .grid-product--view-all a' , function (element) {
            var categoryId = element.getAttribute('data-category-id');
            addStaticClickEvent(element, openEcwidPage('category', {'id': categoryId}));
        })

        addClickHandlers('#' + staticId + ' .grid-product__buy-now', function (element) {
            var productId = element.getAttribute('data-product-id');
            addStaticClickEvent(element, openEcwidPage('product', {'id': productId}));
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
            });
            el.addEventListener('touchmove', function (e) {
                dx = e.changedTouches[0].clientX - x;
                dy = e.changedTouches[0].clientY - y;
            });
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
            e.preventDefault();
            // we must wait for Ecwid first page to be ready before changing it
            addOnPageLoadedCallback(function () {
                if (isDynamicMode()) {
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
                    elementsClassListAction([element], function(list) {
                        list.add("ec-wrapper--transition");
                    });
                }
                Ecwid.openPage(page, params);
            }, 0);
        }
    }

    function addOnPageLoadedCallback(callback, attempt) {
        // Иногда при загрузке витрины (статической версии) на странице еще не определен Ecwid.OnPageLoaded.
        // Надо подождать пока он появится, отложенно отработать клик пользователя (например открыть детали товара).
        // Время ретраев - 2 секунды. 50 ms * 40 попыток = 2000 ms.
        // Время выбрано по времени загрузки script.js https://graphweb.ecwid.com:3000/d/000000007/ecwid-performance-metrics
        // 99 перцентиль иногда поднимается до 1.5 секунд. Сделал небольшой запас

        if (attempt >= 40) {
            if (!!console) {
                console.warn("failed to add Ecwid.OnPageLoaded callback");
            }
            return;
        }

        if (typeof(Ecwid) == 'object' && typeof(Ecwid.OnPageLoaded) == 'object') {
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
                elementsClassListAction([element], function(list) {
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
        elementsClassListAction(arrWrapers, function(list) {
            list.remove('ec-wrapper--animated-transitions');
        });
    }

    function elementsClassListAction(elements, callback) {
        if (!(Array.isArray(elements))) {
            return;
        }
        for (var key in elements) {
            var list = elements[key].classList;
            if (typeof list != "undefined") {
                callback(list);
            }
        }
    }

    processStaticHomePage();

})();
