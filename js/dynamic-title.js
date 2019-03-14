jQuery(document).ready(function() {
    if ( jQuery( '.entry-title' ).length > 0 && typeof Ecwid !== 'undefined' ) {
        Ecwid.OnPageLoaded.add(function(page) {
            
            var alreadyFoundEl = jQuery('h1[data-ecwid-found-title]');
            var el = false;
            
            if ( alreadyFoundEl .length > 0 ) {
                el = alreadyFoundEl;
            } else {
                el = jQuery('h1').filter(
                    function(idx, el) {
                        if ( el.innerText == ecwidOriginalTitle.initialTitle ) 
                            return true; 
                    }
                );
                
                if ( el.length > 0 ) {
                    el.attr('data-ecwid-found-title', 'true');
                }
            }
            
            if ( el.length === 0 ) return;
            
            var isCategory = page.type === 'CATEGORY';
            var isProduct = page.type === 'PRODUCT';

            if ( !isCategory && !isProduct ) return;
            
            var newTitle = jQuery('title').html();

            if ( isCategory && page.categoryId === 0 || newTitle.length === 0 ) {
                newTitle = ecwidOriginalTitle.mainPageTitle;
            } 

            el.html( newTitle );
        });
    }
});