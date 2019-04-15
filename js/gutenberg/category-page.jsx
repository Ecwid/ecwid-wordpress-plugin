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

const { __, _x } = wp.i18n; // Import __() from wp.i18n

const ecwidIcons = EcwidIcons;

const {
    registerBlockType,
} = wp.blocks;

const {
    InspectorControls,
} = wp.editor;

const {
    PanelBody,
    BaseControl,
} = wp.components;

const blockName = 'ec-store/category-page';

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
 
registerBlockType( 'ec-store/category-page', {
    title: __( 'Store Category Page', 'ecwid-shopping-cart' ), // Block title.
    icon: EcwidIcons.category,
    category: 'ec-store', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
    attributes: EcwidGutenbergStoreBlockParams.attributes,
    description: __( 'Display category page', 'ecwid-shopping-cart' ),
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
            <EcwidProductBrowserBlock icon={ EcwidIcons.category } title={ __( 'Store Category Page', 'ecwid-shopping-cart' ) } showDemoButton={ blockParams.isDemoStore }>
                <div className="ec-store-category-products">
                    <div className="ec-store-category-product1"></div>
                    <div className="ec-store-category-product2"></div>
                    <div className="ec-store-category-product3"></div>
                </div>
                <div className="ec-store-category-products">
                    <div className="ec-store-category-product4"></div>
                    <div className="ec-store-category-product5"></div>
                    <div className="ec-store-category-product6"></div>
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

        const isNewProductList = blockParams.isNewProductList;
        const isNewDetailsPage = blockParams.isNewDetailsPage;
        
        const controls = EcwidControls(blockParams.attributes, props);

        return ([
            editor,
            <InspectorControls>
                <div style={{height:"10px"}}></div>
                { !EcwidGutenbergParams.hasCategories &&
                    <div style={{margin:'10px'}}>    
                        <a href="admin.php?page=ec-store-admin-category-id-0-mode-edit" target="_blank" class="button button-primary">{ __('Add categories', 'ecwid-shopping-cart') }</a>
                    </div>
                }
                { EcwidGutenbergParams.hasCategories &&
                    [
                    !props.attributes.default_category_id &&
                        controls.select( 'default_category_id', __( 'Select category', 'ecwid-shopping-cart' ) ),
                    props.attributes.default_category_id && 
                        controls.select( 'default_category_id', __( 'Selected category', 'ecwid-shopping-cart' ) )
                    ]
                }
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
                    { !isNewDetailsPage && productDetailsMigrationWarning }
                </PanelBody>
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
        return null;
    },
} );
