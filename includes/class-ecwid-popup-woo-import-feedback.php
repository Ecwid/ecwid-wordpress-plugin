<?php

require_once ECWID_PLUGIN_DIR . 'includes/class-ecwid-popup.php';

class Ecwid_Popup_Woo_Import_Feedback extends Ecwid_Popup {

	protected $_class = 'ecwid-popup-woo-import-feedback';

	const OPTION_DISABLE_POPUP = 'ecwid_disable_deactivate_popup';

	public function __construct() {
		add_action( 'wp_ajax_ecwid_send_feedback', array( $this, 'ajax_send_feedback' ) );
	}

	public function enqueue_scripts() {
		parent::enqueue_scripts();
		wp_enqueue_script( 'ecwid-popup-deactivate', ECWID_PLUGIN_URL . '/js/popup-deactivate.js', array( 'jquery' ), get_option( 'ecwid_plugin_version' ) );
		wp_enqueue_style( 'ecwid-popup-deactivate', ECWID_PLUGIN_URL . '/css/popup-deactivate.css', array(), get_option( 'ecwid_plugin_version' ) );
	}

	public function ajax_send_feedback() {

		if ( ! current_user_can( 'manage_options' ) ) {
			header( '403 Access Denied' );

			die();
		}

		$to = 'plugins-feedback@ecwid.com';

		$body_lines = array();
		if ( ! ecwid_is_demo_store() ) {
			$body_lines[] = 'Store ID: ' . get_ecwid_store_id();
		}

		$reasons = $this->_get_reasons();

		if ( isset( $_GET['reason'] ) ) {
			$reason = $reasons[ sanitize_text_field( wp_unslash( $_GET['reason'] ) ) ];
		} else {
			$reason = end( $reasons );
		}

		if ( isset( $reason['is_disable_message'] ) ) {
			update_option( self::OPTION_DISABLE_POPUP, true );
		}

		$body_lines[] = 'Store URL: ' . Ecwid_Store_Page::get_store_url();
		$body_lines[] = 'Plugin installed: ' . date_i18n( 'd M Y', get_option( 'ecwid_installation_date' ) );
		$body_lines[] = 'Plugin version: ' . get_option( 'ecwid_plugin_version' );
		$body_lines[] = 'Reason:' . $reason['text'] . "\n" . ( ! empty( $_GET['message'] ) ? sanitize_text_field( wp_unslash( $_GET['message'] ) ) : '[no message]' );

		$api = new Ecwid_Api_V3();

		$profile = $api->get_store_profile();
		if ( $profile && @$profile->account && @$profile->account->accountEmail ) {
			$reply_to = $profile->account->accountEmail;
		} else {
			global $current_user;
			$reply_to = $current_user->user_email;
		}

		$subject_template = __( '[%1$s] WordPress plugin: Woo import feedback (store ID: %2$s)', 'ecwid-shopping-cart' );

		$prefix = $reason['code'];
		if ( ! empty( $_GET['message'] ) ) {
			$prefix .= ', commented';
		}

		$subject = sprintf( $subject_template, $prefix, get_ecwid_store_id() );

		$result = wp_mail(
			$to,
			$subject,
			implode( PHP_EOL, $body_lines ),
			'Reply-To:' . $reply_to
		);

		if ( $result ) {
			header( 'HTTP/1.1 200 OK' );
			die();
		} else {
			header( '500 Send mail failed' );
			die();
		}
	}

	public function is_disabled() {
		$disabled = get_option( self::OPTION_DISABLE_POPUP, false );

		if ( $disabled ) {
			return true;
		}

		if ( Ecwid_Config::is_wl() ) {
			return true;
		}

		if ( strpos( ecwid_get_current_user_locale(), 'en' ) !== 0 ) {
			return true;
		}

		return false;
	}

	protected function _get_footer_buttons() {
		return array(
			(object) array(
				'class' => 'button-primary float-left btn-send-feedback',
				'title' => __( 'Send', 'ecwid-shopping-cart' ),
			),
			(object) array(
				'class' => 'button-link btn-close',
				'title' => __( 'Cancel', 'ecwid-shopping-cart' ),
			),
			(object) array(
				'class' => 'button-link btn-close success-message',
				'title' => __( 'Close', 'ecwid-shopping-cart' ),
			),
		);
	}

	protected function _get_header() {
		return __( 'Help Us Improve', 'ecwid-shopping-cart' );
	}

	protected function _render_body() {
		$reasons = $this->_get_reasons();
		require ECWID_POPUP_TEMPLATES_DIR . 'woo-import-feedback.php';
	}

	protected function _get_reasons() {
		$options = array(
			array(
				'text'         => '',
				'has_message'  => true,
				'code'         => 'feedback',
				'message_hint' => __( 'Please share your experience with us. What was positive? What can we improve?', 'ecwid-shopping-cart' ),
			),
		);

		return $options;
	}
}
