/* var sample = {"success":["main","import-woo-categories","create_category","create_category","upload_category_image","upload_category_image","import-woo-products","import-woo-products-batch","import-woo-product","import-woo-product","import-woo-product","create_product","create_product","create_variation","create_variation"],"error":["create_product","create_variation","create_variation","create_variation","create_variation","create_variation"],"total":21,"current":20,"error_messages":{"create_product":{"400 Bad Request:Negative numbers are not allowed: -4.0":[{"woo_id":21,"woo_link":"http://localhost/wordpress/woocommerce/wp-admin/post.php?post=21&action=edit","name":"neg price"}]},"create_variation":{"400 Bad Request:Stock settings are not same for product and combination. Please set the combination's SKU":[{"woo_id":9,"woo_link":"http://localhost/wordpress/woocommerce/wp-admin/post.php?post=9&action=edit","name":"test","ecwid_id":139559751,"ecwid_link":"http://localhost/wordpress/woocommerce/wp-admin/admin.php?page=ec-store&ec-store-page=product%3Amode%3Dedit%26id%3D139559751","variation_id":14},{"woo_id":9,"woo_link":"http://localhost/wordpress/woocommerce/wp-admin/post.php?post=9&action=edit","name":"test","ecwid_id":139559751,"ecwid_link":"http://localhost/wordpress/woocommerce/wp-admin/admin.php?page=ec-store&ec-store-page=product%3Amode%3Dedit%26id%3D139559751","variation_id":15},{"woo_id":9,"woo_link":"http://localhost/wordpress/woocommerce/wp-admin/post.php?post=9&action=edit","name":"test","ecwid_id":139559751,"ecwid_link":"http://localhost/wordpress/woocommerce/wp-admin/admin.php?page=ec-store&ec-store-page=product%3Amode%3Dedit%26id%3D139559751","variation_id":16}],"409 Conflict:New combination duplicates existing combination or the base product: {}":[{"woo_id":11,"woo_link":"http://localhost/wordpress/woocommerce/wp-admin/post.php?post=11&action=edit","name":"Test Var atts without value","ecwid_id":139559752,"ecwid_link":"http://localhost/wordpress/woocommerce/wp-admin/admin.php?page=ec-store&ec-store-page=product%3Amode%3Dedit%26id%3D139559752","variation_id":20}],"409 Conflict:New combination duplicates existing combination or the base product: {size**ZZZ=l}":[{"woo_id":11,"woo_link":"http://localhost/wordpress/woocommerce/wp-admin/post.php?post=11&action=edit","name":"Test Var atts without value","ecwid_id":139559752,"ecwid_link":"http://localhost/wordpress/woocommerce/wp-admin/admin.php?page=ec-store&ec-store-page=product%3Amode%3Dedit%26id%3D139559752","variation_id":12}]}},"status":"complete"}
jQuery(document).ready(function() {
    jQuery('h2').after(renderImportLog(buildImportLog(sample)));

});*/


jQuery(document).ready(function() {
    var status = {
        'success' : [],
        'error': [],
        'errorMessages': {},
        'planLimitHit': false
    };

    switchWooImportState = function( state ) {
        jQuery('[data-ec-importer-state]').hide();
        jQuery('[data-ec-importer-state=' + state +']').show();
    }

    showWooImportAlert = function( alert_type ) {
        var block = jQuery('#ec-importer-alert'),
            status = 'success';

        if( alert_type == 'warning' || alert_type == 'limit' ) {
            status = 'warning';
        }

        block.find('[data-ec-importer-alert]').hide();
        block.find('[data-ec-importer-alert=' + status +']').show();

        block.addClass( 'a-card--' + status );
        block.find('.iconable-block').addClass( 'iconable-block--' + status );

        if( alert_type == 'limit' ) {
            block.find('[data-ec-importer-alert=limit]').show();
        }

        block.show();
    }

    startWooImport = function() {
        //TO-DO убрать
        var settings = {};

        do_import = function (start = null) {
            switchWooImportState('process');

            var data = {'action': ecwid_importer.do_woo_import_action, settings: settings};
            if (start) {
                data.reset = 1
            }

            jQuery.ajax({
                'url': ajaxurl,
                'data': data,
                'success': function(json) {
                    try {
                        data = jQuery.parseJSON(json);
                    } catch(e) {
                        status.errorMessages['json_failed'] = [];
                        status.errorMessages['json_failed'][json] = 1;
                        doImportComplete(status);
                    }

                    processImportProgress(data);
                },
                'error': function(jqXHR, textStatus, errorThrown) {
                    status.errorMessages[textStatus] = [];
                    status.errorMessages[textStatus][errorThrown] = 1;
                    doImportComplete(status);
                }
            });
        };

        do_import( true );

        processImportProgress = function (data) {

            for (var i = 0; i < data.success.length; i++) {
                if (typeof status.success[data.success[i]] == 'undefined') {
                    status.success[data.success[i]] = 1;
                } else {
                    status.success[data.success[i]]++;
                }
            }

            for (var i = 0; i < data.error.length; i++) {
                if (typeof status.error[data.error[i]] == 'undefined') {
                    status.error[data.error[i]] = 1;
                } else {
                    status.error[data.error[i]]++;
                }
            }

            status.planLimitHit |= typeof data.plan_limit_hit != 'undefined';

            if (data.error_messages) {
                for (var import_type in data.error_messages) {

                    var messages = data.error_messages[import_type];

                    if ( !status.errorMessages[import_type] ) {
                        status.errorMessages[import_type] = {};
                    }

                    for ( var message in messages ) {
                        if ( !status.errorMessages[import_type].hasOwnProperty(message) ) {
                            status.errorMessages[import_type][message] = '';
                        }

                        status.errorMessages[import_type][message] += messages[message];
                    }
                }
            }

            jQuery('#import-progress-current').text((status.success.create_category || 0) + (status.success.create_product || 0));

            if (data.status == 'complete') {
                doImportComplete(data, status);
            } else {
                do_import();
            }
        }

        doImportComplete = function( data, status ) {
            jQuery('#import-results-products').text(status.success.create_product || 0);
            jQuery('#import-results-categories').text(status.success.create_category || 0);

            /*var log = buildImportLog(data);
            jQuery('#fancy-errors').append(renderImportLog(log));

            for(var type in log) {
                delete status.errorMessages[type];
            }

            var errorContent = '';
            for (var importType in status.errorMessages) {
                errorContent += importType + "\n";
                for (var message in status.errorMessages[importType]) {
                   errorContent += '  ' + message + ':' + status.errorMessages[importType][message] + "\n";
                }
            }

            if (errorContent.length > 0) {
                jQuery('.ecwid-importer .errors').find('pre').text(errorContent).show();
            }

            if ( jQuery('pre.details').text().length || jQuery('#fancy-errors').text().length) {
                jQuery('.ecwid-importer .errors').show();
            }*/

            if (status.planLimitHit) {
                showWooImportAlert( 'limit' );
            } else if ( Object.keys( status.error ).length > 0 ) {
                showWooImportAlert( 'warning' );
            } else {
                showWooImportAlert( 'success' );
            }

            switchWooImportState( 'complete' );
        }
    };
   
    jQuery('#ec-importer-woo-go').click(function(){
        startWooImport();
    });

    // jQuery('.ecwid-importer .errors .btn-details').click(function() {
    //     jQuery('.ecwid-importer .errors .details').each(function(idx, el) {if (jQuery(el).text().length) jQuery(el).toggle() });
    // });

    // Autostart import
    if (window.location.hash.indexOf('start') != -1) {
        window.location.hash = '';
        startWooImport();
    }
});


