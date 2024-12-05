const {
    BaseControl,
    ColorPalette,
    ColorIndicator
} = wp.components;

const { withState } = wp.compose;

const { __ } = wp.i18n;

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

function getChameleonColorControl({ manual, color, setState }) {
    const name = arguments[0].name;
    const props = arguments[0].props;
    const titleText = arguments[0].title;

    if (typeof props.attributes[name] == 'undefined') props.attributes[name] = false;

    const isManual = manual === null && props.attributes[name] !== false && props.attributes[name] !== null && props.attributes[name] !== "" || manual === 'manual';

    if (!isManual) {
        props.setAttributes({ [name]: null })
    } else if (color !== null) {
        props.setAttributes({ [name]: color });
    }

    const currentValue = props.attributes[name] || '';

    const titleElement = <span >{titleText}
        {currentValue !== null && <ColorIndicator colorValue={props.attributes[name]} />}
    </span>;

    function colorPaletteChange(newColor) {
        setState((state) => ({ manual: 'manual', color: newColor }));
        props.setAttributes({ [name]: newColor });
    }

    return <BaseControl label={titleElement} className="ec-store-color-picker">
        <select onChange={(value) => setState((state) => ({ manual: event.target.value, color: state.color }))}>
            <option value="auto" selected={!isManual}>{__('Detect automatically', 'ecwid-shopping-cart')}</option>
            <option value="manual" selected={isManual}>{__('Set manually', 'ecwid-shopping-cart')}</option>
        </select>
        {isManual &&
            <ColorPalette
                value={currentValue}
                colors={colors}
                onChange={colorPaletteChange}
            >
            </ColorPalette>
        }
    </BaseControl>;
}

const ColorControl = withState({ manual: null, color: null })(getChameleonColorControl);
export { ColorControl }