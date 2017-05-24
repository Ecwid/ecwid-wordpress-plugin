jQuery(document).ready(function() {

    var ecwidClasses = {};
    for (var i in ecwid_nav_menu_params.items) {
        ecwidClasses[i] = ecwid_nav_menu_params.items[i].name;
    }

    if (ecwid_nav_menu_params.first_run && jQuery('#ecwid_nav_links-hide:checked').length == 0) {
        jQuery('#ecwid_nav_links-hide').click();
    }

    processEcwidLinks = function(element) {

        var ecwidLink = findEcwidLink(element);
        if (!findEcwidLink(element)) return;

        if (jQuery(element).hasClass('ecwid-link')) return;

        jQuery(element).addClass('ecwid-link');
        jQuery(element).find('.item-type').text(ecwid_nav_menu_params.store_page);

        if (isStoreWithCategories(element)) {

            var $message = jQuery('<p>')
                .addClass('ecwid-store-with-cats-message')
                .text(ecwid_nav_menu_params.reset_cache_message)
                .insertAfter(jQuery('.field-move', element));

            $target = jQuery('<p class="ecwid-store-with-cats-reset-cache">').insertAfter($message);

            jQuery('<span>')
                .text(ecwid_nav_menu_params.cache_updated)
                .addClass('ecwid-reset-categories-cache-updated')
                .appendTo($target);

            jQuery('<a>')
                .text(ecwid_nav_menu_params.reset_cats_cache)
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

    findEcwidLink = function(element) {

        for (var i in ecwidClasses) {
            if (jQuery(element).hasClass('menu-item-' + i)) {
                return i;
            }
        }

        return false;
    }

    isStoreWithCategories = function(element) {
        return jQuery(element).hasClass('menu-item-ecwid-store-with-categories');
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
    });

});