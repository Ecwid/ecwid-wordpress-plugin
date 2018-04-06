( function( blocks, components, i18n, element, _ ) {
    var el = element.createElement;

    var ecwidStoreParams = {
        title: EcwidGutenbergParams.title,
        icon: el('div', {className:"ecwid-store-block-icon"}),
        category: 'layout',
        attributes: {
            widgets: { type: 'string' },
            categories_per_row: { type: 'integer' },
            grid: { type: 'string' },
            list: { type: 'integer' },
            table: { type: 'integer' },
            default_category_id: { type: 'integer' },
            default_product_id: { type: 'integer' },
            category_view: { type: 'string' },
            search_view: { type: 'string' },
            minicart_layout: {type: 'string' }
        },
        useOnce: true,
        
        edit: function( props ) {
            
            var val = props.attributes.meta1;

            return el( 'div', {className: 'ecwid-store-block'},
                el( 'button', { className: 'button button-primary ecwid-store-block-button', onClick: function() { ecwid_open_store_popup( props ); } }, i18n.__( 'Edit Appearance' ) )
            );
        },
        save: function( props ) {
            var shortcode = new wp.shortcode({
                'tag': EcwidGutenbergParams.storeShortcodeName,
                'attrs': props.attributes,
                'type': 'single'
            });
            
            return el( element.RawHTML, null, shortcode.string() );
        },

        transforms: {
            from: [{
                type: 'shortcode',
                tag: ['ecwid', 'ec_store'],
                attributes: {
                    widgets: {
                        type: 'string',
                        shortcode: function(named) {
                            debugger;
                            return named.widgets
                        }
                    }
                },
                priority: 10
            }]
        },
    };
    blocks.registerBlockType( 'ecwid/store-block', ecwidStoreParams);
    
} )(
    window.wp.blocks,
    window.wp.components,
    window.wp.i18n,
    window.wp.element,
    window._
);
ecwid_pb_defaults = EcwidGutenbergParams.ecwid_pb_defaults;