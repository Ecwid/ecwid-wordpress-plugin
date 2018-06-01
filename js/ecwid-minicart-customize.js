( function( $ ) {
    
    props = [
        'ecwid_cart_widget_enabled',
        'ecwid_cart_widget_layout',
        'ecwid_cart_widget_icon',
        'ecwid_cart_widget_fixed_shape',
        'ecwid_cart_widget_fixed_position',
        'ecwid_cart_widget_show_empty_cart'
    ];
    
    for (var i = 0; i < props.length; i++) {
        wp.customize(props[i], function( value ) {
            value.bind( function(newval) {
                var name = this.id;

                name = name.substring(6);

                window.ec.storefront[name] = newval;
                Ecwid.refreshConfig();                
            });
        });    
    }
    
    
    wp.customize( 'ec_show_floating_cart_widget', function( value ) {
        value.bind( function( newval ) {
            jQuery('.ec-minicart').css('display', newval ? 'block' : 'none');
        } );
    } );

} )( jQuery );