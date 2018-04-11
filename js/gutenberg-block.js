( function( blocks, components, i18n, element, _ ) {
    var el = element.createElement;

    var ecwidStoreParams = {
        title: EcwidGutenbergParams.title,
        icon: el('div', {className:"ecwid-store-block-icon"}),
        category: 'common',
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
            
            return el( 'div', {className: 'ecwid-store-block' }, 
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
                            return named.widgets
                        }
                    },
                    categories_per_row: { 
                        type: 'integer',
                        shortcode: function(named) {
                            return named.categories_per_row
                        }
                    },
                    grid: { 
                        type: 'string',
                        shortcode: function(named) {
                            return named.grid
                        }
                    },
                    list: { 
                        type: 'integer',
                        shortcode: function(named) {
                            return named.list
                        }
                    },
                    table: { 
                        type: 'integer',
                        shortcode: function(named) {
                            return named.table
                        }
                    },
                    default_category_id: { 
                        type: 'integer',
                        shortcode: function(named) {
                            return named.default_category_id
                        }
                    },
                    default_product_id: { 
                        type: 'integer',
                        shortcode: function(named) {
                            return named.default_product_id
                        }
                    },
                    category_view: { 
                        type: 'string',
                        shortcode: function(named) {
                            return named.category_view
                        }
                    },
                    search_view: { 
                        type: 'string',
                        shortcode: function(named) {
                            return named.search_view
                        }
                    },
                    minicart_layout: {
                        type: 'string',
                        shortcode: function(named) {
                            return named.minicart_layout
                        }
                    }
                },
                priority: 10
            }]
        },
    };
    blocks.registerBlockType( EcwidGutenbergParams.storeBlock, ecwidStoreParams);
    
} )(
    window.wp.blocks,
    window.wp.components,
    window.wp.i18n,
    window.wp.element,
    window._
);
ecwid_pb_defaults = EcwidGutenbergParams.ecwid_pb_defaults;