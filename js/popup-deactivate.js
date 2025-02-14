jQuery(document).ready(function () {

    var deactivateButton = jQuery('tr[data-slug="ecwid-shopping-cart"] .deactivate a');

    jQuery('input[name=reason]').on('click', function () {
        jQuery('.reasons-list-item').removeClass('selected');

        jQuery(this).closest('.reasons-list-item').addClass('selected');
    });

    deactivateButton.on('click', function () {
        jQuery('.ecwid-popup-deactivate').addClass('open');
        jQuery('body').addClass('ecwid-popup-open');

        return false;
    });

    function gatherFeedback(popup) {
        var feedback = {};

        var reasonElement = jQuery(popup + ' input[name=reason]:checked');
        feedback.reason = reasonElement.val();
        feedback.reasonText = reasonElement.attr('data-text');
        feedback.message = jQuery(popup + ' textarea[name="message[' + feedback.reason + ']"]').val();

        return feedback;
    }


    jQuery('.ecwid-popup-deactivate .deactivate').on('click', function () {
        feedback = gatherFeedback('.ecwid-popup-deactivate');
        jQuery.ajax({
            url: wp.ajax.settings.url,
            data: {
                action: 'ecwid_deactivate_feedback',
                reason: feedback.reason,
                message: feedback.message,
                _ajax_nonce: EcwidPopupDeactivate._ajax_nonce
            },
            complete: function () {
                location.href = deactivateButton.attr('href');
            }
        });
    });

    jQuery('.ecwid-popup-woo-import-feedback .btn-send-feedback').on('click', function () {
        feedback = gatherFeedback('.ecwid-popup-woo-import-feedback');
        jQuery.ajax({
            url: ajaxurl,
            data: {
                action: 'ecwid_send_feedback',
                reason: feedback.reason,
                message: feedback.message
            },
            complete: function () {
                jQuery('.ecwid-popup-woo-import-feedback .ecwid-popup-body h3').hide();
                jQuery('.ecwid-popup-woo-import-feedback .ecwid-popup-body .reasons-list').hide();
                jQuery('.ecwid-popup-woo-import-feedback .ecwid-popup-footer .button').hide();

                jQuery('.ecwid-popup-woo-import-feedback .success-message').show();
            }
        });
    });
});