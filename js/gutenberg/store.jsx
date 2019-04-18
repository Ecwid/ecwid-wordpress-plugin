/**
 * BLOCK: my-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';
import {EcwidIcons} from '../icons.js';
import { EcwidControls, EcwidInspectorSubheader, EcwidProductBrowserBlock } from '../includes/controls.js';

const { __, _x } = wp.i18n;

const {
    registerBlockType,
} = wp.blocks;

const {
    InspectorControls,
} = wp.editor;

const {
    PanelBody,
    PanelRow,
	ToggleControl,
	ButtonGroup,
	Button,
	BaseControl,
	Toolbar,
    ColorPalette,
    ColorIndicator
} = wp.components;

const { withState } = wp.compose;

const blockName = 'ecwid/store-block';

const blockParams = EcwidGutenbergParams.blockParams[blockName];
/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType( 'ecwid/store-block', {
	title: __( 'Store Home Page', 'ecwid-shopping-cart' ), // Block title.
	icon: EcwidIcons.store, 
	category: 'ec-store', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
    attributes: blockParams.attributes,
	description: __( 'Add storefront (product listing)', 'ecwid-shopping-cart' ),
    supports: {
        customClassName: false,
        className: false,
        html: false,
        multiple: false
    },

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit: function( props ) {
		
		const { attributes } = props;
		
		// legacy reset 
        props.setAttributes({widgets:''});
        
		const editor =
            <EcwidProductBrowserBlock icon={ EcwidIcons.store } title={ __( 'Store Home Page', 'ecwid-shopping-cart') } showDemoButton={ blockParams.isDemoStore }>
                <div className="ec-store-products">
                    <div className="ec-store-product1"></div>
                    <div className="ec-store-product2"></div>
                    <div className="ec-store-product3"></div>
                </div>
                <div className="ec-store-products">
                    <div className="ec-store-product4"></div>
                    <div className="ec-store-product5"></div>
                    <div className="ec-store-product6"></div>
                </div>
            </EcwidProductBrowserBlock>;
       
        function buildDangerousHTMLMessageWithTitle( title, message ) {
            return <BaseControl label={ title }><div dangerouslySetInnerHTML={{ __html: message }} /></BaseControl>;
        }

		const productMigrationWarning = buildDangerousHTMLMessageWithTitle(
			'',
			__( 'To improve the look and feel of your store and manage your storefront appearance here, please enable the “Next-gen look and feel of the product list on the storefront” option in your store dashboard (“<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings → What’s New</a>”).', 'ecwid-shopping-cart' )
		);
        
        const cartIconMessage = buildDangerousHTMLMessageWithTitle(
        	__( 'Display cart icon', 'ecwid-shopping-cart' ),
            blockParams.customizeMinicartText
		);
        
        const productDetailsMigrationWarning = buildDangerousHTMLMessageWithTitle(
            '',
            __( 'To improve the look and feel of your product page and manage its appearance here, please enable the “Next-gen look and feel of the product page on the storefront” option in your store dashboard (“<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings → What’s New</a>”).', 'ecwid-shopping-cart' )
        );
		
		const isNewProductList = blockParams.isNewProductList;
        const isNewDetailsPage = blockParams.isNewDetailsPage;
        
        const hasCategories = blockParams.attributes.default_category_id && blockParams.attributes.default_category_id.values && blockParams.attributes.default_category_id.values.length > 0;
        
        const controls = EcwidControls(blockParams.attributes, props);
        
        return ([
        	editor, 
			<InspectorControls>
                { hasCategories &&
                <PanelBody title={ __('Category List Appearance', 'ecwid-shopping-cart') } initialOpen={false}>
                    { isNewProductList && 
                        [
                            controls.select('product_list_category_title_behavior'),
                            attributes.product_list_category_title_behavior !== 'SHOW_TEXT_ONLY' &&
                            [
                                controls.buttonGroup('product_list_category_image_size'),
                                controls.toolbar('product_list_category_image_aspect_ratio'),
                            ]
                        ]
                    }
                    { !isNewProductList && productMigrationWarning }
                </PanelBody>
                }
                
                <PanelBody title={ __( 'Product List Appearance', 'ecwid-shopping-cart' ) } initialOpen={false}>
                { isNewProductList &&
                [
                    controls.toggle( 'product_list_show_product_images' ),
                    attributes.product_list_show_product_images && [
                        controls.buttonGroup( 'product_list_image_size' ),
                        controls.toolbar( 'product_list_image_aspect_ratio' )
                    ],
                    controls.toolbar( 'product_list_product_info_layout' ),
                    controls.select( 'product_list_title_behavior' ),
                    controls.select( 'product_list_price_behavior' ),
                    controls.select( 'product_list_sku_behavior' ),
                    controls.select( 'product_list_buybutton_behavior' ),
                    controls.toggle( 'product_list_show_additional_image_on_hover' ),
                    controls.toggle( 'product_list_show_frame' )
                ]
                }
                { !isNewProductList && productMigrationWarning }
                </PanelBody>

                <PanelBody title={ __( 'Product Page Appearance', 'ecwid-shopping-cart' ) } initialOpen={false}>
                    { isNewDetailsPage &&
                    [
                        controls.select('product_details_layout'),
                        ( attributes.product_details_layout === 'TWO_COLUMNS_SIDEBAR_ON_THE_RIGHT'
                        || attributes.product_details_layout === 'TWO_COLUMNS_SIDEBAR_ON_THE_LEFT' ) &&
                        controls.toggle('show_description_under_image'),
                        controls.toolbar('product_details_gallery_layout'),
                        EcwidInspectorSubheader( __('Product sidebar content', 'ecwid-shopping-cart') ),
                        controls.toggle('product_details_show_product_name'),
                        controls.toggle('product_details_show_breadcrumbs'),
                        controls.toggle('product_details_show_product_sku'),
                        controls.toggle('product_details_show_product_price'),
                        controls.toggle('product_details_show_qty'),
                        controls.toggle('product_details_show_number_of_items_in_stock'),
                        controls.toggle('product_details_show_in_stock_label'),
                        controls.toggle('product_details_show_wholesale_prices'),
                        controls.toggle('product_details_show_share_buttons'),
                    ]
                    }
                    { !isNewDetailsPage && productMigrationWarning }
                </PanelBody>
                
                { hasCategories &&
  
                <PanelBody title={ __('Store Front Page', 'ecwid-shopping-cart') } initialOpen={false}>
                    { controls.defaultCategoryId( 'default_category_id' ) }
                </PanelBody>
  
                }
                <PanelBody title={ __( 'Store Navigation', 'ecwid-shopping-cart' ) } initialOpen={false}>
                    { controls.toggle( 'show_categories') }
                    { controls.toggle( 'show_search') }
                    { controls.toggle( 'show_breadcrumbs') }
                    { isNewProductList && controls.toggle( 'show_footer_menu' ) }
                    { controls.toggle( 'show_signin_link') }
                    { controls.toggle( 'product_list_show_sort_viewas_options') }
                    { cartIconMessage }
                </PanelBody>
                <PanelBody title={ __( 'Color settings', 'ecwid-shopping-cart' ) } initialOpen={false}>
                    { controls.color( 'chameleon_color_button' ) }
                    { controls.color( 'chameleon_color_foreground' ) }
                    { controls.color( 'chameleon_color_price' ) }
                    { controls.color( 'chameleon_color_link' ) }
                    { controls.color( 'chameleon_color_background' ) }
                </PanelBody>
			</InspectorControls>
        ]); 
	},

	save: function( props ) {
	    
	    var widgets = ['productbrowser'];
	    if ( props.attributes.show_categories ) {
	        widgets[widgets.length] = 'categories';    
        }
        if ( props.attributes.show_search ) {
            widgets[widgets.length] = 'search';
        }
        const shortcodeAttributes = {
            'widgets': widgets.join(' '),
            'default_category_id': typeof props.attributes.default_category_id !== 'undefined' ? props.attributes.default_category_id  : 0
	    };

        const shortcode = new wp.shortcode({
            'tag': blockParams.shortcodeName,
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
        }, {
            attributes: {
                widgets: { type: 'string', default: 'productbrowser' },
                default_category_id: { type: 'integer', default: 0 }
            },
            
            migrate: function ( attributes ) {
                return {
                    'widgets': attributes.widgets,
                    'default_category_id': attributes.default_category_id
                }
            },
            
            save: function( props ) {
                var shortcodeAttributes = {};
                const attrs = ['widgets', 'default_category_id'];
                for ( var i = 0; i < attrs.length; i++ ) {
                    shortcodeAttributes[attrs[i]] = props.attributes[attrs[i]];
                }
                shortcodeAttributes.default_product_id = 0;

                var shortcode = new wp.shortcode({
                    'tag': blockParams.shortcodeName,
                    'attrs': shortcodeAttributes,
                    'type': 'single'
                });
                
                return shortcode.string();
            },    
        },
        {
            save: function( props ) {
                return '[ecwid]';
            },
        },
        {
            save: function( props ) {
                return '[ecwid widgets="productbrowser" default_category_id="0" default_product_id="0"]';
            },
        },
        {
            save: function( props ) {
                return '[ecwid widgets="productbrowser" default_category_id="0"]';
            },
        },
    ],
	
    transforms: {
        from: [{
            type: 'shortcode',
            tag: ['ecwid', 'ec_store'],
            attributes: {
                default_category_id: {
                    type: 'integer',
                    shortcode: function(named) {
                        return named.default_category_id
                    }
                },
                show_categories: {
                    type: 'boolean',
                    shortcode: function(attributes) {
                        return attributes.named.widgets.indexOf('categories') !== -1
                    }
                },
                show_search: {
                    type: 'boolean',
                    shortcode: function(attributes) {
                        return attributes.named.widgets.indexOf('search') !== -1
                    }
                }
            },
            priority: 10
        }]
    },

} );
