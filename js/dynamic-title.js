jQuery(document).ready(function() {
    if ( jQuery( '.entry-title' ).length > 0 && typeof Ecwid !== 'undefined' ) {
        Ecwid.OnPageLoaded.add(function(page) {
            
            var el = jQuery( '.entry-title' ).eq(0);
            if ( el.length === 0 ) return;
            
            var isCategory = page.type === 'CATEGORY';
            var isProduct = page.type === 'PRODUCT';

            if ( !isCategory && !isProduct ) return;
            
            var newTitle = jQuery('title').html();

            if ( isCategory && page.categoryId === 0 || newTitle.length === 0 ) {
                newTitle = ecwidOriginalTitle.title;
            } 

            el.html( newTitle );
        });
    }
});