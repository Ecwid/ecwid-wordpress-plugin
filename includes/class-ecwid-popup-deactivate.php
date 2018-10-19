<?php

require_once( ECWID_PLUGIN_DIR . 'includes/class-ecwid-popup.php' );

class Ecwid_Popup_Deactivate extends Ecwid_Popup {

	protected $_class = 'ecwid-popup-deactivate';
	
	const OPTION_DISABLE_POPUP = 'ecwid_disable_deactivate_popup';
	
	public function __construct()
	{
		add_action( 'wp_ajax_ecwid_deactivate_feedback', array( $this, 'ajax_deactivate_feedback') );
	}
	
	public function enqueue_scripts()
	{
		parent::enqueue_scripts();
		wp_enqueue_script( 'ecwid-popup-deactivate', ECWID_PLUGIN_URL . '/js/popup-deactivate.js', array( 'jquery' ), get_option('ecwid_plugin_version') );
		wp_enqueue_style( 'ecwid-popup-deactivate', ECWID_PLUGIN_URL . '/css/popup-deactivate.css', array( ), get_option('ecwid_plugin_version') );
	}
	
	public function ajax_deactivate_feedback() {
		if ( !current_user_can('manage_options') ) {
			header('403 Access Denied');

			die();
		}

		$to = 'plugins-feedback@ecwid.com';

		$body_lines = array();
		if ( !ecwid_is_demo_store() ) {
			$body_lines[] = 'Store ID: ' . get_ecwid_store_id();
		}
		
		$reasons = $this->_get_reasons();
		$reason = $reasons[$_GET['reason']];
		
		if ( isset( $reason['is_disable_message'] ) ) {
			update_option( self::OPTION_DISABLE_POPUP, true );
		}
		
		$body_lines[] = 'Store URL: ' . Ecwid_Store_Page::get_store_url();
		$body_lines[] = 'Plugin installed: '  . strftime(  '%d %b %Y', get_option( 'ecwid_installation_date' ) );
		$body_lines[] = 'Plugin version: ' . get_option('ecwid_plugin_version');
		$body_lines[] = 'Reason:' . $reason['text'] . "\n" . ( !empty( $_GET['message'] ) ?  $_GET['message'] : '[no message]' );
		
		$api = new Ecwid_Api_V3();
		
		$profile = $api->get_store_profile();
		if ( $profile && @$profile->account && @$profile->account->accountEmail ) {
			$reply_to = $profile->account->accountEmail;
		} else {
			global $current_user;
			$reply_to = $current_user->user_email;
		}
		
		$subject_template = __( '[%s] WordPress plugin deactivation feedback (store ID: %s)', 'ecwid-shopping-cart' );
		
		$prefix = $reason['code'];
		if ( !empty( $_GET['message'] ) ) {
			$prefix .= ', commented';
		}
		
		$subject = sprintf( $subject_template, $prefix, get_ecwid_store_id() );
		
		$result = wp_mail(
			$to,
			$subject,
			implode( PHP_EOL, $body_lines ),
			'Reply-To:' . $reply_to
		);

		if ($result) {
			header('200 OK');
			
			die();
		} else {
			header('500 Send mail failed');
			die();
		}
	}
	
	public function is_disabled() {
		$disabled = get_option( self::OPTION_DISABLE_POPUP, false );
		
		if ( $disabled ) return true;
		
		if ( Ecwid_Config::is_wl() ) return true;
		
		if (strpos(ecwid_get_current_user_locale(), 'en') !== 0) return true;
		
		return false;
	}
	
	protected function _get_footer_buttons()
	{
		return array(
			(object) array(
				'class' => 'button-secondary deactivate',
				'title' => __( 'Submit & Deactivate', 'ecwid-shopping-cart' )
			),
			(object) array(
				'class' => 'button-primary btn-close',
				'title' => __( 'Cancel', 'ecwid-shopping-cart' )
			)
		);
	}

	protected function _get_header()
	{
		return __( 'Before You Go', 'ecwid-shopping-cart' );
	}

	protected function _render_body()
	{
		$reasons = $this->_get_reasons();
		require ( ECWID_POPUP_TEMPLATES_DIR . 'deactivate.php' );
	}
	
	protected function _get_reasons()
	{
		$options = array(
			array(
				'text' => __( 'I have a problem using this plugin', 'ecwid-shopping-cart' ),
				'has_message' => true,
				'code' => 'problem',
				'message_hint' => __( 'What was wrong?', 'ecwid-shopping-cart' ),
			),
			array(
				'text' => __( 'The plugin is difficult to set up and use', 'ecwid-shopping-cart' ),
				'has_message' => true,
				'code' => 'hard to use',
				'message_hint' => __( 'What was difficult?', 'ecwid-shopping-cart' )
			),
			array(
				'text' => __( 'The plugin doesn\'t support the feature I want', 'ecwid-shopping-cart' ),
				'has_message' => true,
				'code' => 'no feature',
				'message_hint' => __( 'What feature do you need?', 'ecwid-shopping-cart' )
			),
			array(
				'text' => __( 'I found a better plugin', 'ecwid-shopping-cart' ),
				'has_message' => true,
				'code' => 'found better',
				'message_hint' => __( 'Can you share the name of the plugin you chose?', 'ecwid-shopping-cart' )
			),
			array(
				'text' => __( 'It\'s a temporary deactivation. Please do not ask me again.', 'ecwid-shopping-cart' ),
				'has_message' => false,
				'code' => 'temporary',
				'is_disable_message' => true
			),
			array(
				'text' => __( 'Other', 'ecwid-shopping-cart' ),
				'has_message' => true,
				'code' => 'other',
				'message_hint' => __( 'Can you share your feedback? What was wrong?', 'ecwid-shopping-cart' )
			)
		);		
		
		return $options;
	}
}