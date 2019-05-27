/* var sample = {"success":["main","import-woo-categories","create_category","create_category","upload_category_image","upload_category_image","import-woo-products","import-woo-products-batch","import-woo-product","import-woo-product","import-woo-product","create_product","create_product","create_variation","create_variation"],"error":["create_product","create_variation","create_variation","create_variation","create_variation","create_variation"],"total":21,"current":20,"error_messages":{"create_product":{"400 Bad Request:Negative numbers are not allowed: -4.0":[{"woo_id":21,"woo_link":"http://localhost/wordpress/woocommerce/wp-admin/post.php?post=21&action=edit","name":"neg price"}]},"create_variation":{"400 Bad Request:Stock settings are not same for product and combination. Please set the combination's SKU":[{"woo_id":9,"woo_link":"http://localhost/wordpress/woocommerce/wp-admin/post.php?post=9&action=edit","name":"test","ecwid_id":139559751,"ecwid_link":"http://localhost/wordpress/woocommerce/wp-admin/admin.php?page=ec-store&ec-store-page=product%3Amode%3Dedit%26id%3D139559751","variation_id":14},{"woo_id":9,"woo_link":"http://localhost/wordpress/woocommerce/wp-admin/post.php?post=9&action=edit","name":"test","ecwid_id":139559751,"ecwid_link":"http://localhost/wordpress/woocommerce/wp-admin/admin.php?page=ec-store&ec-store-page=product%3Amode%3Dedit%26id%3D139559751","variation_id":15},{"woo_id":9,"woo_link":"http://localhost/wordpress/woocommerce/wp-admin/post.php?post=9&action=edit","name":"test","ecwid_id":139559751,"ecwid_link":"http://localhost/wordpress/woocommerce/wp-admin/admin.php?page=ec-store&ec-store-page=product%3Amode%3Dedit%26id%3D139559751","variation_id":16}],"409 Conflict:New combination duplicates existing combination or the base product: {}":[{"woo_id":11,"woo_link":"http://localhost/wordpress/woocommerce/wp-admin/post.php?post=11&action=edit","name":"Test Var atts without value","ecwid_id":139559752,"ecwid_link":"http://localhost/wordpress/woocommerce/wp-admin/admin.php?page=ec-store&ec-store-page=product%3Amode%3Dedit%26id%3D139559752","variation_id":20}],"409 Conflict:New combination duplicates existing combination or the base product: {size**ZZZ=l}":[{"woo_id":11,"woo_link":"http://localhost/wordpress/woocommerce/wp-admin/post.php?post=11&action=edit","name":"Test Var atts without value","ecwid_id":139559752,"ecwid_link":"http://localhost/wordpress/woocommerce/wp-admin/admin.php?page=ec-store&ec-store-page=product%3Amode%3Dedit%26id%3D139559752","variation_id":12}]}},"status":"complete"}
jQuery(document).ready(function() {
    jQuery('h2').after(renderImportLog(buildImportLog(sample)));

});

*/

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

jQuery(document).ready(function() {
   $wrapper = jQuery('.ecwid-importer'); 
   
   var status = {
       'success' : [],
       'error': [],
       'errorMessages': {},
       'planLimitHit': false
   };
    
   jQuery('#reconnect-button').click(function() {
       var url = this.href;
       var settings = {};
       jQuery('input[type=checkbox].import-settings').each(function(idx, el) {
           if (el.checked) {
               settings[el.name] = true;
           }
       });
       
       for ( var i in settings ) {
            url += '&' + i + '=true';    
       }
       
       location.href = url;
       
       return false;
   });
   
   
   jQuery('#ecwid-importer-woo').click(function() {
       
       window.location.hash="woo";
       
       jQuery.getJSON(ajaxurl, { action: ecwid_importer.check_token_action }, function(data) {
           if (data.has_good_token == false) {
               $wrapper.removeClass('state-landing').addClass('state-no-token');
           } else {
               $wrapper.removeClass('state-landing').addClass('state-woo');
               jQuery('.ecwid-total-products', '.state-woo').text(data.ecwid_total_products);
               jQuery('.ecwid-total-categories', '.state-woo').text(data.ecwid_total_categories);
               jQuery('.woo-total-products', '.state-woo').text(data.woo_total_products);
               jQuery('.woo-total-categories', '.state-woo').text(data.woo_total_categories);
           }
           console.log(data);
       })
       .done(function() {
           console.log( "second success" );
       })
       .fail(function() {
           console.log( "error" );
       })
       .always(function() {
           console.log( "complete" );
       });
   });

   startWooImport = function() {
       $wrapper.removeClass('state-woo-initial').addClass('state-woo-in-progress');
       jQuery('input[type=checkbox].import-settings').attr('onclick', 'return false').closest('label').addClass('readonly');

       var settings = {};
       jQuery('input[type=checkbox].import-settings').each(function(idx, el) {
           if (el.checked) {
               settings[el.name] = true;
           }
           jQuery(el).attr('onclick', 'return false').closest('label').addClass('readonly');
       });

       do_import = function (start = null) {
           
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
       
       do_import( true );

       doImportComplete = function( data, status ) {
           jQuery('#import-results-products').text(status.success.create_product || 0);
           jQuery('#import-results-categories').text(status.success.create_category || 0);
           $wrapper.removeClass('state-woo-in-progress').addClass('state-woo-complete');

           if (status.planLimitHit) {
               jQuery('plan-limit-message').show();
           }
           
           var log = buildImportLog(data);
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
           }
       }
   };
   
   jQuery('#ecwid-importer-woo-go').click(startWooImport);

   jQuery('.ecwid-importer .errors .btn-details').click(function() {
       jQuery('.ecwid-importer .errors .details').each(function(idx, el) {if (jQuery(el).text().length) jQuery(el).toggle() });
   });
    

    if (window.location.hash.indexOf('start') != -1) {
    
        var params = location.hash.split('&');
        
        for ( var i in params ){
            var name = params[i].split('=');
            name = name[0];
            
            var el = jQuery( 'input[name="' + name + '"]' );
            if ( el.length == 1 ) {
                el.prop('checked', true);
            }
        }

        window.location.hash = '';

        startWooImport();
    }
});