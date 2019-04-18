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

if ( !EcwidGutenbergParams.isDemoStore ) {

const { __, _x } = wp.i18n; // Import __() from wp.i18n

const {
    registerBlockType,
} = wp.blocks;

const {
    InspectorControls,
} = wp.editor;

const {
    PanelBody,
	ToggleControl,
} = wp.components;

const { withState } = wp.compose;

const {
    Fragment
} = wp.element;


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
registerBlockType( 'ecwid/product-block', {
	title: __( 'Product card small', 'ecwid-shopping-cart' ), // Block title.
	icon: EcwidIcons.product, 
	category: 'ec-store', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
    attributes: {
        id: {type: 'integer'},
        show_picture: {type: 'boolean', default: true},
        show_title: {type: 'boolean', default: true},
        show_price: {type: 'boolean', default: true},
        show_options: {type: 'boolean', default: true},
        show_qty: {type: 'boolean', default: false},
        show_addtobag: {type: 'boolean', default: true},
        show_price_on_button: {type: 'boolean', default: true},
        show_border: {type: 'boolean', default: true},
        center_align: {type: 'boolean', default: true}
    },
	description: __( 'Display product with a buy button', 'ecwid-shopping-cart' ),
    alignWide: false,
    supports: {
        customClassName: false,
        className: false,
        html: false,
        align: true,
		isPrivate: !EcwidGutenbergParams.isApiAvailable
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
                'id': params.newProps.product.id
            };
			
            EcwidGutenbergParams.products[params.newProps.product.id] = {
            	name: params.newProps.product.name,
				imageUrl: params.newProps.product.thumb
			};
            
            params.originalProps.setAttributes(attributes);
        }
		
        const editor = <div className="ec-store-block ec-store-block-product">
			{ EcwidGutenbergParams.products && attributes.id && EcwidGutenbergParams.products[attributes.id] &&
			<div className="ec-store-block-image">
				<img src={ EcwidGutenbergParams.products[attributes.id].imageUrl }/>
			</div>
            }
            
			{ EcwidGutenbergParams.products && attributes.id && EcwidGutenbergParams.products[attributes.id] &&
			<div className="ec-store-product-title">
				{ EcwidGutenbergParams.products[attributes.id].name }
			</div>
            }

            { !attributes.id &&
            <div className="ec-store-block-product-preview">
                { EcwidIcons.productPreview }
            </div>
            }
            
            { !attributes.id && 
            <div>
                <button className="button ec-store-block-button" onClick={ () => { var params = {'saveCallback':saveCallback, 'props': props}; ecwid_open_product_popup( params ); } }>{ EcwidGutenbergParams.chooseProduct }</button> 
            </div>
            }
			
		</div>;

        function buildToggle(props, name, label) {
            return <ToggleControl
				label={ label }
				checked={ props.attributes[name] }
				onChange={ () => props.setAttributes( { [name]: ! props.attributes[name] } ) }
			/>
        }

        function openEcwidProductPopup( props ) {
        	ecwid_open_product_popup( { 'saveCallback': saveCallback, 'props': props } );
		}
        
        return ([
        	editor, 
			<InspectorControls>

                { attributes.id && 
                    <div>
                        <div className="ec-store-inspector-row">
                            <label className="ec-store-inspector-subheader">{ __( 'Displayed product', 'ecwid-shopping-cart' ) }</label>
                        </div>
                        <div className="ec-store-inspector-row">
                            
                            { EcwidGutenbergParams.products && EcwidGutenbergParams.products[attributes.id] &&
                                <label>{ EcwidGutenbergParams.products[attributes.id].name }</label>
                            }
                            
                            <button className="button" onClick={ () => openEcwidProductPopup( props ) }>{ __( 'Change', 'ecwid-shopping-cart' ) }</button>
                        </div>
                    </div>
                }
                {!attributes.id &&
				<div className="ec-store-inspector-row">
					<button className="button" onClick={ () => openEcwidProductPopup( props ) }>{ __( 'Choose product', 'ecwid-shopping-cart' ) }</button>
				</div>
                }
                <PanelBody title={ _x( 'Content', 'gutenberg-product-block', 'ecwid-shopping-cart' ) } initialOpen={false}>
                    { buildToggle( props, 'show_picture', __( 'Picture', 'ecwid-shopping-cart' ) ) }
                    { buildToggle( props, 'show_title', __( 'Title', 'ecwid-shopping-cart' ) ) }
                    { buildToggle( props, 'show_price', __( 'Price', 'ecwid-shopping-cart' ) ) }
                    { buildToggle( props, 'show_options', __( 'Options', 'ecwid-shopping-cart' ) ) }
                    { buildToggle( props, 'show_qty', __( 'Quantity', 'ecwid-shopping-cart' ) ) }
                    { buildToggle( props, 'show_addtobag', __( '«Buy now» button', 'ecwid-shopping-cart' ) ) }
				</PanelBody>

				<PanelBody title={ __( 'Appearance', 'ecwid-shopping-cart' ) } initialOpen={false}>
                    { buildToggle( props, 'show_price_on_button', __( 'Show price inside the «Buy now» button', 'ecwid-shopping-cart' ) ) }
                    { buildToggle( props, 'show_border', __( 'Add border', 'ecwid-shopping-cart' ) ) }
                    { buildToggle( props, 'center_align', __( 'Center align on a page', 'ecwid-shopping-cart' ) ) }
				</PanelBody>
			</InspectorControls>
        ]); 
	},

	save: function( props ) {
        return false;
    },
    
} );

}