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
import { EcwidControls, EcwidProductBrowserBlock, EcwidImage } from '../includes/controls.js';

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

const blockName = 'ec-store/filters-page';

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
registerBlockType( 'ec-store/filters-page', {
    title: __( 'Product Search and filters', 'ecwid-shopping-cart' ), // Block title.
    icon: EcwidIcons.filters,
    category: 'ec-store', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
    attributes: blockParams.attributes,
    description: __( 'Display search page with filters on a side', 'ecwid-shopping-cart' ),
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
        
        const editor =
            <EcwidProductBrowserBlock icon={ EcwidIcons.filters } title={ __( 'Search and Filters', 'ecwid-shopping-cart' ) }>
                <EcwidImage src="filter-preview.png" />
            </EcwidProductBrowserBlock>;
 /*       const editor =
            <div className="ec-store-block ec-store-block-filters-page">
                <div className="ec-store-block-header">
                    { EcwidIcons.filters }
                    { __( 'Filters Page', 'ecwid-shopping-cart' ) }
                </div>
                <div className="image">
                </div>
                { blockParams.isDemoStore &&
                <div>
                    <a className="button button-primary" href="admin.php?page=ec-store">{ __( 'Set up your store', 'ecwid-shopping-cart') }</a>
                </div>
                }
            </div>
        ;*/

        function buildDangerousHTMLMessageWithTitle( title, message ) {
            return <BaseControl label={ title }><div dangerouslySetInnerHTML={{ __html: message }} /></BaseControl>;
        }

        const filtersDisabledMessage = buildDangerousHTMLMessageWithTitle(
            '',
            __( 'You can enable filters in the store settings: (“<a target="_blank" href="admin.php?page=ec-store-admin-product-filters-mode-main">Settings → Product Filters</a>”).', 'ecwid-shopping-cart' )
        );
        
        const productMigrationWarning = buildDangerousHTMLMessageWithTitle(
            '',
            __( 'To improve the look and feel of your store and manage your storefront appearance here, please enable the “Next-gen look and feel of the product list on the storefront” option in your store dashboard (“<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings → What’s New</a>”).', 'ecwid-shopping-cart' )
        );  
        
        const isNewProductList = blockParams.isNewProductList;
        
        const controls = EcwidControls(blockParams.attributes, props);

        return ([
            editor,
            <InspectorControls>
                <PanelBody title={ __( 'Filters', 'ecwid-shopping-cart' ) } initialOpen={false}>
                    { !blockParams.filtersEnabled && filtersDisabledMessage }
                    { blockParams.filtersEnabled &&
                        [
                            controls.select( 'product_filters_position_search_page' )
                        ]
                    }
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

                <PanelBody title={ __( 'Store Navigation', 'ecwid-shopping-cart' ) } initialOpen={false}>
                    { controls.toggle( 'show_categories') }
                    { controls.toggle( 'show_breadcrumbs') }
                    { isNewProductList && controls.toggle( 'show_footer_menu' ) }
                    { controls.toggle( 'show_signin_link') }
                    { controls.toggle( 'product_list_show_sort_viewas_options') }
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
