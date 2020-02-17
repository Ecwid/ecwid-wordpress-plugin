<?php

class Ecwid_Message_Manager
{
	protected $messages = array();
	
	const MSG_WOO_IMPORT_ONBOARDING = 'connected_woo';
	
	protected function __construct()
	{
		$this->init_messages();
		
		add_action( 'ecwid_connected_via_legacy_page', array( $this, 'on_connected_via_legacy_page' ) );
	}

	public static function show_messages()
	{
		$mm = self::get_instance();

		foreach ($mm->messages as $name => $message) {
			if ($mm->need_to_show_message($name)) {
				$mm->show_message($name);
			}
		}
	}

	public static function get_oauth_message($wp_remote_post_error = '')
	{
		if (!$wp_remote_post_error) {
			$message = sprintf(
				__( <<<TXT
Sorry, there is a problem. This page is supposed to display your store control panel. But this WordPress site doesn't seem to be able to connect to the Ecwid server, that's why there is no dashboard. This is caused by your server misconfiguration and can be fixed by your hosting provider.
<br /><br />
Here is a more techy description of the problem, please send it to your hosting provider: "The WordPress function wp_remote_post() failed to connect a remote server because of some error. Seems like HTTP requests to remote servers are disabled on this server. Specifically, the requests to app.ecwid.com and my.ecwid.com are blocked.".
<br /><br />
Please also feel free to contact us at <a %s>wordpress@ecwid.com</a> and we will help you handle it with your hosting.
<br /><br />
Meanwhile, to manage your store, you can use the Ecwid Web Control Panel at <a %s>my.ecwid.com</a>. Your store front is working fine as well and you can check it here: <a %s>%s</a>.
TXT

		),
				'href="mailto:wordpress@ecwid.com"',
				'target="_blank" href="http://my.ecwid.com"',
				'href="' . Ecwid_Store_Page::get_store_url() . '" target="_blank"',
				Ecwid_Store_Page::get_store_url()
			);
		} else {
			$message = sprintf(
				__('Sorry, there is a problem. This page is supposed to display your store Control Panel. However, this Wordpress site doesn\'t seem to be able to connect to the Ecwid server to show your store dashboard here. This is likely caused by your server misconfiguration and can be fixed by your hosting provider. Here is a more techy description of the problem, which you can send to your hosting provider: "The Wordpress function wp_remote_post() failed to connect a remote server because of some error: "%s". Seems like HTTP POST requests are disabled on this server". <br /><br />Please feel free to contact us at <a %s>wordpress@ecwid.com</a> and we will help you contact your hosting and ask them to fix the issue. <br /><br /> Meanwhile, to manage your store, you can use the Ecwid Web Control Panel at <a %s>my.ecwid.com</a>. Your store front is working fine as well and you can check it here: <a %s>%s</a>.'),
				$wp_remote_post_error,
				'href="mailto:wordpress@ecwid.com"',
				'target="_blank" href="http://my.ecwid.com"',
				'href="' . Ecwid_Store_Page::get_store_url() . '" target="_blank"',
				Ecwid_Store_Page::get_store_url()
			);
		}

		return $message;
	}

	public static function show_message($name, $params = array())
	{
		$mm = self::get_instance();

		$mm->need_to_show_message($name);

		if (!isset($mm->messages[$name]) && empty($params)) {
			trigger_error('Ecwid plugin error: unknown message ' . $name);
			return;
		}

		$params = $mm->get_message_params($name, $params);

		$type = $params['type'];

		$title = $params['title'];
		$message = $params['message'];

		$primary_button = isset($params['primary_title']);
		if ($primary_button) {
			$primary_title = $params['primary_title'];
			$primary_url =   $params['primary_url'];
			$primary_blank = @$params['primary_blank'];
		}
		$secondary_button = isset($params['secondary_title']);
		if ($secondary_button) {
			$secondary_title = $params['secondary_title'];
			$secondary_url   = @$params['secondary_url'];
			$secondary_blank = @$params['secondary_blank'];
			$secondary_hide = @$params['secondary_hide'];
		}

		$do_not_show_again = true == $params['hideable'];


		//TO-DO: delete this block
		$debug = EcwidPlatform::cache_get( 'temporary_debug', null );
		if( $name == 'api_failed_other' && is_null($debug) ) {

			$api_url = 'https://' . Ecwid_Config::get_api_domain() . '/api/v3/';
			$api_profile_url = $api_url . get_ecwid_store_id() . '/profile?token=' . Ecwid_Api_V3::get_token();

			$api_v3_profile_results = wp_remote_get(
				$api_profile_url,
				array('timeout' => 5)
			);

			if( is_wp_error($api_v3_profile_results) ) {
				$error = $api_v3_profile_results->get_error_message();

				preg_match( '/cURL error ([0-9]+):/i', $error, $m );

				$script_js = sprintf(
					'<script src="https://%s/script.js?%s&data_platform=wporg&data_wp_error=%s"></script>',
					Ecwid_Config::get_scriptjs_domain(),
					get_ecwid_store_id(),
					$m[1]
				);

				$message .= $script_js;

				EcwidPlatform::cache_set( 'temporary_debug', 1, WEEK_IN_SECONDS );
			}
		}

		include ECWID_PLUGIN_DIR . 'templates/admin-message.php';
	}

