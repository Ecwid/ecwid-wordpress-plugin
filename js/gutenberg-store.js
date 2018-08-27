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
    
    EcwidGutenbergParams.attributes = {
        widgets: { type: 'string', default: 'productbrowser' },
        default_category_id: { type: 'integer', default: 0 },
        default_product_id: { type: 'integer', default: 0 }
    };

    // Cuz save knows nothing about the original params object, and I need to know the exact original attributes order
    // Cuz their validation routine sucks and does not allow a tiny little difference between expected and actual block content
    EcwidGutenbergParams.ownAttributes = Object.assign({}, EcwidGutenbergParams.attributes);
    
    var ecwidStoreParams = {
        title: EcwidGutenbergParams.storeBlockTitle,
        icon: getIcon(),
        category: 'common',
        attributes: EcwidGutenbergParams.attributes,
        supports: {
            customClassName: false,
            className: false,
            html: false,
            multiple: false
        },
        
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
        },
        save: function( props ) {
            var shortcodeAttributes = {};
            for ( var i in EcwidGutenbergParams.ownAttributes ) {
                if ( EcwidGutenbergParams.ownAttributes.hasOwnProperty(i) ) {
                    shortcodeAttributes[i] = props.attributes[i];
                }
            }
            
            var shortcode = new wp.shortcode({
                'tag': EcwidGutenbergParams.storeShortcodeName,
                'attrs': shortcodeAttributes,
                'type': 'single'
            });
            
            return shortcode.string();
        },

        deprecated: [
            {
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

                save: function( props ) {
                    return null;
                },
            }
        ],
        
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