( function( blocks, components, i18n, element, _ ) {
    var el = element.createElement;
    
    var getIcon = function() {
        return el("svg", {
            "aria-hidden": !0,
            role: "img",
            focusable: "false",
            xmlns: "http://www.w3.org/2000/svg",
            className: "dashicon",
            width: 20,
            height: 20,
            viewBox: "0 0 20 20"
        }, el("path", {
            d: EcwidGutenbergParams.storeIcon
        }));
    }
    
    var ecwidStoreParams = {
        title: EcwidGutenbergParams.storeBlockTitle,
        icon: getIcon(),
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
        supports: {
            customClassName: false,
            className: false,
            html: false
        },
        useOnce: true,
        
        edit: function( props ) {

            return el( 'div', {className: 'ecwid-block' },
                el( 'div', { className: 'ecwid-block-header' },
                    getIcon(),
                    EcwidGutenbergParams.yourStoreWill
                ),
                el( 'div', { className: 'ecwid-block-title' } , EcwidGutenbergParams.storeIdLabel + ': ' + EcwidGutenbergParams.storeId ),
                el( 'div', {},
                    el( 'button', { className: 'button ecwid-block-button', onClick: function() { ecwid_open_store_popup( props ); } }, EcwidGutenbergParams.editAppearance )
                )
            );
            
            return el( 'div', { className: 'ecwid-store-block' }, 
                el( 'button', { className: 'button button-primary ecwid-block-button', onClick: function() { ecwid_open_store_popup( props ); } },  i18n.__( 'Edit Appearance' ) )
            );
        },
        save: function( props ) {
            
            return false;
            
            var shortcode = new wp.shortcode({
                'tag': EcwidGutenbergParams.storeShortcodeName,
                'attrs': props.attributes,
                'type': 'single'
            });
            
            return wp.blocks.serialize(
                wp.blocks.createBlock(EcwidGutenbergParams.storeBlock, props),
                el( element.RawHTML, null, shortcode.string() )
            );
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