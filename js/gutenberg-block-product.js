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
            
            if ( !props.attributes.id ) {
                return el( 'div', { className: 'ecwid-block' },
                    el( 'div', { className: 'ecwid-block-header' },
                        el( 'img', { className: 'ecwid-block-header-icon', 'src': 'http://localhost/wordpress/49/wp-content/plugins/ecwid-shopping-cart/images/gutenberg-block-product.svg'} ),
                        'Your Product'
                    ),
                    el( 'div', {},
                        el( 'button', { className: 'button ecwid-block-button', onClick: function() { var params = {'saveCallback':saveCallback, 'props': props}; ecwid_open_product_popup( params ); } }, EcwidGutenbergParams.chooseProduct )
                    )
                );
            }
            
            return el( 'div', {className: 'ecwid-block' },
                el( 'div', { className: 'ecwid-block-header' },
                    el('div', {className: 'ecwid-product-block-icon'} ),
                    'Your Product' 
                ), el( 'div', { className: 'ecwid-block-image' }, el( 'img', {src: imageUrl } )
                ),
                el( 'div', { className: 'ecwid-block-title' } , productName ),
                el( 'div', {}, 
                    el( 'button', { className: 'button ecwid-block-button', onClick: function() { var params = {'saveCallback':saveCallback, 'props': props}; ecwid_open_product_popup( params ); } }, EcwidGutenbergParams.editAppearance ) 
                )
            );
        },
        save: function( props ) {
            return false;
            var shortcode = new wp.shortcode({
                'tag': EcwidGutenbergParams.productShortcodeName,
                'attrs': props.attributes,
                'type': 'single'
            });

            return el( element.RawHTML, null, shortcode.string() );
        }
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