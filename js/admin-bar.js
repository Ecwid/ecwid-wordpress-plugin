jQuery(document).ready(function() {

    if ( typeof Ecwid != 'undefined' ) {
        
        console.log( '***' );

        console.log( typeof ecwidEditPostLinkParams );

        var $post_edit_links = jQuery('[href*="/wp-admin/post.php"]').filter('[href*="action=edit"]'),
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
            Ecwid.OnPageLoaded.add(function(page){
                var is_product = ( page.type == 'PRODUCT' ),
                    is_subcategory = ( page.type == 'CATEGORY' && page.categoryId > 0 ),
                    new_url = url;

                 if( typeof text != 'undefined' ) new_text = text;

                if( is_product || is_subcategory ) {
                    if( typeof $bar != 'undefined' ) {
                        new_text = ( is_product ) ? ecwidEditPostLinkParams.languages.editProduct : ( is_subcategory ) ? ecwidEditPostLinkParams.languages.editCategory : text;
                    }

                    var id = ( is_product ) ? page.productId : page.categoryId; 
                    new_url = ecwidEditPostLinkParams.url + '#' + page.type.toLowerCase() + ':mode=edit&id=' + id;
                }

                if( typeof $bar != 'undefined' ) $bar.text( new_text );

                if( $post_edit_links.length ) $post_edit_links.attr( 'href', new_url );
            });
        }

        console.log( '***' );
    }

});