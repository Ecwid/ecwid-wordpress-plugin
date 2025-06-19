import './style.scss';
import './editor.scss';

const { registerBlockType } = wp.blocks;
const { InspectorControls } = wp.blockEditor;
const {
    PanelBody,
    BaseControl,
    Modal,
    Button
} = wp.components;
const { useState, useRef } = wp.element;
const { __ } = wp.i18n;

import { EcwidIcons } from '../includes/icons.js';
import { EcwidControls, EcwidInspectorSubheader, EcwidProductBrowserBlock, EcwidImage, EcwidStoreBlockInner } from '../includes/controls.js';

const blockName = 'ecwid/store-block';
const blockParams = EcwidGutenbergParams.blockParams[blockName];

registerBlockType('ecwid/store-block', {
    title: __('Store Home Page', 'ecwid-shopping-cart'), // Block title.
    icon: EcwidIcons.store,
    category: 'ec-store', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
    attributes: blockParams.attributes,
    description: __('Add storefront (product listing)', 'ecwid-shopping-cart'),
    supports: {
        customClassName: false,
        className: false,
        html: false,
        multiple: false,
        inserter: EcwidGutenbergParams.isWidgetsScreen ? false : true
    },
    example: {},

    /**
     * The edit function describes the structure of your block in the context of the editor.
     * This represents what the editor will render when the block is used.
     *
     * The "edit" property must be a valid function.
     *
     * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
     */
    edit: function (props) {

        if (Object.keys(props.attributes).length <= 1) {
            for (var key in blockParams.attributes) {
                if (blockParams.attributes.hasOwnProperty(key)) {
                    props.attributes[key] = blockParams.attributes[key].default
                }
            }
        }

        const { attributes } = props;

        // legacy reset 
        props.setAttributes({ widgets: '' });

        function buildDangerousHTMLMessageWithTitle(title, message) {
            return <BaseControl label={title}><div dangerouslySetInnerHTML={{ __html: message }} /></BaseControl>;
        }

        const productMigrationWarning = buildDangerousHTMLMessageWithTitle(
            '',
            __('To improve the look and feel of your store and manage your storefront appearance here, please enable the “Next-gen look and feel of the product list on the storefront” option in your store dashboard (“<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings → What’s New</a>”).', 'ecwid-shopping-cart')
        );

        const cartIconMessage = buildDangerousHTMLMessageWithTitle(
            __('Display cart icon', 'ecwid-shopping-cart'),
            blockParams.customizeMinicartText
        );

        const productDetailsMigrationWarning = buildDangerousHTMLMessageWithTitle(
            '',
            __('To improve the look and feel of your product page and manage its appearance here, please enable the “Next-gen look and feel of the product page on the storefront” option in your store dashboard (“<a href="admin.php?page=ec-store&ec-store-page=whatsnew">Settings → What’s New</a>”).', 'ecwid-shopping-cart')
        );

        const isNewProductList = blockParams.isNewProductList;
        const isNewDetailsPage = blockParams.isNewDetailsPage;
        const isEnabledProductSubtitles = blockParams.isEnabledProductSubtitles;
        const isLivePreviewEnabled = blockParams.isLivePreviewEnabled;

        const hasCategories = blockParams.attributes.default_category_id && blockParams.attributes.default_category_id.values && blockParams.attributes.default_category_id.values.length > 0;
        const needShowCategories = hasCategories && attributes.storefront_view == 'DEFAULT_CATEGORY_ID';

        if (attributes.show_description_under_image) {
            if (attributes.product_details_layout == 'TWO_COLUMNS_SIDEBAR_ON_THE_LEFT')
                props.setAttributes({ product_details_two_columns_with_left_sidebar_show_product_description_on_sidebar: !attributes.show_description_under_image });

            if (attributes.product_details_layout == 'TWO_COLUMNS_SIDEBAR_ON_THE_RIGHT')
                props.setAttributes({ product_details_two_columns_with_right_sidebar_show_product_description_on_sidebar: !attributes.show_description_under_image });
        } else {
            props.setAttributes({
                product_details_two_columns_with_left_sidebar_show_product_description_on_sidebar: '',
                product_details_two_columns_with_right_sidebar_show_product_description_on_sidebar: ''
            });
        }

        if (!needShowCategories) {
            props.setAttributes({ default_category_id: '' });
        }

        if (!hasCategories) {
            props.setAttributes({ storefront_view: 'COLLAPSE_CATEGORIES' });
        }

        const controls = EcwidControls(blockParams.attributes, props);

        const [isProductPage, setIsProductPage] = useState(false);
        const itemsPanelBodyRef = useRef([]);

        const panelBodyElement = (el) => {
            let i = itemsPanelBodyRef.current.length;
            if (el !== null) itemsPanelBodyRef.current[i] = el;
        };

        const isPreviewInFrame = () => {
            return document.querySelector('[name=editor-canvas]') != null ? true : false;
        }

        const getPreviewFrameContent = () => {
            return document.querySelector('[name=editor-canvas]').contentWindow;
        }

        let w = window;
        if (isPreviewInFrame()) w = getPreviewFrameContent();

        const handleOnToggle = (isToggled) => {
            if (!isLivePreviewEnabled) return;

            if (isToggled) {
                setIsProductPage(false);
                itemsPanelBodyRef.current.map(function (e) {
                    if (e.classList.contains('is-opened')) {
                        e.querySelector('button').click();

                        if (e.classList.contains('ec-store-panelbody-product-details')) {
                            if (attributes.storefront_view == 'FILTERS_PAGE')
                                w.Ecwid.openPage('search');
                            else
                                w.Ecwid.openPage('category');
                        }
                    }
                });
            }
        }

        const handleOnToggleProduct = (isToggled) => {
            if (!isLivePreviewEnabled) return;

            if (isToggled) {
                setIsProductPage(true);
                handleOnToggle(isToggled);
                w.Ecwid.openPage('product', { 'id': blockParams.randomProductId });
            }
        }

        const [isOpen, setOpen] = useState(false);
        const openModal = () => setOpen(true);
        const closeModal = () => setOpen(false);

        if (typeof w.Ecwid != 'undefined') {
            w.Ecwid.OnPageLoaded.add(function (page) {
                if (page.type == 'PRODUCT') {
                    blockParams.randomProductId = page.productId;
                }
            });

            w.Ecwid.OnPageSwitch.add(function (page) {
                if (page.type != 'PRODUCT' && page.type != 'CATEGORY' && page.type != 'SEARCH') {
                    openModal();
                    return false;
                }
            });
        }

        let editor =
            <div>
                <EcwidProductBrowserBlock props={props} attributes={attributes} icon={EcwidIcons.store} title={__('Store Home Page', 'ecwid-shopping-cart')} showDemoButton={blockParams.isDemoStore} isLivePreviewEnabled={isLivePreviewEnabled} blockParams={blockParams} isProductPage={isProductPage}>
                    <EcwidStoreBlockInner state={attributes.storefront_view} />
                </EcwidProductBrowserBlock>
                {isOpen && (
                    <Modal title="Edit Mode" onRequestClose={closeModal}>
                        <p>{__('The transition to this page is disabled in the editor, on a real storefront it works as it should.', 'ecwid-shopping-cart')}</p>
                        <Button variant="secondary" onClick={closeModal}>
                            {__('Continue Editing Page', 'ecwid-shopping-cart')}
                        </Button>
                    </Modal>
                )}
            </div>;

        return ([
            editor,
            <InspectorControls>
                {hasCategories &&
                    <PanelBody title={__('Category List Appearance', 'ecwid-shopping-cart')} initialOpen={false} ref={panelBodyElement} onToggle={handleOnToggle} >
                        {isNewProductList &&
                            [
                                controls.select('product_list_category_title_behavior'),
                                attributes.product_list_category_title_behavior !== 'SHOW_TEXT_ONLY' &&
                                [
                                    controls.buttonGroup('product_list_category_image_size'),
                                    controls.toolbar('product_list_category_image_aspect_ratio'),
                                ]
                            ]
                        }
                        {!isNewProductList && productMigrationWarning}
                    </PanelBody>
                }

                <PanelBody title={__('Product List Appearance', 'ecwid-shopping-cart')} initialOpen={false} ref={panelBodyElement} onToggle={handleOnToggle} >
                    {isNewProductList &&
                        [
                            controls.toggle('product_list_show_product_images'),
                            attributes.product_list_show_product_images && [
                                controls.buttonGroup('product_list_image_size'),
                                controls.toolbar('product_list_image_aspect_ratio')
                            ],
                            controls.toolbar('product_list_product_info_layout'),
                            controls.select('product_list_title_behavior'),
                            (isEnabledProductSubtitles) &&
                            controls.select('product_list_subtitles_behavior'),
                            controls.select('product_list_price_behavior'),
                            controls.select('product_list_sku_behavior'),
                            controls.select('product_list_buybutton_behavior'),
                            controls.toggle('product_list_show_additional_image_on_hover'),
                            controls.toggle('product_list_show_frame')
                        ]
                    }
                    {!isNewProductList && productMigrationWarning}
                </PanelBody>

                <PanelBody title={__('Product Page Appearance', 'ecwid-shopping-cart')} initialOpen={false} ref={panelBodyElement} onToggle={handleOnToggleProduct} className="ec-store-panelbody-product-details">
                    {isNewDetailsPage &&
                        [
                            controls.select('product_details_layout'),
                            (attributes.product_details_layout === 'TWO_COLUMNS_SIDEBAR_ON_THE_RIGHT'
                                || attributes.product_details_layout === 'TWO_COLUMNS_SIDEBAR_ON_THE_LEFT') &&
                            controls.toggle('show_description_under_image'),
                            controls.toolbar('product_details_gallery_layout'),
                            EcwidInspectorSubheader(__('Product sidebar content', 'ecwid-shopping-cart')),
                            controls.toggle('product_details_show_product_name'),
                            (isEnabledProductSubtitles) &&
                            controls.toggle('product_details_show_subtitle'),
                            controls.toggle('product_details_show_breadcrumbs'),
                            controls.toggle('product_details_show_product_sku'),
                            controls.toggle('product_details_show_product_price'),
                            controls.toggle('product_details_show_qty'),
                            controls.toggle('product_details_show_weight'),
                            controls.toggle('product_details_show_number_of_items_in_stock'),
                            controls.toggle('product_details_show_in_stock_label'),
                            controls.toggle('product_details_show_wholesale_prices'),
                            controls.toggle('product_details_show_share_buttons'),
                            controls.toggle('product_details_show_navigation_arrows'),
                            controls.toggle('product_details_show_product_photo_zoom'),
                        ]
                    }
                    {!isNewDetailsPage && productMigrationWarning}
                </PanelBody>

                {hasCategories &&
                    <PanelBody title={__('Store Front Page', 'ecwid-shopping-cart')} initialOpen={false} ref={panelBodyElement} onToggle={handleOnToggle} >
                        {controls.radioButtonWithDescription('storefront_view', isLivePreviewEnabled)}
                    </PanelBody>
                }

                <PanelBody title={__('Store Navigation', 'ecwid-shopping-cart')} initialOpen={false} ref={panelBodyElement} onToggle={handleOnToggle} >
                    {hasCategories &&
                        controls.toggle('show_categories')}
                    {controls.toggle('show_search')}
                    {controls.toggle('show_breadcrumbs')}
                    {isNewProductList && controls.toggle('show_footer_menu')}
                    {controls.toggle('show_signin_link')}
                    {controls.toggle('product_list_show_sort_viewas_options')}
                    {cartIconMessage}
                </PanelBody>
                <PanelBody title={__('Color settings', 'ecwid-shopping-cart')} initialOpen={false} ref={panelBodyElement} onToggle={handleOnToggle} >
                    {controls.color('chameleon_color_button')}
                    {controls.color('chameleon_color_foreground')}
                    {controls.color('chameleon_color_price')}
                    {controls.color('chameleon_color_link')}
                    {controls.color('chameleon_color_background')}
                </PanelBody>
            </InspectorControls>
        ]);
    },

    save: function (props) {

        var widgets = ['productbrowser'];
        if (props.attributes.show_categories) {
            widgets[widgets.length] = 'categories';
        }
        if (props.attributes.show_search) {
            widgets[widgets.length] = 'search';
        }
        const shortcodeAttributes = {
            'widgets': widgets.join(' '),
            'default_category_id': typeof props.attributes.default_category_id !== 'undefined' ? props.attributes.default_category_id : ''
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
                minicart_layout: { type: 'string' }
            },

            save: function (props) {
                return null;
            },
        }, {
            attributes: {
                widgets: { type: 'string', default: 'productbrowser' },
                default_category_id: { type: 'integer', default: 0 }
            },

            migrate: function (attributes) {
                return {
                    'widgets': attributes.widgets,
                    'default_category_id': attributes.default_category_id
                }
            },

            save: function (props) {
                var shortcodeAttributes = {};
                const attrs = ['widgets', 'default_category_id'];
                for (var i = 0; i < attrs.length; i++) {
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
            save: function (props) {
                return '[ecwid]';
            },
        },
        {
            save: function (props) {
                return '[ecwid widgets="productbrowser" default_category_id="0" default_product_id="0"]';
            },
        },
        {
            save: function (props) {
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
                    shortcode: function (named) {
                        return named.default_category_id
                    }
                },
                show_categories: {
                    type: 'boolean',
                    shortcode: function (attributes) {
                        return attributes.named.widgets.indexOf('categories') !== -1
                    }
                },
                show_search: {
                    type: 'boolean',
                    shortcode: function (attributes) {
                        return attributes.named.widgets.indexOf('search') !== -1
                    }
                }
            },
            priority: 10
        }]
    },

});
