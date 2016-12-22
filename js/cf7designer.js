if (typeof(Ecwid) == 'object') {
	Ecwid.OnAPILoaded.add(function(page){
		jQuery('html').attr('id', 'ecwid_html')
	});
}