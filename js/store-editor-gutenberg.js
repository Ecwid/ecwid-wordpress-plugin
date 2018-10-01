jQuery(document).ready(function() {
    $popup = jQuery('#ecwid-store-popup-content');

    /*
    jQuery('.media-modal-content', $popup)
        .attr('data-mode', 'store-settings')
        .attr('data-active-dialog', 'store-settings');

    jQuery('.media-menu-item')
        .removeClass('active')
        .filter('[data-content=store-settings]').addClass('active');
    */


    /*
     * Media buttons handlers
     */
    jQuery('#update-ecwid-button,#insert-ecwid-button').click(ecwid_open_store_popup);

    /*
     * Close button handler
     */
    jQuery('.media-modal-close', $popup).click(function() {

        $popup.data('block-props').attributes.meta1 = 'good' + Math.random();
        $popup.removeClass('open');
        return false;
    });

    jQuery(document).keydown(function(e) {
        if (e.keyCode == 27 && $popup.hasClass('open')) {
            $popup.removeClass('open');
            return false;
        }
    });

    /*
     * Returns default parameters object
     */
    getDefaultParams = function() {
        return {
            'show_search': true,
            'show_minicart': false,
            'show_categories': false,
            'categories_per_row': 3,
            'grid_rows': ecwid_pb_defaults.grid_rows,
            'grid_columns': ecwid_pb_defaults.grid_columns,
            'table_rows': ecwid_pb_defaults.table_rows,
            'list_rows': ecwid_pb_defaults.list_rows,
            'default_category_id': 0,
            'default_product_id': 0,
            'category_view': 'grid',
            'search_view': 'list',
            'minicart_layout': 'MiniAttachToProductBrowser'
        }
    }
    
    /*
     * Handles media modal menus
     */
    jQuery('.media-menu-item', $popup).click(function() {
        jQuery('.media-menu .media-menu-item', $popup).removeClass('active');
        jQuery(this).addClass('active');

        jQuery('.media-modal-content', $popup).attr('data-active-dialog', jQuery(this).attr('data-content'));
        jQuery('.media-menu').removeClass('visible');
        return false;
    });

    jQuery('h1', $popup).click(function() {
        jQuery('.media-menu').toggleClass('visible');
    })

    /*
     * Main button click
     */
    jQuery('.button-primary', $popup).click(function() {

        var result = {}, defaults = getDefaultParams();

        result.widgets = 'productbrowser';
        for (var i in {search:1, categories:1, minicart:1}) {
            if (jQuery('input[name=show_' + i + ']').prop('checked')) {
                result.widgets += ' ' + i;
            }
        }

        getNumber = function(name, fallback) {
            var value = parseInt(jQuery('[name=' + name + ']', $popup).val());

            if (isNaN(value) || value < 0) {
                value = fallback;
            }

            return value;
        }

        getString = function(name, values, fallback) {
            var value = jQuery('[name=' + name + ']', $popup).val();

            if (jQuery.inArray(value, values) == -1) {
                value = fallback;
            }

            return value;
        }

        result.categories_per_row = getNumber('categories_per_row', defaults.categories_per_row);
        result.grid = getNumber('grid_rows', defaults.grid_rows) + ',' + getNumber('grid_columns', defaults.grid_columns);
        result.list = getNumber('list_rows', defaults.list_rows);
        result.table = getNumber('table_rows', defaults.table_rows);
        result.default_category_id = getNumber('default_category_id', defaults.default_category_id);
        result.default_product_id = getNumber('default_product_id', defaults.default_product_id);
        result.category_view = getString('category_view', ['list', 'grid', 'table'], defaults.category_view);
        result.search_view = getString('search_view', ['list', 'grid', 'table'], defaults.search_view);
        result.minicart_layout = defaults.minicart_layout;

        $popup.data('block-props').setAttributes(result);

        jQuery('#ecwid-store-popup-content').removeClass('open');
    });

    updatePreview = function() {
        jQuery('.store-settings input[type=checkbox]', $popup).each(function(idx, el) {
            var widget = jQuery(el).parent().attr('data-ecwid-widget');
            var preview = jQuery('.store-settings-preview svg path.' + widget, $popup);
            if (jQuery(el).prop('checked')) {
                jQuery('.store-settings-wrapper').addClass('ecwid-' + widget);
            } else {
                jQuery('.store-settings-wrapper').removeClass('ecwid-' + widget);
            }
        });
    }

    jQuery('.store-settings-wrapper label', $popup).hover(
        function() {
            jQuery('.store-settings-wrapper').attr('data-ecwid-widget-hover', jQuery(this).attr('data-ecwid-widget'));
        },
        function() {
            jQuery('.store-settings-wrapper').attr('data-ecwid-widget-hover', '');
        }
    );

    jQuery('.store-settings input[type=checkbox]', $popup).change(updatePreview);
});

