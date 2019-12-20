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
            block_status = 'success';

        if( alert_type == 'warning' || alert_type == 'limit' ) {
            block_status = 'warning';
        }

        block.find('[data-ec-importer-alert]').hide();
        block.find('[data-ec-importer-alert=' + block_status +']').show();

        block.addClass( 'a-card--' + block_status );
        block.find('.iconable-block').addClass( 'iconable-block--' + block_status );

        if( alert_type == 'limit' ) {
            block.find('[data-ec-importer-alert=limit]').show();
        }

        block.show();
    }

    startWooImport = function() {
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
                        processImportProgress(data);
                    } catch(e) {
                        status.errorMessages['json_failed'] = [];
                        status.errorMessages['json_failed'][json] = 1;
                        doImportComplete(status);
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
                doImportComplete(status);
            } else {
                do_import();
            }
        }

        doImportComplete = function( status ) {

            jQuery('#import-results-products').text(status.success.create_product || 0);
            jQuery('#import-results-categories').text(status.success.create_category || 0);

            if (status.planLimitHit) {
                showWooImportAlert( 'limit' );
            } else if ( Object.keys( status.error ).length > 0 || Object.keys( status.errorMessages ).length > 0 ) {
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

    // Autostart import
    if (window.location.hash.indexOf('start') != -1) {
        window.location.hash = '';
        startWooImport();
    }
});