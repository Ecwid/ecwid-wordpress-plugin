Ecwid.OnPageLoad.add(function(page) {
	if ( page.type != 'PRODUCT' || page.productId != ecwidPost.productId ) {
		location.href = ecwidPost.storePageUrl + location.hash;
	}
});