<?php

class Ecwid_Integration_WPML
{
	public $hreflang_items;

	public function __construct()
	{
		add_filter( 'ecwid_lang', array( $this, 'force_scriptjs_lang' ) );
		add_filter( 'ecwid_relative_permalink', array( $this, 'mod_relative_permalink' ), 10, 2 );

		// dynamic 
		/*
		add_filter( 'wpml_hreflangs', function( $wpml_hreflangs ){

			if( is_array( $wpml_hreflangs ) ) {
				$hreflang_js = 'window.ec.config.storefrontUrls.enableHreflangTags = true;';
				$hreflang_js .= 'window.ec.config.storefrontUrls.internationalPages = {';
				foreach( $wpml_hreflangs as $lang => $url ) {
					$hreflang_js .= sprintf( '"%s": "%s",', $lang, $url );
				}
				$hreflang_js .= '};';
			}

			// add_filters( 'ecwid_inline_js_config', function( $js ){

			// 	var_dump('xxx');
			// 	var_dump($hreflang_js);

			// 	return $js;
			// });

			return $wpml_hreflangs;
		}, 10, 1 );
		*/

		// static
		add_filter( 'wpml_hreflangs', array( $this, 'set_hreflangs' ), 10, 1 );
	}

	public function set_hreflangs( $hreflang_items )
	{
		$this->hreflang_items = $hreflang_items;
		add_filter( 'ecwid_hreflangs', array( $this, 'get_hreflangs' ), 10, 1);

		return $hreflang_items;
	}

	public function get_hreflangs()
	{
		return $this->hreflang_items;
	}

	public function force_scriptjs_lang( $lang ) 
	{
		$current_language_code = apply_filters( 'wpml_current_language', null );
		return $current_language_code;
	}

	public function mod_relative_permalink( $default_link, $item_id )
	{
		global $sitepress;

		if ( $sitepress->get_setting( 'language_negotiation_type' ) == WPML_LANGUAGE_NEGOTIATION_TYPE_DIRECTORY ) {

			$translation_details = apply_filters( 'wpml_element_language_details', null, array(
				'element_id'   => $item_id,
				'element_type' => 'post_page'
			) );

			$code = $translation_details->language_code;

			$lang_info = apply_filters( 'wpml_active_languages', null );
			$permalink = apply_filters( 'wpml_permalink', get_permalink( $item_id ), $code, true );
			$home_url  = $lang_info[$code]['url'];
			
			$default_link = substr( $permalink, strlen( $home_url ) );
		}

		return $default_link;
	}
}

$ecwid_integration_wpml = new Ecwid_Integration_WPML();