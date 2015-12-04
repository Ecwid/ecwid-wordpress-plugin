jQuery(document).ready(function() {
    jQuery('ul.menu > li.menu-item').each(function(idx, el) {
        processEcwidLinks(el);
    });


    function processEcwidLinks(element) {
        if (!isEcwidLink(element)) return;

        jQuery(element).addClass('ecwid-link');
        jQuery(element).find('.item-type').text(ecwid_l10n.store_page);

        if (isStoreWithCategories(element)) {
            $actions = jQuery('.menu-item-actions', element);
            jQuery('<span>')
                .text(ecwid_l10n.cache_updated)
                .addClass('ecwid-reset-categories-cache-updated')
                .appendTo($actions);

            jQuery('<a>')
                .text(ecwid_l10n.reset_cats_cache)
                .attr('href', 'javascript:void(0);')
                .addClass('ecwid-reset-categories-cache')
                .appendTo($actions)
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

    function isEcwidLink(element) {

        var ecwidClasses = ['ecwid-store', 'ecwid-cart', 'ecwid-account', 'ecwid-store-with-categories', 'ecwid-search'];
        var classes = jQuery('.edit-menu-item-classes', element).val().split(' ');
        for (var i = 0; i < classes.length; i++) {
            if (ecwidClasses.indexOf(classes[i]) != -1) {
                return true;
            }
        }

        return false;
    }

    function isStoreWithCategories(element) {
        var classes = jQuery('.edit-menu-item-classes', element).val().split(' ');
        for (var i = 0; i < classes.length; i++) {
            if (classes[i] == 'ecwid-store-with-categories') {
                return true;
            }
        }

        return false;
    }

    function resetCache(callback) {

        jQuery.getJSON(
            'admin-ajax.php',
            {
                action: 'ecwid_reset_categories_cache'
            },
            callback
        );

    }


});