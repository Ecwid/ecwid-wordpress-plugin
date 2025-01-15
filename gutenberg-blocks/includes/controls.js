const {
    ButtonGroup,
    Button,
    BaseControl,
    ToolbarGroup,
    ToggleControl,
    RadioControl,
    Notice
} = wp.components;

const { __ } = wp.i18n;

import { EcwidIcons } from "./icons.js";
import { ColorControl } from "./color.js";

import { useState } from '@wordpress/element';

function EcwidControls(declaration, properties) {

    const attributes = properties.attributes;

    let buildButtonGroup = function (props, name, label, items) {
        return <BaseControl label={label}>
            <ButtonGroup className="ec-store-inspector-button-group">
                {items.map(function (item) {
                    return <Button isPrimary={attributes[name] === item.value}
                        onClick={() => props.setAttributes({ [name]: item.value })}>
                        {item.title}
                    </Button>
                })}
            </ButtonGroup>
        </BaseControl>;
    };

    let buildToggle = function (props, name, label) {
        return <ToggleControl
            label={label}
            checked={props.attributes[name]}
            onChange={() => props.setAttributes({ [name]: !props.attributes[name] })}
        />
    };

    let buildSelect = function (props, name, label, items, callback = () => { }) {
        return <BaseControl label={label}>
            <select className="ec-store-control-select" onChange={(event) => { props.setAttributes({ [name]: event.target.value }); callback() }}>
                {items.map(function (item) {
                    return <option value={item.value} selected={props.attributes[name] == item.value}>{item.title}</option>
                })}
            </select>
        </BaseControl>;
    };

    let buildTextbox = function (props, name, label) {
        return <BaseControl label={label}>
            <input type="text" value={props.attributes[name]} onChange={(event) => { props.setAttributes({ [name]: event.target.value }) }} />
        </BaseControl>;
    };

    let buildToolbar = function (props, name, label, items) {
        return <BaseControl label={label}>
            <ToolbarGroup
                controls={items.map(function (item) {
                    return {
                        icon: EcwidIcons[item.icon],
                        title: item.title,
                        isActive: props.attributes[name] === item.value,
                        className: 'ecwid-toolbar-icon',
                        onClick: () =>
                            props.setAttributes({ [name]: item.value })
                    }
                })}
            />
        </BaseControl>;
    }

    let buildRadioButtonWithDescription = function (props, name, label, items) {

        const needShowCategories = props.attributes[name] == 'DEFAULT_CATEGORY_ID';
        const item = declaration['default_category_id'];

        let isPreviewInFrame = document.querySelector('[name=editor-canvas]') != null ? true : false;

        let w = window;
        if (isPreviewInFrame)
            w = document.querySelector('[name=editor-canvas]').contentWindow;

        const bodyDone = (value) => {
            if (typeof w.Ecwid != 'undefined' && value != 'FILTERS_PAGE') {

                if (w.document.getElementById('ec-store-preview') != null)
                    w.document.getElementById('ec-store-preview').innerHTML = '';

                setTimeout(function () {
                    w.ecwid_onBodyDone();
                }, 300);
            }
        }

        let select = '';
        if (item.values && item.values.length > 1) {
            select = buildSelect(props, item.name, item.title, item.values, bodyDone);
        }

        let options = items.map(function (item) {
            return {
                value: item.value,
                label: (
                    <div>
                        <span className="ec-store-inspector-radio__title">{item.title}</span>
                        <p>{item.description}</p>
                        {item.value == 'DEFAULT_CATEGORY_ID' && needShowCategories &&
                            [select]
                        }
                    </div>
                )
            };
        });

        return <BaseControl>
            <RadioControl
                label={label}
                className="ec-store-inspector-radio"
                options={options}
                selected={props.attributes[name]}
                onChange={(value) => { props.setAttributes({ [name]: value }); bodyDone(value); }}
            />
        </BaseControl>
    }

    return {
        buttonGroup: function (name) {
            const item = declaration[name];

            if (typeof properties.attributes[name] == 'undefined') {
                properties.attributes[name] = item.default;
            }

            return buildButtonGroup(properties, item.name, item.title, item.values);
        },
        toggle: function (name) {
            const item = declaration[name];

            if (typeof properties.attributes[name] == 'undefined') {
                properties.attributes[name] = item.default;
            }

            return buildToggle(properties, item.name, item.title);
        },
        select: function (name, title = null) {
            const item = declaration[name];

            if (typeof properties.attributes[name] == 'undefined') {
                properties.attributes[name] = item.default;
            }

            return buildSelect(properties, item.name, title ? title : item.title, item.values);
        },
        textbox: function (name) {
            const item = declaration[name];

            return builtTextbox(properties, item.name, item.title);
        },
        toolbar: function (name) {
            const item = declaration[name];

            if (typeof properties.attributes[name] == 'undefined') {
                properties.attributes[name] = item.default;
            }

            return buildToolbar(properties, item.name, item.title, item.values);
        },
        color: function (name) {
            return <ColorControl props={properties} name={name} title={declaration[name].title} />
        },
        defaultCategoryId: function (name) {
            const item = declaration[name];

            if (item.values && item.values.length > 1) {
                if (typeof properties.attributes[name] == 'undefined') {
                    properties.attributes[name] = item.default;
                }

                return buildSelect(properties, item.name, item.title, item.values);
            } else {
                return buildTextbox(properties, item.name, item.title);
            }
        },
        radioButtonWithDescription: function (name) {
            const item = declaration[name];

            if (typeof properties.attributes[name] == 'undefined') {
                properties.attributes[name] = item.default;
            }

            return buildRadioButtonWithDescription(properties, item.name, item.title, item.values);
        }
    }

}

