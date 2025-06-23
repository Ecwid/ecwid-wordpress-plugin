import { __, _x } from '@wordpress/i18n';
import { BaseControl, ColorPalette, ColorIndicator } from '@wordpress/components';
import { useState } from '@wordpress/element';

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

export const ColorControl = ({ name, title, props }) => {
    const [manual, setManual] = useState(null);
    const [color, setColor] = useState(null);

    // Setting default value
    if (typeof props.attributes[name] === 'undefined') {
        props.attributes[name] = false;
    }

    const isManual =
        (manual === null && props.attributes[name] !== false && props.attributes[name] !== null && props.attributes[name] !== '') ||
        manual === 'manual';

    if (!isManual) {
        props.setAttributes({ [name]: false });
    } else if (color !== null) {
        props.setAttributes({ [name]: color === undefined ? false : color });
    }

    const currentValue = props.attributes[name] || '';

    const titleElement = (
        <span>
            {title}
            {currentValue !== null && <ColorIndicator colorValue={props.attributes[name]} />}
        </span>
    );

    function handleColorChange(newColor) {
        setColor(newColor === undefined ? false : newColor);
        props.setAttributes({ [name]: newColor === undefined ? false : newColor });
    }

    function handleSelectChange(event) {
        const newValue = event.target.value;
        setManual(newValue);

        if (newValue === 'auto') {
            setColor(false);
            props.setAttributes({ [name]: false });
        }
    }

    return (
        <BaseControl label={titleElement} className="ec-store-color-picker" __nextHasNoMarginBottom={true}>
            <select onChange={handleSelectChange} value={isManual ? 'manual' : 'auto'}>
                <option value="auto">{__('Detect automatically', 'ecwid-shopping-cart')}</option>
                <option value="manual">{__('Set manually', 'ecwid-shopping-cart')}</option>
            </select>

            {isManual && (
                <ColorPalette
                    value={currentValue}
                    colors={colors}
                    onChange={handleColorChange}
                />
            )}
        </BaseControl>
    );
};