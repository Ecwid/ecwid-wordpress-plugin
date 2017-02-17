jQuery(document).ready(function() {
    var popup = function() {
        return jQuery('#ecwid-product-popup-content');
    };

    popup().data( 'defaultSortBy', 'ADDED_TIME_DESC' );

    jQuery('#insert-ecwid-product-button').click(function() {
        if (ecwidSpwParams && typeof ecwidSpwParams.no_token != 'undefined') {
            location.href='admin.php?page=ec-store&reconnect&reason=spw';
            return false;
        }

        changeTab('add-product');

        populateWidgetParams();
        setSearchParams({});

        if (getInitialSearchData()) {
            buildProductsTable(getInitialSearchData());
        } else {
            updateSearchParams();
        }

        popup().addClass('open');

    });

    jQuery(document).keydown(function(e) {
        if (e.keyCode == 27 && popup().hasClass('open')) {
            popup().removeClass('open');
            return false;
        }
    });

    jQuery('.media-modal-close', popup()).click(function() {
        popup().removeClass('open');
    });

    jQuery('.toolbar-link', popup()).click(function() {
        changeTab(jQuery(this).data('content'));
        return false;
    })

    var populateWidgetParams = function() {

        if (ecwidSpwParams && ecwidSpwParams.display) {
            jQuery('input[type=checkbox]', popup()).prop('checked', false);

            for (var i in ecwidSpwParams.display) {
                jQuery('input[type=checkbox][data-display-option=' + i + ']')
                    .prop('checked', true);
            }
            for (var i in ecwidSpwParams.attributes) {
                jQuery('input[type=checkbox][data-shortcode-attribute=' + i + ']')
                    .prop('checked', true);
            }
        }
    };

    var changeTab = function(tab) {
        jQuery('.media-menu .media-menu-item', popup()).removeClass('active');
        jQuery('.media-menu .media-menu-item[data-content=' + tab + ']', popup()).addClass('active');

        jQuery('.media-modal-content', popup()).attr('data-active-dialog', tab);
        jQuery('.media-menu', popup()).removeClass('visible');

        jQuery('.toolbar-link').show();
        jQuery('.toolbar-link[data-content=' + tab + ']', popup()).hide();
    }

    jQuery('.media-menu-item', popup()).click(function() {
        changeTab(jQuery(this).attr('data-content'));

        return false;
    });

    var closeTopMenuOnExternalClick = function(e) {
        jQuery('.media-menu').toggleClass('visible');
        popup().unbind('click', closeTopMenuOnExternalClick);
    }

    jQuery('h1', popup()).click(function(e) {
        e.stopPropagation();
        jQuery('.media-menu').toggleClass('visible');
        popup().click(closeTopMenuOnExternalClick);
    });


    jQuery('.media-button-select', popup()).click(function() {

        var shortcode = buildShortcode();

        if (tinymce.activeEditor && !tinymce.activeEditor.isHidden()) {
            tinymce.activeEditor.execCommand('mceInsertContent', false, shortcode);
        } else {

            getCursorPosition = function(el) {
                var pos = 0;
                if('selectionStart' in el) {
                    pos = el.selectionStart;
                } else if('selection' in document) {
                    el.focus();
                    var Sel = document.selection.createRange();
                    var SelLength = document.selection.createRange().text.length;
                    Sel.moveStart('character', -el.value.length);
                    pos = Sel.text.length - SelLength;
                }
                return pos;
            };

            var el = jQuery('#content');
            var cursorPosition = getCursorPosition(el.get(0));

            el.val(el.val().substr(0, cursorPosition) + shortcode + el.val().substr(cursorPosition));

        }

        saveParams();

        popup().removeClass('open');
    });

    var saveParams = function() {
        var params = {display: {}, attributes: {} };

        jQuery('input[type=checkbox][data-display-option]:checked').each(function(idx, el) {
            params.display[jQuery(el).data('display-option')] = 1;
        });

        jQuery('input[type=checkbox][data-shortcode-attribute]:checked').each(function(idx, el) {
            params.attributes[jQuery(el).data('shortcode-attribute')] = 1;
        });

        jQuery.getJSON(ajaxurl, {action: 'ecwid-save-spw-params', params: params});

        ecwidSpwParams.display = params.display;
        ecwidSpwParams.attributes = params.attributes;
    };

    var buildShortcode = function() {
        var params = {};

        product = getCurrentProduct();

        params.id = product.id;
        params.version = '2';
        params.display = [];

        jQuery('input[type=checkbox][data-display-option]:checked').each(function(idx, el) {
           params.display[params.display.length] = jQuery(el).data('display-option');
        });

        if (params.display.length == 0) {
            params.display = 'picture title price options addtobag';
        } else {
            params.display = params.display.join(' ');
        }

        jQuery('input[type=checkbox][data-shortcode-attribute]').each(function(idx, el) {
            params[jQuery(el).data('shortcode-attribute')] = jQuery(el).is(':checked') ? 1 : 0;
        });

        var params_order = ['id', 'display', 'version', 'show_border', 'show_price_on_button', 'center_align'];

        var shortcode = '[ecwid_product';

        for (var i = 0; i < params_order.length; i++) {
            shortcode += ' ' + params_order[i] + '="' + params[params_order[i]] + '"';
        }

        shortcode += ']';

        return shortcode;
    };

    var setCurrentProduct = function( product ) {
        popup().data('currentProduct', product);
        updateFormOnCurrentProduct();
    };

    var getCurrentProduct = function() {
        return popup().data('currentProduct');
    };

    var setInitialSearchData = function( data ) {
        popup().data('initialSearchData', data);
    };

    var getInitialSearchData = function() {
        return popup().data('initialSearchData');
    };

    var setSearchParams = function( params ) {
        if (typeof params.page == 'undefined') {
            params.page = 1;
        }
        popup().data('searchParams', params);
    };

    var getSearchParams = function () {
        var params = popup().data('searchParams');

        if (!params) {
            params = {page: 1, sortBy: popup().data('defaultSortBy')};
        }

        return params;
    };

    var updateFormOnCurrentProduct = function() {
        var product = getCurrentProduct();

        if (product) {
            jQuery( '.media-button-select', popup() ).removeClass( 'disabled' );
        } else {
            jQuery( '.media-button-select', popup() ).addClass( 'disabled' );
        }

    }

    var clickProduct = function() {

        if (jQuery('.empty-page', this).length > 0) {
            return;
        }

        if (jQuery(this).hasClass('selected-product')) {
            jQuery(this).closest('tbody').find('tr').removeClass('selected-product');
            setCurrentProduct(null);
        } else {
            jQuery(this).closest('tbody').find('tr').removeClass('selected-product');
            jQuery(this).addClass('selected-product');
            setCurrentProduct(jQuery(this).data('productData'));
        }
    };

    var ecwidSpwSearchProducts = function() {

        var data = {
            'action': 'ecwid-search-products'
        };

        var params = popup().data('searchParams');

        if (params) {
            if (params.keyword) {
                data.keyword = params.keyword;
            }

            if (params.sortBy) {
                data.sortBy = params.sortBy;
            }

            if (params.page) {
                data.page = params.page;
            }
        }

        jQuery('#search-submit').addClass('searching');

        jQuery.getJSON(ajaxurl, data, buildProductsTable);
    }

    var buildProductsTable = function(data) {
        if (Math.ceil(data.total / data.limit) < getSearchParams().page) {
            params = getSearchParams();
            params.page = 1;
            setSearchParams(params);
        }


        var enabledPageTemplate = wp.template( 'pagination-button-enabled' );
        var disabledPageTemplate = wp.template( 'pagination-button-disabled' );

        var totalPages = Math.ceil(data.total / data.limit);

        var prevPages = '';
        var nextPages = '';

        if (totalPages > 1) {
            if (getSearchParams() && getSearchParams().page == 1) {
                prevPages = disabledPageTemplate({symbol: '«'}) + disabledPageTemplate({symbol: '‹'});
            } else {
                prevPages = enabledPageTemplate({
                        'symbol': '«',
                        'name': 'first',
                        'label': ecwidSpwParams.labels.firstPage
                    }) + enabledPageTemplate({
                        'symbol': '‹',
                        'name': 'prev',
                        'label': ecwidSpwParams.labels.prevPage
                    });
            }

            if (getSearchParams().page >= Math.ceil(data.total / data.limit)) {
                nextPages = disabledPageTemplate({symbol: '›'}) + disabledPageTemplate({symbol: '»'});
            } else {
                nextPages = enabledPageTemplate({
                        'symbol': '›',
                        'name': 'next',
                        'label': ecwidSpwParams.labels.nextPage
                    }) + enabledPageTemplate({
                        'symbol': '»',
                        'name': 'last',
                        'label': ecwidSpwParams.labels.lastPage,
                        'page': Math.ceil(data.total / data.limit)
                    });
            }
        }

        var formTemplate = wp.template( 'add-product-form' );

        var tableTemplate = wp.template( 'products-list' );

        var tableHTML = tableTemplate();

        jQuery('.media-frame-content.ecwid-add-product.add-product').empty().append(
            formTemplate( {
                'tableHTML' : tableHTML,
                'page': data.offset / data.limit + 1,
                'total_pages': Math.ceil(data.total / data.limit),
                'total_items': data.total + ' items',
                'prev_pages': prevPages,
                'next_pages': nextPages
            })
        );

        if (data.total > 0) {
            for (var i = 0; i < data.items.length; i++) {
                addProduct(data.items[i]);
            }
        } else {
            showEmpty(params.keyword);
        }

        renderSearchParams();
        assignHandlers();
        setCurrentProduct(null);
        jQuery('#search-submit').removeClass('searching');

        if (totalPages <= 1) {
            jQuery('.tablenav.bottom', popup()).hide();
        }

        if (!getInitialSearchData()) {
            setInitialSearchData(data);
        }
    }


    var addProduct = function(productData) {
        var productTemplate = wp.template('product-in-list');

        var product = productTemplate(
            {'name': productData.name, 'image_url': productData.thumb, 'sku': productData.sku, 'id': productData.id}
        );

        jQuery('.wp-list-table.products tbody').append(product);
        jQuery('#product-' + productData.id).data('productData', productData);
    };

    var addTable = function() {
        tableTemplate = wp.template( 'products-list' );

        jQuery( '.ecwid-add-product.add-product' ).append(tableTemplate());
    };

    var showEmpty = function(term) {
        emptyTemplate = wp.template( 'no-products' );

        jQuery( '.ecwid-add-product.add-product .wp-list-table.products tbody' ).append(emptyTemplate({term:term}));

        jQuery( '.tablenav,.wp-list-table.products thead', popup()).hide();
    };

    var updateSearchParams = function(newParams) {
        var params = popup().data('searchParams');

        if (!params) {
            params = {};
        }
        for (var i in newParams) {
            if (newParams.hasOwnProperty(i)) {
                params[i] = newParams[i];
            }
        }

        popup().data('searchParams', params);

        ecwidSpwSearchProducts();
    };

    var renderSearchParams = function() {
        var searchParams =  popup().data('searchParams');

        if (!searchParams) {
            searchParams = {};
        }

        if (typeof(searchParams.keyword) != 'undefined') {
            jQuery('#product-search-input').val(searchParams.keyword);
        }

        if (typeof(searchParams.sortBy) != 'undefined') {
            if (searchParams.sortBy == 'NAME_ASC') {
                jQuery('#name').addClass('sorted asc');

            } else if (searchParams.sortBy == 'NAME_DESC') {
                jQuery('#name').addClass('sorted desc');
            } else if (searchParams.sortBy == 'SKU_ASC') {
                jQuery('#sku').addClass('sorted asc');
            } else if (searchParams.sortBy == 'SKU_DESC') {
                jQuery('#sku').addClass('sorted desc');
            }
        }
    }


    var renderPagination = function() {
        if (typeof(searchParams.page != 'undefined')) {
            jQuery('#current-page-selector').val(searchParams.page);
            if (searchParams.page > 1) {

            }
        }
    }


    var assignHandlers = function() {

        jQuery('.wp-list-table.products tr').click(clickProduct);

        jQuery('#search-submit').click(function() {
            updateSearchParams({keyword: jQuery('#product-search-input').val(), page: 1});

            return false;
        });

        jQuery('#name a').click(function() {

            var column = jQuery(this).closest('.manage-column');
            var newSort = '';
            if (column.hasClass('asc')) {
                newSort = 'NAME_DESC';
            } else if (column.hasClass('desc')) {
                newSort = popup().data('defaultSort');
            } else {
                newSort = 'NAME_ASC';
            }

            updateSearchParams({'sortBy': newSort});

            return false;
        });

        jQuery('#sku a').click(function() {

            var column = jQuery(this).closest('.manage-column');
            var newSort = '';
            if (column.hasClass('asc')) {
                newSort = 'SKU_DESC';
            } else if (column.hasClass('desc')) {
                newSort = popup().data('defaultSort');
            } else {
                newSort = 'SKU_ASC';
            }

            updateSearchParams({'sortBy': newSort});

            return false;
        });

        jQuery('.pagination-links .prev-page', popup()).click(function() {
            updateSearchParams({'page': getSearchParams().page - 1});

            return false;
        });

        jQuery('.pagination-links .next-page', popup()).click(function() {
            updateSearchParams({'page': getSearchParams().page + 1});

            return false;
        });

        jQuery('.pagination-links .first-page', popup()).click(function() {
            updateSearchParams({'page': 1});

            return false;
        });

        jQuery('.pagination-links .last-page', popup()).click(function() {
            updateSearchParams({'page': jQuery(this).data('page')});

            return false;
        });

        jQuery('#ecwid-reset-search').click(function() {
            setSearchParams({});
            buildProductsTable(getInitialSearchData());
        });
    };

    updateSearchParams();
});

ecwidRenderCheckboxOption = function(data) {

    if (!this.template) {
        this.template = wp.template( 'checkbox-option' );
    }

    if (data.section == 'display-options') {
        var name = data.displayOptionName;
        if (!name) {
            name = data.name;
        }
        data.additionalAttributes = 'data-display-option="' + name + '"';
    } else if (data.section == 'shortcode-attributes') {
        var name = data.name;
        data.additionalAttributes = 'data-shortcode-attribute="' + data.name + '"';
    }


    if (!this.nextTarget || this.nextTarget == 'right') {
        this.nextTarget = 'left';
    } else {
        this.nextTarget = 'right';
    }

    jQuery('#ecwid-product-popup-content .widget-settings.' + data.section + ' .widget-settings__' + this.nextTarget)
        .append(this.template(data));
}
