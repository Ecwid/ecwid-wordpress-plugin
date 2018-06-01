( function( $ ) {
    
    props = [
        'ec_store_cart_widget_layout',
        'ec_store_cart_widget_icon',
        'ec_store_cart_widget_fixed_shape',
        'ec_store_cart_widget_fixed_position',
        'ec_store_cart_widget_show_empty_cart',
        'ec_store_cart_widget_horizontal_indent',
        'ec_store_cart_widget_vertical_indent'

    ];
    
    for (var i = 0; i < props.length; i++) {
        wp.customize(props[i], function( value ) {
            value.bind( function(newval) {
                var name = this.id;

                name = name.substring(9);//strlen(ec_store_)

                window.ec.storefront[name] = newval;
                Ecwid.refreshConfig();                
            });
        });    
    }

    wp.customize( 'ec_show_floating_cart_widget', function( value ) {
        value.bind( function( newval ) {
            jQuery('.ec-minicart').css('display', newval != 'do_not_show' ? 'block' : 'none');
        } );
    } );

} )( jQuery );