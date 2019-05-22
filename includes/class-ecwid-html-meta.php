<?php 

abstract class Ecwid_HTML_Meta
{
	protected function __construct() {
		$this->_init();
	}

	protected function _init() {
		add_action( 'wp_head', array( $this, 'wp_head' ), 1 );
		add_action( 'wp_head', array( $this, 'wp_head_last') , 1000 );
	}
	
	abstract public function wp_head();
	abstract public function wp_head_last();
	
	public static function maybe_create() {
		$obj = null;
		
		if ( Ecwid_Seo_Links::is_noindex_page() ) {
			$obj = new Ecwid_HTML_Meta_Noindex();
		} else if ( Ecwid_Store_Page::is_store_page() ) {
			$obj = new Ecwid_HTML_Meta_Catalog_Entry();
		}
		
		return $obj;
	}

	// static only while ecwid_trim_description exists and meta functionality is not moved into this class
	public static function process_raw_description( $description, $length = 0 ) {
		$description = strip_tags( $description );
		$description = html_entity_decode( $description, ENT_NOQUOTES, 'UTF-8' );

		$description = preg_replace( '![\p{Z}\s]{1,}!u', ' ', $description );
		$description = trim( $description, " \t\xA0\n\r" ); // Space, tab, non-breaking space, newline, carriage return

		if ( function_exists( 'mb_substr' ) ) {
			$description = mb_substr( $description, 0, $length ? $length : ECWID_TRIMMED_DESCRIPTION_LENGTH, 'UTF-8' );
		} else {
			$description = substr( $description, 0, $length ? $length : ECWID_TRIMMED_DESCRIPTION_LENGTH );
		}
		$description = htmlspecialchars( $description, ENT_COMPAT, 'UTF-8' );

		return $description;
	}
}

class Ecwid_HTML_Meta_Catalog_Entry extends Ecwid_HTML_Meta 
{
	protected function __construct() {
		parent::__construct();
	}

	public function wp_head() {
		$this->_print_description();
		$this->_print_og_tags();
		$this->_print_canonical();
	}

	public function wp_head_last() {
		$this->_print_json_ld();
	}

	protected function _print_description() {
		$description_html = false;

		if ( ecwid_is_applicable_escaped_fragment() || Ecwid_Seo_Links::is_product_browser_url() ) {
			
			$description_html = Ecwid_Static_Page::get_meta_description_html();

		} else if ( Ecwid_Store_Page::is_store_page() ) {
			$set_metadesc = false;
			$set_metadesc = apply_filters( 'ecwid_set_mainpage_metadesc', $set_metadesc );

			if( $set_metadesc ) {
				$store_page_params = Ecwid_Store_Page::get_store_page_params();
				if ( isset( $store_page_params['default_category_id'] ) && $store_page_params['default_category_id'] > 0 ) {
					
					$description_html = Ecwid_Static_Page::get_meta_description_html();

				} else {
					$api = new Ecwid_Api_V3();
					$profile = $api->get_store_profile();

					if( !empty($profile->settings->storeDescription) ) {
						
						$description = $profile->settings->storeDescription;
						$description = ecwid_trim_description($description);

						$description_html = sprintf( '<meta name="description" content="%s" />', $description ) . PHP_EOL;
					}
				}
			} 
		}

		if( $description_html ) {
			echo $description_html;
		}

		return;
	}

	protected function _print_og_tags() {
		$og_tags_html = Ecwid_Static_Page::get_og_tags_html();

		$site_name = $this->_get_site_name();
		$og_tags_html = preg_replace(
			'/(<meta property="og:site_name" content=").*?(" \/>)/', 
			'$1'.$site_name.'$2',
			$og_tags_html 
		);

		echo $og_tags_html;
	}


	protected function _print_canonical() {
		if ( get_option( 'ecwid_hide_canonical', false ) ) return;
		
		$link = Ecwid_Static_Page::get_canonical_url();

		if ( $link ) {
			echo '<link rel="canonical" href="' . esc_attr($link) . '" />' . PHP_EOL;
		}
	}

	protected function _print_json_ld() {
		$json_ld = Ecwid_Static_Page::get_json_ld_html();
		echo $json_ld;
	}

	protected function _get_site_name() {
		return get_bloginfo( 'name' );
	}
}

class Ecwid_HTML_Meta_Noindex extends Ecwid_HTML_Meta {
	public function wp_head() {
		echo '<meta name="robots" content="noindex">' . PHP_EOL;
	}

	public function wp_head_last() {
		return false;
	}
}

add_action( 'wp', array( 'Ecwid_HTML_Meta', 'maybe_create' ) );