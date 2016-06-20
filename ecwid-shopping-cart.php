<?php
/*
Plugin Name: Ecwid Shopping Cart
Plugin URI: http://www.ecwid.com?source=wporg
Description: Ecwid is a free full-featured shopping cart. It can be easily integrated with any Wordpress blog and takes less than 5 minutes to set up.
Text Domain: ecwid-shopping-cart
Author: Ecwid Team
Version: 4.4
Author URI: http://www.ecwid.com?source=wporg
*/

register_activation_hook( __FILE__, 'ecwid_store_activate' );
register_deactivation_hook( __FILE__, 'ecwid_store_deactivate' );
register_uninstall_hook( __FILE__, 'ecwid_uninstall' );

define("APP_ECWID_COM", 'app.ecwid.com');
define("ECWID_DEMO_STORE_ID", 1003);

define ('ECWID_TRIMMED_DESCRIPTION_LENGTH', 160);

define ( 'ECWID_VERSION_BUILTIN_CHAMELEON', '10.4.4' );

if ( ! defined( 'ECWID_PLUGIN_DIR' ) ) {
	define( 'ECWID_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'ECWID_PLUGIN_BASENAME' ) ) {
	define( 'ECWID_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'ECWID_PLUGIN_URL' ) ) {
	define( 'ECWID_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

// Older versions of Google XML Sitemaps plugin generate it in admin, newer in site area, so the hook should be assigned in both of them
add_action('sm_buildmap', 'ecwid_build_google_xml_sitemap');

// Needs to be in both front-end and back-end to allow admin zone recognize the shortcode
add_shortcode('ecwid_productbrowser', 'ecwid_productbrowser_shortcode');
add_shortcode('ecwid', 'ecwid_shortcode');

add_action( 'plugins_loaded', 'ecwid_init_integrations' );
add_filter('plugins_loaded', 'ecwid_load_textdomain');

if ( is_admin() ){ 
  add_action('admin_init', 'ecwid_settings_api_init');
	add_action('admin_init', 'ecwid_check_version');
	add_action('admin_init', 'ecwid_process_oauth_params');
  add_action('admin_notices', 'ecwid_show_admin_messages');
  add_action('admin_menu', 'ecwid_build_menu');
  add_action('wp_dashboard_setup', 'ecwid_add_dashboard_widgets' );
  add_action('admin_enqueue_scripts', 'ecwid_common_admin_scripts');
  add_action('admin_enqueue_scripts', 'ecwid_register_admin_styles');
  add_action('admin_enqueue_scripts', 'ecwid_register_settings_styles');
  add_action('wp_ajax_ecwid_hide_vote_message', 'ecwid_hide_vote_message');
  add_action('wp_ajax_ecwid_hide_message', 'ecwid_ajax_hide_message');
	add_action('wp_ajax_save-widget', 'ecwid_ajax_save_widget');
	add_action('wp_ajax_ecwid_reset_categories_cache', 'ecwid_reset_categories_cache');
	add_action('wp_ajax_ecwid_create_store', 'ecwid_create_store');
  add_filter('plugin_action_links_' . ECWID_PLUGIN_BASENAME, 'ecwid_plugin_actions');
  add_action('admin_head', 'ecwid_ie8_fonts_inclusion');
  add_action('admin_head', 'ecwid_send_stats');
  add_action('save_post', 'ecwid_save_post');
  add_action('init', 'ecwid_apply_theme');
	add_action('get_footer', 'ecwid_admin_get_footer');
	add_action('admin_post_ecwid_connect', 'ecwid_admin_post_connect');
	add_filter('tiny_mce_before_init', 'ecwid_tinymce_init');
	add_action('admin_post_ecwid_get_debug', 'ecwid_get_debug_file');
} else {
  add_shortcode('ecwid_script', 'ecwid_script_shortcode');
  add_shortcode('ecwid_minicart', 'ecwid_minicart_shortcode');
  add_shortcode('ecwid_searchbox', 'ecwid_searchbox_shortcode');
  add_shortcode('ecwid_categories', 'ecwid_categories_shortcode');
  add_shortcode('ecwid_product', 'ecwid_product_shortcode');
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
	add_action('wp_head', 'ecwid_send_stats');
	add_action('wp_head', 'ecwid_product_browser_url_in_head');
  add_filter( 'widget_meta_poweredby', 'ecwid_add_credits');
  add_filter('the_content', 'ecwid_content_started', 0);
  add_filter('body_class', 'ecwid_body_class');
  $ecwid_seo_title = '';
}
add_action('admin_bar_menu', 'add_ecwid_admin_bar_node', 1000);
if (get_option('ecwid_last_oauth_fail_time') > 0) {
	add_action('plugins_loaded', 'ecwid_test_oauth');
}

$ecwid_script_rendered = false; // controls single script.js on page

require_once ECWID_PLUGIN_DIR . 'includes/themes.php';
require_once ECWID_PLUGIN_DIR . 'includes/oembed.php';
require_once ECWID_PLUGIN_DIR . 'includes/widgets.php';
require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-message-manager.php';
require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-store-editor.php';
require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-oauth.php';
require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-kissmetrics.php';

if (is_admin()) {
	require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-help-page.php';
}

require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-nav-menus.php';

require_once ECWID_PLUGIN_DIR . 'lib/ecwid_platform.php';
require_once ECWID_PLUGIN_DIR . 'lib/ecwid_api_v3.php';

function ecwid_init_integrations()
{
	if ( !function_exists( 'get_plugins' ) ) { require_once ( ABSPATH . 'wp-admin/includes/plugin.php' ); }

	$integrations = array(
		'aiosp' => 'all-in-one-seo-pack/all_in_one_seo_pack.php',
		'wpseo' => 'wordpress-seo/wp-seo.php'
	);

	foreach ($integrations as $key => $plugin) {
		if ( is_plugin_active($plugin) ) {
			require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-integration-' . $key . '.php';
		}
	}
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
	if (ecwid_page_has_productbrowser()) {
		$classes[] = 'ecwid-shopping-cart';
	}

	return $classes;
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


function ecwid_enqueue_frontend() {

	wp_enqueue_script('jquery-ui-widget');
	wp_register_script('ecwid-products-list-js', ECWID_PLUGIN_URL . 'js/products-list.js', array('jquery-ui-widget'), get_option('ecwid_plugin_version'));
	wp_register_style('ecwid-products-list-css', ECWID_PLUGIN_URL . 'css/products-list.css', array(), get_option('ecwid_plugin_version'));
	wp_enqueue_style('ecwid-css', ECWID_PLUGIN_URL . 'css/frontend.css',array(), get_option('ecwid_plugin_version'));
	wp_enqueue_style('ecwid-fonts-css', ECWID_PLUGIN_URL . 'css/fonts.css', array(), get_option('ecwid_plugin_version'));

	wp_enqueue_script( 'ecwid-frontend-js', ECWID_PLUGIN_URL . 'js/frontend.js', array( 'jquery' ), get_option( 'ecwid_plugin_version' ) );

	if ((bool)get_option('ecwid_use_chameleon')) {
		if (ecwid_migrations_is_original_plugin_version_older_than( ECWID_VERSION_BUILTIN_CHAMELEON )) {
			ecwid_enqueue_external_chameleon();
		} else {
			ecwid_enqueue_builtin_chameleon();
		}
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
}

function ecwid_enqueue_external_chameleon() {
	wp_enqueue_script('ecwid-chameleon-js', 'https://dj925myfyz5v.cloudfront.net/widgets/chameleon/v1/ecwid-chameleon.js', array(), get_option('ecwid_plugin_version'), true);

	$primary    = get_option('ecwid_chameleon_primary');
	$background = get_option('ecwid_chameleon_background');
	$links      = get_option('ecwid_chameleon_links');

	$localize = array();

	if (get_option('ecwid_chameleon_primary')) {
		$localize['primary_color'] = get_option('ecwid_chameleon_primary');
	}
	if (get_option('ecwid_chameleon_background')) {
		$localize['primary_background'] = get_option('ecwid_chameleon_background');
	}
	if (get_option('ecwid_chameleon_links')) {
		$localize['primary_link'] = get_option('ecwid_chameleon_links');
	}

	if (!empty( $localize )) {
		wp_localize_script('ecwid-chameleon-js', 'ecwidChameleon', $localize);
	}
}

function ecwid_enqueue_builtin_chameleon() {
	wp_enqueue_script('ecwid-chameleon-js', ECWID_PLUGIN_URL . '/js/chameleon.js', array(), get_option('ecwid_plugin_version'), true);

	$colors = array();

	foreach (array('foreground', 'background', 'link', 'price', 'button') as $kind) {
		$color = get_option( 'ecwid_chameleon_colors_' . $kind );
		if ( $color ) {
			$colors['color-' . $kind] = $color;
		}
	}

	if (empty($colors)) {
		$colors = 'auto';
	}
	wp_localize_script('ecwid-chameleon-js', 'ecwidChameleon', array(
		'colors' => $colors
	));
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
		if ($params['mode'] == 'product') {
			$result = $api->get_product($params['id']);
		} elseif ($params['mode'] == 'category') {
			$result = $api->get_category($params['id']);
		}
		if (empty($result)) {
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

	$page_id = ecwid_get_current_store_page_id();

	if (get_post_status($page_id) == 'publish') {
		require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-sitemap-builder.php';

		$sitemap = new EcwidSitemapBuilder(ecwid_get_store_page_url(), $callback, ecwid_new_product_api());

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

		ecwid_plugin_add_oauth();
		do_action('ecwid_plugin_installed', $current_version);
		add_option('ecwid_plugin_version', $current_version);

		update_option('ecwid_use_chameleon', true);

		add_option('ecwid_use_new_horizontal_categories', 'Y');
		add_option('ecwid_use_new_search', 'Y');
	} elseif ($upgrade) {

		ecwid_plugin_add_oauth();
		do_action('ecwid_plugin_upgraded', array( 'old' => $stored_version, 'new' => $current_version ) );
		update_option('ecwid_plugin_version', $current_version);

		add_option('ecwid_chameleon_primary', '');
		add_option('ecwid_chameleon_background', '');
		add_option('ecwid_chameleon_links', '');

		add_option('ecwid_use_new_horizontal_categories', '');
		add_option('ecwid_use_new_search', '');
	}

	if ($fresh_install || $upgrade || @$_GET['ecwid_reinit']) {
		if (ecwid_migrations_is_original_plugin_version_older_than('4.3')) {
			add_option('ecwid_fetch_url_use_file_get_contents', '');
			add_option('ecwid_remote_get_timeout', '5');
		}

		if (ecwid_migrations_is_original_plugin_version_older_than('4.1.3')) {
			add_option( 'ecwid_support_email', 'wordpress@ecwid.com' );
		}


		if (!ecwid_migrations_is_original_plugin_version_older_than( ECWID_VERSION_BUILTIN_CHAMELEON )) {
			add_option('ecwid_chameleon_colors_foreground', '');
			add_option('ecwid_chameleon_colors_background', '');
			add_option('ecwid_chameleon_colors_link', '');
			add_option('ecwid_chameleon_colors_button', '');
			add_option('ecwid_chameleon_colors_price', '');
		}

		$all_plugins = get_plugins();
		$has_woo = ecwid_get_woocommerce_status();

		if ($has_woo) {
			add_option('ecwid_extended_help', true);
		}
	}
}

function ecwid_get_woocommerce_status() {

	$woo = EcwidPlatform::cache_get('woo_status');

	if (is_null($woo)) {
		$woo = 0;
		$all_plugins = get_plugins();
		if (array_key_exists('woocommerce/woocommerce.php', $all_plugins)) {
			$active_plugins = get_option('active_plugins');
			if (in_array('woocommerce/woocommerce.php', $active_plugins)) {
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

function ecwid_plugin_add_oauth()
{
	add_option('ecwid_oauth_client_id', 'RD4o2KQimiGUrFZc');
	add_option('ecwid_oauth_client_secret', 'jEPVdcA3KbzKVrG8FZDgNnsY3wKHDTF8');

	update_option('ecwid_oauth_client_id', 'RD4o2KQimiGUrFZc');
	update_option('ecwid_oauth_client_secret', 'jEPVdcA3KbzKVrG8FZDgNnsY3wKHDTF8');
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

	if ($seo_ultimate && ecwid_page_has_productbrowser()) {
		remove_action('template_redirect', array($seo_ultimate->modules['titles'], 'before_header'), 0);
		remove_action('wp_head', array($seo_ultimate->modules['titles'], 'after_header'), 1000);
		remove_action('su_head', array($seo_ultimate->modules['meta-descriptions'], 'head_tag_output'));
		remove_action('su_head', array($seo_ultimate->modules['canonical'], 'link_rel_canonical_tag'));
		remove_action('su_head', array($seo_ultimate->modules['canonical'], 'http_link_rel_canonical'));
	}
}

function ecwid_remove_default_canonical()
{
	if (array_key_exists('_escaped_fragment_', $_GET) && ecwid_page_has_productbrowser()) {
		remove_action( 'wp_head','rel_canonical');
	}
}

function ecwid_seo_compatibility_init($title)
{
    if (!array_key_exists('_escaped_fragment_', $_GET) || !ecwid_page_has_productbrowser()) {
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
    if (!array_key_exists('_escaped_fragment_', $_GET) || !ecwid_page_has_productbrowser()) {
        return;
    }

    ecwid_override_option('psp_canonical');
    ecwid_override_option('aiosp_rewrite_titles');
}

function add_ecwid_admin_bar_node() {
	global $wp_admin_bar;

	if ( !is_super_admin() || !is_admin_bar_showing() )
		return;

	$theme     = ecwid_get_theme_name();
	$store_url = ecwid_get_store_page_url();


	if (!is_admin()) {
		$subject = sprintf(__('Ecwid plugin doesn\'t work well with my "%s" theme', 'ecwid-shopping-cart'), $theme);
		$body = <<<TEXT
Hey Ecwid,

My store looks bad with my theme on Wordpress.

The theme title is %s.
The store URL is %s

Can you have a look?

Thanks.
TEXT;
	} else {
		$subject = __('I have a problem with my Ecwid store', 'ecwid-shopping-cart');
		$body = <<<TEXT
Hey Ecwid,

I have a problem with my Ecwid store.

[Please provide details here]

The theme title is %s.
The store URL is %

Can you have a look?

Thanks.
TEXT;
	}

	$body = __($body, 'ecwid-shopping-cart');
	$body = sprintf($body, $theme, $store_url);

	$wp_admin_bar->add_menu( array(
		'id' => 'ecwid-main',
		'title' => '<span class="ab-icon ecwid-top-menu-item"></span>',
		'href' => 'admin.php?page=ecwid',
	));

	$wp_admin_bar->add_menu(array(
			"id" => "ecwid-go-to-page",
			"title" => __("Visit storefront", 'ecwid-shopping-cart'),
			"parent" => "ecwid-main",
			'href' => ecwid_get_store_page_url()
		)
	);

	$wp_admin_bar->add_menu(array(
			"id" => "ecwid-control-panel",
			"title" => __("Manage my store", 'ecwid-shopping-cart'),
			"parent" => "ecwid-main",
			'href' =>  admin_url('admin.php?page=ecwid')
		)
	);

	$wp_admin_bar->add_menu(array(
			"id" => "ecwid-faq",
			"title" => __("Read FAQ", 'ecwid-shopping-cart'),
			"parent" => "ecwid-main",
			'href' =>  __('https://help.ecwid.com/customer/portal/articles/1085017-wordpress-downloadable', 'ecwid-shopping-cart'),
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

function ecwid_content_has_productbrowser($content) {

	$result = has_shortcode($content, 'ecwid_productbrowser');

	if (!$result && has_shortcode($content, 'ecwid')) {
		$shortcodes = ecwid_find_shortcodes($content, 'ecwid');
		if ($shortcodes) foreach ($shortcodes as $shortcode) {

			$attributes = shortcode_parse_atts($shortcode[3]);

			if (isset($attributes['widgets'])) {
				$widgets = preg_split('![^0-9^a-z^A-Z^-^_]!', $attributes['widgets']);
				if (is_array($widgets) && in_array('productbrowser', $widgets)) {
					$result = true;
				}
			}
		}
	}

	return $result;
}

function ecwid_page_has_productbrowser($post_id = null)
{
	static $results = null;

	if (is_null($post_id)) {
		if (is_admin()) return;
		$post_id = get_the_ID();
	}

	if (!isset($results[$post_id])) {
		$post = get_post($post_id);

		if ($post) {
			$post_content = get_post($post_id)->post_content;

			$results[$post_id] = ecwid_content_has_productbrowser($post_content);
			$results[$post_id] = apply_filters( 'ecwid_page_has_product_browser', $results[$post_id] );
		}
	}

	return $results[$post_id];
}

function ecwid_ajax_crawling_fragment() {
    if (ecwid_is_api_enabled() && !isset($_GET['_escaped_fragment_']) && ecwid_page_has_productbrowser())
        echo '<meta name="fragment" content="!">' . PHP_EOL; 
}

function ecwid_meta() {

    echo '<link rel="dns-prefetch" href="//images-cdn.ecwid.com/">' . PHP_EOL;
    echo '<link rel="dns-prefetch" href="//images.ecwid.com/">' . PHP_EOL;
    echo '<link rel="dns-prefetch" href="//app.ecwid.com/">' . PHP_EOL;

    if (!ecwid_page_has_productbrowser() && ecwid_is_store_page_available()) {
        $page_url = ecwid_get_store_page_url();
        echo '<link rel="prefetch" href="' . $page_url . '" />' . PHP_EOL;
        echo '<link rel="prerender" href="' . $page_url . '" />' . PHP_EOL;
    }
}

function ecwid_product_browser_url_in_head() {
	echo ecwid_get_product_browser_url_script();
}


function ecwid_canonical() {
	$allowed = ecwid_is_api_enabled() && isset($_GET['_escaped_fragment_']);
	if (!$allowed) return;

	$params = ecwid_parse_escaped_fragment($_GET['_escaped_fragment_']);
	if (!$params) return;

	if (!in_array($params['mode'], array('category', 'product')) || !isset($params['id'])) return;

	$api = ecwid_new_product_api();

	if ($params['mode'] == 'product') {
		$product = $api->get_product($params['id']);
		$link = ecwid_get_product_url($product);
	} else if ($params['mode'] == 'category') {
		$category = $api->get_category($params['id']);
		$link = ecwid_get_category_url($category);
	}

	echo '<link rel="canonical" href="' . esc_attr($link) . '" />' . PHP_EOL;
}

function ecwid_meta_description() {

    $allowed = ecwid_is_api_enabled() && isset($_GET['_escaped_fragment_']);
    if (!$allowed) return;

    $params = ecwid_parse_escaped_fragment($_GET['_escaped_fragment_']);
    if (!$params) return;

    if (!in_array($params['mode'], array('category', 'product')) || !isset($params['id'])) return;

    $api = ecwid_new_product_api();
    if ($params['mode'] == 'product') {
        $product = $api->get_product($params['id']);
        $description = $product['description'];
    } elseif ($params['mode'] == 'category') {
        $category = $api->get_category($params['id']);
        $description = $category['description'];
    } else return;

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
	if (!isset($_GET['_escaped_fragment_']) || !ecwid_is_api_enabled()) return;

	$params = ecwid_parse_escaped_fragment( $_GET['_escaped_fragment_'] );
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
			__('<a %s>Online store powered by Ecwid</a>', 'ecwid-shopping-cart'),
			'target="_blank" href="//www.ecwid.com?source=wporg-metalink"'
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
    return "<!-- Ecwid shopping cart plugin v 4.4 --><!-- noptimize -->"
		   . ecwid_get_scriptjs_code(@$attrs['lang'])
	       . "<div class=\"ecwid-shopping-cart-$name\">$content</div>"
		   . "<!-- /noptimize --><!-- END Ecwid Shopping Cart v 4.4 -->";
}

function ecwid_get_scriptjs_code($force_lang = null) {
	global $ecwid_script_rendered;

    if (!$ecwid_script_rendered) {
		$store_id = get_ecwid_store_id();
		$force_lang_str = !empty($force_lang) ? "&lang=$force_lang" : '';
		$s =  '<script data-cfasync="false" type="text/javascript" src="https://' . APP_ECWID_COM . '/script.js?' . $store_id . '&data_platform=wporg' . $force_lang_str . '"></script>';
		$s = $s . ecwid_sso();
		$s .= '<script type="text/javascript">if (jQuery && jQuery.mobile) { jQuery.mobile.hashListeningEnabled = false; jQuery.mobile.pushStateEnabled=false; }</script>';
		$ecwid_script_rendered = true;

		return $s;
    } else {
		return '';
    }
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

	$params = shortcode_atts(
		array(
			'layout' => null,
			'is_ecwid_shortcode' => false,
			'lang' => null
		), $attributes
	);

	$layout = $params['layout'];
	if (!in_array($layout, array('', 'attachToCategories', 'floating', 'Mini', 'MiniAttachToProductBrowser'), true)) {
		$layout = 'MiniAttachToProductBrowser';
	}

	if ($params['is_ecwid_shortcode']) {
		// it is a part of the ecwid shortcode, we need to show it anyways
		$ecwid_enable_minicart = $ecwid_show_categories = true;
	} else {
		// it is a ecwid_minicart widget that works based on appearance settings
		$ecwid_enable_minicart = get_option('ecwid_enable_minicart');
		$ecwid_show_categories = get_option('ecwid_show_categories');
	}

	$result = '';

	if (!empty($ecwid_enable_minicart) && !empty($ecwid_show_categories)) {
		$result = <<<EOT
<script data-cfasync="false" type="text/javascript"> xMinicart("style=","layout=$layout"); </script>
EOT;
	}

	$result = apply_filters('ecwid_minicart_shortcode_content', $result);

	if (!empty($result)) {
		$result = ecwid_wrap_shortcode_content($result, 'minicart', $params);
	}

	return $result;
}

function ecwid_get_search_js_code() {
	if (get_option('ecwid_use_new_search', false)) {
		return 'xSearch("style=");';
	} else {
		return 'xSearchPanel("style=")';
	}
}

function ecwid_searchbox_shortcode($attributes) {

	$params = shortcode_atts(
		array(
			'is_ecwid_shortcode' => false,
			'lang' => null
		), $attributes
	);

	$ecwid_show_search_box = $params['is_ecwid_shortcode'] ? true : get_option('ecwid_show_search_box');

	$result = '';
	if (!empty($ecwid_show_search_box)) {
		$code = ecwid_get_search_js_code();
  	$result = <<<EOT
<script data-cfasync="false" type="text/javascript"> $code </script>
EOT;
  }

	$result = apply_filters('ecwid_search_shortcode_content', $result);

	if (!empty($result)) {
		$result = ecwid_wrap_shortcode_content($result, 'search', $params);
	}

	return $result;
}

function ecwid_categories_shortcode($attributes) {

	$params = shortcode_atts(
		array(
			'is_ecwid_shortcode' => false,
			'lang' => null
		), $attributes
	);

  $ecwid_show_categories = $params['is_ecwid_shortcode'] ? true : get_option('ecwid_show_categories');

	$result = '';
  if (!empty($ecwid_show_categories)) {
	  if (get_option('ecwid_use_new_horizontal_categories')) {
		  $store_id = get_ecwid_store_id();
		  $ver = get_option('ecwid_plugin_version');
		  $result = <<<HTML
<div id="horizontal-menu" data-storeid="$store_id"></div>
<script src="https://djqizrxa6f10j.cloudfront.net/horizontal-category-widget/v1.2/horizontal-widget.js?ver=$ver"></script>
HTML;
	  } else {
		  $result = <<<EOT
<script data-cfasync="false" type="text/javascript"> xCategories("style="); </script>
EOT;
	  }
  }

	$result = apply_filters('ecwid_categories_shortcode_content', $result);

	if (!empty($result)) {
		$result = ecwid_wrap_shortcode_content($result, 'categories', $params);
	}

	return $result;
}

function ecwid_product_shortcode($shortcode_attributes) {

	$attributes = shortcode_atts(
		array(
			'id' => null,
			'display' => 'picture title price options addtobag',
			'link' => 'yes'
			),
		$shortcode_attributes
	);

	$id = $attributes['id'];

	if (is_null($id) || !is_numeric($id) || $id <= 0) return;

	if ($attributes['link'] == 'yes' && !ecwid_is_store_page_available()) {
		$attributes['link'] = 'no';
	}

	$display_items = array(
		'picture'  => '<div itemprop="picture"></div>',
		'title'    => '<div class="ecwid-title" itemprop="title"></div>',
		'price'    => '<div itemtype="http://schema.org/Offer" itemscope itemprop="offers">'
					    . '<div class="ecwid-productBrowser-price ecwid-price" itemprop="price"></div>'
				 	    . '</div>',
		'options'  => '<div itemprop="options"></div>',
		'qty' 	   => '<div itemprop="qty"></div>',
		'addtobag' => '<div itemprop="addtobag"></div>'
 	);

	$result = sprintf(
		'<div class="ecwid ecwid-SingleProduct ecwid-Product ecwid-Product-%d" '
		. 'itemscope itemtype="http://schema.org/Product" '
		. 'data-single-product-id="%d">',
		$id, $id
	);

	$items = preg_split('![^0-9^a-z^A-Z^\-^_]!', $attributes['display']);

	if (is_array($items) && count($items) > 0) foreach ($items as $item) {
		if (array_key_exists($item, $display_items)) {
			if ($attributes['link'] == 'yes' && in_array($item, array('title', 'picture'))) {
				$product_link = ecwid_get_store_page_url() . '#!/~/product/id=' . $id;
				$result .= '<a href="' . esc_url($product_link) . '">' . $display_items[$item] . '</a>';
			} else {
				$result .= $display_items[$item];
			}
		}
	}

	$result .= '</div>';

	$result .= ecwid_get_product_browser_url_script();
	$result .= '<script data-cfasync="false" type="text/javascript">xSingleProduct()</script>';

	update_option('ecwid_single_product_used', time());

	return ecwid_wrap_shortcode_content($result, 'product', $shortcode_attributes);
}

function ecwid_shortcode($attributes)
{
	$attributes = shortcode_atts(
		array(
			'widgets' 					  => 'productbrowser',
			'categories_per_row'  => '3',
			'category_view' 		  => 'grid',
			'search_view' 			  => 'grid',
			'grid' 							  => '3,3',
			'list' 							  => '10',
			'table' 						  => '20',
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
			if ($widget == 'search') {
				$widget = 'searchbox';
			}

			$result .= call_user_func_array('ecwid_' . $widget . '_shortcode', array($attributes));
		}
	}

	update_option('ecwid_store_shortcode_used', time());

	return $result;
}

function ecwid_productbrowser_shortcode($shortcode_params) {

		if (current_user_can('manage_options')) {
			Ecwid_Kissmetrics::record('storefrontIsOpened');
		}
	
		$atts = shortcode_atts(
			array(
				'categories_per_row' => false,
				'grid' => false,
				'list' => false,
				'table' => false,
				'search_view' => false,
				'category_view' => false
			), $shortcode_params
		);

		$grid = explode(',', $atts['grid']);
	  if (count($grid) == 2) {
			$atts['grid_rows'] = intval($grid[0]);
			$atts['grid_cols'] = intval($grid[1]);
		} else {
			list($atts['grid_rows'], $atts['grid_cols']) = array(false, false);
		}

    $store_id = get_ecwid_store_id();
    $list_of_views = array('list','grid','table');

    $ecwid_pb_categoriesperrow = $atts['categories_per_row'] ? $atts['categories_per_row'] : get_option('ecwid_pb_categoriesperrow');
    $ecwid_pb_productspercolumn_grid = $atts['grid_rows'] ? $atts['grid_rows'] : get_option('ecwid_pb_productspercolumn_grid');
    $ecwid_pb_productsperrow_grid = $atts['grid_cols'] ? $atts['grid_cols'] : get_option('ecwid_pb_productsperrow_grid');
    $ecwid_pb_productsperpage_list = $atts['list'] ? $atts['list'] : get_option('ecwid_pb_productsperpage_list');
    $ecwid_pb_productsperpage_table = $atts['table'] ? $atts['table'] : get_option('ecwid_pb_productsperpage_table');
    $ecwid_pb_defaultview = $atts['category_view'] ? $atts['category_view'] : get_option('ecwid_pb_defaultview');
    $ecwid_pb_searchview = $atts['search_view'] ? $atts['search_view'] : get_option('ecwid_pb_searchview');

    $ecwid_mobile_catalog_link = get_option('ecwid_mobile_catalog_link');
    $ecwid_default_category_id =
        !empty($shortcode_params) && array_key_exists('default_category_id', $shortcode_params)
        ? $shortcode_params['default_category_id']
        : get_option('ecwid_default_category_id');

    if (empty($ecwid_pb_categoriesperrow)) {
        $ecwid_pb_categoriesperrow = 3;
    }
    if (empty($ecwid_pb_productspercolumn_grid)) {
        $ecwid_pb_productspercolumn_grid = 3;
    }
    if (empty($ecwid_pb_productsperrow_grid)) {
        $ecwid_pb_productsperrow_grid = 3;
    }
    if (empty($ecwid_pb_productsperpage_list)) {
        $ecwid_pb_productsperpage_list = 10;
    }
    if (empty($ecwid_pb_productsperpage_table)) {
        $ecwid_pb_productsperpage_table = 20;
    }

    if (empty($ecwid_pb_defaultview) || !in_array($ecwid_pb_defaultview, $list_of_views)) {
        $ecwid_pb_defaultview = 'grid';
    }
    if (empty($ecwid_pb_searchview) || !in_array($ecwid_pb_searchview, $list_of_views)) {
        $ecwid_pb_searchview = 'list';
    }

	  if (empty($ecwid_default_category_id)) {
        $ecwid_default_category_str = '';
    } else {
        $ecwid_default_category_str = ',"defaultCategoryId='. $ecwid_default_category_id .'"';
    }

    $plain_content = '';

    if (ecwid_can_display_html_catalog()) {
		$params = ecwid_parse_escaped_fragment($_GET['_escaped_fragment_']);
		include_once ECWID_PLUGIN_DIR . 'lib/ecwid_product_api.php';
		include_once ECWID_PLUGIN_DIR . 'lib/ecwid_catalog.php';

		$page_url = get_page_link();

		$catalog = new EcwidCatalog($store_id, $page_url);

		if (isset($params['mode']) && !empty($params['mode'])) {
			if ($params['mode'] == 'product') {
				$plain_content = $catalog->get_product($params['id']);
				$url = ecwid_get_product_url(ecwid_new_product_api()->get_product($params['id']));
			} elseif ($params['mode'] == 'category') {
				$plain_content = $catalog->get_category($params['id']);
				$ecwid_default_category_str = ',"defaultCategoryId=' . $params['id'] . '"';
				$url = ecwid_get_category_url(ecwid_new_product_api()->get_category($params['id']));
			}

		} else {
			$plain_content = $catalog->get_category(intval($ecwid_default_category_id));
			if (empty($plain_content)) {
				$plain_content = $catalog->get_category(0);
			} else {
				$url = ecwid_get_category_url(ecwid_new_product_api()->get_category($params['id']));
			}
		}
		if ($url) {
			$parsed = parse_url($url);
			$plain_content .= '<script data-cfasync="false" type="text/javascript"> if (!document.location.hash) document.location.hash = "'. $parsed['fragment'] . '";</script>';
		}
    }

	$s = '';

	$s = <<<EOT
    <div id="ecwid-store-$store_id">
		{$plain_content}
	</div>
	<script data-cfasync="false" type="text/javascript"> xProductBrowser("categoriesPerRow=$ecwid_pb_categoriesperrow","views=grid($ecwid_pb_productspercolumn_grid,$ecwid_pb_productsperrow_grid) list($ecwid_pb_productsperpage_list) table($ecwid_pb_productsperpage_table)","categoryView=$ecwid_pb_defaultview","searchView=$ecwid_pb_searchview","style="$ecwid_default_category_str, "id=ecwid-store-$store_id");</script>
EOT;
    return ecwid_wrap_shortcode_content($s, 'product-browser', $shortcode_params);
}


function ecwid_parse_escaped_fragment($escaped_fragment) {
	$fragment = urldecode($escaped_fragment);
	$return = array();

	if (preg_match('/^(\/~\/)([a-z]+)\/(.*)$/', $fragment, $matches)) {
		parse_str($matches[3], $return);
		$return['mode'] = $matches[2];
	} elseif (preg_match('!.*/(p|c)/([0-9]*)!', $fragment, $matches)) {
		if (count($matches) == 3 && in_array($matches[1], array('p', 'c'))) {
			$return  = array(
				'mode' => 'p' == $matches[1] ? 'product' : 'category',
				'id' => $matches[2]
			);
		}
	}

	return $return;
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

add_filter('autoptimize_filter_js_exclude','ecwid_override_jsexclude',10,1);
function ecwid_override_jsexclude($exclude)
{
	$code = ecwid_get_search_js_code();
	return $exclude . ", $code";
}

function ecwid_store_activate() {

	if (get_option('ecwid_store_id', null) === null) {
		Ecwid_Kissmetrics::record('firstactivated');
	}
	$my_post = array();
	$content = <<<EOT
[ecwid widgets="productbrowser minicart categories search" grid="3,3" list="10" table="20" default_category_id="0" category_view="grid" search_view="grid" minicart_layout="MiniAttachToProductBrowser" ]
EOT;
	add_option("ecwid_store_page_id", '', '', 'yes');
	add_option("ecwid_store_page_id_auto", '', '', 'yes');

	add_option("ecwid_store_id", ECWID_DEMO_STORE_ID, '', 'yes');
	
	add_option("ecwid_enable_minicart", 'Y', '', 'yes');
	add_option("ecwid_show_categories", '', '', 'yes');
	add_option("ecwid_show_search_box", '', '', 'yes');

	add_option("ecwid_pb_categoriesperrow", '3', '', 'yes');

	add_option("ecwid_pb_productspercolumn_grid", '3', '', 'yes');
	add_option("ecwid_pb_productsperrow_grid", '3', '', 'yes');
	add_option("ecwid_pb_productsperpage_list", '10', '', 'yes');
	add_option("ecwid_pb_productsperpage_table", '20', '', 'yes');

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

	add_option('ecwid_chameleon_primary', '');
	add_option('ecwid_chameleon_background', '');
	add_option('ecwid_chameleon_links', '');

	/* All new options should go to check_version thing */

	$id = get_option("ecwid_store_page_id");	
	$_tmp_page = null;
	if (!empty($id) and ($id > 0)) { 
		$_tmp_page = get_post($id);
	}
	if ($_tmp_page !== null) {
		$my_post = array();
		$my_post['ID'] = $id;
		$my_post['post_status'] = 'publish';
		wp_update_post( $my_post );

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

		if (ecwid_get_theme_identification() == 'responsive') {
			update_post_meta($id, '_wp_page_template', 'full-width-page.php');
			update_option("ecwid_show_search_box", 'Y');
		}
	}

	require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-nav-menus.php';

	Ecwid_Nav_Menus::add_menu_on_activate();

	Ecwid_Message_Manager::enable_message('on_activate');
	Ecwid_Kissmetrics::record('wpPluginActivated');

}

add_action('in_admin_header', 'ecwid_disable_other_notices');
function ecwid_disable_other_notices() {

	$pages = array('toplevel_page_ecwid', 'admin_page_ecwid-help');

	if (!in_array(get_current_screen()->base, $pages)) return;

	global $wp_filter;

	foreach ($wp_filter['admin_notices'] as $priority => $collection) {
		foreach ($collection as $name => $item) {
			if ($name != 'ecwid_show_admin_messages') {
				unset($wp_filter['admin_notices'][$priority][$name]);
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
	Ecwid_Kissmetrics::record('wpPluginDeactivated');
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

function ecwid_abs_intval($value) {
	if (!is_null($value))
    	return abs(intval($value));
	else
		return null;
}

function ecwid_build_menu() {

	$is_newbie = get_ecwid_store_id() == ECWID_DEMO_STORE_ID;

	add_menu_page(
		__('Ecwid shopping cart settings', 'ecwid-shopping-cart'),
		__('Ecwid Store', 'ecwid-shopping-cart'),
		'manage_options',
		'ecwid',
		'ecwid_general_settings_do_page',
		'',
		'2.562347345'
	);

	if ($is_newbie) {
		$title = __('Setup', 'ecwid-shopping-cart');
	} else {
		$title = __('Dashboard', 'ecwid-shopping-cart');
	}
	add_submenu_page(
		'ecwid',
		$title,
		$title,
		'manage_options',
		'ecwid',
		'ecwid_general_settings_do_page'
	);

	global $ecwid_oauth;
	if (!$is_newbie && $ecwid_oauth->has_scope( 'allow_sso' )) {
		add_submenu_page(
			'ecwid',
			__('Sales', 'ecwid-shopping-cart'),
			__('Sales', 'ecwid-shopping-cart'),
			'manage_options',
			'ecwid-admin-orders',
			'ecwid_admin_orders_do_page'
		);


		add_submenu_page(
			'ecwid',
			__('Products', 'ecwid-shopping-cart'),
			__('Products', 'ecwid-shopping-cart'),
			'manage_options',
			'ecwid-admin-products',
			'ecwid_admin_products_do_page'
		);
	}
	if (get_option('ecwid_hide_appearance_menu') != 'Y') {
		add_submenu_page(
			'ecwid',
			__('Appearance settings', 'ecwid-shopping-cart'),
			__('Appearance', 'ecwid-shopping-cart'),
			'manage_options',
			'ecwid-appearance',
			'ecwid_appearance_settings_do_page'
		);
	}

	if (!$is_newbie || (isset($_GET['page']) && $_GET['page'] == 'ecwid-advanced')) {
		add_submenu_page(
			'ecwid',
			__('Advanced settings', 'ecwid-shopping-cart'),
			__('Advanced', 'ecwid-shopping-cart'),
			'manage_options',
			'ecwid-advanced',
			'ecwid_advanced_settings_do_page'
		);
	}

	add_submenu_page('', 'Ecwid debug', '', 'manage_options', 'ecwid-debug', 'ecwid_debug_do_page');
	add_submenu_page('', 'Ecwid get mobile app', '', 'manage_options', 'ecwid-admin-mobile', 'ecwid_admin_mobile_do_page');
	add_submenu_page(
		'ecwid',
		__('Help', 'ecwid-shopping-cart'),
		__('Help', 'ecwid-shopping-cart'),
		'manage_options', 'ecwid-help', 'ecwid_help_do_page'
	);
}

function ecwid_get_categories() {
	$categories = EcwidPlatform::cache_get('all_categories');

	if ( false == $categories ) {
		$callback = 'ecwidcatscallback';
		$result = EcwidPlatform::fetch_url(ecwid_get_categories_js_url($callback));
		$result = $result['data'];

		$prefix_length = strlen($callback . '(');
		$suffix_length = strlen(');');
		$result = substr($result, $prefix_length, strlen($result) - $suffix_length - $prefix_length - 1);

		$categories = json_decode($result);

		$result = EcwidPlatform::cache_set('all_categories', $categories, 60 * 60 * 12);
	}

	return $categories;
}

function ecwid_reset_categories_cache()
{
	if (!current_user_can('manage_options')) {
		return;
	}

	EcwidPlatform::cache_reset('all_categories');
}

function ecwid_register_admin_styles($hook_suffix) {

	wp_enqueue_style('ecwid-admin-css', ECWID_PLUGIN_URL . 'css/admin.css', array(), get_option('ecwid_plugin_version'));
	wp_enqueue_style('ecwid-fonts-css', ECWID_PLUGIN_URL . 'css/fonts.css', array(), get_option('ecwid_plugin_version'));


	if (version_compare(get_bloginfo('version'), '3.8-beta') > 0) {
		wp_enqueue_style('ecwid-admin38-css', ECWID_PLUGIN_URL . 'css/admin.3.8.css', array('ecwid-admin-css'), get_option('ecwid_plugin_version'), 'all');
	}

	if (isset($_GET['page']) && $_GET['page'] == 'ecwid') {

		if (get_option('ecwid_store_id') == ECWID_DEMO_STORE_ID) {
			// Open dashboard for the first time, ecwid store id is set to demo => need landing styles/scripts
			wp_enqueue_script('ecwid-landing-js', ECWID_PLUGIN_URL . 'js/landing.js', array(), get_option('ecwid_plugin_version'));
			wp_localize_script('ecwid-landing-js', 'ecwidParams', array(
				'registerLink' => ecwid_get_register_link()
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

	if ( ($hook_suffix != 'post.php' && $hook_suffix != 'post-new.php') && strpos($hook_suffix, 'ecwid') === false) return;

	wp_enqueue_style('ecwid-settings-css', ECWID_PLUGIN_URL . 'css/settings.css', array(), get_option('ecwid_plugin_version'), 'all');

	if (version_compare(get_bloginfo('version'), '3.8-beta') > 0) {
		wp_enqueue_style('ecwid-settings38-css', ECWID_PLUGIN_URL . 'css/settings.3.8.css', array('ecwid-settings-css'), '', 'all');
	}}

function ecwid_plugin_actions($links) {
	$settings_link = "<a href='admin.php?page=ecwid'>"
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
				register_setting( 'ecwid_options_page', 'ecwid_use_new_horizontal_categories' );
				register_setting( 'ecwid_options_page', 'ecwid_use_new_search' );
				break;
		}

		if (get_option('ecwid_use_chameleon') && !@$_POST['ecwid_use_chameleon']) {
			Ecwid_Kissmetrics::record('chameleonSkinOff');
		} else if (!get_option('ecwid_use_chameleon') && @$_POST['ecwid_use_chameleon']) {
			Ecwid_Kissmetrics::record('chameleonSkinOn');
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

	wp_localize_script('ecwid-admin-js', 'ecwid_l10n', array(
		'dashboard' => __('Dashboard', 'ecwid-shopping-cart'),
		'dashboard_url' => 'admin.php?page=ecwid',
		'products' => __('Products', 'ecwid-shopping-cart'),
		'products_url' => 'admin.php?page=ecwid-admin-products',
		'orders' => __('Orders', 'ecwid-shopping-cart'),
		'orders_url' => 'admin.php?page=ecwid-admin-orders',
		'reset_cats_cache' => __('Refresh categories list', 'ecwid-shopping-cart'),
		'cache_updated' => __('Done', 'ecwid-shopping-cart'),
		'reset_cache_message' => __('The store top-level categories are automatically added to this drop-down menu', 'ecwid-shopping-cart'),
	));
}

function ecwid_get_register_link()
{
	$link = 'https://my.ecwid.com/cp/?source=wporg&partner=wporg%s#register';

	global $current_user;
	get_currentuserinfo();

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
	if (is_array($result) && $result['response']['code'] == 200) {
		$data = json_decode($result['body']);

		update_option('ecwid_store_id', $data->id);

		$api->save_token($data->token);
		update_option('ecwid_oauth_scope', 'read_profile read_catalog allow_sso');

		Ecwid_Kissmetrics::record('Signed Up');
		Ecwid_Kissmetrics::set( 'plan_id', 'WPORG_SKINNY_FREE-1' );
		Ecwid_Kissmetrics::set( 'plan_period', '1' );
		Ecwid_Kissmetrics::set( 'plan_name', 'WPORG_SKINNY_FREE' );
		Ecwid_Kissmetrics::set( 'is_paid', 'false' );
		Ecwid_Kissmetrics::set( 'channelid', 'wporg' );
		Ecwid_Kissmetrics::set( 'registered_via_api', 'true' );
		Ecwid_Kissmetrics::record('accountConnected');

		header( 'HTTP/1.1 200 OK' );

	} else {
		EcwidPlatform::cache_set( 'user_was_redirected_to_ecwid_site_to_create_account', 1, 60*60*24 );
		header( 'HTTP/1.1 ' . $result['response']['code'] . ' ' . $result['response']['message'] );
		Ecwid_Kissmetrics::record( 'Create Store Failed' );
	}
}

function ecwid_general_settings_do_page() {
	
	$store_id = get_option( 'ecwid_store_id' );

	$connection_error = isset( $_GET['connection_error'] );

	//die( var_dump( EcwidPlatform::cache_set( 'user_was_redirected_to_ecwid_site_to_create_account') ) );

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
				Ecwid_Kissmetrics::set( 'existing account on install', true );
				Ecwid_Kissmetrics::record( 'account is found connect page viewed' );
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
					case '1': $reconnect_message = "Message 1"; break;
					case '2': $reconnect_message = "Message 2"; break;
				}

				$scopes = '';

				$connection_error = isset($_GET['connection_error']);

				require_once ECWID_PLUGIN_DIR . 'templates/reconnect.php';
			} else {
				$time = time() - get_option('ecwid_time_correction', 0);
				$page = 'dashboard';
					$iframe_src = sprintf(
						'https://my.ecwid.com/api/v3/%s/sso?token=%s&timestamp=%s&signature=%s&place=%s&inline&lang=%s&min-height=700',
						get_ecwid_store_id(),
						Ecwid_Api_V3::get_token(),
						$time,
						hash( 'sha256', get_ecwid_store_id() . Ecwid_Api_V3::get_token() . $time . Ecwid_Api_V3::CLIENT_SECRET ),
						$page,
						substr( get_bloginfo( 'language' ), 0, 2 )
					);

				$result = EcwidPlatform::fetch_url( $iframe_src );

				if ($result['code'] == 403 && strpos($result['data'], 'Token too old') !== false ) {

					$result = wp_remote_get($iframe_src);

					if (isset($result['headers']['date'])) {
						$time = strtotime($result['headers']['date']);

						$iframe_src = sprintf(
							'https://my.ecwid.com/api/v3/%s/sso?token=%s&timestamp=%s&signature=%s&place=%s&inline&lang=%s&min-height=700',
							get_ecwid_store_id(),
							Ecwid_Api_V3::get_token(),
							$time,
							hash('sha256', get_ecwid_store_id() . Ecwid_Api_V3::get_token() . $time . Ecwid_Api_V3::CLIENT_SECRET),
							$page,
							substr(get_bloginfo('language'), 0, 2)
						);

						$result = EcwidPlatform::fetch_url($iframe_src);

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

function ecwid_admin_do_page( $page ) {

	if (@$_GET['show_timeout'] == '1') {
		require_once ECWID_PLUGIN_DIR . 'templates/admin-timeout.php';
		die();
	}
	global $ecwid_oauth;

	if ($page == 'dashboard') {
		$show_reconnect = true;
		if (isset($_GET['just-created'])) {
			$page = 'dashboard:wizard=ADD_PRODUCTS';
		}
	}

	$time = time() - get_option('ecwid_time_correction', 0);

	$iframe_src = sprintf(
		'https://my.ecwid.com/api/v3/%s/sso?token=%s&timestamp=%s&signature=%s&place=%s&inline&lang=%s&min-height=700',
		get_ecwid_store_id(),
		Ecwid_Api_V3::get_token(),
		$time,
		hash('sha256', get_ecwid_store_id() . Ecwid_Api_V3::get_token() . $time . Ecwid_Api_V3::CLIENT_SECRET),
		$page,
		substr(get_bloginfo('language'), 0, 2)
	);

	$result = EcwidPlatform::fetch_url($iframe_src);

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

	$is_dashboard = $_GET['page'] == 'ecwid';

	if (!$is_dashboard) {
		return;
	}

	global $ecwid_oauth;
	$is_connect = get_ecwid_store_id() != ECWID_DEMO_STORE_ID && !isset($_GET['connection_error']);

	$is_reconnect = isset($_GET['reconnect']) && !isset($_GET['connection_error']);

	if ($is_connect) {
		$ecwid_oauth->update_state( array( 'mode' => 'connect' ) );
	}

	if ($is_reconnect) {
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
		wp_redirect('admin.php?page=ecwid');
		exit;
	}
	global $ecwid_oauth;

	if (ecwid_test_oauth(true)) {

		wp_redirect($ecwid_oauth->get_auth_dialog_url());
	} else if (!isset($_GET['reconnect'])) {
		wp_redirect('admin.php?page=ecwid&oauth=no&connection_error');
	} else {
		wp_redirect('admin.php?page=ecwid&reconnect&connection_error');
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

	$result = walk_through_categories(ecwid_get_categories(), "");

	return $result;
}

function ecwid_advanced_settings_do_page() {
	$categories = ecwid_get_categories_for_selector();

	require_once ECWID_PLUGIN_DIR . 'templates/advanced-settings.php';
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

	if (ecwid_is_api_enabled()) {
		$remote_get_results = wp_remote_get( 'http://app.ecwid.com/api/v1/' . get_ecwid_store_id() . '/profile' );

		$api_v3_profile_results = wp_remote_get( 'https://app.ecwid.com/api/v3/' . get_ecwid_store_id() . '/profile?token=' . Ecwid_Api_V3::get_token() );
	}

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

function ecwid_save_post($post_id)
{
	// If primary or auto store page gets updated
	if ($post_id == get_option('ecwid_store_page_id') || $post_id == get_option('ecwid_store_page_id_auto')) {
		$new_status = get_post_status($post_id);

		// and the update either disables the page or removes product browser
		if (!in_array($new_status, array('publish', 'private')) || !ecwid_page_has_productbrowser($post_id)) {

			// then look for another enabled page that has a product browser in it
			$pages = get_pages(array('post_status' => 'publish,private'));

			foreach ($pages as $page) {
				if (ecwid_page_has_productbrowser($page->ID)) {
					update_option('ecwid_store_page_id_auto', $page->ID);
					return;
				}
			}
		}
	}

	// if there is no current store page and this new page has a product browser
	if (ecwid_page_has_productbrowser($post_id) && !ecwid_get_current_store_page_id()) {
		// then this page becomes a new store page
		update_option('ecwid_store_page_id_auto', $post_id);
	}
}

function ecwid_get_current_store_page_id()
{
	static $page_id = null;

	if (is_null($page_id)) {
		$page_id = false;
		foreach(array('ecwid_store_page_id', 'ecwid_store_page_id_auto') as $option) {
			$id = get_option($option);
			if ($id) {
				$status = get_post_status($id);

				if ($status == 'publish' || $status == 'private') {
					$page_id = $id;
					break;
				}
			}
		}
	}

	return $page_id;
}

function ecwid_get_store_page_url()
{
	static $link = null;

	if (is_null($link)) {
		$link = get_page_link(ecwid_get_current_store_page_id());
	}

	return $link;
}

function ecwid_is_store_page_available()
{
	return ecwid_get_current_store_page_id() != false;
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

	$link = ecwid_get_store_page_url();

	if (is_numeric($entity)) {
		return $link . '#!/' . $type . '/' . $entity;
	} elseif (is_array($entity) && isset($entity['url'])) {
		$link .= substr($entity['url'], strpos($entity['url'], '#'));
	}

	return $link;

}

function ecwid_get_product_browser_url_script()
{
	$str = '';
	if (ecwid_is_store_page_available()) {
		$url = ecwid_get_store_page_url();

		$str = '<script data-cfasync="false" type="text/javascript">var ecwid_ProductBrowserURL = "' . esc_js($url) . '";</script>';
	}

	return $str;

}

function ecwid_send_stats()
{
	$storeid = get_ecwid_store_id();

	if ($storeid == ECWID_DEMO_STORE_ID) {
		$storeid = 805056;
	}

	$last_stats_sent = get_option('ecwid_stats_sent_date');
	if (!$last_stats_sent) {
		add_option('ecwid_stats_sent_date', time());
	} else if ($last_stats_sent + 24*60*60 > time()) {
		return;
	}

	$stats = ecwid_gather_stats();

	$url = 'http://' . APP_ECWID_COM . '/script.js?' . $storeid . '&data_platform=wporg';


	foreach ($stats as $name => $value) {
		$url .= '&data_wporg_' . $name . '=' . urlencode($value);
	}

	$link = '';
	if (ecwid_is_store_page_available()) {
		$link = ecwid_get_store_page_url();
	} else {
		$link = get_bloginfo('url');
	}

	wp_remote_get($url, array('headers' => array('Referer' => $link)));

	update_option('ecwid_stats_sent_date', time());
}

function ecwid_gather_stats()
{
	$usage_version = 1;

	$stats = array();

	$stats['version'] = get_bloginfo('version');
	$stats['theme'] = ecwid_get_theme_identification();

	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

	$vector_contents = array(
		'paid',
		'display_search',
		'horizontal_categories_enabled',
		'minicart_enabled',
		'search_widget',
		'vcategories_widget',
		'minicart_normal_widget',
		'minicart_mini_widget',
		'badge_widget',
		'sso_enabled',
		'default_category',
		'google_xml_sitemaps_used',
		'ecwid_product_advisor_used',
		'ecwid_single_product_used',
		'ecwid_store_shortcode_used',
		'store_link_widget',
		'recently_viewed_widget',
		'avalanche_used',
		'chameleon_used',
		'http_post_fails',
		'ecwid_use_new_horizontal_categories',
		'is_wp_newbie',
		'ecwid_remote_get_fails',
		'has_woocommerce',
		'store_root_cats_widget'
	);

	$usage_stats = ecwid_gather_usage_stats();
	$stats['usage'] = '';

	$usage = '';
	foreach ($vector_contents as $index => $item) {
		$usage[$index] = is_string($usage_stats[$item]) ? $usage_stats[$item] : (int)$usage_stats[$item];
	}
	$stats['usage'] = $usage_version . '_' . implode('', $usage);

	$stats['wp_install_date'] = $usage_stats['wp_install_date'];
	$stats['plugin_install_date'] = $usage_stats['wp_install_date'];

	$checked = get_option('ecwid_checked_for_remote_connection_errors', null);
	if (is_null($checked) || $usage_stats['ecwid_remote_get_fails'] || $usage_stats['http_post_fails']) {
		$results = ecwid_check_for_remote_connection_errors();
		if (!empty($results)) {
			$stats = array_merge($stats, $results);
		}
		update_option('ecwid_checked_for_remote_connection_errors', empty($results));
	}

	return $stats;
}

function ecwid_gather_usage_stats()
{
	$usage_stats = array();
	$usage_stats['paid'] = ecwid_is_paid_account();
	$usage_stats['display_search'] = (bool) get_option('ecwid_show_search_box');
	$usage_stats['horizontal_categories_enabled'] = (bool) get_option('ecwid_show_categories');
	$usage_stats['minicart_enabled'] = (bool) get_option('ecwid_enable_minicart');
	$usage_stats['search_widget'] = (bool) is_active_widget(false, false, 'ecwidsearch');
	$usage_stats['vcategories_widget'] = (bool) is_active_widget(false, false, 'ecwidvcategories');
	$usage_stats['minicart_normal_widget'] = (bool) is_active_widget(false, false, 'ecwidminicart');
	$usage_stats['minicart_mini_widget'] = (bool) is_active_widget(false, false, 'ecwidminicart_miniview');
	$usage_stats['badge_widget'] = (bool) is_active_widget(false, false, 'ecwidbadge');
	$usage_stats['sso_enabled'] = (bool) get_option('ecwid_sso_secret_key');
	$usage_stats['default_category'] = (bool) get_option('ecwid_default_category_id');
	$usage_stats['google_xml_sitemaps_used'] = (bool) is_plugin_active('google-sitemap-generator/sitemap.php');
	$usage_stats['ecwid_product_advisor_used'] = (bool) is_plugin_active('ecwid-useful-tools/ecwid-product-advisor.php');
	$usage_stats['ecwid_single_product_used'] = (bool) (get_option('ecwid_single_product_used') + 60*60*24*14 > time());
	$usage_stats['ecwid_store_shortcode_used'] = (bool) (get_option('ecwid_store_shortcode_used') + 60*60*24*14 > time());
	$usage_stats['store_link_widget'] = (bool) is_active_widget(false, false, 'ecwidstorelink');
	$usage_stats['recently_viewed_widget'] = (bool) is_active_widget(false, false, 'ecwidrecentlyviewed');
	$usage_stats['avalanche_used'] = (bool) is_plugin_active('ecwid-widgets-avalanche/ecwid_widgets_avalanche.php');
	$usage_stats['chameleon_used'] = (bool)get_option('ecwid_use_chameleon');
	$usage_stats['http_post_fails'] = get_option('ecwid_last_oauth_fail_time') > 0;
	$usage_stats['ecwid_use_new_horizontal_categories'] = (bool) get_option('ecwid_use_new_horizontal_categories');

	$wp_date = get_option('ecwid_wp_install_date');
	if (!$wp_date) {
		global $wpdb;
		$oldest_user = strtotime($wpdb->get_var("SELECT min(`user_registered`) FROM {$wpdb->users}"));
		$oldest_post = strtotime($wpdb->get_var("SELECT min(`post_date`) FROM {$wpdb->posts}"));
		$wpconfig_create = filectime(ABSPATH . '/wp-config.php');

		$wp_date = min($oldest_user, $oldest_post, $wpconfig_create);
		update_option('ecwid_wp_install_date', $wp_date);
	}

	$ecwid_date = get_option('ecwid_installation_date');

	$usage_stats['wp_install_date'] = $wp_date;
	$usage_stats['plugin_install_date'] = $ecwid_date;

	$usage_stats['is_wp_newbie'] = (bool) (($ecwid_date - $wp_date)  / 60 / 60 / 24 <= 30);

	$usage_stats['ecwid_remote_get_fails'] = (bool) get_option('ecwid_remote_get_fails');

	$usage_stats['has_woocommerce'] = ecwid_get_woocommerce_status();
	$usage_stats['store_root_cats_widget'] = (bool) is_active_widget(false, false, 'ecwidvcategorieslist');

	return $usage_stats;
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

function ecwid_sso() {
    $key = get_option('ecwid_sso_secret_key');
    if (empty($key)) {
        return "";
    }

    global $current_user;
    get_currentuserinfo();

		$signin_url = wp_login_url(ecwid_get_store_page_url());
		$signout_url = wp_logout_url(ecwid_get_store_page_url());
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

	/*
	$signin_url = wp_login_url("URL_TO_REDIRECT");
	$signout_url = wp_logout_url('URL_TO_REDIRECT');
	$sign_in_out_urls = <<<JS
window.EcwidSignInUrl = '$signin_url';
window.EcwidSignOutUrl = '$signout_url';
window.Ecwid.OnAPILoaded.add(function() {

    window.Ecwid.setSignInUrls({
        signInUrl: '$signin_url',
        signOutUrl: '$signout_url'
    });


		window.Ecwid.setSignInProvider({
			addSignInLinkToPB: function() { return true; },
			signIn: function() {
				location.href = window.EcwidSignInUrl.replace('URL_TO_REDIRECT', encodeURIComponent(location.href));
			},
			signOut: function() {
				location.href = window.EcwidSignOutUrl.replace('URL_TO_REDIRECT', encodeURIComponent(location.href));
			},
			canSignOut: true,
			canSignIn: true
		});

});


JS;
*/
	$ecwid_sso_profile = '';
    if ($current_user->ID) {
			$meta = get_user_meta($current_user->ID);


      $user_data = array(
            'appId' => "wp_" . get_ecwid_store_id(),
            'userId' => "{$current_user->ID}",
            'profile' => array(
            'email' => $current_user->user_email,
            'billingPerson' => array(
        	  'name' => $meta['first_name'][0] . ' ' . $meta['last_name'][0]
					)
        )
      );
			$user_data = base64_encode(json_encode($user_data));
			$time = time();
			$hmac = ecwid_hmacsha1("$user_data $time", $key);

			$ecwid_sso_profile ="$user_data $hmac $time";
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

function ecwid_is_paid_account()
{
	return ecwid_is_api_enabled() && get_ecwid_store_id() != ECWID_DEMO_STORE_ID;
}

function ecwid_is_api_enabled()
{
    $ecwid_is_api_enabled = get_option('ecwid_is_api_enabled');
    $ecwid_api_check_time = get_option('ecwid_api_check_time');
    $now = time();

    if ( $now > ($ecwid_api_check_time + 60 * 60 * 3) && get_ecwid_store_id() != ECWID_DEMO_STORE_ID ) {
        // check whether API is available once in 3 hours
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

function ecwid_get_categories_js_url($callback) {
	return 'https://my.ecwid.com/categories.js?ownerid=' . get_ecwid_store_id() . '&callback=' . $callback;
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
