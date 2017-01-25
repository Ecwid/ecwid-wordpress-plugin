<?php

class Ecwid_Message_Manager
{
	protected $messages = array();

	protected function __construct()
	{
		$this->init_messages();
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
				'href="' . ecwid_get_store_page_url() . '" target="_blank"',
				ecwid_get_store_page_url()
			);
		} else {
			$message = sprintf(
				__('Sorry, there is a problem. This page is supposed to display your store Control Panel. However, this Wordpress site doesn\'t seem to be able to connect to the Ecwid server to show your store dashboard here. This is likely caused by your server misconfiguration and can be fixed by your hosting provider. Here is a more techy description of the problem, which you can send to your hosting provider: "The Wordpress function wp_remote_post() failed to connect a remote server because of some error: "%s". Seems like HTTP POST requests are disabled on this server". <br /><br />Please feel free to contact us at <a %s>wordpress@ecwid.com</a> and we will help you contact your hosting and ask them to fix the issue. <br /><br /> Meanwhile, to manage your store, you can use the Ecwid Web Control Panel at <a %s>my.ecwid.com</a>. Your store front is working fine as well and you can check it here: <a %s>%s</a>.'),
				$wp_remote_post_error,
				'href="mailto:wordpress@ecwid.com"',
				'target="_blank" href="http://my.ecwid.com"',
				'href="' . ecwid_get_store_page_url() . '" target="_blank"',
				ecwid_get_store_page_url()
			);
		}

		return $message;
	}

	public static function get_upgrade_cats_message() {

		$main_message = __( 'Updated %s widgets are available for your %s store. They are more mobile friendly and look better. Please enable them on the plugin settings page and check how they work in your store. The new widgets will be enabled automatically for all users in one of the upcoming plugin versions.', 'ecwid-shopping-cart' );

		$widgets = _x( 'Categories', 'upgrade widgets message', 'ecwid-shopping-cart' );

		return sprintf($main_message, Ecwid_WL::get_brand(), $widgets);
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
			$secondary_url   = $params['secondary_url'];
			$secondary_blank = @$params['secondary_blank'];
		}

		$do_not_show_again = true == $params['hideable'];

		include ECWID_PLUGIN_DIR . 'templates/admin-message.php';
	}

	public static function disable_message($name)
	{
		$messages = get_option('ecwid_disabled_messages');
		$messages[$name] = true;

		update_option('ecwid_disabled_messages', $messages);
	}

	public static function enable_message($name)
	{
		$messages = get_option('ecwid_disabled_messages');
		if (isset($messages['name']))
			unset($messages['name']);

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

		if ( !empty( $hidden_messages ) ) {
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
			$params['primary_url'] = ecwid_get_store_page_url();
			$params['secondary_url'] = 'post.php?post=' . ecwid_get_current_store_page_id() . '&action=edit&show-ecwid=true';
		}

		if ($name == 'on_appearance_widgets') {

			if (isset($_GET['from-ecwid']) && $_GET['from-ecwid'] == 'appearance') {
				$admin_page = 'admin.php?page=ecwid-appearance';
			} elseif (isset($_GET['from-ecwid']) && $_GET['from-ecwid'] == 'new') {
				$admin_page = 'post-new.php?post_type=page';
			} elseif (isset($_GET['from-ecwid']) && is_numeric($_GET['from-ecwid'])) {
				$admin_page = 'post.php?post=' . $_GET['from-ecwid'] . '&action=edit';
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
		return array(
			'on_activate' => array(
				'title' => sprintf( __( 'Greetings! Your %s plugin is now active.', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ),
				'message' => __('Take a few simple steps to complete store setup', 'ecwid-shopping-cart'),
				'primary_title' => sprintf( __( 'Set up %s Store', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ),
				'primary_url' => 'admin.php?page=ecwid',
				'hideable'  => true,
				'default'  => 'disabled'
			),

			'on_no_storeid_on_setup_pages' => array(
				'type' => 'warning',
				'title' => __('Your store is almost ready!', 'ecwid-shopping-cart' ),
				'message' => __('Complete setup and start selling', 'ecwid-shopping-cart' ),
				'primary_title' => __('Complete Setup', 'ecwid-shopping-cart' ),
				'primary_url'   => 'admin.php?page=ecwid',
				'hideable'  => true
			),

			'on_appearance_widgets' => array(
				'message' => sprintf( __( 'To add extra functions to your store, drag and drop %s store elements on your site. When you\'re done, you can get back to modifying your settings.', 'ecwid-shopping-cart' ), Ecwid_WL::get_brand() ),
				'secondary_title' => __('Back to Store Settings', 'ecwid-shopping-cart'),
				'secondary_url'   => 'admin.php?page=ecwid-appearance',
				'hideable'  => true
			),

			'please_vote' => array(
				'message' => sprintf(
					__('Do you like your %s online store? We\'d appreciate it if you add your review and vote for the plugin on WordPress site.', 'ecwid-shopping-cart'),
					Ecwid_WL::get_brand(),
					'target="_blank" href="http://wordpress.org/support/view/plugin-reviews/ecwid-shopping-cart"'
				),
				'primary_title' => sprintf( __( 'Rate %s at WordPress.org', 'ecwid-shopping-cart'), Ecwid_WL::get_brand() ),
				'primary_url' => 'http://wordpress.org/support/view/plugin-reviews/ecwid-shopping-cart',
				'hideable' => true
			),

			'no_oauth' => array(

				'message' => Ecwid_Message_Manager::get_oauth_message(),
				'hideable' => false,
				'type' => 'error'
			),

			'install_ecwid_theme' => array(
				'title' => __( 'Looking for a Wordpress theme for your store?', 'ecwid-shopping-cart' ),
				'message' => __ ( 'We created the "Ecwid Ecommerce" theme to make Ecwid stores like yours look great in WordPress. Give it a try – the Ecwid theme is free.', 'ecwid-shopping-cart' ),
				'primary_title' => __( 'Install the Ecwid theme', 'ecwid-shopping-cart' ),
				'primary_url' => 'admin.php?page=ecwid-install-theme',
				'hideable' => true
			),

			'upgrade_cats' => array(
				'message' => Ecwid_Message_Manager::get_upgrade_cats_message(),
				'hideable' => true,
				'primary_title' => sprintf( __( 'Open %s store settings', 'ecwid-shopping-cart' ), Ecwid_WL::get_brand() ),
				'primary_url' => 'admin.php?page=ecwid-advanced'
			)
		);
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

		if ($admin_page == 'toplevel_page_ecwid' && isset($_GET['reconnect'])) {
			return false;
		}

		switch ($name) {
			case 'on_activate':
				return $admin_page != 'toplevel_page_ecwid' && get_ecwid_store_id() == ECWID_DEMO_STORE_ID;

			case 'on_storeid_set':
				return get_ecwid_store_id() != ECWID_DEMO_STORE_ID && @$_GET['settings-updated'] == 'true' && $admin_page == 'toplevel_page_ecwid';

			case 'on_no_storeid_on_setup_pages':
				$is_newbie = get_ecwid_store_id() == ECWID_DEMO_STORE_ID;

				$is_ecwid_settings = in_array($admin_page, array('ecwid-store_page_ecwid-advanced', 'ecwid-store_page_ecwid-appearance'));
				$is_store_page = $admin_page == 'post' && isset($_GET['post']) && $_GET['post'] == ecwid_get_current_store_page_id();

				return $is_newbie && ($is_ecwid_settings || $is_store_page);

			case 'on_appearance_widgets':
				return isset($_GET['from-ecwid']) && $_GET['from-ecwid'] != 'true' && $admin_page == 'widgets';

			case 'please_vote':
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

			case "upgrade_cats":
				return ecwid_is_old_cats_widget_used();

			case "install_ecwid_theme":
				return false;
				$install_date = ecwid_get_wp_install_date();
				$theme = ecwid_get_theme_identification();

				$default_themes = array(
					'twentyten',
					'twentyeleven',
					'twentytwelve',
					'twentythirteen',
					'twentyfourteen',
					'twentyfifteen',
					'twentysixteen'
				);

				$is_default_theme = in_array($theme, $default_themes);
				$is_newbie = (time() - $install_date) < 60*60*24*31;
				$is_ecwid_connected = get_ecwid_store_id() != ECWID_DEMO_STORE_ID;
				$is_installing = get_current_screen()->base == 'admin_page_ecwid-install-theme';
				$theme_object = wp_get_theme('ecwid-ecommerce');
				$err = $theme_object->errors();
				$is_theme_installed = $theme_object;
				if ($is_theme_installed) {
					if ($err && $err->get_error_code() == 'theme_not_found') {
						$is_theme_installed = false;
					}
				}

				if ( $is_default_theme && $is_newbie && $is_ecwid_connected && !$is_installing && !$is_theme_installed ) {
					return true;
				}

		}
	}

}