function EcwidInspectorSubheader(title) {
    return <div className="ec-store-inspector-subheader-row">
        <label className="ec-store-inspector-subheader">
            {title}
        </label>
    </div>
};

function trackDynamicProperties(currentDocument, props, dynamicProps) {

    const blockProps = props.props;
    const dynamicProperties = dynamicProps.split(' ');

    const blockId = blockProps.clientId;
    const wrapperId = '#ec-store-block-' + blockId;

    const storedData = jQuery(currentDocument).find(wrapperId).data('ec-store-block-stored-properties');

    let changed = false;

    let propValues = {};

    for (let i = 0; i < dynamicProperties.length; i++) {
        let name = dynamicProperties[i];

        if (!storedData || blockProps.attributes[name] != storedData[name]) {
            changed = true;
        }

        propValues[name] = blockProps.attributes[name];
    }

    jQuery(currentDocument).find(wrapperId).data('ec-store-block-stored-properties', propValues);

    return changed;
}

function EcwidProductBrowserBlock(props) {
    const blockProps = props.props;
    const attributes = props.attributes;
    const blockId = blockProps.clientId;
    const showCats = blockProps.attributes.show_categories;
    const showSearch = blockProps.attributes.show_search;
    const render = typeof props.render === 'undefined' ? true : props.render;
    const wrapperId = 'ec-store-block-' + blockId;

    const isPreviewInFrame = () => {
        return document.querySelector('[name=editor-canvas]') != null ? true : false;
    }

    const getPreviewFrameContent = () => {
        return document.querySelector('[name=editor-canvas]').contentWindow;
    }

    let w = window;
    if (isPreviewInFrame()) w = getPreviewFrameContent();

    let widget = "productbrowser";

    let args = '';
    if (blockProps.attributes.default_category_id) {
        args = "defaultCategoryId=" + blockProps.attributes.default_category_id;
    } else if (blockProps.attributes.default_product_id) {
        args = "defaultProductId=" + blockProps.attributes.default_product_id;
    }

    let className = '';

    if (!props.isLivePreviewEnabled) {
        className = "ec-store-generic-block ec-store-dynamic-block";

        if (!render || !w.document.getElementById(wrapperId) || !w.document.getElementById(wrapperId).getAttribute('data-ec-store-rendered')) {
            className += " ec-store-block";
        }

        if (showCats) {
            className += " ec-store-with-categories";
        }

        if (showSearch) {
            className += " ec-store-with-search";
        }

        className += " ec-store-with-stub";
    }

    let changed = trackDynamicProperties(w.document, props, "default_product_id default_category_id show_search show_categories");

    if (render && changed) {

        w.document.getElementById(wrapperId)
            && w.document.getElementById(wrapperId).removeAttribute('data-ec-store-rendered');

        if ("undefined" != typeof EcwidGutenberg) {
            setTimeout(function () {
                EcwidGutenberg.refresh()
            });
        }
    }

    w.ec = w.ec || {};
    w.ec.storefront = w.ec.storefront || {};
    w.ec.config = w.ec.config || {};
    w.ec.config.chameleon = w.ec.config.chameleon || {};
    w.ec.config.chameleon.colors = [];
    w.ec.config.disable_all_cookies = true;

    Object.keys(attributes).map((i) => {
        let value = typeof blockProps.attributes[i] !== 'undefined' ?
            blockProps.attributes[i] : attributes.default;

        if (i.indexOf('chameleon') !== -1) {
            if (value) {
                w.ec.config.chameleon.colors['color-' + i.substr(16)] = value;
            }
        } else {
            if (typeof value != 'undefined') {
                w.ec.storefront[i] = value;
            }
        }
    });

    delete w.ec.storefront.enable_catalog_on_one_page;
    delete w.ec.storefront.show_root_categories;

    if (!!props.isLivePreviewEnabled) {

        const [lastBlockId, setLastBlockId] = useState('');

        const openPage = (page, params = {}) => {
            if (props.isProductPage) return;

            if ("undefined" != typeof w.Ecwid && w.Ecwid.openPage) {
                w.Ecwid.openPage(page, params);
            }
        }

        const clearUrlHash = () => {
            history.replaceState(null, null, ' ');
        }

        switch (attributes.storefront_view) {
            case 'EXPAND_CATEGORIES':
                w.ec.storefront.enable_catalog_on_one_page = true;
                clearUrlHash();
                break;

            case 'SHOW_ROOT_CATEGORIES':
                w.ec.storefront.show_root_categories = false;
                clearUrlHash();
                break;

            case 'FILTERS_PAGE':
                openPage("search");
                break;

            case 'DEFAULT_CATEGORY_ID':
            case 'COLLAPSE_CATEGORIES':
            default:
                w.ec.storefront.enable_catalog_on_one_page = false;
                clearUrlHash();
        }

        const loadScriptJS = (el) => {

            if (el == null) return;

            if (typeof w.Ecwid != 'undefined' && w.Ecwid.refreshConfig) w.Ecwid.refreshConfig();
            if (typeof w.Ecwid != 'undefined' && w.Ecwid.destroy) w.Ecwid.destroy();

            w.ecwid_script_defer = true;
            w.ecwid_dynamic_widgets = true;
            w._xnext_initialization_scripts = [];

            localStorage.setItem('ec_disabled_apps', "all");

            let was_opened_once = false;
            let searchNode = w.document.getElementById('ec-store-search-preview');
            let hasSearchBoxAdded = searchNode && searchNode.childNodes && searchNode.childNodes.length > 0;

            if (!!props.attributes.show_search && !hasSearchBoxAdded) {
                w._xnext_initialization_scripts.push({
                    widgetType: 'SearchWidget',
                    id: 'ec-store-search-preview',
                    arg: ''
                });
            }

            if (!!props.attributes.show_categories) {
                w._xnext_initialization_scripts.push({
                    widgetType: 'CategoriesV2',
                    id: 'ec-store-categories-preview',
                    arg: ["id=ec-store-categories-preview"]
                });
            }

            w._xnext_initialization_scripts.push({
                widgetType: 'ProductBrowser',
                id: 'ec-store-preview',
                arg: [args]
            });

            if (!w.document.getElementById('ec-store-script')) {
                var script = w.document.createElement('script');
                script.type = 'text/javascript';
                script.id = 'ec-store-script';
                script.src = EcwidGutenbergParams.scriptJsUrl;

                el.innerHTML = '';
                el.appendChild(script);

                script.addEventListener('load', function () {
                    if (typeof w.Ecwid == 'undefined') {
                        return;
                    }

                    w.ecwid_loader('ec-store-preview');

                    var nodes = w.document.getElementsByClassName('ec-cart-widget')
                    if (nodes.length > 0) {
                        w.Ecwid.init();
                    }

                    w.Ecwid.OnAPILoaded.add(function () {
                        if (attributes.storefront_view == 'FILTERS_PAGE' && !was_opened_once) {
                            openPage("search");
                            was_opened_once = true;
                        }
                    });

                    setLastBlockId(blockId);
                });
            } else {
                if (typeof w.Ecwid != 'undefined') {

                    if (lastBlockId != blockId) {
                        setLastBlockId(blockId);
                        w.ecwid_onBodyDone();
                    }

                    if (changed) {
                        w.document.getElementById('ec-store-preview').innerHTML = '';
                        w.ecwid_onBodyDone();
                    }
                }
            }
        }

        const noticeActions = [{
            'label': __('Set up your store', 'ecwid-shopping-cart'),
            'url': 'admin.php?page=ec-store',
            'variant': 'primary'
        }];

        return <div
            className={className}
            data-ec-store-widget={widget}
            data-ec-store-id={blockId}
            data-ec-store-args={args}
            data-ec-store-with-search={showSearch}
            data-ec-store-with-categories={showCats}
            id={wrapperId}>

            {props.attributes.show_search &&
                <div id="ec-store-search-preview" />
            }

            {props.attributes.show_categories &&
                <div id="ec-store-categories-preview" />
            }

            <div id="ec-store-preview" />

            <div ref={loadScriptJS} />

            {props.showDemoButton &&
                <Notice status="info" isDismissible={false} actions={noticeActions}>
                    <div style={{ margin: '0 0 12px 12px' }}>
                        {__('This is a demo store. Create your store to see your store products here.', 'ecwid-shopping-cart')}
                    </div>
                </Notice>
            }

        </div>
    } else {
        return <div
            className={className}
            data-ec-store-widget={widget}
            data-ec-store-id={blockId}
            data-ec-store-args={args}
            data-ec-store-with-search={showSearch}
            data-ec-store-with-categories={showCats}
            id={wrapperId}>

            <div className="ec-store-block-header">
                {props.icon}
                {props.title}
            </div>
            <div className="ec-store-block-content">
                {props.children}
            </div>
            {props.showDemoButton &&
                <div>
                    <a className="button button-primary" href="admin.php?page=ec-store">{__('Set up your store', 'ecwid-shopping-cart')}</a>
                </div>
            }
        </div>
    }
};

