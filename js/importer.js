jQuery(document).ready(function() {
   $wrapper = jQuery('.ecwid-importer'); 
   
   var status = {
       'success' : [],
       'error': [],
   };
    
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
   
   jQuery('#ecwid-importer-woo-go').click(function() {
       $wrapper.removeClass('state-woo-initial').addClass('state-woo-in-progress');

       debugger;
       do_import = function () {
           jQuery.ajax({
               'url': ajaxurl,
               'data': {'action': ecwid_importer.do_woo_import_action},
               'success': function (json) {
                   debugger;
                   data = jQuery.parseJSON(json);

                   for (var i = 0; i < data.success.length; i++) {
                       if (typeof status.success[data.success[i]] == 'undefined' ) {
                           status.success[data.success[i]] = 1;
                       } else {
                           status.success[data.success[i]]++;
                       }
                   }


                   for (var i = 0; i < data.error.length; i++) {
                       if (typeof status.error[data.error[i]] == 'undefined' ) {
                           status.error[data.error[i]] = 1;
                       } else {
                           status.error[data.error[i]]++;
                       }
                   }

                   jQuery('#import-progress-current').text((status.success.create_category || 0) + (status.success.create_product || 0));

                   if (data.status == 'complete') {
                       jQuery('#import-results-products').text(status.success.create_product || 0);
                       jQuery('#import-results-categories').text(status.success.create_category || 0);
                       $wrapper.removeClass('state-woo-in-progress').addClass('state-woo-complete');
                   } else {
                       
                       jQuery('.progress-bar').css('width', parseInt(data.current / data.total * 100) + '%');
                       
                       do_import();
                   }
               }
           });
       };
       
       do_import();
   });
});