( function( blocks, components, i18n, element, _ ) {
    var el = element.createElement;

    blocks.registerBlockType( 'ecwid/store-block', {
        title: EcwidGutenbergParams.title,
        icon: el('div', {class:"ecwid-store-block-icon"}),
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

            return el( 'div', {className: 'ecwid-store-block'},
                el( 'button', { className: 'button button-primary ecwid-store-block-button', onClick: function() { ecwid_open_store_popup( props ); } }, i18n.__( 'Edit Appearance', 'ecwid-shopping-cart' ) )
            );
        },
        save: function( props ) {
            return '[ecwid]';
        }
    } );

} )(
    window.wp.blocks,
    window.wp.components,
    window.wp.i18n,
    window.wp.element,
    window._
);
ecwid_pb_defaults = EcwidGutenbergParams.ecwid_pb_defaults;