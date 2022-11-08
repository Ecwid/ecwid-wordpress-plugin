<?php

class Ecwid_Integration_Google_Sitemap_Generator {
	public function __construct() {
		// Older versions of Google XML Sitemaps plugin generate it in admin, newer in site area, so the hook should be assigned in both of them
		add_action( 'sm_buildmap', array( $this, 'build_sitemap' ) );

		$plugin_data          = get_file_data( WP_PLUGIN_DIR . '/google-sitemap-generator/sitemap.php', array( 'version' => 'Version' ), 'plugin' );
		$this->plugin_version = $plugin_data['version'];
	}

	public function build_sitemap() {
		return ecwid_build_sitemap( array( $this, 'sitemap_callback' ) );
	}

    //phpcs:disable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
	public function sitemap_callback( $url, $priority, $frequency ) {

		if ( ! class_exists( 'GoogleSitemapGenerator' ) || ! class_exists( 'GoogleSitemapGeneratorPage' ) ) {
			return false;
		}

		if ( version_compare( $this->plugin_version, '4.1.2', '>=' ) ) {
			$generator_object = GoogleSitemapGenerator::get_instance();
		} else {
			$generator_object = GoogleSitemapGenerator::GetInstance();
		}

		if ( $generator_object !== null ) {
			$page = new GoogleSitemapGeneratorPage( $url, $priority, $frequency );

			if ( version_compare( $this->plugin_version, '4.1.2', '>=' ) ) {
				$generator_object->add_element( $page );
			} else {
				$generator_object->AddElement( $page );
			}
		}
	}
    //phpcs:enable WordPress.NamingConventions.ValidVariableName.VariableNotSnakeCase
}

new Ecwid_Integration_Google_Sitemap_Generator();
