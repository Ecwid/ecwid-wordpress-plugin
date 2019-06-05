jQuery(document).ready(function() {

	function ecwidCheckApiCache() {
		jQuery.getJSON(
			ecwidCacheControlParams.ajax_url,
			{
				action: 'check_api_cache',
			}
		);
	}
	ecwidCheckApiCache();

})