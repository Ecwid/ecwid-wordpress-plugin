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
               'success': function (json) {
                   
                   try {
                        data = jQuery.parseJSON(json);
                   } catch(e) {
                        status.errorMessages['json_failed'] = [];
                        status.errorMessages['json_failed'][json] = 1;
                        doImportComplete(status);
                   }
                   
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
                                   status.errorMessages[import_type][message] = 0;
                               }
                               status.errorMessages[import_type][message] += messages[message];
                           }
                       }
                   }

                   jQuery('#import-progress-current').text((status.success.create_category || 0) + (status.success.create_product || 0));

                   if (data.status == 'complete') {

                       doImportComplete(status);
                   } else {
                       do_import();
                   }
               },
               'error': function(jqXHR, textStatus, errorThrown) {
                   status.errorMessages[textStatus] = [];
                   status.errorMessages[textStatus][errorThrown] = 1;
                   doImportComplete(status);
               }
           });
       };

       do_import( true );

       doImportComplete = function( status ) {
           jQuery('#import-results-products').text(status.success.create_product || 0);
           jQuery('#import-results-categories').text(status.success.create_category || 0);
           $wrapper.removeClass('state-woo-in-progress').addClass('state-woo-complete');

           if (status.planLimitHit) {
               jQuery('plan-limit-message').show();
           }

           var errorContent = '';
           for (var importType in status.errorMessages) {
               errorContent += importType + "\n";
               for (var message in status.errorMessages[importType]) {
                   errorContent += '  ' + message + ':' + status.errorMessages[importType][message] + "\n";
               }
           }

           if (errorContent.length > 0) {
               jQuery('.ecwid-importer .errors').show().find('pre').text(errorContent);
           }
       }
   };
   
   jQuery('#ecwid-importer-woo-go').click(startWooImport);

   jQuery('.ecwid-importer .errors .btn-details').click(function() {
       jQuery('.ecwid-importer .errors .details').toggle();
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