buildParams = function(attributes) {
    
    if (jQuery.inArray(attributes.category_view, ['grid', 'list', 'table']) == -1) {
        attributes.category_view = undefined;
    }

    if (!jQuery.inArray(attributes.search_view, ['grid', 'list', 'table']) == -1) {
        attributes.search_view = undefined;
    }

    var defaults = getDefaultParams();

    if (!attributes.grid || attributes.grid.match(/^\d+,\d+$/) === null) {
        attributes.grid = defaults.grid_columns + ',' + defaults.grid_rows;
    }

    var grid = attributes.grid.match(/^(\d+),(\d+)/);
    attributes.grid_rows = grid[1];
    attributes.grid_columns = grid[2];

    for (var i in {'categories_per_row': defaults.categories_per_row, 'list': defaults.list_rows, 'table': defaults.table_rows, 'grid_rows': defaults.grid_rows, 'grid_columns': defaults.grid_columns, 'default_category_id': 0, 'default_product_id': 0}) {
        parsed = parseInt(attributes[i]);
        if (isNaN(parsed) || parsed < 0) {
            attributes[i] = undefined;
        }
    }

    var widgets = attributes.widgets;
    if (typeof widgets == 'undefined') {
        widgets = "productbrowser";
    }

    widgets = widgets.split(/[^a-z^A-Z^0-9^-^_]/);

    return {
        'show_search': jQuery.inArray('search', widgets) != -1,
        'show_categories': jQuery.inArray('categories', widgets) != -1,
        'show_minicart': jQuery.inArray('minicart', widgets) != -1,
        'categories_per_row': attributes.categories_per_row,
        'category_view': attributes.category_view,
        'search_view': attributes.search_view,
        'list_rows': attributes.list,
        'table_rows': attributes.table,
        'grid_rows': grid[1],
        'grid_columns': grid[2],
        'default_category_id': attributes.default_category_id,
        'default_product_id': attributes.default_product_id,
        'minicart_layout': 'attachToCategories'
    };

}

ecwid_open_store_popup = function( props ) {

    $popup.data('block-props', props);
    var attributes = buildParams(props.attributes);
    
    params = {};
    jQuery.extend(params, 
        getDefaultParams(), 
        attributes
    );
    
    for (var i in params) {
        var el = jQuery('[name=' + i + ']', $popup);
        if (el.attr('type') == 'checkbox') {
            el.prop('checked', params[i]);
        } else {
            el.val(params[i]);
        }
    }

    // mode determines whether it is a new store or not, and active dialog is the current tab
    // in other words, mode = [add-store,store-settings] and active dialog is [add-store|store-settings, appearance]
    // buttons and menu items are for mode, current title and content are for dialog
    jQuery('.media-modal-content', $popup).attr('data-mode', 'store-settings');
    jQuery('.media-modal-content', $popup).attr('data-active-dialog', 'store-settings');
    jQuery('.media-menu-item')
        .removeClass('active')
        .filter('[data-content=store-settings]').addClass('active');

    updatePreview();
    
    $popup.addClass('open');

    return false;
};
