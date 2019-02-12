<?php

class Ecwid_Help_Page {
	const CONTACT_US_ACTION_NAME = 'ecwid_contact_us';
	public function __construct() {
		add_action('wp_ajax_' . self::CONTACT_US_ACTION_NAME, array( $this, 'submit_contact_us') );
	}

	public function submit_contact_us() {

		if ( !current_user_can('administrator') ) {
			header('403 Access Denied');

			die();
		}
		if ( !wp_verify_nonce($_POST['wp-nonce'], self::CONTACT_US_ACTION_NAME) ) {
			header('403 Access Denied');

			die();
		}

		$to = get_option( 'ecwid_support_email' );

		$body_lines = array();
		if ( !ecwid_is_demo_store() ) {
			$body_lines[] = 'Store ID: ' . get_ecwid_store_id();
		}
		$body_lines[] = 'Store URL: ' . Ecwid_Store_Page::get_store_url();
		$body_lines[] = 'Wp theme: ' . ecwid_get_theme_name();
		$body_lines[] = 'Ecwid plugin version: ' . get_option('ecwid_plugin_version');
		$body_lines[] = 'Wordpress version: '  . get_bloginfo('version');
		$body_lines[] = '';
		$body_lines[] = 'Message:';
		$body_lines[] = '';
		$body_lines[] = $_POST['body'];

		global $current_user;
		$reply_to = $current_user->user_email;

		$result = wp_mail(
			$to,
			$_POST['subject'],
			implode(PHP_EOL, $body_lines),
			'Reply-To:' . $reply_to
		);

		if ($result) {
			$nonce = wp_create_nonce( self::CONTACT_US_ACTION_NAME );

			echo json_encode(
				array(
					'nonce' => $nonce
				)
			);
			wp_die();
		} else {
			header('500 Send mail failed');
			die();
		}
	}

	public function get_faqs() {
		global $faqs;

		include 'faq_entries.php';

		$max = 8;

		$guaranteed_3 = null;

		foreach ( $faqs as $idx => $faq ) {
			if ( isset( $faq['priority'] ) && $faq['priority'] == 'guaranteed_3' ) {
				$guaranteed_3 = array();
				$guaranteed_3[] = $faq;
				unset( $faqs[$idx] );
				break;
			}
		}
		
		$result = array();
		foreach ( $faqs as $idx => $faq ) {
			if ( isset($faq['priority']) && $faq['priority'] == 'newbie_with_woo' ) {
				$installed_within_two_weeks = time() - get_option( 'ecwid_installation_date' ) < 60 * 60 * 24 * 14;

				if ( ecwid_get_woocommerce_status() && $installed_within_two_weeks ) {
					$result[] = $faq;
					unset($faqs[$idx]);
				}
			}
		}
		$faqs = array_values($faqs);

		while ( count($result) < $max + ( $guaranteed_3 ? 1 : 0 ) ) {
			$rand = rand(0, count($faqs) - 1);
			$result[] = $faqs[$rand];

			unset($faqs[$rand]);
			$faqs = array_values($faqs);
		}

		array_splice( $result, 2, 0, $guaranteed_3 );
		
		$faqs = $result;

		$result = array();
		foreach ($faqs as $faq) {
			$faq['body'] = preg_replace('!<img alt="" src="([^"]*)"!', '<img alt="" src="' . ECWID_PLUGIN_URL . '/images/help/' . '$1"', $faq['body']);

			$result[] = (object) $faq;
		}

		return $result;
	}
}

$ecwid_help_page = new Ecwid_Help_Page();