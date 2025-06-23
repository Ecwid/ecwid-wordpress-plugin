import { registerBlockType } from '@wordpress/blocks';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

import './style.scss';
import './editor.scss';

import { EcwidIcons } from '../includes/icons.js';
import { EcwidProductBrowserBlock, EcwidImage } from '../includes/controls.js';

registerBlockType('ec-store/buynow', {
    title: __('Buy Now Button', 'ecwid-shopping-cart'),
    icon: EcwidIcons.button,
    category: 'ec-store', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.
    attributes: {
        id: { type: 'integer' },
        show_price_on_button: { type: 'boolean', default: true },
        center_align: { type: 'boolean', default: true }
    },
    description: __('Display a buy button', 'ecwid-shopping-cart'),
    supports: {
        customClassName: false,
        className: false,
        html: false,
        align: true,
        alignWide: false,
        inserter: EcwidGutenbergParams.isApiAvailable,
        isPrivate: !EcwidGutenbergParams.isApiAvailable
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

        const { attributes } = props;

        const saveCallback = function (params) {

            const attributes = {
                'id': params.newProps.id
            };

            EcwidGutenbergParams.products[params.newProps.id] = {
                name: params.newProps.product.name,
                imageUrl: params.newProps.product.thumb
            };

            params.originalProps.setAttributes(attributes);
        };

        const editor =
            <EcwidProductBrowserBlock props={props} attributes={attributes} icon={EcwidIcons.button} title={__('Buy Now Button', 'ecwid-shopping-cart')}>
                <div className="ec-store-block-cart-page">
                    <EcwidImage src="buy-now-preview.png" className="ec-store-block-buynow-preview" />
                </div>

                {!attributes.id &&
                    <div className="button-container">
                        <button className="button ec-store-block-button" onClick={() => { var params = { 'saveCallback': saveCallback, 'props': props }; ecwid_open_product_popup(params); }}>{EcwidGutenbergParams.chooseProduct}</button>
                    </div>
                }

            </EcwidProductBrowserBlock>;

        function buildToggle(props, name, label) {
            return <ToggleControl
                label={label}
                checked={props.attributes[name]}
                onChange={() => props.setAttributes({ [name]: !props.attributes[name] })}
            />
        }

        function openEcwidProductPopup(props) {
            ecwid_open_product_popup({ 'saveCallback': saveCallback, 'props': props });
        }

        return ([
            editor,
            <InspectorControls>
                {attributes.id &&
                    <PanelBody>
                        <div className="ec-store-inspector-row">
                            <label className="ec-store-inspector-subheader">{__('Linked product', 'ecwid-shopping-cart')}</label>
                        </div>

                        <div className="ec-store-inspector-row">

                            {EcwidGutenbergParams.products && EcwidGutenbergParams.products[attributes.id] &&
                                <label>{EcwidGutenbergParams.products[attributes.id].name}</label>
                            }

                            <button className="button" onClick={() => openEcwidProductPopup(props)}>{__('Change', 'ecwid-shopping-cart')}</button>
                        </div>
                    </PanelBody>
                }

                {!attributes.id &&
                    <PanelBody>
                        <button className="button" onClick={() => openEcwidProductPopup(props)}>{__('Choose product', 'ecwid-shopping-cart')}</button>
                    </PanelBody>
                }

                <PanelBody title={__('Appearance', 'ecwid-shopping-cart')} initialOpen={false}>
                    {buildToggle(props, 'show_price_on_button', __('Show price inside the «Buy now» button', 'ecwid-shopping-cart'))}
                    {buildToggle(props, 'center_align', __('Center align on a page', 'ecwid-shopping-cart'))}
                </PanelBody>
            </InspectorControls>
        ]);
    },

    save: function (props) {
        return false;
    },

});
