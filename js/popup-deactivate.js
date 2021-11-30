jQuery(document).ready(function() {
    
    var deactivateButton = jQuery('tr[data-slug="ecwid-shopping-cart"] .deactivate a');
    
    jQuery('input[name=reason]').on('click', function() {
        jQuery('.reasons-list-item').removeClass('selected');
        
        jQuery(this).closest('.reasons-list-item').addClass('selected');
    }); 
   
    deactivateButton.on('click', function() {
        jQuery('.ecwid-popup-deactivate').addClass('open');
        jQuery('body').addClass('ecwid-popup-open');
        
        return false;
    });
    
    function gatherFeedback() {
        var feedback = {};
        
        var reasonElement = jQuery('.ecwid-popup-deactivate input[name=reason]:checked');
        feedback.reason = reasonElement.val();
        feedback.reasonText = reasonElement.attr('data-text');
        feedback.message = jQuery('.ecwid-popup-deactivate textarea[name="message[' + feedback.reason + ']"]').val();
    
        return feedback;
    }
    
    
    jQuery('.ecwid-popup-deactivate .deactivate').on('click', function() {
        feedback = gatherFeedback();
        jQuery.ajax({
            url: wp.ajax.settings.url,
            data: {
                action: 'ecwid_deactivate_feedback',
                reason: feedback.reason,
                message: feedback.message
            },
            complete: function() {
                location.href = deactivateButton.attr('href');
            }
        });
    });
});