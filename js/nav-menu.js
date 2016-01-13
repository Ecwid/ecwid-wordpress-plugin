jQuery(document).ready(function() {
    var ecwidClasses = {
        'ecwid-store': 'store',
        'ecwid-cart': 'cart',
        'ecwid-my-account': 'account',
        'ecwid-product-search': 'search',
        'ecwid-store-with-categories': 'storeWithCategories'
    };

    processEcwidLinks = function(element) {

        var ecwidLink = findEcwidLink(element);
        if (!findEcwidLink(element)) return;

        if (jQuery(element).hasClass('ecwid-link')) return;

        jQuery(element).addClass('ecwid-link');
        jQuery(element).find('.item-type').text(ecwid_l10n.store_page);

        if (isStoreWithCategories(element)) {

            var $message = jQuery('<p>')
                .addClass('ecwid-store-with-cats-message')
                .text(ecwid_l10n.reset_cache_message)
                .insertAfter(jQuery('.field-move', element));

            $target = jQuery('<p class="ecwid-store-with-cats-reset-cache">').insertAfter($message);

            jQuery('<span>')
                .text(ecwid_l10n.cache_updated)
                .addClass('ecwid-reset-categories-cache-updated')
                .appendTo($target);

            jQuery('<a>')
                .text(ecwid_l10n.reset_cats_cache)
                .attr('href', 'javascript:void(0);')
                .addClass('ecwid-reset-categories-cache')
                .appendTo($target)
                .click(function() {

                    var that = this;
                    jQuery(this).css('cursor', 'wait');
                    resetCache(function() {
                        jQuery(that).fadeOut(100, function() {
                            jQuery(that).prev('.ecwid-reset-categories-cache-updated').fadeIn(100, function() {
                                setTimeout(function () {
                                    jQuery(that).prev('.ecwid-reset-categories-cache-updated').fadeOut(500, function () {
                                        jQuery(that).fadeIn(500);
                                    })
                                }, 4000);
                            });
                        });

                        jQuery(that).css('cursor', 'pointer');
                    });
                });
        }
    }

    trackAddedMenuItems = function(element) {
        var ecwidLink = findEcwidLink(element);
        if (!findEcwidLink(element)) return;

        ecwid_kissmetrics_record('menu-items ' + ecwidClasses[ecwidLink] + 'Added');

    }

    findEcwidLink = function(element) {

        var classes = jQuery('.edit-menu-item-classes', element).val().split(' ');
        for (var i = 0; i < classes.length; i++) {
            if (ecwidClasses.hasOwnProperty(classes[i])) {
                return classes[i];
            }
        }

        return false;
    }

    isStoreWithCategories = function(element) {
        for (var i in ecwidClasses) {
            if (i == 'ecwid-store-with-categories') {
                return true;
            }
        }

        return false;
    }

    resetCache = function(callback) {

        jQuery.getJSON(
            'admin-ajax.php',
            {
                action: 'ecwid_reset_categories_cache'
            },
            callback
        );

    }

    jQuery('ul.menu > li.menu-item').each(function(idx, el) {
        processEcwidLinks(el);
    });

    jQuery('#ecwid_nav_links').insertAfter(jQuery('#add-page'));

    jQuery('#menu-to-edit').on('DOMNodeInserted', function(e) {
        if (!jQuery(e.srcElement).hasClass('menu-item')) return;

        processEcwidLinks(e.srcElement);

        trackAddedMenuItems(e.srcElement);
    });

});