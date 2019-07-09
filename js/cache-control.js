
ecwidCheckApiCache = function(){
	jQuery.getJSON(
		ecwidCacheControlParams.ajax_url,
		{
			action: 'check_api_cache',
		}
	);
}

jQuery(document).ready(function() {

	ecwidCheckApiCache();

});