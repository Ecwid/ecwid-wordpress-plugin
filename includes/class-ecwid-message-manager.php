<?php

class Ecwid_Message_Manager
{
	protected $messages = array();

	protected function Ecwid_Message_Manager()
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

		include ECWID_PLUGIN_DIR . '/templates/admin-message.php';
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
				'title' => __('Greetings! Your Ecwid store is now active.', 'ecwid-shopping-cart'),
				'message' => __('Take a few simple steps to complete store setup', 'ecwid-shopping-cart'),
				'primary_title' => __('Set up Ecwid Store', 'ecwid-shopping-cart'),
				'primary_url' => 'admin.php?page=ecwid',
				'hideable'  => true,
				'default'  => 'disabled'
			),

			'on_storeid_set' => array(
				'title' => __('Good job! Your store is set up and you\'re ready to sell.', 'ecwid-shopping-cart'),
				'message' => __('Now you can fine-tune your store\'s appearance', 'ecwid-shopping-cart'),
				'primary_title' => __('Visit Storefront', 'ecwid-shopping-cart'),
				'primary_url' => '',
				'primary_blank' => true,
				'secondary_title' => __('Configure Appearance', 'ecwid-shopping-cart'),
				'secondary_url' => 'admin.php?page=ecwid-appearance',
				'hideable' => true
			),

			'on_no_storeid_on_setup_pages' => array(
				'type' => 'warning',
				'title' => __('Your store is almost ready!', 'ecwid-shopping-cart' ),
				'message' => __('Connect your Ecwid account with this site to complete setup and start selling', 'ecwid-shopping-cart' ),
				'primary_title' => __('Connect Your Ecwid Store', 'ecwid-shopping-cart' ),
				'primary_url'   => 'admin.php?page=ecwid',
				'hideable'  => true
			),

			'on_appearance_widgets' => array(
				'message' => __('To add extra functions to your store, drag and drop Ecwid store elements on your site. When you\'re done, you can get back to modifying your settings.', 'ecwid-shopping-cart' ),
				'secondary_title' => __('Back to Store Settings', 'ecwid-shopping-cart'),
				'secondary_url'   => 'admin.php?page=ecwid-appearance',
				'hideable'  => true
			),

			'please_vote' => array(
				'message' => sprintf(
					__('Do you like your Ecwid online store? We\'d appreciate it if you add your review and vote for the plugin on Wordpress site.', 'ecwid-shopping-cart'),
					'target="_blank" href="http://wordpress.org/support/view/plugin-reviews/ecwid-shopping-cart"'
				),
				'primary_title' => __('Rate Ecwid at WordPress.org', 'ecwid-shopping-cart'),
				'primary_url' => 'http://wordpress.org/support/view/plugin-reviews/ecwid-shopping-cart',
				'hideable' => true
			)
		);
	}

	protected function need_to_show_message($name)
	{
		$admin_page = '';
		if (function_exists('get_current_screen')) {
			$screen = get_current_screen();
			$admin_page = $screen->base;
		}

		switch ($name) {
			case 'on_activate':
				return $admin_page == 'plugins' && get_ecwid_store_id() == ECWID_DEMO_STORE_ID;

			case 'on_storeid_set':
				return get_ecwid_store_id() != ECWID_DEMO_STORE_ID && @$_GET['settings-updated'] == 'true' && $admin_page == 'toplevel_page_ecwid';

			case 'on_no_storeid_on_setup_pages':
				$is_newbie = get_ecwid_store_id() == ECWID_DEMO_STORE_ID;

				$is_ecwid_settings = in_array($admin_page, array('ecwid-store_page_ecwid-advanced', 'ecwid-store_page_ecwid-appearance'));
				$is_store_page = $admin_page == 'post' && isset($_GET['post']) && $_GET['post'] == ecwid_get_current_store_page_id();

				return $is_newbie && ($is_ecwid_settings || $is_store_page);

			case 'on_appearance_widgets':
				return isset($_GET['from-ecwid']) && $admin_page == 'widgets';

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
		}
	}

}