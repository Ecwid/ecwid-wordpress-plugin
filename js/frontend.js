jQuery(document).ready(function() {

    if (typeof window.Ecwid != 'undefined') {
        Ecwid.OnPageLoaded.add(function() {
            jQuery.get(ecwid_ajax_object.ajax_url,
                {'action': 'ecwid_ajax_seo_title', '_escaped_fragment_': window.location.hash, title_template: ecwid_ajax_object.title_template},
                function(new_title) {
                    if (new_title == ecwid_ajax_object.title_template.replace('ECWID_SEO_TITLE', '')) {
                        new_title = ecwid_ajax_object.original_title;
                    }
                    jQuery('title').text(new_title);
                }
            );
        });
    }

    jQuery('.ecwid-store-with-categories a').click(function() {jQuery(':focus').blur()});
})
