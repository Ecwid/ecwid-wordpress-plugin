
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

        jQuery('[data-ec-importer-card-stack]').each(function(){
            if( jQuery(this).find('.a-card:visible').length > 1 ) {
                jQuery(this).addClass('a-card-stack');
            } else {
                jQuery(this).removeClass('a-card-stack');
            }
        });
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