jQuery(document).ready(function() {
    jQuery('#customize-control-ec_show_floating_cart_widget select').change(function() {
           if ( jQuery(this).val() == 'do_not_show' ) {
               jQuery('[id^="customize-control-ec_store_cart"]:not(#customize-control-ec_show_floating_cart_widget)').hide();
           } else {
               jQuery('[id^="customize-control-ec_store_cart"]:not(#customize-control-ec_show_floating_cart_widget)').show();
           }
    }).trigger('change');
})