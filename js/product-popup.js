jQuery(document).ready(function() {
    var popup = function() {
        return jQuery('#ecwid-product-popup-content');
    };

    popup().data( 'defaultSortBy', 'ADDED_TIME_DESC' );

    jQuery('#insert-ecwid-product-button').click(function() {
        if (typeof ecwidProductWidgetParams != 'undefined' && typeof ecwidProductWidgetParams.no_token != 'undefined') {
            location.href='admin.php?page=ecwid&reconnect&reason=spw';
            return false;
        }

        popup().addClass('open');
        populateWidgetParams();
    });

    jQuery('.media-modal-close', popup()).click(function() {
        popup().removeClass('open');
    });

    populateWidgetParams = function() {

        if (typeof ecwidProductWidgetParams != 'undefined') {
            for (var i in ecwidProductWidgetParams.display) {
                jQuery('input[type=checkbox][data-display-option=' + i + ']')
                    .prop('checked', true);
            }
            for (var i in ecwidProductWidgetParams.attributes) {
                jQuery('input[type=checkbox][data-shortcode-attribute=' + i + ']')
                    .prop('checked', true);
            }
        }
    };

    jQuery('.media-menu-item', popup()).click(function() {
        jQuery('.media-menu .media-menu-item', popup()).removeClass('active');
        jQuery(this).addClass('active');

        jQuery('.media-modal-content', popup()).attr('data-active-dialog', jQuery(this).attr('data-content'));
        jQuery('.media-menu').removeClass('visible');
        return false;
    });

    jQuery('h1', popup()).click(function() {
        jQuery('.media-menu').toggleClass('visible');
    });


    jQuery('.media-button-select', popup()).click(function() {

        var shortcode = buildShortcode();

        if (tinymce.activeEditor && !tinymce.activeEditor.isHidden()) {
            tinymce.activeEditor.execCommand('mceInsertContent', false, shortcode);
            tinymce.activeEditor.execCommand('mceSetContent', false, tinymce.activeEditor.getBody().innerHTML);
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

    saveParams = function() {
        var params = {display: {}, attributes: {} };

        jQuery('input[type=checkbox][data-display-option]:checked').each(function(idx, el) {
            params.display[jQuery(el).data('display-option')] = 1;
        });

        jQuery('input[type=checkbox][data-shortcode-attribute]:checked').each(function(idx, el) {
            params.attributes[jQuery(el).data('shortcode-attribute')] = 1;
        });

        jQuery.getJSON(ajaxurl, {action: 'ecwid-save-spw-params', params: params});
    };

    buildShortcode = function() {
        var params = {};

        product = getCurrentProduct();

        params.id = product.id;
        params.version = '2';
        params.display = [];

        jQuery('input[type=checkbox][data-display-option]:checked').each(function(idx, el) {
           params.display[params.display.length] = jQuery(el).data('display-option');
        });

        jQuery('input[type=checkbox][data-shortcode-attribute]').each(function(idx, el) {
            params[jQuery(el).data('shortcode-attribute')] = jQuery(el).is(':checked') ? 1 : 0;
        });

        if (params.display.length > 0) {
            params.display = params.display.join(' ');
        } else {
            params.display = undefined;
        }
        var params_order = ['id', 'display', 'version', 'show_border', 'show_price_on_button', 'center_align'];

        var shortcode = '[ecwid_product';

        for (var i = 0; i < params_order.length; i++) {
            shortcode += ' ' + params_order[i] + '="' + params[params_order[i]] + '"';
        }

        shortcode += ']';

        return shortcode;
    };

    setCurrentProduct = function( product ) {
        popup().data('currentProduct', product);
        updateFormOnCurrentProduct();
    };

    getCurrentProduct = function() {
        return popup().data('currentProduct');
    };

    setSearchParams = function( params ) {
        popup().data('searchParams', params);
    };

    getSearchParams = function () {
        var params = popup().data('searchParams');

        if (!params) {
            params = {page: 1, sortBy: popup().data('defaultSortBy')};
        }

        return params;
    };

    updateFormOnCurrentProduct = function() {
        var product = getCurrentProduct();

        if (product) {
            jQuery( '.media-button-select', popup() ).removeClass( 'disabled' );
        } else {
            jQuery( '.media-button-select', popup() ).addClass( 'disabled' );
        }

    }

    clickProduct = function() {

        if (jQuery(this).hasClass('selected-product')) {
            jQuery(this).closest('tbody').find('tr').removeClass('selected-product');
            setCurrentProduct(null);
        } else {
            jQuery(this).closest('tbody').find('tr').removeClass('selected-product');
            jQuery(this).addClass('selected-product');
            setCurrentProduct(jQuery(this).data('productData'));
        }
    };

    addProduct = function(productData) {
        var productTemplate = wp.template('product-in-list');

        var product = productTemplate(
            {'name': productData.name, 'image_url': productData.thumb, 'sku': productData.sku, 'id': productData.id}
        );

        jQuery('.wp-list-table.products tbody').append(product);
        jQuery('#product-' + productData.id).data('productData', productData);
    };

    addTable = function() {
        tableTemplate = wp.template( 'products-list' );

        jQuery( '.ecwid-add-product.add-product' ).append(tableTemplate());
    };

    showEmpty = function(term) {
        emptyTemplate = wp.template( 'no-products' );

        jQuery( '.ecwid-add-product.add-product .wp-list-table.products tbody' ).append(emptyTemplate({term:term}));

        jQuery( '.tablenav', popup()).hide();
    };

    renderAddProductForm = function() {
        ecwidSpwSearchProducts();
    };

    updateSearchParams = function(newParams) {
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

    renderSearchParams = function() {
        var searchParams =  popup().data('searchParams');

        if (!searchParams) {
            searchParams = {};
        }

        if (typeof(searchParams.keyword) != 'undefined') {
            jQuery('#product-search-input').val(searchParams.keyword);
        }

        if (typeof(searchParams.sortBy) != 'undefined') {
            if (searchParams.sortBy == 'NAME_ASC') {
                jQuery('#name').addClass('asc');
            } else if (searchParams.sortBy == 'NAME_DESC') {
                jQuery('#name').addClass('desc');
            } else if (searchParams.sortBy == 'SKU_ASC') {
                jQuery('#sku').addClass('asc');
            } else if (searchParams.sortBy == 'SKU_DESC') {
                jQuery('#sku').addClass('desc');
            }
        }
    }


    renderPagination = function() {
        if (typeof(searchParams.page != 'undefined')) {
            jQuery('#current-page-selector').val(searchParams.page);
            if (searchParams.page > 1) {

            }
        }
    }


    assignHandlers = function() {

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
            updateSearchParams();
        });
    };
});