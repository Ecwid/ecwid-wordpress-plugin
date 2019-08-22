<?php
/*
* This file is deprecated. It cannot be directly connected in your plugins. To work with the Ecwid store, use the official API: https://developers.ecwid.com/api-documentation
* With the help of Ecwid API, you can create an application that will extend Ecwid functionality in various ways.
*/

_deprecated_file( basename( __FILE__ ), '6.8.1', 'the official API (https://developers.ecwid.com/api-documentation)' );

class EcwidProductApi {

    function __construct($store_id) {
    	_deprecated_function( __FUNCTION__, '6.8.1' );
    }

	function get_request($url) {
		_deprecated_function( __FUNCTION__, '6.8.1' );
	}

    function process_request($url) {
    	_deprecated_function( __FUNCTION__, '6.8.1' );
    }

    function get_all_categories() {
    	_deprecated_function( __FUNCTION__, '6.8.1' );
    }

    function get_subcategories_by_id($parent_category_id = 0) {
    	_deprecated_function( __FUNCTION__, '6.8.1' );
    }

    function get_all_products() {
    	_deprecated_function( __FUNCTION__, '6.8.1' );
    }

    function get_products_by_category_id($category_id = 0) {
    	_deprecated_function( __FUNCTION__, '6.8.1' );
    }

    function get_product($product_id) {
    	_deprecated_function( __FUNCTION__, '6.8.1' );
    }

	function get_product_https($product_id) {
		_deprecated_function( __FUNCTION__, '6.8.1' );
	}

    function get_category($category_id) {
    	_deprecated_function( __FUNCTION__, '6.8.1' );
    }
        
    function get_batch_request($params) {
    	_deprecated_function( __FUNCTION__, '6.8.1' );
    }

    function get_random_products($count) {
    	_deprecated_function( __FUNCTION__, '6.8.1' );
    }
    
    function get_profile() {
    	_deprecated_function( __FUNCTION__, '6.8.1' );
    }

    function is_api_enabled() {
    	_deprecated_function( __FUNCTION__, '6.8.1' );
    }

    function get_method_response_stream($method) {
    	_deprecated_function( __FUNCTION__, '6.8.1' );
    }
}

?>