function EcwidImage(props) {
    const url = EcwidGutenbergParams.imagesUrl + props.src;
    let class_name = ''

    if (props.className != '') {
        class_name = props.className;
    }

    return <img src={url} className={class_name} />
}

function EcwidStoreBlockInner(props) {

    let products = function (title = '') {

        if (title != '') {
            title = <h5>{title}</h5>
        }

        return <div>
            <div className="ec-store-block-subheader">{__('Categories', 'ecwid-shopping-cart')}</div>
            <div className="ec-store-products">
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-category-sneaker"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-category-bag"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-category-shirt"></div>
                </div>
            </div>
            <div className="ec-store-block-subheader">{__('Featured Products', 'ecwid-shopping-cart')}</div>
            <div className="ec-store-products">
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-g_sneaker"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-p_shirt"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-b_hat"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
            </div>
        </div>;
    }

    let only_featured_products = function () {

        return <div>
            <div className="ec-store-block-subheader">{__('Featured Products', 'ecwid-shopping-cart')}</div>
            <div className="ec-store-products">
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-m_sneaker"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-p_shirt"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-g_hat"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
            </div>
            <div className="ec-store-products">
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-b_watch"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-y_bag"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-p_sneaker"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
            </div>
        </div>;
    }

    let category = function () {
        return <div>
            <div className="ec-store-block-subheader">{__('Category #1', 'ecwid-shopping-cart')}</div>
            <div className="ec-store-products">
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-y_sneaker"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-y_shirt"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-y_watch"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
            </div>
            <div className="ec-store-products">
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-y_bag"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-y_hat"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-y_sneaker"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
            </div>
        </div>;
    }

    let menu_mode = function () {
        return <div>
            <div className="ec-store-block-subheader">{__('Category #1', 'ecwid-shopping-cart')}</div>
            <div className="ec-store-products">
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-y_sneaker"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-y_shirt"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-y_watch"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
            </div>
            <div className="ec-store-block-subheader">{__('Category #2', 'ecwid-shopping-cart')}</div>
            <div className="ec-store-products">
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-g_sneaker"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-g_shirt"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-g_watch"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
            </div>
        </div>;
    }

    let filter = function (class_name = '') {
        return <div className={class_name}>
            <div className="ec-store-products">
                <div className="ec-store-product-block ec-store-product-filter">
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-y_shirt"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-b_watch"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
            </div>
            <div className="ec-store-products">
                <div className="ec-store-product-block" />
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-g_sneaker"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
                <div className="ec-store-product-block">
                    <div className="ec-store-product ec-store-product-g_hat"></div>
                    <div className="ec-store-stub-sample"></div>
                </div>
            </div>
        </div>;
    }

    switch (props.state) {
        case 'EXPAND_CATEGORIES':
            return menu_mode();

        case 'SHOW_ROOT_CATEGORIES':
            return only_featured_products();

        case 'DEFAULT_CATEGORY_ID':
            return category();

        case 'FILTERS_PAGE':
            return filter();

        case 'SEARCH_FILTERS_PAGE':
            return filter('ec-store-block-filters-page');

        default:
            return products();
    }
}

export { EcwidControls, EcwidInspectorSubheader, EcwidImage, EcwidProductBrowserBlock, EcwidStoreBlockInner };
