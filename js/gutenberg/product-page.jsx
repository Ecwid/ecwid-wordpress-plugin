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
import { EcwidControls, EcwidInspectorSubheader, EcwidProductBrowserBlock, EcwidImage } from '../includes/controls.js';

const { __, _x } = wp.i18n;

const {
    registerBlockType,
} = wp.blocks;

const {
    InspectorControls,
} = wp.editor;

const {
    PanelBody,
    BaseControl
} = wp.components;

const { withState } = wp.compose;

const blockName = 'ec-store/product-page';

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
registerBlockType( 'ec-store/product-page', {
    title: __( 'Product Card Large', 'ecwid-shopping-cart' ),
    icon: EcwidIcons.product,
    category: 'ec-store', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
    attributes: blockParams.attributes,
    description: __( 'Display product page with description and a buy button', 'ecwid-shopping-cart' ),
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

        const saveCallback = function( params ) {

            const attributes = {
                'default_product_id': params.newProps.product.id
            };

            EcwidGutenbergParams.products[params.newProps.product.id] = {
                name: params.newProps.product.name,
                imageUrl: params.newProps.product.thumb
            };

            params.originalProps.setAttributes(attributes);
        };

        function openEcwidProductPopup( props ) {
            ecwid_open_product_popup( { 'saveCallback': saveCallback, 'props': props } );
        }

        const editor =
            <EcwidProductBrowserBlock icon={ EcwidIcons.product } title={ __( 'Product Card Large') }>
                <EcwidImage src="product-page-preview.png" />
                { !attributes.default_product_id &&

                    <div className="button-container">
                        <button className="button ec-store-block-button" onClick={ () => { var params = {'saveCallback':saveCallback, 'props': props}; ecwid_open_product_popup( params ); } }>{ EcwidGutenbergParams.chooseProduct }</button>
                    </div>
                }
            </EcwidProductBrowserBlock>;

        function buildDangerousHTMLMessageWithTitle( title, message ) {
            return <BaseControl label={ title }><div dangerouslySetInnerHTML={{ __html: message }} /></BaseControl>;
        }

        const productMigrationWarning = buildDangerousHTMLMessageWithTitle(
            '',
            __( 'To improve the look and feel of your store and manage your storefront appearance here, please enable the “Next-gen look and feel of the product list on the storefront” option in your store dashboard (“<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings → What’s New</a>”).', 'ecwid-shopping-cart' )
        );  

        const productDetailsMigrationWarning = buildDangerousHTMLMessageWithTitle(
            '',
            __( 'To improve the look and feel of your product page and manage your its appearance here, please enable the “Next-gen look and feel of the product page on the storefront” option in your store dashboard (“<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings → What’s New</a>”).', 'ecwid-shopping-cart' )
        );

        const isNewDetailsPage = blockParams.isNewDetailsPage;
        
        const controls = EcwidControls(blockParams.attributes, props);

        return ([
            editor,
            <InspectorControls>
                {attributes.default_product_id > 0 &&
                <div>
                    <div className="ec-store-inspector-row">
                        <label className="ec-store-inspector-subheader">{ __( 'Linked product', 'ecwid-shopping-cart' ) }</label>
                    </div>

                    <div className="ec-store-inspector-row">

                        { EcwidGutenbergParams.products && EcwidGutenbergParams.products[attributes.default_product_id] &&
                        <label>{ EcwidGutenbergParams.products[attributes.default_product_id].name }</label>
                        }

                        <button className="button" onClick={ () => openEcwidProductPopup( props ) }>{ __( 'Change', 'ecwid-shopping-cart' ) }</button>
                    </div>
                </div>
                }
                {!attributes.default_product_id &&
                <div className="ec-store-inspector-row">
                    <button className="button" onClick={ () => openEcwidProductPopup( props ) }>{ __( 'Choose product', 'ecwid-shopping-cart' ) }</button>
                </div>
                }

                <PanelBody title={ __( 'Appearance', 'ecwid-shopping-cart' ) } initialOpen={false}>
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
    }
} );