	public static function disable_message($name)
	{
		$messages = get_option('ecwid_disabled_messages');

		if( !is_array($messages) ) {
			$messages = array();
		}

		$messages[$name] = true;

		update_option('ecwid_disabled_messages', $messages);
	}

	public static function enable_message($name)
	{
		$messages = get_option('ecwid_disabled_messages');
		if (isset($messages['name'])) {
			unset($messages['name']);
		}

		update_option('ecwid_disabled_messages', $messages);
	}

	public static function reset_hidden_messages()
	{
		$hidden_messages = array();

		$messages = self::get_default_messages();
		foreach ($messages as $name => $message) {
			if (isset($message['default']) && $message['default'] == 'hidden') {
				$hidden_messages[$name] = true;
			}
		}

		update_option('ecwid_disabled_messages', array());
	}

	protected static function get_instance()
	{
		static $instance = null;

		if (is_null($instance)) {
			$instance = new Ecwid_Message_Manager();
		}

		return $instance;
	}

	protected function init_messages()
	{
		$this->messages = $this->get_default_messages();

		$hidden_messages = get_option('ecwid_disabled_messages');

		if ( !empty( $hidden_messages ) && is_array( $hidden_messages ) ) {
			foreach ($hidden_messages as $name => $message) {
				unset ($this->messages[$name]);
			}
		}
	}

	protected function get_message_params($name, $params)
	{
		if (is_array($name)) {
			$params = $name;
			$name = '';
		}

		if (isset($this->messages[$name])) {
			$params = array_merge(
				array('type' => 'info', 'title' => '', 'message' => '', 'hideable' => false),
				$this->messages[$name],
				$params
			);
		}

		if ($name == 'on_storeid_set') {
			$params['primary_url'] = Ecwid_Store_Page::get_store_url();
			$params['secondary_url'] = 'post.php?post=' . Ecwid_Store_Page::get_current_store_page_id() . '&action=edit&show-ecwid=true';
		}

		if ($name == 'on_appearance_widgets') {

			if (isset($_GET['from-ec-store']) && $_GET['from-ec-store'] == 'appearance') {
				$admin_page = Ecwid_Admin::get_dashboard_url() . '-appearance';
			} elseif (isset($_GET['from-ec-store']) && $_GET['from-ec-store'] == 'new') {
				$admin_page = 'post-new.php?post_type=page';
			} elseif (isset($_GET['from-ec-store']) && is_numeric($_GET['from-ec-store'])) {
				$admin_page = 'post.php?post=' . $_GET['from-ec-store'] . '&action=edit';
			}

			$params['secondary_url'] = $admin_page;
		}
		$types_map = array(
			'info' => 'updated',
			'warning' => 'update-nag',
			'error' => 'error'
		);
		$params['type'] = $types_map[$params['type']];

		return $params;
	}

