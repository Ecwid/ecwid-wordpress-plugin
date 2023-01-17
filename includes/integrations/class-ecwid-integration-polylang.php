<?php

class Ecwid_Integration_Polylang {

	public $hreflang_items;

	public function __construct() {
		add_filter( 'ecwid_lang', array( $this, 'force_scriptjs_lang' ) );

		add_filter( 'pll_rel_hreflang_attributes', array( $this, 'filter_hreflangs' ), 1, 1 );
		add_action( 'wp_head', array( $this, 'print_hreflang_js_config' ) );
	}

	public function force_scriptjs_lang( $lang ) {
		if ( function_exists( 'pll_current_language' ) ) {
			$lang = pll_current_language();
		}
		return $lang;
	}

	public function set_hreflangs( $hreflangs ) {
		$this->hreflang_items = $hreflangs;
	}

	public function get_hreflangs( $hreflangs ) {
		return $this->hreflang_items;
	}

	public function filter_hreflangs( $hreflangs ) {

		$this->set_hreflangs( $hreflangs );

		add_filter( 'ecwid_hreflangs', array( $this, 'get_hreflangs' ), 1000 );

		if ( class_exists( 'Ecwid_Static_Page' ) && Ecwid_Static_Page::is_data_available() ) {

			$ecwid_hreflang_html = Ecwid_Static_Page::get_href_lang_html();

			if ( $ecwid_hreflang_html ) {
				$ecwid_hreflangs = $this->parse_hreflang_html_to_array( $ecwid_hreflang_html );

				if ( $ecwid_hreflangs ) {
					return $ecwid_hreflangs;
				}
			}
		}

		return $hreflangs;
	}

	public function parse_hreflang_html_to_array( $string ) {
		$pattern = "/<link rel='alternate' hreflang='(.*?)' href='(.*?)' \/>/i";

		preg_match_all( $pattern, $string, $matches );

		if ( ! empty( $matches[1] ) ) {
			return array_combine( $matches[1], $matches[2] );
		}

		return false;
	}

	public function print_hreflang_js_config() {

		if ( ! is_array( $this->hreflang_items ) ) {
			return;
		}

		if ( ! Ecwid_Store_Page::is_store_page() ) {
			return;
		}

		$pages = array();
		foreach ( $this->hreflang_items as $lang => $url ) {
			$pages[ $lang ] = $url;
		}
		?>
		<script data-cfasync="false" type="text/javascript">
			window.ec = window.ec || Object();
			window.ec.config = window.ec.config || Object();
			window.ec.config.storefrontUrls.enableHreflangTags = true;
			window.ec.config.storefrontUrls.internationalPages = <?php echo wp_json_encode( $pages, JSON_UNESCAPED_SLASHES ); ?>;
		</script>
		<?php
	}
}

$ecwid_integration_polylang = new Ecwid_Integration_Polylang();
