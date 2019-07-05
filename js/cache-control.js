var is_ecwid_check_api_cache_started = false;

ecwidCheckApiCache = function(){

	if( typeof is_ecwid_check_api_cache_started == 'undefined' || is_ecwid_check_api_cache_started ) {
		return;
	}

	is_ecwid_check_api_cache_started = true;

	jQuery.getJSON(
		ecwidCacheControlParams.ajax_url,
		{
			action: 'check_api_cache',
		},
		function() {
			setTimeout( function(){
				is_ecwid_check_api_cache_started = false;
			}, 2000);
		}
	);
}

jQuery(document).ready(function() {

	ecwidCheckApiCache();

});