	protected static function get_default_messages()
	{
		$messages = array(
			'on_activate' => array(
				'title' => sprintf( __( 'Greetings! Your %s plugin is now active.', 'ecwid-shopping-cart'), Ecwid_Config::get_brand() ),
				'message' => __('Take a few simple steps to complete store setup', 'ecwid-shopping-cart'),
				'primary_title' => __( 'Set up your store', 'ecwid-shopping-cart'),
				'primary_url' => 'admin.php?page=' . Ecwid_Admin::ADMIN_SLUG,
				'hideable'  => true,
				'default'  => 'disabled'
			),

			'on_no_storeid_on_setup_pages' => array(
				'type' => 'warning',
				'title' => __('Your store is almost ready!', 'ecwid-shopping-cart' ),
				'message' => __('Complete setup and start selling', 'ecwid-shopping-cart' ),
				'primary_title' => __('Complete Setup', 'ecwid-shopping-cart' ),
				'primary_url'   => Ecwid_Admin::get_dashboard_url(),
				'hideable'  => true
			),

			'on_appearance_widgets' => array(
				'message' => sprintf( __( 'To add extra functions to your store, drag and drop %s store elements on your site. When you\'re done, you can get back to modifying your settings.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ),
				'secondary_title' => __('Back to Store Settings', 'ecwid-shopping-cart'),
				'secondary_url'   => Ecwid_Admin::get_dashboard_url() . '-appearance',
				'hideable'  => true
			),

			'please_vote' => array(
				'message' => sprintf(
					__('Do you like your %s online store? We\'d appreciate it if you add your review and vote for the plugin on WordPress site.', 'ecwid-shopping-cart'),
					Ecwid_Config::get_brand(),
					'target="_blank" href="http://wordpress.org/support/view/plugin-reviews/ecwid-shopping-cart"'
				),
				'primary_title' => sprintf( __( 'Rate %s at WordPress.org', 'ecwid-shopping-cart'), Ecwid_Config::get_brand() ),
				'primary_url' => 'http://wordpress.org/support/view/plugin-reviews/ecwid-shopping-cart',
				'hideable' => true
			),

			'no_oauth' => array(

				'message' => Ecwid_Message_Manager::get_oauth_message(),
				'hideable' => false,
				'type' => 'error'
			),
			
			'no_token' => array(
				'title' => sprintf( __( 'Action required: please connect your %s account', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ),
				'message' => sprintf( __( 'Your storefront (product listing and checkout) is working fine, but the advanced store functions like SEO and sidebar widgets are disabled. To enable them and make sure your store works properly, please press the button below to connect your %s account. This will take less than a minute — you will only be asked to log in to your account and allow this site to get your store data.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ),
				'type' => 'error',
				'primary_title' => __( 'Connect', 'ecwid-shopping-cart' ),
				'primary_url' => admin_url( 'admin-post.php?action=ec_connect&reconnect' ),
				'hideable' => true
			),
			
			'api_failed_tls' => array(
				'title' => __( 
					'Warning: some of your online store features are disabled. Please contact your hosting provider to resolve.', 
					'ecwid-shopping-cart' 
				),
				'message' => sprintf( 
					__( 
						<<<HTML
<b>What happened:</b> This WordPress site doesn't seem to be able to connect to the %1\$s servers. Your store is working and your products can be purchased from your site, but some features are disabled, including SEO, product sidebar widgets, advanced site menu and store navigation. The %1\$s plugin tries to reach the %1\$s APIs at our servers and cannot do that because of your server misconfiguration.
<br /><br />
<b>How to fix:</b> Your server seems to be using outdated software (TLS v1.0) to communicate with the %1\$s APIs. The reason can also be a deprecated version of the CURL module. This can be fixed by your hosting provider by updating your server software to the latest version. Please send this message to your hosting provider and ask them to check it for you. If this doesn't help, please contact us at <a target="_blank" href="%2\$s">%2\$s</a>.
HTML
						, 'ecwid-shopping-cart' 
					),
					Ecwid_Config::get_brand(),
					Ecwid_Config::get_contact_us_url()
				),
				'type' => 'warning',
				'hideable' => false
			),
			
			'api_failed_other' => array(
				'title' => __( 
					'Warning: some of your online store features are disabled. Please contact your hosting provider to resolve.', 
					'ecwid-shopping-cart' 
				),
				'message' => sprintf( 
					__( 
						<<<HTML
<b>What happened:</b> This WordPress site doesn't seem to be able to connect to the %1\$s servers. Your store is working and your products can be purchased from your site, but some features are disabled, including SEO, product sidebar widgets, advanced site menu and store navigation. The %1\$s plugin tries to reach the %1\$s APIs at our servers and cannot do that as your server blocks those requests for some reason.
<br /><br />
<b>How to fix:</b> Refresh this page after a few minutes. If this message does not disappear, then the problem is likely caused by your server misconfiguration and can be fixed by your hosting provider. In particular, the CURL module can be disabled in your PHP config or a firewall might block requests to our servers. Please send this message to your hosting provider and ask them to check it for you. If this doesn't help, please contact us at <a target="_blank" href="%2\$s">%2\$s</a>.
HTML
					, 'ecwid-shopping-cart' ),
					Ecwid_Config::get_brand(),
					Ecwid_Config::get_contact_us_url()
				),
				'type' => 'warning',
				'hideable' => false
			)
		);
		
		if ( class_exists( 'Ecwid_Import_Page' ) ) {
			$messages[self::MSG_WOO_IMPORT_ONBOARDING] = array(
				'title' => sprintf( __( 'Need help importing your products from WooCommerce to %s?', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ),
				'message' => sprintf( __( 'We noticed you have WooCommerce installed. If you want to easily copy your WooCommerce products to %s, this tool will help you.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ),
				'hideable' => false,
				'primary_title' => __( 'Import my products from WooCommerce', 'ecwid-shopping-cart' ),
				'primary_url' => Ecwid_Import_Page::get_woo_page_url_from_message(),
				'secondary_title' => __( 'No Thanks', 'ecwid-shopping-cart' ),
				'secondary_hide' => true
			);
		}
		
		return $messages;
	}

	protected function need_to_show_message($name)
	{
		if ( !current_user_can( 'manage_options' ) ) {
			return false;
		}

		$admin_page = '';
		if (function_exists('get_current_screen')) {
			$screen = get_current_screen();
			$admin_page = $screen->base;
		}

		$is_ecwid_menu = $admin_page == 'toplevel_page_' . Ecwid_Admin::ADMIN_SLUG;
		if ($is_ecwid_menu && isset($_GET['reconnect'])) {
			return false;
		}
		
		switch ($name) {
			case 'on_activate':
				return !$this->should_display_on_no_storeid_on_setup_pages()
					&& $admin_page != 'toplevel_page_ec-store'
					&& ecwid_is_demo_store()
					&& $admin_page != 'ecwid_page_' . Ecwid_Admin_Storefront_Page::ADMIN_SLUG;

			case 'on_storeid_set':
				return !ecwid_is_demo_store() && @$_GET['settings-updated'] == 'true' && $admin_page == 'toplevel_page_ec-store';

			case 'on_no_storeid_on_setup_pages':
				return $this->should_display_on_no_storeid_on_setup_pages();

			case 'on_appearance_widgets':
				return isset($_GET['from-ec-store']) && $_GET['from-ec-store'] != 'true' && $admin_page == 'widgets';

			case 'no_token':
				$no_token = Ecwid_Api_V3::get_token() == false;
				$is_not_demo = !ecwid_is_demo_store();
				return 
					$no_token 
					&& $is_not_demo 
					&& !$is_ecwid_menu 
					&& in_array( 
						Ecwid_Api_V3::get_api_status(), 
						array( 
							Ecwid_Api_V3::API_STATUS_OK, 
							Ecwid_Api_V3::API_STATUS_ERROR_TOKEN
						) 
					);
				
			case self::MSG_WOO_IMPORT_ONBOARDING:
				if ( !class_exists( 'Ecwid_Importer' ) ) {
					require_once ECWID_PLUGIN_DIR . 'includes/importer/class-ecwid-importer.php';
				}

				return 
					is_plugin_active( 'woocommerce/woocommerce.php' ) 
					&& strpos( $admin_page, Ecwid_Import::PAGE_SLUG ) === false 
					&& $_GET['import'] != 'ec-store-import'
					&& !$this->need_to_show_message( 'on_activate' ) 
					&& Ecwid_Api_V3::is_available()
					&& !ecwid_is_demo_store()
					&& !get_option( Ecwid_Importer::OPTION_WOO_CATALOG_IMPORTED, false )
					&& wp_count_posts( 'product' )->publish > 0
					&& ecwid_is_recent_installation();
				
			case 'please_vote':
				
				if ( Ecwid_Config::is_wl() ) return false;

				if ( strpos( $admin_page, Ecwid_Admin::ADMIN_SLUG ) === false ) {
					return false;
				}
				
				$install_date = get_option('ecwid_installation_date');

				$result = false;
				if (!$install_date) {
					add_option('ecwid_installation_date', time());
				} else {
					$result = ecwid_is_paid_account() && $install_date + 60*60*24*30 < time();
				}

				foreach ($this->messages as $_name => $message) {
					if ($_name != $name && $this->need_to_show_message($_name)) {
						return false;
					}
				}

				return $result;
				
			case 'api_failed_tls':
				return 
					!ecwid_is_demo_store()
					&& get_current_screen()->parent_base == Ecwid_Admin::ADMIN_SLUG
					&& Ecwid_Api_V3::get_api_status() == Ecwid_Api_V3::API_STATUS_ERROR_TLS
					&& time() - get_option( 'ecwid_connected_via_legacy_page_time' ) > 15 * MINUTE_IN_SECONDS;
				
			case 'api_failed_other':
				return
					!ecwid_is_demo_store()
					&& get_current_screen()->parent_base == Ecwid_Admin::ADMIN_SLUG
					&& Ecwid_Api_V3::get_api_status() == Ecwid_Api_V3::API_STATUS_ERROR_OTHER
					&& time() - get_option( 'ecwid_connected_via_legacy_page_time' ) > 15 * MINUTE_IN_SECONDS;
		}
	}

	protected function should_display_on_no_storeid_on_setup_pages() {
		$screen = get_current_screen();
		
		$admin_page = $screen->base;
		
		$is_newbie = ecwid_is_demo_store();

		$is_ecwid_settings = in_array($admin_page, array('ecwid-store_page_ecwid-advanced', 'ecwid-store_page_ecwid-appearance'));
		$is_store_page = $admin_page == 'post' && isset($_GET['post']) && $_GET['post'] == Ecwid_Store_Page::get_current_store_page_id();

		return $is_newbie && ($is_ecwid_settings || $is_store_page);		
	}
}