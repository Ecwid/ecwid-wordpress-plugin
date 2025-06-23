import { BaseControl } from '@wordpress/components';

/**
 * Returns a message with HTML inside, safely wrapped in BaseControl
 * @param {string} title - Block title
 * @param {string} message - HTML message (will be inserted as innerHTML)
 */
export function buildDangerousHTMLMessageWithTitle(title, message) {
    return (
        <BaseControl label={title} __nextHasNoMarginBottom={true}>
            <div dangerouslySetInnerHTML={{ __html: message }} />
        </BaseControl>
    );
}
