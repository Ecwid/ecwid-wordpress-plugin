( function( $ ) {
    
    props = [
        'ec_store_cart_widget_layout',
        'ec_store_cart_widget_icon',
        'ec_store_cart_widget_fixed_shape',
        'ec_store_cart_widget_fixed_position',
        'ec_store_cart_widget_horizontal_indent',
        'ec_store_cart_widget_vertical_indent'
    ];
    
    propsmap = {
        'ec_store_cart_widget_layout': 'layout',
        'ec_store_cart_widget_icon': 'icon',
        'ec_store_cart_widget_fixed_shape': 'fixed-shape',
        'ec_store_cart_widget_fixed_position': 'fixed-position',
        'ec_store_cart_widget_horizontal_indent': 'horizontal-indent',
        'ec_store_cart_widget_vertical_indent': 'vertical-indent'
    };
    
    for (var i = 0; i < props.length; i++) {
        wp.customize(props[i], function( value ) {
            
            value.bind( function(newval) {
                var name = 'data-' + propsmap[this.id];

                jQuery('#ec-customize-cart').attr(name, newval).attr('data-ec-widget-loaded', null);
                
                Ecwid.renderCartWidget(document.getElementById('ec-customize-cart'));
            });
        });    
    }

    wp.customize( 'ec_show_floating_cart_widget', function( value ) {
        value.bind( function( newval ) {
            jQuery('#ec-customize-cart').css('display', newval != 'do_not_show' ? 'block' : 'none');
        } );
    } );

} )( jQuery );