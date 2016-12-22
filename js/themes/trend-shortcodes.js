window.ecwid_script_defer = true;
window.ecwid_dynamic_widgets = true;

if (typeof Ecwid != 'undefined') Ecwid.destroy();

if (typeof ecwid_shortcodes != 'undefined' && typeof ecwid_store_id != 'undefined') {
	window._xnext_initialization_scripts = ecwid_shortcodes;

	if (!document.getElementById('ecwid-script')) {
		var script = document.createElement('script');
		script.charset = 'utf-8';
		script.type = 'text/javascript';
		script.src = 'https://app.ecwid.com/script.js?' + ecwid_store_id;
		script.id = 'ecwid-script'
		document.body.appendChild(script);
	} else {
		ecwid_onBodyDone();
	}

}
