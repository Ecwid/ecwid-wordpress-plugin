<?php

class Ecwid_Integration_WPML
{
	public function __construct() {
		$plugin_data = get_plugin_data( WP_PLUGIN_DIR . '/sitepress-multilingual-cms/sitepress.php' );
		$ver = $plugin_data['Version'];
		
		if ( version_compare( $ver, '3.2', '>=' ) ) {
			add_filter( 'ecwid_relative_permalink', array( $this, 'mod_relative_permalink' ), 10, 2 );
			add_filter( 'ecwid_rewrite_rules_relative_link', array( $this, 'mod_rewrite_rules' ), 10, 2 );
			add_filter( 'ecwid_rewrite_rules_page_id', array( $this, 'mod_default_page_id' ), 10, 2 );
		}

		add_filter( 'ecwid_lang', array( $this, 'force_scriptjs_lang' ) );
		add_filter( 'wpml_hreflangs', array( $this, 'filter_hreflangs' ), 10, 1 );
		add_filter( 'wpml_hreflangs_html', array( $this, 'filter_hreflangs_html'), 10, 1 );
	}
	
	public function mod_rewrite_rules( $link, $page_id ) {
		$post_slug = get_post_field( 'post_name', get_post( $page_id ) );

		return $post_slug;
	}

	public function mod_default_page_id( $page_id, $link ) {
		global $sitepress;

		$lang = $sitepress->get_default_language();
		if( function_exists('icl_object_id') ) {
			return apply_filters( 'wpml_object_id', $page_id, 'post', false, $lang );
		}

		return $page_id;
	}

	public function force_scriptjs_lang( $lang ) {
		$current_language_code = apply_filters( 'wpml_current_language', null );
		return $current_language_code;
	}

	public function set_hreflangs( $hreflang_items ) {
		$this->hreflang_items = $hreflang_items;
	}

	public function get_hreflangs() {
		return $this->hreflang_items;
	}

	public function filter_hreflangs( $hreflang_items ) {
		$this->set_hreflangs( $hreflang_items );

		add_filter( 'ecwid_hreflangs', array( $this, 'get_hreflangs' ), 10, 1);
		add_filter( 'ecwid_inline_js_config', array( $this, 'add_inline_js_config' ), 10, 1);

		return $hreflang_items;
	}

	public function filter_hreflangs_html( $hreflang ) {
		if( class_exists( 'Ecwid_Static_Page' ) && Ecwid_Static_Page::is_data_available() ) {
			$ecwid_hreflang = Ecwid_Static_Page::get_href_lang_html();
			if( $ecwid_hreflang ) {
				return $ecwid_hreflang;
			}
		}

		return $hreflang;
	}

	public function add_inline_js_config( $js ) {
		if( is_array( $this->hreflang_items ) ) {
			$js .= 'window.ec.config.storefrontUrls.enableHreflangTags = true;';
			$js .= 'window.ec.config.storefrontUrls.internationalPages = {';
			
			foreach( $this->hreflang_items as $lang => $url ) {
				$js .= sprintf( '"%s": "%s",', $lang, $url );
			}

			$js .= '};';
		}

		return $js;
	}

	public function mod_relative_permalink( $default_link, $item_id ) {
		global $sitepress;
		
		if ( $sitepress->get_setting( 'language_negotiation_type' ) == WPML_LANGUAGE_NEGOTIATION_TYPE_DIRECTORY ) {

			$translation_details = apply_filters( 'wpml_element_language_details', null, array(
				'element_id'   => $item_id,
				'element_type' => 'post_page'
			) );

			$code = $translation_details->language_code;

			$lang_info = apply_filters( 'wpml_active_languages', null );
			$permalink = apply_filters( 'wpml_permalink', get_permalink( $item_id ), $code, true );

			if( isset($lang_info[$code]) ) {
				$home_url  = $lang_info[$code]['url'];
				$default_link = substr( $permalink, strlen( $home_url ) );
			}
		}

		return $default_link;
	}
}

$ecwid_integration_wpml = new Ecwid_Integration_WPML();