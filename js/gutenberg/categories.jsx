//  Import CSS.
import './style.scss';
import './editor.scss';
import {EcwidIcons} from '../icons.js';

if ( !EcwidGutenbergParams.isDemoStore ) {

const {
	InspectorControls
} = wp.editor;

const { __, _x } = wp.i18n; // Import __() from wp.i18n

const {
    registerBlockType,
} = wp.blocks;

const blockName = 'ec-store/categories';

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
registerBlockType( 'ec-store/categories', {
	title: __( 'Store Categories Menu', 'ecwid-shopping-cart' ),
	icon: EcwidIcons.categories, 
	category: 'ec-store', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
	description: __( 'Display categories menu', 'ecwid-shopping-cart' ),
    supports: {
        customClassName: false,
        className: false,
        html: false,
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
		
        const editor = <div className="ec-store-block ec-store-block-categories">
			<div className="ec-store-block-header">
                { EcwidIcons.categories }
				{ __( 'Categories', 'ecwid-shopping-cart' ) }
			</div>
		</div>;
        
		const message = __( 'The block is hidden because you don\'t have categories in your store. <a target="_blank" href="admin.php?page=ec-store-admin-category-id-0-mode-edit">Add categories.</a>', 'ecwid-shopping-cart' );	
			
        return ([
        	editor,
			<InspectorControls>
				<div style={{ height: '10px' }}></div>

                { !blockParams.has_categories &&
				<div dangerouslySetInnerHTML={{__html: message}}/>
                }
			</InspectorControls>
        ]); 
	},

	save: function( props ) {
        return false;
    },
    
} );

}