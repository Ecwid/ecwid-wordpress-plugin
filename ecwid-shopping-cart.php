<?php
/*
Plugin Name: Ecwid Shopping Cart
Plugin URI: http://www.ecwid.com?source=wporg
Description: Ecwid is a free full-featured shopping cart. It can be easily integrated with any Wordpress blog and takes less than 5 minutes to set up.
Text Domain: ecwid-shopping-cart
Author: Ecwid Team
Version: 5.3
Author URI: http://www.ecwid.com?source=wporg
*/

register_activation_hook( __FILE__, 'ecwid_store_activate' );
register_deactivation_hook( __FILE__, 'ecwid_store_deactivate' );
register_uninstall_hook( __FILE__, 'ecwid_uninstall' );

define("APP_ECWID_COM", 'app.ecwid.com');
define("ECWID_DEMO_STORE_ID", 1003);
define('ECWID_API_AVAILABILITY_CHECK_TIME', 60*60*3);

define ('ECWID_TRIMMED_DESCRIPTION_LENGTH', 160);

define ( 'ECWID_VERSION_BUILTIN_CHAMELEON', '4.4.2.1' );

if ( ! defined( 'ECWID_PLUGIN_DIR' ) ) {
	define( 'ECWID_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ECWID_PLUGIN_BASENAME' ) ) {
	define( 'ECWID_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'ECWID_PLUGIN_URL' ) ) {
	define( 'ECWID_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined('ECWID_SHORTCODES_DIR' ) ) {
	define( 'ECWID_SHORTCODES_DIR', ECWID_PLUGIN_DIR . 'includes/shortcodes' );
}

require_once ECWID_PLUGIN_DIR . 'includes/themes.php';
require_once ECWID_PLUGIN_DIR . 'includes/oembed.php';
require_once ECWID_PLUGIN_DIR . 'includes/widgets.php';
require_once ECWID_PLUGIN_DIR . 'includes/shortcodes.php';

require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-message-manager.php';
require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-store-editor.php';
require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-product-popup.php';
require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-oauth.php';
require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-products.php';
require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-config.php';

require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-admin.php';

if ( is_admin() ) {
	require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-help-page.php';
}

require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-nav-menus.php';

require_once ECWID_PLUGIN_DIR . 'lib/ecwid_platform.php';
require_once ECWID_PLUGIN_DIR . 'lib/ecwid_api_v3.php';


// Older versions of Google XML Sitemaps plugin generate it in admin, newer in site area, so the hook should be assigned in both of them
add_action('sm_buildmap', 'ecwid_build_google_xml_sitemap');

add_action( 'plugins_loaded', 'ecwid_init_integrations' );
add_filter('plugins_loaded', 'ecwid_load_textdomain');

if ( is_admin() ){ 
  add_action('admin_init', 'ecwid_settings_api_init');
	add_action('admin_init', 'ecwid_check_version');
	add_action('admin_init', 'ecwid_process_oauth_params');
  add_action('admin_notices', 'ecwid_show_admin_messages');
  add_action('admin_enqueue_scripts', 'ecwid_common_admin_scripts');
  add_action('admin_enqueue_scripts', 'ecwid_register_admin_styles');
  add_action('admin_enqueue_scripts', 'ecwid_register_settings_styles');
  add_action('wp_ajax_ecwid_hide_vote_message', 'ecwid_hide_vote_message');
  add_action('wp_ajax_ecwid_hide_message', 'ecwid_ajax_hide_message');
	add_action('wp_ajax_save-widget', 'ecwid_ajax_save_widget');
	add_action('wp_ajax_ecwid_reset_categories_cache', 'ecwid_reset_categories_cache');
	add_action('wp_ajax_ecwid_create_store', 'ecwid_create_store');
  add_filter('plugin_action_links_' . ECWID_PLUGIN_BASENAME, 'ecwid_plugin_actions');
  add_action('admin_post_ecwid_sync_products', 'ecwid_sync_products');
  add_action('wp_ajax_ecwid_sync_products', 'ecwid_sync_products');
  add_action('admin_head', 'ecwid_ie8_fonts_inclusion');
  add_action('init', 'ecwid_apply_theme', 0);
	add_action('get_footer', 'ecwid_admin_get_footer');
	add_action('admin_post_ec_connect', 'ecwid_admin_post_connect');
	add_filter('tiny_mce_before_init', 'ecwid_tinymce_init');
	add_action('admin_post_ecwid_get_debug', 'ecwid_get_debug_file');
	add_action('admin_init', 'ecwid_admin_check_api_cache');
} else {
  add_shortcode('ecwid_script', 'ecwid_script_shortcode');
  add_action('init', 'ecwid_backward_compatibility');
  add_action('send_headers', 'ecwid_503_on_store_closed');
  add_action('template_redirect', 'ecwid_404_on_broken_escaped_fragment');
  add_action('template_redirect', 'ecwid_apply_theme');
  add_action('wp_enqueue_scripts', 'ecwid_enqueue_frontend');
  add_action('wp', 'ecwid_seo_ultimate_compatibility', 0);
  add_action('wp', 'ecwid_remove_default_canonical');
  add_filter('wp', 'ecwid_seo_compatibility_init', 0);
  add_filter('wp_title', 'ecwid_seo_title', 10000);
  add_filter('document_title_parts', 'ecwid_seo_title_parts');
  add_action('plugins_loaded', 'ecwid_minifier_compatibility', 0);
  add_action('wp_head', 'ecwid_meta_description', 0);
  add_action('wp_head', 'ecwid_ajax_crawling_fragment');
  add_action('wp_head', 'ecwid_meta');
  add_action('wp_head', 'ecwid_canonical');
  add_action('wp_head', 'ecwid_seo_compatibility_restore', 1000);
	add_action('wp_head', 'ecwid_print_inline_js_config');
	add_action('wp_head', 'ecwid_product_browser_url_in_head');
  add_filter( 'widget_meta_poweredby', 'ecwid_add_credits');
  add_filter('the_content', 'ecwid_content_started', 0);
  add_filter('body_class', 'ecwid_body_class');
  add_action('redirect_canonical', 'ecwid_redirect_canonical', 10, 2 );
  add_action('init', 'ecwid_check_api_cache');
  $ecwid_seo_title = '';
}
add_action('admin_bar_menu', 'add_ecwid_admin_bar_node', 1000);
if (get_option('ecwid_last_oauth_fail_time') > 0) {
	add_action('plugins_loaded', 'ecwid_test_oauth');
}

// Needs to be in both front-end and back-end to allow admin zone recognize the shortcode
add_shortcode( Ecwid_Shortcode_Base::get_store_shortcode_name(), 'ecwid_shortcode' );

$ecwid_script_rendered = false; // controls single script.js on page

require_once ECWID_PLUGIN_DIR . 'includes/themes.php';
require_once ECWID_PLUGIN_DIR . 'includes/oembed.php';
require_once ECWID_PLUGIN_DIR . 'includes/widgets.php';
require_once ECWID_PLUGIN_DIR . 'includes/shortcodes.php';

require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-message-manager.php';
require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-store-editor.php';
require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-product-popup.php';
require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-oauth.php';
require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-products.php';

if (is_admin()) {
	require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-help-page.php';
}

require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-nav-menus.php';
require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-seo-links.php';
require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-store-page.php';

$ecwid_script_rendered = false; // controls single script.js on page

function ecwid_init_integrations()
{
	if ( !function_exists( 'get_plugins' ) ) { require_once ( ABSPATH . 'wp-admin/includes/plugin.php' ); }

	$integrations = array(
		'aiosp' => 'all-in-one-seo-pack/all_in_one_seo_pack.php',
		'wpseo' => 'wordpress-seo/wp-seo.php',
		'divibuilder' => 'divi-builder/divi-builder.php',
		'autoptimize' => 'autoptimize/autoptimize.php'
	);

	foreach ($integrations as $key => $plugin) {
		if ( is_plugin_active($plugin) ) {
			require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-integration-' . $key . '.php';
		}
	}
}

add_action('admin_post_ecwid_estimate_sync', 'ecwid_estimate_sync');

function ecwid_estimate_sync() {
	$p = new Ecwid_Products();

	$result = $p->estimate_sync();

	echo json_encode($result);
}

$version = get_bloginfo('version');

function ecwid_add_breadcrumbs_navxt($trail)
{
	$breadcrumb = new bcn_breadcrumb('Ecwid', '', '', 'http://ecwid.com');
	$trail->add($breadcrumb);
}

function ecwid_add_breadcrumb_links_wpseo($links)
{
	return array_merge((array)$links, array(
		array(
		'text' => 'ecwid.com',
		'url' => 'http://ecwid.com'
		)
	));
}
if (version_compare($version, '3.6') < 0) {
    /**
     * A copy of has_shortcode functionality from wordpress 3.6
     * http://core.trac.wordpress.org/browser/tags/3.6/wp-includes/shortcodes.php
     */

	if (!function_exists('shortcode_exists')) {
		function shortcode_exists( $tag ) {
			global $shortcode_tags;
			return array_key_exists( $tag, $shortcode_tags );
		}
	}

	if (!function_exists('has_shortcode')) {
		function has_shortcode( $content, $tag ) {
			if ( false === strpos( $content, '[' ) ) {
				return false;
			}

			if ( shortcode_exists( $tag ) ) {
				preg_match_all( '/' . get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
				if ( empty( $matches ) )
					return false;

				foreach ( $matches as $shortcode ) {
					if ( $tag === $shortcode[2] ) {
						return true;
					} elseif ( ! empty( $shortcode[5] ) && has_shortcode( $shortcode[5], $tag ) ) {
						return true;
					}
				}
			}
			return false;
		}
	}
}

if (is_admin()) {
	$main_button_class = "";
	if (version_compare($version, '3.8-beta') > 0) {
		$main_button_class = "button-primary";
	} else {
		$main_button_class = "pure-button pure-button-primary";
	}

	define('ECWID_MAIN_BUTTON_CLASS', $main_button_class);
}

function ecwid_body_class($classes)
{
	if ( Ecwid_Store_Page::is_store_page() ) {
		$classes[] = 'ecwid-shopping-cart';
	}

	return $classes;
}

function ecwid_redirect_canonical($redirect_url, $requested_url) {
	if (!is_front_page()) {
		return $redirect_url;
	}

	if (strpos($requested_url, '_escaped_fragment_') === false) {
		return $redirect_url;
	}

	$parsed = parse_url($requested_url);
	$query = array();
	parse_str($parsed['query'], $query);

	if (!array_key_exists('_escaped_fragment_', $query)) {
		return $redirect_url;
	}

	if (! Ecwid_Store_Page::is_store_page() ) {
		return $redirect_url;
	}

	return $requested_url;
}

function ecwid_ie8_fonts_inclusion()
{
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8') === false) return;

	$url = ECWID_PLUGIN_URL . 'fonts/ecwid-logo.eot';
	echo <<<HTML
<style>
@font-face {
	font-family: 'ecwid-logo';
	src:url($url);
}
</style>
HTML;

}

add_action('wp_ajax_ecwid_get_product_info', 'ecwid_ajax_get_product_info' );
add_action('wp_ajax_nopriv_ecwid_get_product_info', 'ecwid_ajax_get_product_info' );

add_filter('redirect_canonical', 'ecwid_redirect_canonical2', 10, 3);

function ecwid_redirect_canonical2($redir, $req) {
	global $wp_query;

	if ($wp_query->get('page_id') == Ecwid_Store_Page::get_current_store_page_id() && $req . '/' == $redir) {
		return false;
	}

	return $redir;
}

function ecwid_enqueue_frontend() {


	global $ecwid_current_theme;

	if ( $ecwid_current_theme && $ecwid_current_theme->historyjs_html4mode || get_option('ecwid_historyjs_html4mode') ) {
		wp_enqueue_script('ecwid-historyjs-wa', ECWID_PLUGIN_URL . 'js/historywa.js');
	}

	if (!wp_script_is('jquery-ui-widget')) {
		wp_enqueue_script('jquery-ui-widget', includes_url() . 'js/jquery/ui/widget.min.js', array('jquery'));
	}

	wp_register_script('ecwid-products-list-js', ECWID_PLUGIN_URL . 'js/products-list.js', array('jquery-ui-widget'), get_option('ecwid_plugin_version'));
	wp_enqueue_script('ecwid-products-list-js');

	wp_register_style('ecwid-products-list-css', ECWID_PLUGIN_URL . 'css/products-list.css', array(), get_option('ecwid_plugin_version'));
	wp_enqueue_style('ecwid-css', ECWID_PLUGIN_URL . 'css/frontend.css',array(), get_option('ecwid_plugin_version'));
	wp_enqueue_style('ecwid-fonts-css', ECWID_PLUGIN_URL . 'css/fonts.css', array(), get_option('ecwid_plugin_version'));

	wp_enqueue_script( 'ecwid-frontend-js', ECWID_PLUGIN_URL . 'js/frontend.js', array( 'jquery' ), get_option( 'ecwid_plugin_version' ) );

	if ( get_post() && get_post()->post_type == Ecwid_Products::POST_TYPE_PRODUCT ) {
		wp_enqueue_script( 'ecwid-post-product', ECWID_PLUGIN_URL . 'js/post-product.js', array(), get_option( 'ecwid_plugin_version' ), TRUE );

		$meta = get_post_meta(get_the_ID(), 'ecwid_id');

		wp_localize_script( 'ecwid-post-product', 'ecwidPost', array(
			'productId' => $meta[0],
			'storePageUrl' => Ecwid_Store_Page::get_store_url()
		) );
	}

	if (is_active_widget(false, false, 'ecwidrecentlyviewed')) {
		wp_enqueue_script('ecwid-recently-viewed', ECWID_PLUGIN_URL . 'js/recently-viewed-common.js', array('jquery', 'utils'), get_option('ecwid_plugin_version'), true);

		wp_localize_script(
			'ecwid-products-list-js',
			'wp_ecwid_products_list_vars',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'is_api_available' => ecwid_is_paid_account()
			)
		);
	}

	if (is_plugin_active('contact-form-7-designer/cf7-styles.php')) {
		wp_enqueue_script('ecwid-cf7designer', ECWID_PLUGIN_URL . 'js/cf7designer.js', array(), get_option('ecwid-plugin-version'), true);
	}
}

function ecwid_print_inline_js_config() {
	if ( is_plugin_active( 'shiftnav-pro/shiftnav.php' ) ) {
		add_action ('ecwid_print_inline_js_config', 'ecwid_disable_interactive' );
	}

	echo <<<HTML
<script type="text/javascript">
window.ec = window.ec || Object();
window.ec.config = window.ec.config || Object();
window.ec.config.enable_canonical_urls = true;

HTML;

	do_action('ecwid_print_inline_js_config');
	echo '</script>';
}

function ecwid_disable_interactive() {
	echo "window.ec.config.interactive = false;\n";
}

add_action( 'ecwid_print_inline_js_config', 'ecwid_add_chameleon' );
function ecwid_add_chameleon() {

	$colors = array();
	foreach (array('foreground', 'background', 'link', 'price', 'button') as $kind) {
		$color = get_option( 'ecwid_chameleon_colors_' . $kind );
		if ( $color ) {
			$colors['color-' . $kind] = $color;
		}
	}

	if ( !get_option( 'ecwid_use_chameleon' ) && empty( $colors ) ) {
		return;
	}

	if ( empty( $colors ) ) {
		$colors = 'auto';
	}

	$colors = json_encode($colors);
	$font = '"auto"';

	$chameleon = apply_filters( 'ecwid_chameleon_settings', array( 'colors' => $colors, 'font' => $font ) );

	if ( !is_array($chameleon ) ) {
		$chameleon = array(
			'colors' => $colors,
			'font'   => $font
		);
	}

	if ( !isset( $chameleon['colors'] ) ) {
		$chameleon['colors'] = json_encode($colors);
	}

	if ( !isset( $chameleon['font'] ) ) {
		$chameleon['font'] = $font;
	}

	echo <<<JS
window.ec.config.chameleon = window.ec.config.chameleon || Object();
window.ec.config.chameleon.font = $chameleon[font];
window.ec.config.chameleon.colors = $chameleon[colors];
JS;

}

function ecwid_load_textdomain() {
	load_plugin_textdomain( 'ecwid-shopping-cart', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

}

function ecwid_404_on_broken_escaped_fragment() {
	if (!ecwid_is_api_enabled()) {
		return;
	}

	if (!isset($_GET['_escaped_fragment_'])) {
		return;
	}

	$params = ecwid_parse_escaped_fragment($_GET['_escaped_fragment_']);
	$api = ecwid_new_product_api();

	if (isset($params['mode']) && !empty($params['mode']) && isset($params['id'])) {
		$result = array();
		$is_root_cat = $params['mode'] == 'category' && $params['id'] == 0;
		if ($params['mode'] == 'product') {
			$result = $api->get_product($params['id']);
		} elseif (!$is_root_cat && $params['mode'] == 'category') {
			$result = $api->get_category($params['id']);
		}

		if (!$is_root_cat && empty($result)) {
			global $wp_query;

			$wp_query->set_404();
			status_header(404);
		}
	}
}

function ecwid_503_on_store_closed() {
	if (!ecwid_is_api_enabled()) {
		return;
	}

	if (!isset($_GET['_escaped_fragment_'])) {
		return;
	}

	$api = ecwid_new_product_api();
	$profile = $api->get_profile();

	if ($profile['closed']) {
		header('HTTP/1.1 503 Service Temporarily Unavailable');
		header('Status: 503 Service Temporarily Unavailable');
	}
}

function ecwid_backward_compatibility() {


    // Backward compatibility with 1.1.2 and earlier
    if (isset($_GET['ecwid_product_id']) || isset($_GET['ecwid_category_id'])) {

        if (isset($_GET['ecwid_product_id']))
            $redirect = ecwid_get_product_url(intval($_GET['ecwid_product_id']));
        elseif (isset($_GET['ecwid_category_id']))
            $redirect = ecwid_get_category_url(intval($_GET['ecwid_category_id']));

        wp_redirect($redirect, 301);
        exit();
    }
}

function ecwid_build_sitemap($callback)
{
	if (!ecwid_is_paid_account() || !ecwid_is_store_page_available()) return;

	$page_id = Ecwid_Store_Page::get_current_store_page_id();

	if (get_post_status($page_id) == 'publish') {
		require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-sitemap-builder.php';

		$sitemap = new EcwidSitemapBuilder(Ecwid_Store_Page::get_store_url(), $callback, ecwid_new_product_api());

		$sitemap->generate();
	}
}

function ecwid_build_google_xml_sitemap()
{
	return ecwid_build_sitemap('ecwid_google_xml_sitemap_build_sitemap_callback');
}

function ecwid_google_xml_sitemap_build_sitemap_callback($url, $priority, $frequency)
{
	static $generatorObject = null;
	if (is_null($generatorObject)) {
		$generatorObject = GoogleSitemapGenerator::GetInstance(); //Please note the "&" sign!
	}

	if($generatorObject != null) {
		$page = new GoogleSitemapGeneratorPage($url, $priority, $frequency);
		$generatorObject->AddElement($page);
	}
}

function ecwid_minifier_compatibility()
{
	if ( !function_exists( 'get_plugins' ) ) { require_once ( ABSPATH . 'wp-admin/includes/plugin.php' ); }

	$plugins = get_plugins();
	$wp_minify_plugin = 'wp-minify/wp-minify.php';
	if (array_key_exists($wp_minify_plugin, $plugins) && is_plugin_active($wp_minify_plugin)) {
		global $wp_minify;

		if (is_object($wp_minify) && array_key_exists('default_exclude', get_object_vars($wp_minify)) && is_array($wp_minify->default_exclude)) {
			$wp_minify->default_exclude[] = 'ecwid.com/script.js';
		}
	}
}

function ecwid_check_version()
{
	$plugin_data = get_plugin_data(__FILE__);
	$current_version = $plugin_data['Version'];
	$stored_version = get_option('ecwid_plugin_version', null);

	$migration_since_version = get_option('ecwid_plugin_migration_since_version', null);
	if (is_null($migration_since_version)) {
		update_option('ecwid_plugin_migration_since_version', $current_version);
	}

	$fresh_install = !$stored_version;
	$upgrade = $stored_version && version_compare($current_version, $stored_version) > 0;

	if ($fresh_install) {

		do_action('ecwid_plugin_installed', $current_version);
		add_option('ecwid_plugin_version', $current_version);

		add_option('ecwid_use_chameleon', false);

		add_option('ecwid_use_new_horizontal_categories', 'Y');

		// Called in Ecwid_Seo_Links->on_fresh_install
		do_action( 'ecwid_on_fresh_install' );

	} elseif ($upgrade) {

		do_action('ecwid_plugin_upgraded', array( 'old' => $stored_version, 'new' => $current_version ) );
		update_option('ecwid_plugin_version', $current_version);

		add_option('ecwid_use_new_horizontal_categories', '');

		do_action( 'ecwid_on_plugin_upgrade' );
	}

	if ($fresh_install || $upgrade || @$_GET['ecwid_reinit']) {

		add_option( Ecwid_Seo_Links::OPTION_ENABLED, false );

		if (ecwid_migrations_is_original_plugin_version_older_than('4.3')) {
			add_option('ecwid_fetch_url_use_file_get_contents', '');
			add_option('ecwid_remote_get_timeout', '5');
		}

		if (ecwid_migrations_is_original_plugin_version_older_than('4.1.3')) {
			add_option( 'ecwid_support_email', 'wordpress@ecwid.com' );
		}

		if (ecwid_migrations_is_original_plugin_version_older_than('4.4.5')) {
			add_option('ecwid_enable_sso');
		}

		add_option( Ecwid_Products::OPTION_ENABLED, Ecwid_Products::is_enabled() );

		add_option('ecwid_chameleon_colors_foreground', '');
        add_option('ecwid_chameleon_colors_background', '');
        add_option('ecwid_chameleon_colors_link', '');
        add_option('ecwid_chameleon_colors_button', '');
        add_option('ecwid_chameleon_colors_price', '');
		add_option('ecwid_disable_pb_url', false );
		add_option('ecwid_historyjs_html4mode', false);

        add_option(Ecwid_Widget_Floating_Shopping_Cart::OPTION_DISPLAY_POSITION, '');

		update_option('ecwid_use_new_search', 'Y');

		Ecwid_Config::load_from_ini();
		update_option('ecwid_use_new_categories', 'Y');

		add_option( 'force_scriptjs_render', false );

		do_action( 'ecwid_on_plugin_update' );

		Ecwid_Store_Page::add_store_page( get_option('ecwid_store_page_id') );
		Ecwid_Store_Page::add_store_page( get_option('ecwid_store_page_id_auto') );

		if (Ecwid_Store_Page::get_current_store_page_id()) {
			delete_option('ecwid_store_page_id_auto');
		}

		flush_rewrite_rules();
	}
}

function ecwid_get_woocommerce_status() {

	$woo = EcwidPlatform::cache_get('woo_status', null);

	if (is_null($woo)) {
		$woo = 0;
		$all_plugins = get_plugins();
		if (array_key_exists('woocommerce/woocommerce.php', $all_plugins)) {
			$active_plugins = get_option('active_plugins');
			if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', $active_plugins))) {
				$woo = 2;
			} else {
				$woo = 1;
			}
		}
		EcwidPlatform::cache_set('woo_status', $woo, 60 * 60 * 24);
	}

	return $woo;
}

function ecwid_migrations_is_original_plugin_version_older_than($version)
{
	$migration_since_version = get_option('ecwid_plugin_migration_since_version', null);
	return version_compare($migration_since_version, $version) < 0;
}

function ecwid_log_error($message)
{
	$errors = get_option('ecwid_error_log');
	if (!$errors) {
		$errors = array();
	} else {
		$errors = json_decode($errors);
		if (!is_array($errors)) {
			$errors = array();
		}
	}

	while (count($errors) > 10) {
		array_shift($errors);
	}

	$errors[] = array(
		'message' => $message,
		'date' => strftime('%c')
	);

	update_option('ecwid_error_log', json_encode($errors));
}

function ecwid_get_last_logged_error()
{
	return '';
}


function ecwid_override_option($name, $new_value = null)
{
    static $overridden = array();

    if (!array_key_exists($name, $overridden)) {
        $overridden[$name] = get_option($name);
    }

    if (!is_null($new_value)) {
        update_option($name, $new_value);
    } else {
        update_option($name, $overridden[$name]);
    }
}
function ecwid_tinymce_init($in)
{
	if(!empty($in['extended_valid_elements'])) {
		$in['extended_valid_elements'] .= ',';
	} else {
		$in['extended_valid_elements'] = '';
	}

	$in['extended_valid_elements'] .= '@[id|class|style|title|itemscope|itemtype|itemprop|customprop|datetime|rel],div,dl,ul,dt,dd,li,span,a|rev|charset|href|lang|tabindex|accesskey|type|name|href|target|title|class|onfocus|onblur]';

	return $in;
}

function ecwid_seo_ultimate_compatibility()
{
	global $seo_ultimate;

	if ($seo_ultimate && Ecwid_Store_Page::is_store_page()) {
		remove_action('template_redirect', array($seo_ultimate->modules['titles'], 'before_header'), 0);
		remove_action('wp_head', array($seo_ultimate->modules['titles'], 'after_header'), 1000);
		remove_action('su_head', array($seo_ultimate->modules['meta-descriptions'], 'head_tag_output'));
		remove_action('su_head', array($seo_ultimate->modules['canonical'], 'link_rel_canonical_tag'));
		remove_action('su_head', array($seo_ultimate->modules['canonical'], 'http_link_rel_canonical'));
	}
}

function ecwid_remove_default_canonical()
{
	if ( Ecwid_Store_Page::is_store_page() ) {
		remove_action( 'wp_head','rel_canonical');
	}
}

function ecwid_seo_compatibility_init($title)
{
    if ( !array_key_exists('_escaped_fragment_', $_GET) || !Ecwid_Store_Page::is_store_page() ) {
        return $title;
    }

	// Platinum SEO Pack
  // Canonical
  ecwid_override_option('psp_canonical', false);
  // Title
  ecwid_override_option('aiosp_rewrite_titles', false);

	add_action('amt_basic_metadata_head', 'ecwid_amt_remove_description');
	return $title;

}

function ecwid_amt_remove_description($params)
{
	foreach ($params as $key => $value) {
		if (preg_match('/meta name="description"/', $value)) {
			unset ($params[$key]);
		}
	}

	return $params;
}

function ecwid_seo_compatibility_restore()
{
    if (!array_key_exists('_escaped_fragment_', $_GET) || !Ecwid_Store_Page::is_store_page()) {
        return;
    }

    ecwid_override_option('psp_canonical');
    ecwid_override_option('aiosp_rewrite_titles');
}

function ecwid_check_api_cache()
{
	$last_cache = get_option('ecwid_last_api_cache_check');

	if (time() - $last_cache > HOUR_IN_SECONDS ) {
		ecwid_invalidate_cache();
	}

	update_option('ecwid_last_api_cache_check', time());
}

function ecwid_admin_check_api_cache()
{
	$last_cache = get_option('ecwid_last_api_cache_check');

	if (time() - $last_cache > MINUTE_IN_SECONDS * 5 ) {
		ecwid_invalidate_cache();
	}

	update_option('ecwid_last_api_cache_check', time());
}

function ecwid_invalidate_cache()
{
	$api = new Ecwid_Api_V3();

	if ($api->is_available()) {
		$stats = $api->get_store_update_stats();

		if ($stats) {
			EcwidPlatform::invalidate_products_cache_from(strtotime($stats->productsUpdated));
			EcwidPlatform::invalidate_categories_cache_from(strtotime($stats->categoriesUpdated));
		}

	}
}

function add_ecwid_admin_bar_node() {
	global $wp_admin_bar;

	if ( !is_super_admin() || !is_admin_bar_showing() || Ecwid_Config::is_wl() )
		return;

	$theme     = ecwid_get_theme_name();
	$store_url = Ecwid_Store_Page::get_store_url();


	$brand = Ecwid_Config::get_brand();
	if (!is_admin()) {
		$subject = sprintf( __('%s plugin doesn\'t work well with my "%s" theme', 'ecwid-shopping-cart'), Ecwid_Config::get_brand(), $theme );

		$body = <<<TEXT
Hey %s,

My store looks bad with my theme on Wordpress.

The theme title is %s.
The store URL is %s

Can you have a look?

Thanks.
TEXT;
	} else {
		$subject = __('I have a problem with my %s store', 'ecwid-shopping-cart');
		$body = <<<TEXT
Hey %s,

I have a problem with my store.

[Please provide details here]

The theme title is %s.
The store URL is %

Can you have a look?

Thanks.
TEXT;
	}

	$body = __($body, 'ecwid-shopping-cart');
	$body = sprintf($body, Ecwid_Config::get_brand(), $theme, $store_url);

	$wp_admin_bar->add_menu( array(
		'id' => 'ecwid-main',
		'title' => '<span class="ab-icon ecwid-top-menu-item"></span>',
		'href' => Ecwid_Admin::get_dashboard_url(),
	));

	$wp_admin_bar->add_menu(array(
			"id" => "ecwid-go-to-page",
			"title" => __("Visit storefront", 'ecwid-shopping-cart'),
			"parent" => "ecwid-main",
			'href' => Ecwid_Store_Page::get_store_url()
		)
	);

	$wp_admin_bar->add_menu(array(
			"id" => "ecwid-control-panel",
			"title" => __("Manage my store", 'ecwid-shopping-cart'),
			"parent" => "ecwid-main",
			'href' =>  Ecwid_Admin::get_dashboard_url()
		)
	);

	$wp_admin_bar->add_menu(array(
			"id" => "ecwid-faq",
			"title" => __("Read FAQ", 'ecwid-shopping-cart'),
			"parent" => "ecwid-main",
			'href' =>  __('https://support.ecwid.com/hc/en-us/articles/207101259-Wordpress-downloadable-', 'ecwid-shopping-cart'),
			'meta' => array(
				'target' => '_blank'
			)
		)
	);

	$wp_admin_bar->add_menu(array(
		'id' => 'ecwid-report-problem',
		'title' => __( 'Report a problem with the store', 'ecwid-shopping-cart' ),
		'parent' => 'ecwid-main',
		'href' => 'mailto:wordpress@ecwid.com?subject=' . rawurlencode($subject) . '&body=' . rawurlencode($body),
		'meta' => array(
			'target' => '_blank'
		)
	));
}

function ecwid_content_has_productbrowser( $content ) {

	$result = has_shortcode( $content, 'ecwid_productbrowser' );

	if ( !$result && has_shortcode($content, Ecwid_Shortcode_Base::get_store_shortcode_name() ) ) {
		$shortcodes = ecwid_find_shortcodes( $content, Ecwid_Shortcode_Base::get_store_shortcode_name() );
		if ( $shortcodes ) foreach ( $shortcodes as $shortcode ) {

			$attributes = shortcode_parse_atts( $shortcode[3] );

			if ( isset( $attributes['widgets'] ) ) {
				$widgets = preg_split( '![^0-9^a-z^A-Z^-^_]!', $attributes['widgets'] );
				if ( is_array( $widgets ) && in_array('productbrowser', $widgets ) ) {
					$result = true;
				}
			}
		}
	}

	return $result;
}

function ecwid_ajax_crawling_fragment() {

	if ( !ecwid_is_api_enabled() ) return;

	if ( isset( $_GET['_escaped_fragment_'] ) ) return;

	if ( ! Ecwid_Store_Page::is_store_page() ) return;

	global $wp, $ecwid_seo_links;

	$slug = ltrim( strrchr( $wp->request, '/' ), '/' );

	if ( Ecwid_Seo_Links::is_enabled() ) return;

    echo '<meta name="fragment" content="!">' . PHP_EOL;
}

function ecwid_meta() {

	echo '<meta http-equiv="x-dns-prefetch-control" content="on">' . PHP_EOL;
    echo '<link rel="dns-prefetch" href="//images-cdn.ecwid.com/">' . PHP_EOL;
    echo '<link rel="dns-prefetch" href="//images.ecwid.com/">' . PHP_EOL;
    echo '<link rel="dns-prefetch" href="//app.ecwid.com/">' . PHP_EOL;
	echo '<link rel="dns-prefetch" href="//ecwid-static-ru.r.worldssl.net">' . PHP_EOL;
	echo '<link rel="dns-prefetch" href="//ecwid-images-ru.r.worldssl.net">' . PHP_EOL;

    if (!Ecwid_Store_Page::is_store_page() && ecwid_is_store_page_available()) {
		$page_url = Ecwid_Store_Page::get_store_url();
		echo '<link rel="prefetch" href="' . $page_url . '" />' . PHP_EOL;
		echo '<link rel="prerender" href="' . $page_url . '" />' . PHP_EOL;
	} else {
        $store_id = get_ecwid_store_id();
        $params = ecwid_get_scriptjs_params();
		echo '<link rel="preload" href="https://app.ecwid.com/script.js?'
			. $store_id . $params . '" as="script">' . PHP_EOL;
    }
}

function ecwid_product_browser_url_in_head() {
	echo ecwid_get_product_browser_url_script();
}


function ecwid_canonical() {

	if ( ecwid_is_applicable_escaped_fragment() ) {

		$params = ecwid_parse_escaped_fragment($_GET['_escaped_fragment_']);

		$api = ecwid_new_product_api();

		if ($params['mode'] == 'product') {
			$product = $api->get_product($params['id']);
			$link = ecwid_get_product_url($product);
		} else if ($params['mode'] == 'category') {
			$category = $api->get_category($params['id']);
			$link = ecwid_get_category_url($category);
		}
	} else if ( Ecwid_Seo_Links::is_product_browser_url() ) {
		$params = Ecwid_Seo_Links::maybe_extract_html_catalog_params();

		if ($params) {
			$api = new Ecwid_Api_V3();

			if ( $params['mode'] == 'product' ) {
				$product = $api->get_product( $params['id'] );
				$link = $product->url;
			} elseif ( $params['mode'] == 'category' ) {
				$category = $api->get_category( $params['id'] );
				$link = $category->url;
			}
		}
	}

	if ($link) {
		echo '<link rel="canonical" href="' . esc_attr($link) . '" />' . PHP_EOL;
	}
}

function ecwid_is_applicable_escaped_fragment() {

	$allowed = ecwid_is_api_enabled() && isset($_GET['_escaped_fragment_']);
	if (!$allowed) return false;

	$params = ecwid_parse_escaped_fragment($_GET['_escaped_fragment_']);
	if (!$params) return false;

	if (!in_array($params['mode'], array('category', 'product')) || !isset($params['id'])) return false;

	return true;
}

function ecwid_meta_description() {

	$params = array();

	if ( ecwid_is_applicable_escaped_fragment() ) {
		$params = ecwid_parse_escaped_fragment( $_GET['_escaped_fragment_'] );
		$api = ecwid_new_product_api();
		if ($params['mode'] == 'product') {
			$product = $api->get_product($params['id']);
			$description = $product['description'];
		} elseif ($params['mode'] == 'category') {
			$category = $api->get_category($params['id']);
			$description = $category['description'];
		}
	} else if ( Ecwid_Seo_Links::is_product_browser_url() ) {
		$params = Ecwid_Seo_Links::maybe_extract_html_catalog_params();
		if ($params) {
			$api = new Ecwid_Api_V3();

			if ( $params['mode'] == 'product' ) {
				$product = $api->get_product( $params['id'] );
				$description = $product->seoDescription;
				if (!$description) {
					$description = $product->description;
				}
			} elseif ( $params['mode'] == 'category' ) {
				$category = $api->get_category( $params['id'] );
				$description = $category->seoDescription;
				if (!$description) {
					$description = $category->description;
				}
			}
		}
	}

	if ( !$description ) {
		return;
	}

	$description = ecwid_trim_description($description);

    echo <<<HTML
<meta name="description" content="$description" />
HTML;
}

function ecwid_trim_description($description)
{
	$description = strip_tags($description);
	$description = html_entity_decode($description, ENT_NOQUOTES, 'UTF-8');

	$description = preg_replace('![\p{Z}\s]{1,}!u', ' ', $description);
	$description = trim($description, " \t\xA0\n\r"); // Space, tab, non-breaking space, newline, carriage return
	$description = mb_substr($description, 0, ECWID_TRIMMED_DESCRIPTION_LENGTH, 'UTF-8');
	$description = htmlspecialchars($description, ENT_COMPAT, 'UTF-8');

	return $description;
}


function ecwid_ajax_hide_message($params)
{
	if (!current_user_can('manage_options')) {
		return;
	}

	if (Ecwid_Message_Manager::disable_message($_GET['message'])) {
		wp_send_json(array('status' => 'success'));
	}
}

function ecwid_hide_vote_message()
{
	update_option('ecwid_show_vote_message', false);
}

function ecwid_get_product_and_category($category_id, $product_id) {
    $params = array 
    (
        array("alias" => "c", "action" => "category", "params" => array("id" => $category_id)),
        array("alias" => "p", "action" => "product", "params" => array("id" => $product_id)),           
    );

    $api = ecwid_new_product_api();
    $batch_result = $api->get_batch_request($params);

	if (false == $batch_result) {
		$product = $api->get_product($product_id);
		$category = false;
	} else {
		$category = $batch_result["c"];
		$product = $batch_result["p"];
	}

    $return = "";

    if (is_array($product)) {
        $return .=$product["name"];
    }

    if(is_array($category)) {
        $return.=" | ";
        $return .=$category["name"];
    }
    return $return;
}

function ecwid_get_title_separator()
{
	$sep = apply_filters('document_title_separator', '|');

	if (!empty($sep)) {
		return $sep;
	}

	return apply_filters('ecwid_title_separator', '|');
}

function ecwid_seo_title($content) {

	$title = _ecwid_get_seo_title();
	if (!empty($title)) {
		$sep = ecwid_get_title_separator();

		return "$title $sep $content";
	}

	return $content;
}


function ecwid_seo_title_parts($parts)
{
	$title = _ecwid_get_seo_title();
	if ($title) {
		array_unshift($parts, $title);
	}

	return $parts;
}

function _ecwid_get_seo_title()
{
	$params = array();

	if ( ecwid_is_applicable_escaped_fragment() ) {
		$params = ecwid_parse_escaped_fragment( $_GET['_escaped_fragment_'] );
		if ( empty( $params ) ) return;

		$ecwid_seo_title = '';

		$separator = ecwid_get_title_separator();

		$api = ecwid_new_product_api();

		if ( isset( $params['mode'] ) && ! empty( $params['mode'] ) ) {
			if ( $params['mode'] == 'product' ) {
				if ( isset( $params['category'] ) && ! empty( $params['category'] ) ) {
					$ecwid_seo_title = ecwid_get_product_and_category( $params['category'], $params['id'] );
				} elseif ( empty( $params['category'] ) ) {
					$ecwid_product   = $api->get_product( $params['id'] );
					$ecwid_seo_title = $ecwid_product['name'];
					if ( isset( $ecwid_product['categories'] ) && is_array( $ecwid_product['categories'] ) ) {
						foreach ( $ecwid_product['categories'] as $ecwid_category ) {
							if ( $ecwid_category['defaultCategory'] == true ) {
								$ecwid_seo_title .= ' ' . $separator . ' ';
								$ecwid_seo_title .= $ecwid_category['name'];
							}
						}
					}
				}
			} elseif ( $params['mode'] == 'category' ) {
				$api             = ecwid_new_product_api();
				$ecwid_category  = $api->get_category( $params['id'] );
				$ecwid_seo_title = $ecwid_category['name'];
			}
		}

	} else if ( Ecwid_Seo_Links::is_product_browser_url() ) {

		$params = Ecwid_Seo_Links::maybe_extract_html_catalog_params();

		if ( $params ) {
			$api = new Ecwid_Api_V3();

			if ( $params['mode'] == 'product' ) {
				$product = $api->get_product( $params['id'] );
				if ( $product->seoTitle ) {
					$ecwid_seo_title = $product->seoTitle;
				} else {
					$ecwid_seo_title = $product->name;
				}

				if ( $product->defaultCategoryId ) {
					$category = $api->get_category( $product->defaultCategoryId );
					if ( $category ) {
						$ecwid_seo_title .=  ' z' . ecwid_get_title_separator() . 'z ' . $category->name;
					}
				}
			} else if ( $params['mode'] == 'category' ) {
				$category = $api->get_product( $params['id'] );
				$ecwid_seo_title = $category->name;
			}
		}
	}


	if ( ! empty( $ecwid_seo_title ) ) {
		return $ecwid_seo_title;
	}

	return "";
}

function ecwid_add_credits($powered_by)
{
	if (!ecwid_is_paid_account()) {

		$new_powered_by = '<li>';
		$new_powered_by .= sprintf(
			__('<a %s>Online store powered by %s</a>', 'ecwid-shopping-cart'),
			'target="_blank" href="//www.ecwid.com?source=wporg-metalink"',
			Ecwid_Config::get_brand()
		);
		$new_powered_by .= '</li>';

		$powered_by .= $new_powered_by;
	}

	return $powered_by;
}

function ecwid_content_started($content)
{
	global $ecwid_script_rendered;

	$ecwid_script_rendered = false;

	return $content;
}

function ecwid_wrap_shortcode_content($content, $name, $attrs)
{
	$version = get_option('ecwid_plugin_version');

	$shortcode_content = ecwid_get_scriptjs_code(@$attrs['lang']);

	if ($name == 'product2') {
		$shortcode_content .= $content;
	} else {
		$shortcode_content .= "<div class=\"ecwid-shopping-cart-$name\">$content</div>";
	}

	$shortcode_content = "<!-- Ecwid shopping cart plugin v $version -->"
	                     . $shortcode_content
	                     . "<!-- END Ecwid Shopping Cart v $version -->";

	return apply_filters('ecwid_shortcode_content', $shortcode_content);
}

function ecwid_get_scriptjs_code($force_lang = null) {
	global $ecwid_script_rendered;

    if ( !$ecwid_script_rendered || get_option( 'force_scriptjs_render' ) ) {
		$store_id = get_ecwid_store_id();
		$params = ecwid_get_scriptjs_params( $force_lang );

		$s =  '<script data-cfasync="false" type="text/javascript" src="https://' . APP_ECWID_COM . '/script.js?' . $store_id . $params . '"></script>';
		$s = $s . ecwid_sso();
		$s .= '<script type="text/javascript">if (jQuery && jQuery.mobile) { jQuery.mobile.hashListeningEnabled = false; jQuery.mobile.pushStateEnabled=false; }</script>';
		$ecwid_script_rendered = true;

		return $s;
    } else {
		return '';
    }
}

function ecwid_get_scriptjs_params( $force_lang = null ) {

	$store_id = get_ecwid_store_id();
	$force_lang_str = !empty( $force_lang ) ? "&lang=$force_lang" : '';
	$params = '&data_platform=wporg' . $force_lang_str;
	if ( Ecwid_Products::is_enabled() ) {
		$params .= '&data_sync_products=1';
	}

	if ( Ecwid_Seo_Links::is_enabled() ) {
		$params .= '&data_clean_urls=1';
	}

	return $params;
}

function ecwid_script_shortcode($params) {

	$attributes = shortcode_atts(
		array(
			'lang' => null
		), $params
	);

	$content = "";
	if (!is_null($attributes['lang'])) {
		$content = ecwid_get_scriptjs_code($attributes['lang']);
	}

    return ecwid_wrap_shortcode_content($content, 'script', $params);
}

function ecwid_minicart_shortcode($attributes) {

	$shortcode = new Ecwid_Shortcode_Minicart($attributes);

	return $shortcode->render();
}

function ecwid_get_search_js_code() {
	if (get_option('ecwid_use_new_search', false)) {
		return 'xSearch("style=");';
	} else {
		return 'xSearchPanel("style=")';
	}
}

function _ecwid_get_single_product_widget_parts_v1($attributes) {
	return array(
		'display_items' => array(
			'picture'  => '<div itemprop="picture"></div>',
			'title'    => '<div class="ecwid-title" itemprop="title"></div>',
			'price'    => '<div itemtype="http://schema.org/Offer" itemscope itemprop="offers">'
			              . '<div class="ecwid-productBrowser-price ecwid-price" itemprop="price"></div>'
			              . '</div>',
			'options'  => '<div itemprop="options"></div>',
			'qty' 	   => '<div itemprop="qty"></div>',
			'addtobag' => '<div itemprop="addtobag"></div>'
		),
		'opening_div' => sprintf('<div class="ecwid ecwid-SingleProduct ecwid-Product ecwid-Product-%d" '
		                 . 'itemscope itemtype="http://schema.org/Product" '
		                 . 'data-single-product-id="%d">', $attributes['id'], $attributes['id']),
		'widget_call' => '<script data-cfasync="false" type="text/javascript">xSingleProduct()</script>'
	);
}

function _ecwid_get_single_product_widget_parts_v2($attributes) {

	$price_location_attributes = '  data-spw-price-location="button"';
	$bordered_class = ' ecwid-SingleProduct-v2-bordered';
	if ($attributes['show_border'] == 0) {
		$bordered_class = '';
	}

	if ($attributes['show_price_on_button'] == 0) {
		$price_location_attributes = '';
	}

	return array(
		'display_items' => array(
			'picture'  => '<div itemprop="picture"></div>',
			'title'    => '<div class="ecwid-title" itemprop="title"></div>',
			'price'    => '<div itemtype="http://schema.org/Offer" itemscope itemprop="offers">'
			              . '<div class="ecwid-productBrowser-price ecwid-price" itemprop="price"' . $price_location_attributes . '>'
			              . '<div itemprop="priceCurrency"></div>'
			              . '</div>'
			              . '</div>',
			'options'  => '<div customprop="options"></div>',
			'qty' 	   => '<div customprop="qty"></div>',
			'addtobag' => '<div customprop="addtobag"></div>'
		),
		'opening_div' => sprintf('<div class="ecwid ecwid-SingleProduct-v2' . $bordered_class . ' ecwid-Product ecwid-Product-%d"'
		. 'itemscope itemtype="http://schema.org/Product" data-single-product-id="%d">', $attributes['id'], $attributes['id']),
		'widget_call' => '<script data-cfasync="false" type="text/javascript">xProduct()</script>'
	);
}

function ecwid_shortcode($attributes)
{
	$defaults = ecwid_get_default_pb_size();

	$attributes = shortcode_atts(
		array(
			'widgets' 					  => 'productbrowser',
			'categories_per_row'  => '3',
			'category_view' 		  => 'grid',
			'search_view' 			  => 'grid',
			'grid' 							  => $defaults['grid_rows'] . ',' . $defaults['grid_columns'],
			'list' 							  => $defaults['list_rows'],
			'table' 						  => $defaults['table_rows'],
			'minicart_layout' 	  => 'MiniAttachToProductBrowser',
			'default_category_id' => 0,
			'lang' => ''
		)
		, $attributes
	);

	$allowed_widgets = array('productbrowser', 'search', 'categories', 'minicart');
	$widgets = preg_split('![^0-9^a-z^A-Z^-^_]!', $attributes['widgets']);
	foreach ($widgets as $key => $widget) {
		if (!in_array($widget, $allowed_widgets)) {
			unset($widgets[$key]);
		}
	}

	if (empty($widgets)) {
		$widgets = array('productbrowser');
	}

	$attributes['layout'] = $attributes['minicart_layout'];
	$attributes['is_ecwid_shortcode'] = true;

	$result = '';

	$widgets_order = array('minicart', 'search', 'categories', 'productbrowser');
	foreach ($widgets_order as $widget) {
		if (in_array($widget, $widgets)) {
			if ( class_exists( 'Ecwid_Shortcode_' . $widget ) ) {

				$class = 'Ecwid_Shortcode_' . $widget;

				$shortcode = new $class($attributes);

				$result .= $shortcode->render();
			} else {
				$result .= call_user_func_array( 'ecwid_' . $widget . '_shortcode', array( $attributes ) );
			}
		}
	}

	update_option('ecwid_store_shortcode_used', time());

	return $result;
}

function ecwid_parse_escaped_fragment($escaped_fragment) {
	static $parsed = array();

	if (empty($parsed[$escaped_fragment])) {

		$fragment = urldecode( $escaped_fragment );
		$return   = array();

		if ( preg_match( '/^(\/~\/)([a-z]+)\/(.*)$/', $fragment, $matches ) ) {
			parse_str( $matches[3], $return );
			$return['mode'] = $matches[2];
		} elseif ( preg_match( '!.*/(p|c)/([0-9]*)!', $fragment, $matches ) ) {
			if ( count( $matches ) == 3 && in_array( $matches[1], array( 'p', 'c' ) ) ) {
				$return = array(
					'mode' => 'p' == $matches[1] ? 'product' : 'category',
					'id'   => $matches[2]
				);
			}
		}

		$parsed[$escaped_fragment] = $return;
	}

	return $parsed[$escaped_fragment];
}

function ecwid_ajax_get_product_info() {
	$id = $_GET['id'];

	if (ecwid_is_api_enabled()) {
		$api = ecwid_new_product_api();
		$product = $api->get_product_https($id);

		echo json_encode($product);
	}

	exit();
}

function ecwid_store_activate() {

	$my_post = array();
	$defaults = ecwid_get_default_pb_size();

	$shortcode = Ecwid_Shortcode_Base::get_store_shortcode_name();
	$content = <<<EOT
[$shortcode widgets="productbrowser minicart categories search" grid="$defaults[grid_rows],$defaults[grid_columns]" list="$defaults[list_rows]" table="$defaults[table_rows]" default_category_id="0" category_view="grid" search_view="grid" minicart_layout="MiniAttachToProductBrowser" ]
EOT;
	add_option("ecwid_store_page_id", '', '', 'yes');

	add_option("ecwid_store_id", ECWID_DEMO_STORE_ID, '', 'yes');
	
	add_option("ecwid_enable_minicart", 'Y', '', 'yes');
	add_option("ecwid_show_categories", '', '', 'yes');
	add_option("ecwid_show_search_box", '', '', 'yes');

	add_option("ecwid_pb_categoriesperrow", '3', '', 'yes');

	add_option("ecwid_pb_productspercolumn_grid", $defaults['grid_rows'], '', 'yes');
	add_option("ecwid_pb_productsperrow_grid", $defaults['grid_columns'], '', 'yes');
	add_option("ecwid_pb_productsperpage_list", $defaults['list_rows'], '', 'yes');
	add_option("ecwid_pb_productsperpage_table", $defaults['table_rows'], '', 'yes');

	add_option("ecwid_pb_defaultview", 'grid', '', 'yes');
	add_option("ecwid_pb_searchview", 'list', '', 'yes');

	add_option("ecwid_mobile_catalog_link", '', '', 'yes');  
	add_option("ecwid_default_category_id", '', '', 'yes');  
	 
	add_option('ecwid_is_api_enabled', 'on', '', 'yes');
	add_option('ecwid_api_check_time', 0, '', 'yes');

	add_option('ecwid_show_vote_message', true);

	add_option("ecwid_sso_secret_key", '', '', 'yes'); 

	add_option("ecwid_installation_date", time());

	add_option('ecwid_hide_appearance_menu', get_option('ecwid_store_id') == ECWID_DEMO_STORE_ID ? 'Y' : 'N', '', 'yes');

	/* All new options should go to check_version thing */

	require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-nav-menus.php';

	$id = get_option("ecwid_store_page_id");	
	$_tmp_page = null;
	if (!empty($id) and ($id > 0)) { 
		$_tmp_page = get_post($id);
	}
	if (is_null($_tmp_page)) {
		$id = get_option('ecwid_store_page_id_auto');

		if (!empty($id) and ($id > 0)) {
			$_tmp_page = get_post($id);
		}
	}
	if ($_tmp_page !== null) {
		$my_post = array();
		$my_post['ID'] = $id;
		$my_post['post_status'] = 'publish';
		wp_update_post( $my_post );

		if ($id == get_option('ecwid_store_page_id_auto')) {
			update_option('ecwid_store_page_id', $id);
		}

	} else {

		ecwid_load_textdomain();
		$my_post['post_title'] = __('Store', 'ecwid-shopping-cart');
		$my_post['post_content'] = $content;
		$my_post['post_status'] = 'publish';
		$my_post['post_author'] = 1;
		$my_post['post_type'] = 'page';
		$my_post['comment_status'] = 'closed';
		$id = wp_insert_post( $my_post );
		update_option('ecwid_store_page_id', $id);

		Ecwid_Nav_Menus::replace_auto_added_menu();

		if (ecwid_get_theme_identification() == 'responsive') {
			update_post_meta($id, '_wp_page_template', 'full-width-page.php');
			update_option("ecwid_show_search_box", 'Y');
		}
	}

	Ecwid_Nav_Menus::add_menu_on_activate();

	$p = new Ecwid_Products();
	$p->enable_all_products();

	Ecwid_Message_Manager::enable_message('on_activate');
}

add_action('in_admin_header', 'ecwid_disable_other_notices');
function ecwid_disable_other_notices() {

	$pages = array('toplevel_page_ec-store', 'toplevel_page_ec_store', 'admin_page_ecwid-help');

	if (!in_array(get_current_screen()->base, $pages)) return;


	global $wp_filter;

	if (!$wp_filter || !isset($wp_filter['admin_notices']) || !class_exists('WP_Hook') || ! ( $wp_filter['admin_notices'] instanceof WP_Hook) ) {
   		return;
    }

	foreach ($wp_filter['admin_notices']->callbacks as $priority => $collection) {
		foreach ($collection as $name => $item) {
			if ($name != 'ecwid_show_admin_messages') {
				remove_action('admin_notices', $item['function'], $priority);
			}
		}
    }
}

function ecwid_show_admin_messages() {
	if (is_admin()) {
		Ecwid_Message_Manager::show_messages();
	}
}

function ecwid_show_admin_message($message) {

	$class = version_compare(get_bloginfo('version'), '3.0') < 0 ? "updated fade" : "update-nag";
	echo sprintf('<div class="%s" style="margin-top: 5px">%s</div>', $class, $message);
}

function ecwid_store_deactivate() {
	$ecwid_page_id = get_option("ecwid_store_page_id");
	$_tmp_page = null;
	if (!empty($ecwid_page_id) and ($ecwid_page_id > 0)) {
		$_tmp_page = get_page($ecwid_page_id);
		if ($_tmp_page !== null) {
			$my_post = array();
			$my_post['ID'] = $ecwid_page_id;
			$my_post['post_status'] = 'draft';
			wp_update_post( $my_post );
		} else {
			update_option('ecwid_store_page_id', '');	
		}
	}

	Ecwid_Message_Manager::reset_hidden_messages();

	$p = new Ecwid_Products();
	$p->disable_all_products();
}

function ecwid_uninstall() {
    delete_option("ecwid_store_page_id_auto");
    delete_option("ecwid_store_id");
    delete_option("ecwid_enable_minicart");
    delete_option("ecwid_show_categories");
    delete_option("ecwid_show_search_box");
    delete_option("ecwid_pb_categoriesperrow");
    delete_option("ecwid_pb_productspercolumn_grid");
    delete_option("ecwid_pb_productsperrow_grid");
    delete_option("ecwid_pb_productsperpage_list");
    delete_option("ecwid_pb_productsperpage_table");
    delete_option("ecwid_pb_defaultview");
    delete_option("ecwid_pb_searchview");
    delete_option("ecwid_mobile_catalog_link");
    delete_option("ecwid_default_category_id");
    delete_option('ecwid_is_api_enabled');
    delete_option('ecwid_api_check_time');
    delete_option('ecwid_show_vote_message');
    delete_option("ecwid_sso_secret_key");
    delete_option("ecwid_installation_date");
    delete_option('ecwid_hide_appearance_menu');

	delete_option("ecwid_plugin_version");
	delete_option("ecwid_use_chameleon");
}

function ecwid_get_store_shortcode_widgets()
{
	if (get_option('ecwid_use_new_horizontal_categories')) return false;


	$page_contents = get_post(Ecwid_Store_Page::get_current_store_page_id())->post_content;
	$shortcodes = ecwid_find_shortcodes($page_contents, 'ecwid');

	if (!$shortcodes) {
		return null;
	}

	$shortcode = $shortcodes[0];
	$attributes = shortcode_parse_atts($shortcode[3]);

	if (!isset($attributes['widgets'])) {
		return null;
	}

	return explode(' ', $attributes['widgets']);
}

function ecwid_abs_intval($value) {
	if (!is_null($value))
    	return abs(intval($value));
	else
		return null;
}

function ecwid_sync_do_page() {

	require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-products.php';

	$prods = new Ecwid_Products();

	$estimation = $prods->estimate_sync();

	require_once ECWID_PLUGIN_DIR . 'templates/sync.php';
}

function ecwid_get_categories($nocache = false) {
	$categories = EcwidPlatform::cache_get('all_categories');

	if ( false == $categories || $nocache ) {

		$request = Ecwid_Http::create_get(
			'get_categories_through_endpoint',
			ecwid_get_categories_js_url(),
			array( Ecwid_Http::POLICY_EXPECT_JSONP )
		);

		if (!$request) {
			return array();
		}

		$categories = $request->do_request();

		if (!is_null($categories)) {
			EcwidPlatform::cache_set( 'all_categories', $categories, 60 * 60 * 2 );
		}
	}

	if ( !is_array($categories) || !$categories ) {
		return array();
	}

	return $categories;
}

function ecwid_reset_categories_cache()
{
	if (!current_user_can('manage_options')) {
		return;
	}

	EcwidPlatform::cache_reset( 'nav_categories_posts' );
	EcwidPlatform::cache_reset( 'all_categories' );
}

function ecwid_register_admin_styles($hook_suffix) {

	wp_enqueue_style('ecwid-admin-css', ECWID_PLUGIN_URL . 'css/admin.css', array(), get_option('ecwid_plugin_version'));
	wp_enqueue_style('ecwid-fonts-css', ECWID_PLUGIN_URL . 'css/fonts.css', array(), get_option('ecwid_plugin_version'));

	if (isset($_GET['page']) && $_GET['page'] == 'ec-store') {

		if (get_option('ecwid_store_id') == ECWID_DEMO_STORE_ID) {
			// Open dashboard for the first time, ecwid store id is set to demo => need landing styles/scripts
			wp_enqueue_script('ecwid-landing-js', ECWID_PLUGIN_URL . 'js/landing.js', array(), get_option('ecwid_plugin_version'));
			wp_localize_script('ecwid-landing-js', 'ecwidParams', array(
				'registerLink' => ecwid_get_register_link(),
				'isWL' => Ecwid_Config::is_wl()
			));
			if (ecwid_use_old_landing()) {
				wp_enqueue_style('ecwid-landing-css', ECWID_PLUGIN_URL . 'css/landing_old.css', array(), get_option('ecwid_plugin_version'), 'all');
			} else {
				wp_enqueue_style('ecwid-landing-css', ECWID_PLUGIN_URL . 'css/landing.css', array(), get_option('ecwid_plugin_version'), 'all');
			}
			wp_enqueue_style('ecwid-landing-fonts', 'http://fonts.googleapis.com/css?family=Open+Sans:400,600,700,300', array(), get_option('ecwid_plugin_version'));
		} else {
			// We already connected and disconnected the store, no need for fancy landing
			wp_enqueue_script('ecwid-connect-js', ECWID_PLUGIN_URL . 'js/dashboard.js', array(), get_option('ecwid_plugin_version'));
		}
	}
}

function ecwid_register_settings_styles($hook_suffix) {

	if ( ($hook_suffix != 'post.php' && $hook_suffix != 'post-new.php') && strpos( $hook_suffix, Ecwid_Admin::ADMIN_SLUG ) === false) return;

	wp_enqueue_style('ecwid-settings-css', ECWID_PLUGIN_URL . 'css/settings.css', array(), get_option('ecwid_plugin_version'), 'all');

	if (version_compare(get_bloginfo('version'), '3.8-beta') > 0) {
		wp_enqueue_style('ecwid-settings38-css', ECWID_PLUGIN_URL . 'css/settings.3.8.css', array('ecwid-settings-css'), '', 'all');
	}
}

function ecwid_plugin_actions($links) {
	$settings_link = "<a href='" . Ecwid_Admin::get_dashboard_url() . "'>"
		. (get_ecwid_store_id() == ECWID_DEMO_STORE_ID ? __('Setup', 'ecwid-shopping-cart') : __('Settings') )
		. "</a>";
	array_unshift( $links, $settings_link );

	return $links;
}

function ecwid_settings_api_init() {

    if ( isset( $_POST['settings_section'] ) ) {
		switch ( $_POST['settings_section'] ) {
			case 'appearance':
				register_setting( 'ecwid_options_page', 'ecwid_enable_minicart' );

				register_setting( 'ecwid_options_page', 'ecwid_show_categories' );
				register_setting( 'ecwid_options_page', 'ecwid_show_search_box' );

				register_setting( 'ecwid_options_page', 'ecwid_pb_categoriesperrow', 'ecwid_abs_intval' );
				register_setting( 'ecwid_options_page', 'ecwid_pb_productspercolumn_grid', 'ecwid_abs_intval' );
				register_setting( 'ecwid_options_page', 'ecwid_pb_productsperrow_grid', 'ecwid_abs_intval' );
				register_setting( 'ecwid_options_page', 'ecwid_pb_productsperpage_list', 'ecwid_abs_intval' );
				register_setting( 'ecwid_options_page', 'ecwid_pb_productsperpage_table', 'ecwid_abs_intval' );
				register_setting( 'ecwid_options_page', 'ecwid_pb_defaultview' );
				register_setting( 'ecwid_options_page', 'ecwid_pb_searchview' );
				break;

			case 'general':
				register_setting( 'ecwid_options_page', 'ecwid_store_id', 'ecwid_abs_intval' );
				if ( isset( $_POST['ecwid_store_id'] ) && intval( $_POST['ecwid_store_id'] ) == 0 ) {
					Ecwid_Message_Manager::reset_hidden_messages();
				}
				break;

			case 'advanced':
				register_setting( 'ecwid_options_page', 'ecwid_default_category_id', 'ecwid_abs_intval' );
				register_setting( 'ecwid_options_page', 'ecwid_sso_secret_key' );
				register_setting( 'ecwid_options_page', 'ecwid_use_chameleon' );
				register_setting( 'ecwid_options_page', 'ecwid_is_sso_enabled' );
				break;
		}

        if ($_POST['settings_section'] == 'advanced' && isset($_POST[Ecwid_Products::OPTION_ENABLED]) && !Ecwid_Products::is_enabled()) {
            Ecwid_Products::enable();
        } else if ($_POST['settings_section'] == 'advanced' && !isset($_POST[Ecwid_Products::OPTION_ENABLED]) && Ecwid_Products::is_enabled()) {
            Ecwid_Products::disable();
        }


        if (Ecwid_Seo_Links::should_display_option()) {
			if ($_POST['settings_section'] == 'advanced' && isset($_POST[Ecwid_Seo_Links::OPTION_ENABLED]) && !Ecwid_Seo_Links::is_enabled()) {
				Ecwid_Seo_Links::enable();
			} else if ($_POST['settings_section'] == 'advanced' && !isset($_POST[Ecwid_Seo_Links::OPTION_ENABLED]) && Ecwid_Seo_Links::is_enabled()) {
				Ecwid_Seo_Links::disable();
			}
        }

		if ($_POST['settings_section'] == 'advanced' && !@$_POST['ecwid_is_sso_enabled']) {
			update_option('ecwid_sso_secret_key', '');
		}
	}

	if (isset($_POST['ecwid_store_id'])) {
		update_option('ecwid_is_api_enabled', 'off');
		update_option('ecwid_api_check_time', 0);
		update_option('ecwid_last_oauth_fail_time', 0);
	}


}

function ecwid_common_admin_scripts() {

	wp_enqueue_script('ecwid-admin-js', ECWID_PLUGIN_URL . 'js/admin.js', array(), get_option('ecwid_plugin_version'));
	wp_enqueue_script('ecwid-modernizr-js', ECWID_PLUGIN_URL . 'js/modernizr.js', array(), get_option('ecwid_plugin_version'));

	wp_localize_script('ecwid-admin-js', 'ecwid_params', array(
		'dashboard' => __('Dashboard', 'ecwid-shopping-cart'),
		'dashboard_url' => Ecwid_Admin::get_dashboard_url(),
		'products' => __('Products', 'ecwid-shopping-cart'),
		'products_url' => Ecwid_Admin::get_dashboard_url() . '-admin-products',
		'orders' => __('Orders', 'ecwid-shopping-cart'),
		'orders_url' => Ecwid_Admin::get_dashboard_url() . '-admin-orders',
		'reset_cats_cache' => __('Refresh categories list', 'ecwid-shopping-cart'),
		'cache_updated' => __('Done', 'ecwid-shopping-cart'),
		'reset_cache_message' => __('The store top-level categories are automatically added to this drop-down menu', 'ecwid-shopping-cart'),
		'store_shortcode' => Ecwid_Shortcode_Base::get_store_shortcode_name(),
		'product_shortcode' => Ecwid_Shortcode_Product::get_shortcode_name()
	));

	wp_enqueue_script('ecwid-sync', ECWID_PLUGIN_URL . 'js/sync.js', array(), get_option('ecwid_plugin_version'));
}

function ecwid_get_register_link()
{
	$link = Ecwid_Config::get_registration_url();

	if ( strpos($link, '?') ) {
		$link .= '&';
	} else {
		$link .= '?';
	}
	$link .= 'partner='
		. Ecwid_Config::get_channel_id()
		. '%s#register';

	$current_user = wp_get_current_user();

	$user_data = '';
	if ($current_user->ID && function_exists('get_user_meta')) {
		$meta = get_user_meta($current_user->ID);

		$data = array(
			'name' => get_user_meta($current_user->ID, 'first_name', true) . ' ' . get_user_meta($current_user->ID, 'last_name', true),
			'nickname' => $current_user->display_name,
			'email' => $current_user->user_email
		);

		foreach ($data as $key => $value) {
			if (trim($value) == '') {
				unset($data[$key]);
			}
		}
		$user_data = '&' . build_query($data);
	}

	$link = sprintf($link, $user_data);

	return $link;
}

function ecwid_create_store() {
	$api = new Ecwid_Api_V3();
	$result = $api->create_store();

	if ( is_array( $result ) && $result['response']['code'] == 200 ) {
		$data = json_decode( $result['body'] );

		ecwid_update_store_id($data->id);

		$api->save_token( $data->token );
		update_option( 'ecwid_oauth_scope', 'read_profile read_catalog allow_sso create_customers public_storefront' );

		header( 'HTTP/1.1 200 OK' );

	} else {

		header( 'HTTP/1.1 ' . $result['response']['code'] . ' ' . $result['response']['message'] );
	}
}

function ecwid_general_settings_do_page() {

	$store_id = get_option( 'ecwid_store_id' );

	$connection_error = isset( $_GET['connection_error'] );

	if ( $store_id == ECWID_DEMO_STORE_ID ) {
		$no_oauth = @$_GET['oauth'] == 'no';

		$there_was_oauth_error = isset( $connection_error ) && $no_oauth;
		$customer_returned_from_creating_store_at_ecwid =
			EcwidPlatform::cache_get( 'user_was_redirected_to_ecwid_site_to_create_account' );

		if ( $there_was_oauth_error || $customer_returned_from_creating_store_at_ecwid ) {
			EcwidPlatform::cache_reset( 'user_was_redirected_to_ecwid_site_to_create_account' );
			require_once ECWID_PLUGIN_DIR . 'templates/connect.php';
		} else {
			$register = ! $connection_error && ! isset( $_GET['connect'] ) && ! @$_COOKIE['ecwid_create_store_clicked'];

			$api = new Ecwid_Api_V3();
			global $current_user;

			if ( ecwid_use_old_landing() ) {
				require_once( ECWID_PLUGIN_DIR . '/templates/landing_old.php' );
			} else if ($api->does_store_exist($current_user->user_email)) {
				require_once ECWID_PLUGIN_DIR . '/templates/connect.php';
			} else {
				require_once( ECWID_PLUGIN_DIR . '/templates/landing.php' );
			}
		}
	} else {
		global $ecwid_oauth;

		if ( !$ecwid_oauth->has_scope( 'allow_sso' ) && !isset($_GET['reconnect']) ) {
			if ( ecwid_test_oauth(true) ) {
				require_once ECWID_PLUGIN_DIR . 'templates/reconnect-sso.php';
			} else {
				require_once ECWID_PLUGIN_DIR . 'templates/dashboard.php';
			}
		} else {

			if ($connection_error || isset($_GET['reconnect'])) {
				if (isset($_GET['reason'])) switch ($_GET['reason']) {
					case 'spw': $reconnect_message = sprintf( __( 'To be able to choose a product to insert to your posts and pages, you will need to re-connect your site to your %s store. This will only require you to accept permissions request – so that the plugin will be able to list your products in the "Add product" dialog.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() );
					break;
				}

				$scopes = '';

				$connection_error = isset($_GET['connection_error']);

				require_once ECWID_PLUGIN_DIR . 'templates/reconnect.php';
			} else {
				$time = time() - get_option('ecwid_time_correction', 0);
				$page = 'dashboard';

				$iframe_src = ecwid_get_iframe_src($time, $page);

				$request = Ecwid_Http::create_get('embedded_admin_iframe', $iframe_src, array(Ecwid_Http::POLICY_RETURN_VERBOSE));
				if (!$request) {
					echo Ecwid_Message_Manager::show_message('no_oauth');
					return;
				}

				$result = $request->do_request(array(
					'timeout' => 20
				));

				if ($result['code'] == 403 && strpos($result['data'], 'Token too old') !== false ) {

					if (isset($result['headers']['date'])) {
						$time = strtotime($result['headers']['date']);

						$iframe_src = ecwid_get_iframe_src($time, $page);

						$request = Ecwid_Http::create_get('embedded_admin_iframe', $iframe_src, array(Ecwid_Http::POLICY_RETURN_VERBOSE));
                        if (!$request) {
                            echo Ecwid_Message_Manager::show_message('no_oauth');
                            return;
                        }
						$result = $request->do_request();

						if ($result['code'] == 200) {
							update_option('ecwid_time_correction', time() - $time);
						}
					}
				}

				if ( is_array( $result ) && $result['code'] == 200 ) {
					ecwid_admin_do_page( 'dashboard' );
				} else {
					require_once ECWID_PLUGIN_DIR . 'templates/reconnect-sso.php';
				}
			}
		}
	}
}

function ecwid_get_iframe_src($time, $page) {

	if (function_exists('get_user_locale')) {
		$lang = get_user_locale();
	} else {
		$lang = get_locale();
	}

	return sprintf(
		'https://my.ecwid.com/api/v3/%s/sso?token=%s&timestamp=%s&signature=%s&place=%s&inline&lang=%s&min-height=700',
		get_ecwid_store_id(),
		Ecwid_Api_V3::get_token(),
		$time,
		hash( 'sha256', get_ecwid_store_id() . Ecwid_Api_V3::get_token() . $time . Ecwid_Config::get_oauth_appsecret() ),
		$page,
		substr( $lang, 0, 2 )
	);
}

function ecwid_admin_do_page( $page ) {

	if (isset($_GET['show_timeout']) && $_GET['show_timeout'] == '1') {
		require_once ECWID_PLUGIN_DIR . 'templates/admin-timeout.php';
		die();
	}
	global $ecwid_oauth;

	if (isset($_GET['ecwid_page']) && $_GET['ecwid_page']) {
		$page = $_GET['ecwid_page'];
	}

	if ($page == ecwid_get_admin_iframe_upgrade_page()) {
		update_option('ecwid_api_check_time', time() - ECWID_API_AVAILABILITY_CHECK_TIME + 10 * 60);
	}

	if ($page == 'dashboard') {
		$show_reconnect = true;
	}

	$time = time() - get_option('ecwid_time_correction', 0);

	$iframe_src = ecwid_get_iframe_src($time, $page);

	$request = Ecwid_Http::create_get('embedded_admin_iframe', $iframe_src, array(Ecwid_Http::POLICY_RETURN_VERBOSE));
    if (!$request) {
        echo Ecwid_Message_Manager::show_message('no_oauth');
        return;
    }

	$result = $request->do_request();

	if (empty($result['code']) && empty($result['data'])) {
		require_once ECWID_PLUGIN_DIR . 'templates/admin-timeout.php';
	} else if ($result['code'] != 200) {
		if (ecwid_test_oauth(true)) {
			require_once ECWID_PLUGIN_DIR . 'templates/reconnect-sso.php';
		} else {
			require_once ECWID_PLUGIN_DIR . 'templates/dashboard.php';
		}
	} else {
		require_once ECWID_PLUGIN_DIR . 'templates/ecwid-admin.php';
	}
}


function ecwid_admin_products_do_page() {
	ecwid_admin_do_page('products');
}

function ecwid_admin_orders_do_page() {
	ecwid_admin_do_page('orders');
}


function ecwid_admin_mobile_do_page() {
	ecwid_admin_do_page('mobile');
}

function ecwid_help_do_page() {

	$help = new Ecwid_Help_Page();
	$faqs = $help->get_faqs();

	wp_enqueue_style('ecwid-help', ECWID_PLUGIN_URL . 'css/help.css',array(), get_option('ecwid_plugin_version'));

	$col_size = 6;
	require_once ECWID_PLUGIN_DIR . 'templates/help.php';
}

function ecwid_process_oauth_params() {

	if (strtoupper($_SERVER['REQUEST_METHOD']) != 'GET' || !isset($_GET['page'])) {
		return;
	}

	$is_dashboard = $_GET['page'] == 'ec-store';

	if (!$is_dashboard) {
		return;
	}

	global $ecwid_oauth;
	$is_connect = get_ecwid_store_id() != ECWID_DEMO_STORE_ID && !isset($_GET['connection_error']);

	$is_reconnect = isset($_GET['reconnect']) && !isset($_GET['connection_error']);

	if ($is_connect) {
		$ecwid_oauth->update_state( array( 'mode' => 'connect' ) );
	}

	if ($is_reconnect && !isset($_GET['api_v3_sso'])) {
		$ecwid_oauth->update_state( array(
			'mode' => 'reconnect',
			// explicitly set to empty array if not available to reset current state
			'scope' => isset($_GET['scope']) ? $_GET['scope'] : array(),
			// explicitly set to empty string if not available to reset current state
			'return_url' => isset($_GET['return-url']) ? $_GET['return-url'] : '',
			'reason' => isset($_GET['reason']) ? $_GET['reason'] : ''
		));
	}
}

function ecwid_admin_post_connect()
{
	if (!current_user_can('manage_options')) {
		return;
	}

	if (isset($_GET['force_store_id'])) {
		update_option('ecwid_store_id', $_GET['force_store_id']);
		update_option('ecwid_is_api_enabled', 'off');
		update_option('ecwid_api_check_time', 0);
		update_option('ecwid_last_oauth_fail_time', 1);
		wp_redirect( Ecwid_Admin::get_dashboard_url() );
		exit;
	}
	global $ecwid_oauth;

	if (ecwid_test_oauth(true)) {

		if (@isset($_GET['api_v3_sso'])) {
			$ecwid_oauth->update_state(array('mode' => 'reconnect', 'return_url' => Ecwid_Admin::get_dashboard_url() . '-advanced' ));
			wp_redirect($ecwid_oauth->get_sso_reconnect_dialog_url());
		} else {
			wp_redirect( $ecwid_oauth->get_auth_dialog_url() );
		}
	} else if (!isset($_GET['reconnect'])) {
		wp_redirect(Ecwid_Admin::get_dashboard_url() . '&oauth=no&connection_error');
	} else {
		wp_redirect(Ecwid_Admin::get_dashboard_url() . '&reconnect&connection_error');
	}
	exit;
}

function ecwid_test_oauth($force = false)
{
	global $ecwid_oauth;

	$last_fail = get_option('ecwid_last_oauth_fail_time');

	if ( ($last_fail > 0 && $last_fail + 60*60*24 < time()) || $force) {

		$result = $ecwid_oauth->test_post();

		if ($result) {
			update_option('ecwid_last_oauth_fail_time', $last_fail = 0);
		} else {
			update_option('ecwid_last_oauth_fail_time', $last_fail = time());
		}
	}

	return $last_fail == 0;
}

function ecwid_get_categories_for_selector() {

	function walk_through_categories($categories, $parent_prefix) {
		if (empty($categories)) {
			return array();
		}
		$result = array();

		foreach ($categories as $category) {
			$result[$category->id] = $category;
			$result[$category->id]->path = $parent_prefix . $category->name;
			$result = array_merge($result, walk_through_categories($category->sub, $category->name . ' > '));
			unset($result[$category->id]->sub);
		}

		return $result;
	}

	$result = walk_through_categories(ecwid_get_categories(true), "");

	return $result;
}

function ecwid_get_product_seo_url( $product_id ) {
	if ( Ecwid_Products::is_enabled() ) {
		global $ecwid_products;

		return $ecwid_products->get_product_link( $product_id );
	} else {
		$api = new Ecwid_Api_V3();
		if ( $api->is_api_available() ) {
			$product = $api->get_product( $product_id );

			return $product->url;
		}
	}

	return Ecwid_Store_Page::get_store_url() . '#!/p/' . $product_id;
}

function ecwid_advanced_settings_do_page() {
	$categories = ecwid_get_categories_for_selector();

	$is_sso_enabled = ecwid_is_sso_enabled();

	global $ecwid_oauth;

	$has_create_customers_scope = $ecwid_oauth->has_scope('create_customers');

	$key = get_option('ecwid_sso_secret_key');
	$is_sso_checkbox_disabled = !$is_sso_enabled && !$has_create_customers_scope && empty($key);
	if (!ecwid_is_paid_account()) {
		$is_sso_checkbox_disabled = true;
	}
	
	$reconnect_link = get_reconnect_link();

	require_once ECWID_PLUGIN_DIR . 'templates/advanced-settings.php';
}

function get_reconnect_link() {
	return admin_url('admin-post.php?action=ec_connect&reconnect&api_v3_sso');
}

function ecwid_get_admin_iframe_upgrade_page() {
	return 'billing:feature=sso&plan=ecwid_venture';
}

function ecwid_appearance_settings_do_page() {

	wp_register_script('ecwid-appearance-js', ECWID_PLUGIN_URL . 'js/appearance.js', array(), get_option('ecwid_plugin_version'), true);
	wp_enqueue_script('ecwid-appearance-js');

	$disabled = false;
	if (!empty($ecwid_page_id) && ($ecwid_page_id > 0)) {
		$_tmp_page = get_post($ecwid_page_id);
		$content = $_tmp_page->post_content;
		if ( (strpos($content, "[ecwid_productbrowser]") === false) && (strpos($content, "xProductBrowser") !== false) )
			$disabled = true;
	}
	// $disabled_str is used in appearance settings template
	if ($disabled)
		$disabled_str = 'disabled = "disabled"';
	else
		$disabled_str = "";

	require_once ECWID_PLUGIN_DIR . 'templates/appearance-settings.php';
}

function ecwid_debug_do_page() {

	$remote_get_results = wp_remote_get( 'http://app.ecwid.com/api/v1/' . get_ecwid_store_id() . '/profile' );

	$api_v3_profile_results = wp_remote_get( 'https://app.ecwid.com/api/v3/' . get_ecwid_store_id() . '/profile?token=' . Ecwid_Api_V3::get_token() );

	global $ecwid_oauth;

	require_once ECWID_PLUGIN_DIR . 'templates/debug.php';
}

function ecwid_get_debug_file() {
	if (!current_user_can('manage_options')) {
		return;
	}

	header('Content-Disposition: attachment;filename=ecwid-plugin-log.html');


	ecwid_debug_do_page();
	wp_die();
}

function get_ecwid_store_id() {
	$store_id = get_option('ecwid_store_id');
	if (empty($store_id)) {
		$store_id = ECWID_DEMO_STORE_ID;
	}

	return $store_id;
}

function ecwid_sync_products() {

	set_time_limit(3600);
	if (!defined('DOING_AJAX')) {
	 echo '<html><body>Lets begin<br />';
		flush();
	}
	$p = new Ecwid_Products();
	$p->sync();

	if (defined('DOING_AJAX') && DOING_AJAX) {
		echo 'OK';
		wp_die();
	} else {
		wp_redirect(Ecwid_Admin::get_dashboard_url() . '-advanced');
	}
}


function ecwid_sync_progress_callback($status) {
	if (!@$status['event']) {
		$status['event'] = 'progress';
	}

	echo 'event: ' . $status['event'] . "\n";

	echo 'data: ' . json_encode($status) . "\n\n";
	flush();
}

add_action('admin_post_ecwid_sync_sse', 'ecwid_sync_products_sse');
function ecwid_sync_products_sse() {
	set_time_limit(0);

	header("Content-Type: text/event-stream\n\n");
    Ecwid_Products::enable();
	$p = new Ecwid_Products();

	$p->set_sync_progress_callback('ecwid_sync_progress_callback');
	$p->sync();

	ecwid_sync_progress_callback(
		array(
			'event' => 'completed',
			'last_update' => ecwid_format_date( $p->get_last_sync_time() )
		)
	);
}

function ecwid_format_date( $unixtime ) {

	return date_i18n( get_option('date_format') . ' ' . get_option('time_format'), $unixtime + get_option( 'gmt_offset' ) * 60 * 60 );
}

function ecwid_slow_sync_progress($status) {
	global $ecwid_sync_status;
	if (!Ecwid_Products::is_enabled()) {
        Ecwid_Products::enable();
	}

	if (!isset($ecwid_sync_status)) {
		$ecwid_sync_status = array(
			'limit'  => -1,
			'offset' => -1,
			'total'  => -1,
			'count'  => -1,
			'updated' => 0,
			'deleted_disabled' => 0,
			'created' => 0,
			'deleted' => 0,
			'skipped_deleted' => 0
		);
	}

	if ($status['event'] == 'fetching_products' || $status['event'] == 'fetching_deleted_product_ids') {
		$ecwid_sync_status['offset'] = $status['offset'];
		$ecwid_sync_status['limit'] = $status['limit'];
	} else if ($status['event'] == 'found_updated' || $status['event'] == 'found_deleted') {
		$ecwid_sync_status['total'] = $status['total'];
		$ecwid_sync_status['count'] = $status['count'];

	} else if ($status['event'] == 'created_product') {
		$ecwid_sync_status['created']++;
	} else if ($status['event'] == 'updated_product') {
		$ecwid_sync_status['updated']++;
	} else if ($status['event'] == 'deleted_disabled_product') {
		$ecwid_sync_status['deleted_disabled']++;
	} else if ($status['event'] == 'deleted_product') {
		$ecwid_sync_status['deleted'] ++;
	} else if ($status['event'] == 'skipped_deleted') {
		$ecwid_sync_status['skipped_deleted']++;
	}
}

add_action('admin_post_ecwid_sync_reset', 'ecwid_sync_reset');

function ecwid_sync_reset()
{
	EcwidPlatform::set(Ecwid_Products_Sync_Status::OPTION_UPDATE_TIME, 0);
	EcwidPlatform::set(Ecwid_Products_Sync_Status::OPTION_LAST_PRODUCT_UPDATE_TIME, 0);
	EcwidPlatform::set(Ecwid_Products_Sync_Status::OPTION_LAST_PRODUCT_DELETE_TIME, 0);

	wp_redirect( Ecwid_Admin::get_dashboard_url() . '-advanced' );
}

add_action('admin_post_ecwid_sync_no_sse', 'ecwid_sync_products_no_sse');
function ecwid_sync_products_no_sse() {
	$p = new Ecwid_Products();

	$p->set_sync_progress_callback('ecwid_slow_sync_progress');

	$over = $p->sync(array(
		'mode' => $_GET['mode'] == 'deleted' ? 'deleted' : 'updated',
		'offset' => intval($_GET['offset']),
		'one_at_a_time' => true,
		'from' => $_GET['time']
	));

	global $ecwid_sync_status;

	if (!$over) {
		echo json_encode($ecwid_sync_status);
	} else {
		echo json_encode(array_merge($ecwid_sync_status, array('status' => 'complete', 'last_update' => ecwid_format_date( $p->get_last_sync_time() ))));
	}
}

add_action('admin_post_ecwid_tick', 'ecwid_tick');

function ecwid_tick() {

	var_dump(ini_get('max_execution_time'));
	set_time_limit(12345);
	var_dump(ini_get('max_execution_time'));
	error_log('tick');
	header("Content-Type: text/event-stream\n\n");
	for ($i = 0; $i < 30; $i++) {
		echo "data: $i \n\n";
		flush();
		sleep(2);
		//usleep(2000);
	}
	die();
}


function ecwid_dashboard_widget_function() {
	if (!is_ssl()) {
		require_once ECWID_PLUGIN_DIR . 'templates/wp-dashboard-widget.php';
	}
}

function ecwid_add_dashboard_widgets() {
  if (current_user_can('manage_options')) {
    wp_add_dashboard_widget('ecwid_dashboard_widget', __('Recommendations for Your Online Store', 'ecwid-shopping-cart'), 'ecwid_dashboard_widget_function');
  }
}

function ecwid_get_store_page_base_url( $page = 0 ) {

	$url = parse_url( get_permalink( $page ) );
	return $url['path'];
}

function ecwid_get_store_page_url()
{
	static $link = null;

	if (is_null($link)) {
		$link = get_page_link( Ecwid_Store_Page::get_current_store_page_id() );
	}

	return $link;
}

function ecwid_is_store_page_available()
{
	return Ecwid_Store_Page::get_current_store_page_id() != false;
}

function ecwid_get_product_url($product)
{
	return ecwid_get_entity_url($product, 'p');
}

function ecwid_get_category_url($category)
{
	return ecwid_get_entity_url($category, 'c');
}

function ecwid_get_entity_url($entity, $type) {

	$link = Ecwid_Store_Page::get_store_url();

	if (is_numeric($entity)) {
		return $link . '#!/' . $type . '/' . $entity;
	} elseif (is_array($entity) && isset($entity['url'])) {
		$link .= substr($entity['url'], strpos($entity['url'], '#'));
	}

	return $link;

}

function ecwid_get_product_browser_url_script()
{
	if ( get_option('ecwid_disable_pb_url' ) ) {
		return;
	}

	$str = '';
	if (ecwid_is_store_page_available()) {

		$url = Ecwid_Store_Page::get_store_url();

		$str = '<script data-cfasync="false" type="text/javascript">var ecwid_ProductBrowserURL = "' . esc_js($url) . '";</script>';
	}

	return $str;

}

function ecwid_get_wp_install_date( ) {
	global $wpdb;

	$wp_date = get_option( 'ecwid_wp_install_date' );
	if ( ! $wp_date ) {
		global $wpdb;
		$oldest_user     = strtotime( $wpdb->get_var( "SELECT min(`user_registered`) FROM {$wpdb->users}" ) );
		$oldest_post     = strtotime( $wpdb->get_var( "SELECT min(`post_date`) FROM {$wpdb->posts}" ) );
		$wpconfig_create = @filectime( ABSPATH . '/wp-config.php' );

		$wp_date = min( $oldest_user, $oldest_post, $wpconfig_create );
		update_option( 'ecwid_wp_install_date', $wp_date );
	}

	return $wp_date;
}

function ecwid_check_for_remote_connection_errors()
{
	global $ecwid_oauth;

	$results = array();
	$results['https_get_error'] = wp_remote_get(ecwid_get_categories_js_url('abc'));
	$results['https_post_error'] = wp_remote_post($ecwid_oauth->get_test_post_url());

	foreach ($results as $type => $value) {
		if (is_wp_error($value)) {
			$results[$type] = $value->get_error_message();
		} else {
			unset($results[$type]);
		}
	}

	return $results;
}

function ecwid_is_sso_enabled() {
	global $ecwid_oauth;

	$is_sso_enabled = false;

	$is_apiv3_sso = ecwid_is_paid_account() && get_option('ecwid_is_sso_enabled') && $ecwid_oauth->has_scope('create_customers');
	$is_apiv1_sso = ecwid_is_paid_account() && get_option('ecwid_sso_secret_key');

	$is_sso_enabled = $is_apiv3_sso || $is_apiv1_sso;

	return $is_sso_enabled;
}

function ecwid_sso() {

	if (!ecwid_is_sso_enabled()) return;

    $current_user = wp_get_current_user();

	$signin_url = wp_login_url(Ecwid_Store_Page::get_store_url());
	$signout_url = wp_logout_url(Ecwid_Store_Page::get_store_url());
	$sign_in_out_urls = <<<JS
window.EcwidSignInUrl = '$signin_url';
window.EcwidSignOutUrl = '$signout_url';
window.Ecwid.OnAPILoaded.add(function() {

    window.Ecwid.setSignInUrls({
        signInUrl: '$signin_url',
        signOutUrl: '$signout_url'
    });
});
JS;

	$ecwid_sso_profile = '';
    if ($current_user->ID) {
		$meta = get_user_meta($current_user->ID);

		$name = $meta['first_name'][0] . ' ' . $meta['last_name'][0];

		if ($name == ' ') {
			$name = $meta['nickname'][0];
		}

	    $user_data = array(
            'userId' => "{$current_user->ID}",
            'profile' => array(
                'email' => $current_user->user_email,
                'billingPerson' => array(
	                'name' => $name
                )
            )
        );

	    global $ecwid_oauth;
	    if ($ecwid_oauth->has_scope('create_customers')) {
		    $key = Ecwid_Config::get_oauth_appsecret();
		    $user_data['appClientId'] = Ecwid_Config::get_oauth_appid();
	    } else {
		    $key = get_option('ecwid_sso_secret_key');
		    $user_data['appId'] = "wp_" . get_ecwid_store_id();
	    }

		$user_data_encoded = base64_encode(json_encode($user_data));
		$time = time();
		$hmac = ecwid_hmacsha1("$user_data_encoded $time", $key);

		$ecwid_sso_profile = "$user_data_encoded $hmac $time";

    }


	return <<<HTML
<script data-cfasync="false" type="text/javascript">

var ecwid_sso_profile='$ecwid_sso_profile';
$sign_in_out_urls
</script>
HTML;
}

// from: http://www.php.net/manual/en/function.sha1.php#39492

function ecwid_hmacsha1($data, $key) {
  if (function_exists("hash_hmac")) {
    return hash_hmac('sha1', $data, $key);
  } else {
    $blocksize=64;
    $hashfunc='sha1';
    if (strlen($key)>$blocksize)
        $key=pack('H*', $hashfunc($key));
    $key=str_pad($key,$blocksize,chr(0x00));
    $ipad=str_repeat(chr(0x36),$blocksize);
    $opad=str_repeat(chr(0x5c),$blocksize);
    $hmac = pack(
                'H*',$hashfunc(
                    ($key^$opad).pack(
                        'H*',$hashfunc(
                            ($key^$ipad).$data
                        )
                    )
                )
            );
    return bin2hex($hmac);
    }
}

function ecwid_can_display_html_catalog()
{
	if (!isset($_GET['_escaped_fragment_'])) return;

	$api = ecwid_new_product_api();
	if (!$api) return;

	$profile = $api->get_profile();
	if (!$profile) return;
	return $profile['closed'] != true;
}

function ecwid_get_default_pb_size() {
	return array(
		'grid_rows' =>    10,
		'grid_columns' => 3,
		'list_rows' =>    60,
		'table_rows' =>   60
	);
}

function ecwid_update_store_id( $new_store_id ) {

	EcwidPlatform::cache_reset( 'nav_categories_posts' );

	update_option( 'ecwid_store_id', $new_store_id );
	update_option( 'ecwid_is_api_enabled', 'off' );
	update_option( 'ecwid_api_check_time', 0 );

	do_action('ecwid_update_store_id', $new_store_id);
}

function ecwid_is_paid_account()
{
	return ecwid_is_api_enabled() && get_ecwid_store_id() != ECWID_DEMO_STORE_ID;
}

function ecwid_is_api_enabled()
{
    $ecwid_is_api_enabled = get_option('ecwid_is_api_enabled');
    $ecwid_api_check_time = get_option('ecwid_api_check_time');
    $now = time();

    if ( $now > ($ecwid_api_check_time + ECWID_API_AVAILABILITY_CHECK_TIME) && get_ecwid_store_id() != ECWID_DEMO_STORE_ID ) {
        $ecwid = ecwid_new_product_api();

        $ecwid_is_api_enabled = ($ecwid->is_api_enabled() ? 'on' : 'off');

        update_option('ecwid_is_api_enabled', $ecwid_is_api_enabled);
        update_option('ecwid_api_check_time', $now);
    }

    if ('on' == $ecwid_is_api_enabled)
        return true;
    else
        return false;
}

function ecwid_new_product_api()
{
    include_once ECWID_PLUGIN_DIR . 'lib/ecwid_product_api.php';
    $ecwid_store_id = intval(get_ecwid_store_id());
    $api = new EcwidProductApi($ecwid_store_id);

    return $api;
}

function ecwid_embed_svg($name) {
	$code = file_get_contents(ECWID_PLUGIN_DIR . 'images/' . $name . '.svg');

	echo $code;
}

function ecwid_get_categories_js_url($callback = null) {

	$url = 'https://my.ecwid.com/categories.js?ownerid=' . get_ecwid_store_id();

	if ($callback) {
		$url .= '&callback=' . $callback;
	}

	return $url;
}


function ecwid_use_old_landing() {
	return version_compare(get_bloginfo('version'), '3.7') < 0;
}

/*
 * Basically a copy of has_shortcode that returns the matched shortcode
 */
function ecwid_find_shortcodes( $content, $tag ) {

	if ( false === strpos( $content, '[' ) ) {
		return false;
	}

	if ( shortcode_exists( $tag ) ) {
		preg_match_all( '/' . ecwid_get_shortcode_regex() . '/s', $content, $matches, PREG_SET_ORDER );
		if ( empty( $matches ) )
			return false;

		$result = array();
		foreach ( $matches as $shortcode ) {
			if ( $tag === $shortcode[2] ) {
				$result[] = $shortcode;
			} elseif ( !empty($shortcode[5]) && $found = ecwid_find_shortcodes( $shortcode[5], $tag ) ) {
				$result = array_merge($result, $found);
			}
		}

		if (empty($result)) {
			$result = false;
		}
		return $result;
	}
	return false;
}

// Since we use shortcode regex in our own functions, we need it to be persistent
function ecwid_get_shortcode_regex() {
	global $shortcode_tags;
	$tagnames = array_keys($shortcode_tags);
	$tagregexp = join( '|', array_map('preg_quote', $tagnames) );

	// WARNING! Do not change this regex without changing do_shortcode_tag() and strip_shortcode_tag()
	// Also, see shortcode_unautop() and shortcode.js.
	return
		'\\['                              // Opening bracket
		. '(\\[?)'                           // 1: Optional second opening bracket for escaping shortcodes: [[tag]]
		. "($tagregexp)"                     // 2: Shortcode name
		. '(?![\\w-])'                       // Not followed by word character or hyphen
		. '('                                // 3: Unroll the loop: Inside the opening shortcode tag
		.     '[^\\]\\/]*'                   // Not a closing bracket or forward slash
		.     '(?:'
		.         '\\/(?!\\])'               // A forward slash not followed by a closing bracket
		.         '[^\\]\\/]*'               // Not a closing bracket or forward slash
		.     ')*?'
		. ')'
		. '(?:'
		.     '(\\/)'                        // 4: Self closing tag ...
		.     '\\]'                          // ... and closing bracket
		. '|'
		.     '\\]'                          // Closing bracket
		.     '(?:'
		.         '('                        // 5: Unroll the loop: Optionally, anything between the opening and closing shortcode tags
		.             '[^\\[]*+'             // Not an opening bracket
		.             '(?:'
		.                 '\\[(?!\\/\\2\\])' // An opening bracket not followed by the closing shortcode tag
		.                 '[^\\[]*+'         // Not an opening bracket
		.             ')*+'
		.         ')'
		.         '\\[\\/\\2\\]'             // Closing shortcode tag
		.     ')?'
		. ')'
		. '(\\]?)';                          // 6: Optional second closing brocket for escaping shortcodes: [[tag]]
}

?>