function renderImportLog(log) {
    var logContainer = jQuery('<div>');
    for ( var type in log ) {

        var entryTypeContainer = jQuery('<div>').append('<h4>' + type + '</h4>').appendTo(logContainer);
        entryTypeContainer.append(
            renderImportLogEntryType(type, log[type])
        ).appendTo(logContainer);
    }

    return logContainer;
}

function renderImportLogEntryType(type, data) {
    
    var items = [];
    for ( var error in data ) {
        var item = jQuery('<div class="log-entry">');
        var code = error.substr(0, error.indexOf(':'));
        var errorText = error.substr(error.indexOf(':') + 1);
        item.append('<div class="hidden">' + code + '</div>').append(errorText);
        item.append(' ');
        jQuery('<a href="#">')
            .append('(' + Object.keys(data[error]).length + " total)" )
            .click(function() {jQuery(this).closest('.log-entry').toggleClass('expanded'); return false;})
            .appendTo(item);

        item.append( renderProductLogEntries(data[error]) );

        items[items.length] = item;
    }
    return items;
}

function renderProductLogEntries(data) {
    var products = [];
    for ( var i in data ) {
        var entry = data[i];
        var productContainer = jQuery('<div class="log-entry-product">');

        var woo_link = jQuery('<a>').attr('href', entry.woo_link).append(entry.name).appendTo(productContainer);

        if (entry.ecwid_link) {
            var ecwid_link = jQuery('<a>').attr('href', entry.ecwid_link).append("Product at Ecwid");
            productContainer.append(' (').append(ecwid_link).append( ' )');
        }
        
        if ( typeof entry.variations == 'array' && entry.variations.length > 0 ) {
            productContainer.append(" Variations ");
            for ( var j = 0; j < entry.variations.length; j++ ) {
                productContainer.append('#' + entry.variations[j]);
                
                if ( j + 1 < entry.variations.length ) {
                    productContainer.append(', ');
                }
            }
        }        
        products[products.length] = productContainer;
    }
    
    return products;
}

function buildImportLog(data) {
    
    var log = {};
    
    for ( var type in data.error_messages) {
        if ( typeof log[type] == 'undefined' ) {
            log[type] = {};
        }
        
        for ( var message in data.error_messages[type] ) {
            if ( typeof log[type][message] == 'undefined' ) {
                log[type][message] = [];
            }
            log[type][message].concat( buildProductErrorLog( log[type][message], data.error_messages[type][message] ) );
        }
    }
    
    return log;
}

function buildProductErrorLog(log, items) {
    
    for (var i = 0; i < items.length; i++) {
        var item = items[i];
        if (!log[item.woo_id]) {
            var entry = {
                name: item.name,
                type: 'variation',
                woo_link: item.woo_link,
                ecwid_link: item.ecwid_link,
                variations: []
            };
        } else {
            entry = log[item.woo_id];
        }
        
        if ( item.variation_id ) {
            entry.variations[entry.variations.length] = item.variation_id;
        }
        log[item.woo_id] = entry;
    }
    
    return log;
}