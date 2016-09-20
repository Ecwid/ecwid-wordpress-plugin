Ecwid.OnPageLoad.add(function(page) {
	debugger;
	if ( page.type != 'PRODUCT' || page.productId != ecwidPost.productId ) {
		location.href = ecwidPost.storePageUrl + location.hash;
	}
});