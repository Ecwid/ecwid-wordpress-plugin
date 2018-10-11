/**
 * BLOCK: my-block
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';
import {EcwidIcons} from './icons.js';

const { __ } = wp.i18n; // Import __() from wp.i18n

const ecwidIcons = EcwidIcons;

const {
    BlockControls,
    registerBlockType,
} = wp.blocks;

const {
    RichText,
    InspectorControls,
    AlignmentToolbar,
} = wp.editor;

const {
    PanelBody,
    PanelRow,
	ToggleControl,
	ButtonGroup,
	Button,
	IconButton,
	BaseControl,
	Toolbar,
	Dropdown
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
registerBlockType( 'ecwid/store-block', {
	title: EcwidGutenbergParams.storeBlockTitle, // Block title.
	icon: ( 
		<svg className="dashicon" viewBox="0 0 20 20" width="20" height="20">
			<path d={ EcwidGutenbergParams.storeIcon }></path>
		</svg>
	), 
	category: 'common', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
    attributes: EcwidGutenbergStoreBlockParams.attributes,
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
		
		function setProp( props, name, value ) {
			props.setAttributes( { [name]: value } );
		}
		
		const editor = 		
			<div className="ec-store-block ec-store-block-product-browser">
				<div className="ec-store-block-header">
					<svg className="dashicon" viewBox="0 0 20 20" width="20" height="20">
						<path d={ EcwidGutenbergParams.storeIcon }></path>
					</svg>
					{ __( 'Your store will be shown here', 'ecwid-shopping-cart' ) }
				</div>
				<div className="ec-store-block-title">
					{ __( 'Store ID', 'ecwid-shopping-cart' ) }: { EcwidGutenbergParams.storeId }
				</div>
			</div>
		;
		
        function buildToggle(props, name, label) {
            return <ToggleControl
				label={ label }
				checked={ props.attributes[name] }
				onChange={ () => props.setAttributes( { [name]: ! props.attributes[name] } ) }
			/>
        }

        function buildToolbar(props, name, label, items) {
            return <BaseControl label={label}>
				<Toolbar
					controls={ items.map( function(item) {
                        return {
                            icon: EcwidIcons[item.icon],
                            title: item.title,
                            isActive: props.attributes[name] === item.value,
                            className: 'ecwid-toolbar-icon',
                            onClick: () =>
                                props.setAttributes( { [name]: item.value } )
                        }
                    } ) }
				/>
			</BaseControl>;
        }
        
        function buildSelect(props, name, label, items) {
			return <BaseControl label={ label }>
				<select onChange={ (event) => { props.setAttributes( { [name]:event.target.value } ) } }>
                    { items.map( function(item) {
                        return <option value={item.value} selected={ props.attributes[name] == item.value }>{item.title}</option>
                    })}
				</select>
			</BaseControl>;	
		}

        function buildTextbox(props, name, label) {
            return <BaseControl label={ label }>
				<input type="text" value={props.attributes[name]} onChange={ (event) => { props.setAttributes( { [name]:event.target.value } ) } } />
			</BaseControl>;
        }
		
		function buildButtonGroup(props, name, label, items) {
        	return <BaseControl label={label}>
				<ButtonGroup>
                    { items.map( function(item) {
                        return <Button isDefault isButton
									   isPrimary={ attributes[name] == item.value }
									   onClick={ () => props.setAttributes( { [name]: item.value } ) }>
                            { item.title }
						</Button>
                    } ) }
				</ButtonGroup>
			</BaseControl>;
		}
		
		function buildMessageWithTitle(title, message) {
			return <BaseControl label={title}>{ message }</BaseControl>;
		}
		
		function buildItem(props, name, type) {
        	
        	const item = EcwidGutenbergStoreBlockParams.attributes[name];
        	
        	if ( typeof type === 'undefined' ) {
        		type = item.type;
			}
			
			if ( type === 'default_category_id' ) {
        		if ( item.values && item.values.length > 1 ) {
        			type = 'select';
                } else {
        			type = 'textbox';
				}
			} 
        	
        	if ( type === 'buttonGroup' ) {
        		return buildButtonGroup( props, item.name, item.title, item.values );
        	} else if ( type === 'toolbar' ) {
        		return buildToolbar( props, item.name, item.title, item.values );
			} else if ( type === 'select' ) {
        		return buildSelect( props, item.name, item.title, item.values );
			} else if ( type === 'text' ) {
        		return buildMessageWithTitle( item.title, item.message );
			} else if ( type === 'textbox') {
                return buildTextbox( props, item.name, item.title );
            } else {
        		return buildToggle( props, item.name, item.title );
			}
        }
		
		const productMigrationWarning = buildMessageWithTitle(
			__( 'Migration warning', 'ecwid-shopping-cart' ),
			__( 'To improve the look and feel of your store and manage your storefront appearance here, please enable the “Next-gen look and feel of the product list on the storefront” option in your store dashboard (Settings → What’s New).', 'ecwid-shopping-cart' )
		);
        
        const cartIconMessage = buildMessageWithTitle(
        	__( 'Display cart icon', 'ecwid-shopping-cart' ),
			__( 'You can enable an extra shopping bag icon widget that will appear on your site pages. Open “Appearance → Customize → Ecwid” menu to enable it.', 'ecwid-shopping-cart' )
		);
		
		const isNewProductList = EcwidGutenbergStoreBlockParams.is_new_product_list;
		
        return ([
        	editor, 
			<InspectorControls>
				<PanelBody title={ __( 'Product List Appearance', 'ecwid-shopping-cart' ) }>
					{ isNewProductList && buildItem( props, 'product_list_show_product_images', 'toggle' ) }
					{ isNewProductList && attributes.product_list_show_product_images && 
						buildItem( props, 'product_list_image_size', 'buttonGroup' ) }
                    { isNewProductList && attributes.product_list_show_product_images &&
	                    buildItem( props, 'product_list_image_aspect_ratio', 'toolbar' ) }
                    { isNewProductList && buildItem( props, 'product_list_show_frame', 'toggle' ) }
                    { isNewProductList && buildItem( props, 'product_list_product_info_layout', 'toolbar' ) }
                    { isNewProductList && buildItem( props, 'product_list_title_behavior', 'select' ) }
                    { isNewProductList && buildItem( props, 'product_list_price_behavior', 'select' ) }
                    { isNewProductList && buildItem( props, 'product_list_sku_behavior', 'select' ) }
                    { isNewProductList && buildItem( props, 'product_list_buybutton_behavior', 'select' ) }
                    { isNewProductList && buildItem( props, 'product_list_show_additional_image_on_hover', 'toggle' ) }
					{ !isNewProductList && productMigrationWarning }
				</PanelBody>
				<PanelBody title={ __( 'Category List Appearance', 'ecwid-shopping-cart' ) }>
                    { isNewProductList && buildItem( props, 'product_list_category_title_behavior', 'select' ) }
                    { isNewProductList && attributes.product_list_category_title_behavior !== 'SHOW_TEXT_ONLY' &&
                    buildItem( props, 'product_list_category_image_size', 'buttonGroup' ) }
                    { isNewProductList && attributes.product_list_category_title_behavior !== 'SHOW_TEXT_ONLY' &&
                    buildItem( props, 'product_list_category_image_aspect_ratio', 'toolbar' ) }
                    { !isNewProductList && productMigrationWarning }
				</PanelBody>
				<PanelBody title={ __( 'Store Navigation', 'ecwid-shopping-cart' ) }>
					{ buildItem( props, 'show_categories', 'toggle' ) }
                    { buildItem( props, 'show_search', 'toggle' ) }
                    { buildItem( props, 'show_breadcrumbs', 'toggle' ) }
                    { isNewProductList && buildItem( props, 'show_footer_menu', 'toggle' ) }
                    { buildItem( props, 'show_signin_link', 'toggle' ) }
                    { buildItem( props, 'product_list_show_sort_viewas_options', 'toggle' ) }
					{ cartIconMessage }
				</PanelBody>
				<PanelBody title={ __( 'Store Front Page', 'ecwid-shopping-cart' ) }>
                    { buildItem( props, 'default_category_id', 'default_category_id' ) }
				</PanelBody>
			</InspectorControls>
        ]); 
	},

	save: function( props ) {
        var shortcodeAttributes = {};
        for ( var i in EcwidGutenbergParams.ownAttributes ) {
            if ( EcwidGutenbergParams.ownAttributes.hasOwnProperty(i) ) {
                shortcodeAttributes[i] = props.attributes[i];
            }
        }

        var shortcode = new wp.shortcode({
            'tag': EcwidGutenbergParams.storeShortcodeName,
            'attrs': shortcodeAttributes,
            'type': 'single'
        });

        return shortcode.string();	},

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
        }
    ],
	
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

} );
