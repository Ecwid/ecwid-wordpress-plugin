jQuery(document).ready(function() {
	if (typeof Ecwid != 'undefined') {
        Ecwid.OnPageLoaded.add(function (page) {
            if (page.type == 'PRODUCT') {

                jQuery.getJSON(
                    ecwidProduct.ajaxurl,
                    {'action': 'ecwid_get_post_link', 'product_id': page.productId},
                    function (result) {
                        $canonical = jQuery('link[rel=canonical]');
                        if ($canonical.length == 0) {
                            $canonical = jQuery('<link rel="canonical">').appendTo('head');
                        }
                        if (result) {
                            $canonical.attr('href', result);
                        }
                    }
                );
            }
        });
    }
});