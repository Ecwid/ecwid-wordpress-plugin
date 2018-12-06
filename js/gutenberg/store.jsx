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

const { __, _x } = wp.i18n; // Import __() from wp.i18n

const ecwidIcons = EcwidIcons;

const {
    BlockControls,
    registerBlockType,
} = wp.blocks;

const {
    InspectorControls,
    AlignmentToolbar,
    withColors
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
    ColorPalette,
    ColorIndicator
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
	category: 'ec-store', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
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
		
		// legacy reset 
        props.setAttributes({widgets:''});
        
		const editor = 		
			<div className="ec-store-block ec-store-block-product-browser">
				<div className="ec-store-block-header">
					<svg className="dashicon" viewBox="0 0 20 20" width="20" height="20">
						<path d={ EcwidGutenbergParams.storeIcon }></path>
					</svg>
                    { EcwidGutenbergParams.isDemoStore && __( 'Demo store', 'ecwid-shopping-cart' ) }
                    { !EcwidGutenbergParams.isDemoStore && EcwidGutenbergStoreBlockParams.storeBlockTitle }
				</div>
                { EcwidGutenbergParams.isDemoStore &&
                <div>
                    <a className="button button-primary" href="admin.php?page=ec-store">{ __( 'Set up your store', 'ecwid-shopping-cart') }</a>
                </div>
                }
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


        function buildDangerousHTMLMessageWithTitle(title, message) {
            return <BaseControl label={title}><div dangerouslySetInnerHTML={{ __html: message }} /></BaseControl>;
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
            } else if ( type === 'colorPalette' ) {
                return buildColorPalette( props, item.name, item.title );
            } else if ( type === 'text' ) {
                return buildDangerousHTMLMessageWithTitle( item.title, item.message );
            } else if ( type === 'textbox') {
                return buildTextbox( props, item.name, item.title );
            } else {
                return buildToggle( props, item.name, item.title );
            }
        }

        const colors = [{
            name: __("Pale pink"),
            slug: "pale-pink",
            color: "#f78da7"
        }, {
            name: __("Vivid red"),
            slug: "vivid-red",
            color: "#cf2e2e"
        }, {
            name: __("Luminous vivid orange"),
            slug: "luminous-vivid-orange",
            color: "#ff6900"
        }, {
            name: __("Luminous vivid amber"),
            slug: "luminous-vivid-amber",
            color: "#fcb900"
        }, {
            name: __("Light green cyan"),
            slug: "light-green-cyan",
            color: "#7bdcb5"
        }, {
            name: __("Vivid green cyan"),
            slug: "vivid-green-cyan",
            color: "#00d084"
        }, {
            name: __("Pale cyan blue"),
            slug: "pale-cyan-blue",
            color: "#8ed1fc"
        }, {
            name: __("Vivid cyan blue"),
            slug: "vivid-cyan-blue",
            color: "#0693e3"
        }, {
            name: __("Very light gray"),
            slug: "very-light-gray",
            color: "#eeeeee"
        }, {
            name: __("Cyan bluish gray"),
            slug: "cyan-bluish-gray",
            color: "#abb8c3"
        }, {
            name: __("Very dark gray"),
            slug: "very-dark-gray",
            color: "#313131"
        }];
		
		function buildColorPalette(props, name, label ) {
        
            const titleElement = <span>{ label }
                    <ColorIndicator colorValue={ attributes[name]} />
            </span>;
            
            return <BaseControl label={titleElement} className="ec-store-color-picker">
                <ColorPalette
                    value={ attributes[name] }
                    colors={ colors }
                    onChange={ (color) => props.setAttributes( { [name]: color } ) }
                />
            </BaseControl>;
        }
        
        function getChameleonColorControl( { manual, color, setState } ) {
            const name = arguments[0].name;
            const props = arguments[0].props;
            const titleText = EcwidGutenbergStoreBlockParams.attributes[name].title;

            const isManual = manual === null && props.attributes[name] !== null && props.attributes[name] !== ""
                || manual === 'manual';
            if ( !isManual ) {
                props.setAttributes( { [name]: null } )
            } else if ( color !== null ) {
                props.setAttributes( { [name]: color } );
            }

            const currentValue = props.attributes[name];
            
            const titleElement = <span >{ titleText }
                { currentValue !== null && <ColorIndicator colorValue={ attributes[name] } /> }
            </span>;
                
            function colorPaletteChange( newColor ) {
                setState( (state) => ( { manual: 'manual', color: newColor } ) );
                props.setAttributes( { [name]: newColor } );
            }
                
            return <BaseControl label={titleElement} className="ec-store-color-picker">
                <select onChange={ (value) => setState( ( state ) => ( { manual: event.target.value, color: state.color } ) ) }>
                    <option value="auto" selected={ !isManual }>{ __( 'Detect automatically', 'ecwid-shopping-cart' ) }</option>
                    <option value="manual" selected={ isManual }>{ __( 'Set manually', 'ecwid-shopping-cart' ) }</option>
                </select>
                { isManual &&
                <ColorPalette
                    value={ currentValue }
                    colors={ colors }
                    onChange={ colorPaletteChange }
                >
                </ColorPalette>
                }
            </BaseControl>;
        }
            
        const ChameleonColorControl = withState( {manual: null, color: null} ) ( getChameleonColorControl );
		
		function simpleState( { count, setState } ) {
		    return <div>
                <button onClick={ () => setState( (state) => ( { count: state.count+1 } ) ) }>text {count} {arguments[0].color}</button>
            </div>
        }
        
        const Counter = withState( {count:0 } ) (simpleState);
        
		const productMigrationWarning = buildDangerousHTMLMessageWithTitle(
			'',
			__( 'To improve the look and feel of your store and manage your storefront appearance here, please enable the “Next-gen look and feel of the product list on the storefront” option in your store dashboard (“<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings → What’s New</a>”).', 'ecwid-shopping-cart' )
		);
        
        const cartIconMessage = buildDangerousHTMLMessageWithTitle(
        	__( 'Display cart icon', 'ecwid-shopping-cart' ),
            EcwidGutenbergParams.customizeMinicartText
		);
        
        const productDetailsMigrationWarning = buildDangerousHTMLMessageWithTitle(
            '',
            __( 'To improve the look and feel of your product page and manage your its appearance here, please enable the “Next-gen look and feel of the product page on the storefront” option in your store dashboard (“<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings → What’s New</a>”).', 'ecwid-shopping-cart' )
        );
		
		const isNewProductList = EcwidGutenbergStoreBlockParams.is_new_product_list;
        const isNewDetailsPage = EcwidGutenbergStoreBlockParams.is_new_details_page;
        
        const hasCategories = EcwidGutenbergStoreBlockParams.attributes.default_category_id && EcwidGutenbergStoreBlockParams.attributes.default_category_id.values && EcwidGutenbergStoreBlockParams.attributes.default_category_id.values.length > 0;
        
        return ([
        	editor, 
			<InspectorControls>
                { hasCategories &&
                
                <PanelBody title={ __('Category List Appearance', 'ecwid-shopping-cart') } initialOpen={false}>
                    { isNewProductList && buildItem(props, 'product_list_category_title_behavior', 'select') }
                    { isNewProductList && attributes.product_list_category_title_behavior !== 'SHOW_TEXT_ONLY' &&
                    buildItem(props, 'product_list_category_image_size', 'buttonGroup') }
                    { isNewProductList && attributes.product_list_category_title_behavior !== 'SHOW_TEXT_ONLY' &&
                    buildItem(props, 'product_list_category_image_aspect_ratio', 'toolbar') }
                    { !isNewProductList && productMigrationWarning }
                </PanelBody>
                
                }
                <PanelBody title={ __( 'Product List Appearance', 'ecwid-shopping-cart' ) } initialOpen={false}>
                    { isNewProductList && buildItem( props, 'product_list_show_product_images', 'toggle' ) }
                    { isNewProductList && attributes.product_list_show_product_images &&
                    buildItem( props, 'product_list_image_size', 'buttonGroup' ) }
                    { isNewProductList && attributes.product_list_show_product_images &&
                    buildItem( props, 'product_list_image_aspect_ratio', 'toolbar' ) }
                    { isNewProductList && buildItem( props, 'product_list_product_info_layout', 'toolbar' ) }
                    { isNewProductList && buildItem( props, 'product_list_title_behavior', 'select' ) }
                    { isNewProductList && buildItem( props, 'product_list_price_behavior', 'select' ) }
                    { isNewProductList && buildItem( props, 'product_list_sku_behavior', 'select' ) }
                    { isNewProductList && buildItem( props, 'product_list_buybutton_behavior', 'select' ) }
                    { isNewProductList && buildItem( props, 'product_list_show_additional_image_on_hover', 'toggle' ) }
                    { isNewProductList && buildItem( props, 'product_list_show_frame', 'toggle' ) }
                    { !isNewProductList && productMigrationWarning }
                </PanelBody>
                <PanelBody title={ __( 'Product Page Appearance', 'ecwid-shopping-cart' ) } initialOpen={false}>

                    { isNewDetailsPage && buildItem( props, 'product_details_layout', 'select' ) }
                    { 
                        isNewDetailsPage 
                        && ( 
                            attributes.product_details_layout == 'TWO_COLUMNS_SIDEBAR_ON_THE_RIGHT'
                            || attributes.product_details_layout == 'TWO_COLUMNS_SIDEBAR_ON_THE_LEFT'
                        ) && buildItem( props, 'show_description_under_image', 'toggle' ) 
                    }
                    { isNewDetailsPage && buildItem( props, 'product_details_gallery_layout', 'toolbar' ) }
                    { isNewDetailsPage &&
                    <PanelRow>
                        <label className="ec-store-inspector-subheader">{ __( 'Product sidebar content', 'ecwid-shopping-cart' ) }</label>
                    </PanelRow>
                    }
                    { isNewDetailsPage && buildItem( props, 'product_details_show_product_name', 'toggle' ) }
                    { isNewDetailsPage && buildItem( props, 'product_details_show_breadcrumbs', 'toggle' ) }
                    { isNewDetailsPage && buildItem( props, 'product_details_show_product_sku', 'toggle' ) }
                    { isNewDetailsPage && buildItem( props, 'product_details_show_product_price', 'toggle' ) }
                    { isNewDetailsPage && buildItem( props, 'product_details_show_qty', 'toggle' ) }
                    { isNewDetailsPage && buildItem( props, 'product_details_show_number_of_items_in_stock', 'toggle' ) }
                    { isNewDetailsPage && buildItem( props, 'product_details_show_in_stock_label', 'toggle' ) }
                    { isNewDetailsPage && buildItem( props, 'product_details_show_wholesale_prices', 'toggle' ) }
                    { isNewDetailsPage && buildItem( props, 'product_details_show_share_buttons', 'toggle' ) }
                    { !isNewDetailsPage && productMigrationWarning }
                </PanelBody>
                { hasCategories &&
  
                <PanelBody title={ __('Store Front Page', 'ecwid-shopping-cart') } initialOpen={false}>
                    { buildItem(props, 'default_category_id', 'default_category_id') }
                </PanelBody>
  
                }
                <PanelBody title={ __( 'Store Navigation', 'ecwid-shopping-cart' ) } initialOpen={false}>
                    { buildItem( props, 'show_categories', 'toggle' ) }
                    { buildItem( props, 'show_search', 'toggle' ) }
                    { buildItem( props, 'show_breadcrumbs', 'toggle' ) }
                    { isNewProductList && buildItem( props, 'show_footer_menu', 'toggle' ) }
                    { buildItem( props, 'show_signin_link', 'toggle' ) }
                    { buildItem( props, 'product_list_show_sort_viewas_options', 'toggle' ) }
                    { cartIconMessage }
                </PanelBody>
                <PanelBody title={ __( 'Color settings', 'ecwid-shopping-cart' ) } initialOpen={false}>
                    <ChameleonColorControl props={props} name="chameleon_color_button" />
                    <ChameleonColorControl props={props} name="chameleon_color_foreground" />
                    <ChameleonColorControl props={props} name="chameleon_color_price" />
                    <ChameleonColorControl props={props} name="chameleon_color_link" />
                    <ChameleonColorControl props={props} name="chameleon_color_background" />
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
            'default_category_id': typeof props.attributes.default_category_id != 'undefined' ? props.attributes.default_category_id  : 0
	    };

        const shortcode = new wp.shortcode({
            'tag': EcwidGutenbergParams.storeShortcodeName,
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
                    'tag': EcwidGutenbergParams.storeShortcodeName,
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
