jQuery(document).ready(function() {

    if ( typeof Ecwid != 'undefined' ) {
        var $post_edit_links = jQuery('[href*="'+ecwidEditPostLinkParams.admin_url+'post.php"]').filter('[href*="action=edit"]'),
            url = ( $post_edit_links.length ) ? $post_edit_links.eq(0).attr( 'href' ) : false,
            $bar,
            text;

        $post_edit_links.each( function() {
            if( jQuery( this ).closest('#wpadminbar').length ) {
                $bar = jQuery( this );
                text = $bar.text();
            }
        });

        if( url ) {
            Ecwid.OnPageLoad.add(function(page){
                var is_product = ( page.type == 'PRODUCT' ),
                    is_subcategory = ( page.type == 'CATEGORY' && page.categoryId > 0 ),
                    new_url = url;

                 if( typeof text != 'undefined' ) new_text = text;

                if( is_product || is_subcategory ) {
                    if( typeof $bar != 'undefined' ) {
                        new_text = ( is_product ) ? ecwidEditPostLinkParams.languages.editProduct : ( is_subcategory ) ? ecwidEditPostLinkParams.languages.editCategory : text;
                    }

                    var id = ( is_product ) ? page.productId : page.categoryId,
                        hash = page.type.toLowerCase() + ':mode=edit&id=' + id;

                    if( ecwidEditPostLinkParams.is_api_available ) {
                        hash = encodeURIComponent( hash );
                    }

                    new_url = ecwidEditPostLinkParams.url + hash;
                }

                if( typeof $bar != 'undefined' ) {
                    $bar.text( new_text );
                }

                if( $post_edit_links.length ) {
                    $post_edit_links.attr( 'href', new_url );
                }
            });
        }
    }

});