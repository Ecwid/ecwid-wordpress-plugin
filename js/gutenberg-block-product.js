( function( blocks, components, i18n, element, _ ) {
    var el = element.createElement;

    var saveCallback = function( params ) {
        
        var attributes = {
            'id': params.newProps.id
        };
        
        for (var i in {center_align:1, show_border:1, show_price_on_button:1}) {
            attributes[i] = params.newProps[i];
        }
        
        for (var i in {picture:1,title:1,price:1,options:1,qty:1,addtobag:1}) {
            attributes['show_' + i] = params.newProps.display.indexOf(i) != -1;
        }
        
        attributes.productName = params.newProps.product.name;
        attributes.productSKU = params.newProps.product.sku;
        attributes.productImageURL = params.newProps.product.thumb;
        
        params.originalProps.setAttributes(attributes);
    }
    
    var ecwidBlockParams = {
        title: EcwidGutenbergParams.productBlockTitle,
        icon: el('div', {className:"ecwid-product-block-icon"}),
        category: 'common',
        attributes: {
            id: { type: 'integer' },
            show_picture: { type: 'boolean' },
            show_title: { type: 'boolean' },
            show_price: { type: 'boolean' },
            show_options: { type: 'boolean' },
            show_qty: { type: 'boolean' },
            show_addtobag: { type: 'boolean' },
            show_price_on_button: { type: 'boolean' },
            show_border: { type: 'boolean' },
            center_align: { type: 'boolean' },
            productName: {type: 'string' },
            productSKU: {type: 'string' },
            productImageURL: {type: 'string' }
        },

        edit: function( props ) {

            var imageUrl = props.attributes.productImageURL;
            var productName = props.attributes.productName;
            
            if (!imageUrl)
            imageUrl = 'https://ecwid-images.scdn4.secure.raxcdn.com/images.ecwid.com/default-store/00006.jpg';
            if (!productName)productName = 'Horse Radish';
            
            return el( 'div', {className: 'ecwid-product-block' },
                el( 'div', { className: 'ecwid-product-block-header' }, 
                    el( 'img', { className: 'ecwid-product-block-header-icon', 'src': 'http://localhost/wordpress/49/wp-content/plugins/ecwid-shopping-cart/images/gutenberg-block-product.svg'} ), 
                    'Your Product' 
                ), el( 'div', { className: 'ecwid-product-block-image' }, el( 'img', {src: imageUrl } )
                ),
                el( 'div', { className: 'ecwid-product-block-title' } , productName ),
                el( 'div', {}, 
                    el( 'button', { className: 'button ecwid-product-block-button', onClick: function() { var params = {'saveCallback':saveCallback, 'props': props}; ecwid_open_product_popup( params ); } }, i18n.__( 'Edit Appearance' ) ) 
                )
            );
        },
        save: function( props ) {
            return null;
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
    blocks.registerBlockType( EcwidGutenbergParams.productBlock, ecwidBlockParams);

} )(
    window.wp.blocks,
    window.wp.components,
    window.wp.i18n,
    window.wp.element,
    window._
);
ecwid_pb_defaults = EcwidGutenbergParams.ecwid_pb_defaults;