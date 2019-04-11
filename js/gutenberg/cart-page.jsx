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

const blockName = 'ec-store/cart-page';

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
registerBlockType( 'ec-store/cart-page', {
    title: __( 'Cart and Checkout', 'ecwid-shopping-cart' ), // Block title.
    icon: EcwidIcons.cartPage,
    category: 'ec-store', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
    attributes: blockParams.attributes,
    description: __( 'Display shopping cart and checkout page', 'ecwid-shopping-cart' ),
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
            <EcwidProductBrowserBlock icon={ EcwidIcons.cartPage } title={ __( 'Cart and Checkout') }>
                <EcwidImage src="cart-page-preview.png" />
            </EcwidProductBrowserBlock>;

        function buildDangerousHTMLMessageWithTitle( title, message ) {
            return <BaseControl label={ title }><div dangerouslySetInnerHTML={{ __html: message }} /></BaseControl>;
        }

        return ([
            editor
        ]);
    },

    save: function( props ) {
        return null;
    }
} );
