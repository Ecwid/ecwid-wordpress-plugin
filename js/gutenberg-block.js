( function( blocks, components, i18n, element, _ ) {
    var el = element.createElement;

    blocks.registerBlockType( 'ecwid/store-block', {
        title: i18n.__( 'Ecwid Store Block' ),
        icon: 'index-card',
        category: 'layout',
        attributes: {
            widgets: { type: 'string' },
            categories_per_row: { type: 'integer' },
            grid: { type: 'string' },
            list: { type: 'integer' },
            table: { type: 'integer' },
            default_category_id: { type: 'integer' },
            default_product_id: { type: 'integer' },
            search_view: { type: 'string' },
            category_view: { type: 'string' }
        },
        
        edit: function( props ) {
            
            var val = props.attributes.meta1;
            //props.setAttributes({meta1: 'abc'});

            return el( 'div', {}, 
                el( 'div', {}, 'val:' + val),
                el( 'button', { onClick: function() { ecwid_open_store_popup( props ); } }, 'abc')
            );
        },
        save: function( props ) {

            return null;
            return el( 'script', { }, 'alert(123)');
        }
    } );

} )(
    window.wp.blocks,
    window.wp.components,
    window.wp.i18n,
    window.wp.element,
    window._
);