//  Import CSS.
import './style.scss';
import './editor.scss';

import {EcwidIcons} from '../icons.js';

if ( !EcwidGutenbergParams.isDemoStore ) {

const { __, _x } = wp.i18n; // Import __() from wp.i18n

const {
	InspectorControls
} = wp.editor;

const {
    PanelBody,
	BaseControl,
} = wp.components;

const {
    registerBlockType,
} = wp.blocks;

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
registerBlockType( 'ec-store/minicart', {
	title: __( 'Shopping Cart Icon', 'ecwid-shopping-cart' ),
	icon: EcwidIcons.cart, 
	category: 'ec-store', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	description: __( 'Display shopping bag link and summary', 'ecwid-shopping-cart' ),
    supports: {
        customClassName: false,
        className: false,
        html: false,
		isPrivate: !EcwidGutenbergParams.isApiAvailable,
		align: true,
		alignWide: false
    },
	attributes: EcwidGutenbergParams.minicartAttributes,

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

        function buildSelect(props, name, label, items) {
            return <BaseControl label={ label }>
				<select className="ec-store-inspector-select" onChange={ (event) => { props.setAttributes( { [name]:event.target.value } ) } }>
                    { items.map( function(item) {
                        return <option value={item.value} selected={ props.attributes[name] == item.value }>{item.title}</option>
                    })}
				</select>
			</BaseControl>;
        }

        function buildItem(props, name, type) {

            const item = EcwidGutenbergParams.minicartAttributes[name];

            if ( typeof type === 'undefined' ) {
                type = item.type;
            }

			return buildSelect( props, item.name, item.title, item.values );
        }
        
        const editor = <div className="ec-store-block ec-store-block-minicart">
			<div className="image">
			</div>
		</div>;
        
        return ([
        	editor,
			<InspectorControls>
				<PanelBody title={ __('Appearance', 'ecwid-shopping-cart') } initialOpen={true}>
				{ buildItem(props, 'layout', 'select' ) }
				{ buildItem(props, 'icon', 'select' ) }
				{ buildItem(props, 'fixed_shape', 'select' ) }
				</PanelBody>
			</InspectorControls>	
		]); 
	},

	save: function( props ) {
        return false;
    },
    
} );

}