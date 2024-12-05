<?php
class Ec_Store_WP_CLI extends WP_CLI_Command {

	public static $command = 'ec-store';
	
	/**
	* Setting WL configuration. If you need more information, please contact your manager.
	* ## OPTIONS
	*
	* [--wl_mode]
	* : Is whitelable mode enabled — true or false
	*
	* [--brand]
	* : Brand name
	*
	* [--contact_us_url]
	* : Link to contacts page in admin pages footer
	*
	* [--channel_id]
	* : Chanel ID. Used for creating a registration link
	*
	* [--oauth_authorize_url]
	* : Authorization link
	*
	* [--oauth_token_url]
	* : Link for getting OAuth token
	*
	* [--registration_url]
	* : Link for user registration
	*
	* [--oauth_appid]
	* : Application ID
	*
	* [--oauth_appsecret]
	* : Application secret key
	*
	* [--oauth_token]
	* : Store token. Not working without Store ID
	*
	* [--store_id]
	* : Store ID. Not working without token
	*
	* [--scriptjs_domain]
	* : Domain for script.js
	*
	* [--api_domain]
	* : API domain
	*
	* [--cp_domain]
	* : Control panel domain
	*
	* [--demo_store_id]
	* : Demo store ID
	*
	*/
	function config( $args, $assoc_args ) {

		$config = $assoc_args;

		Ecwid_Config::load_from_cli( $config );

		WP_CLI::line( 'Configuration saved!' );
	}

	/**
	* Create a new store. It will automatically connect to the plugin.
	* ## OPTIONS
	*
	* [--email]
	* : Client email. Required.
	*
	* [--name]
	* : Client name. Required.
	*
	* [--password]
	* : Client password. Optional, if not set, it will be generated automatically.
	*
	* [--channel_id]
	* : Chanel ID. To bind the client to a channel. Optional.
	*
	* [--goods=<goods>]
	* : Goods. To set the type of demo products. For a list of current options, contact your manager. Optional.
	* ---
	* default: apparel
	* options:
	*   - apparel
	*   - health
	*   - electronics
	*   - jewelry
	*   - food_ecommerce
	*   - food_restaurant
	* ---
	*
	*/
	function create_store( $args, $assoc_args ) {
		
		$params = $assoc_args;
		$is_set_user = ! empty( WP_CLI::get_config( 'user' ) );

		if ( empty( $params['email'] ) && ! $is_set_user ) {
			WP_CLI::error( 'Email is required', true );
		}

		if ( empty( $params['name'] ) && ! $is_set_user ) {
			WP_CLI::error( 'Name is required', true );
		}

		$result = ecwid_create_store( $params );
		
		$data = json_decode( $result['body'] );
		$is_store_created = is_array( $result ) && $result['response']['code'] == 200;
		
		if( $is_store_created ) {
			WP_CLI::success( 'Store created: ' . $data->id );
		} else {
			WP_CLI::error( 'Store creation failed: [' . $result['response']['code'] . '] ' . $data->errorCode . ' : ' . $data->errorMessage, true );
		}
	}

}
 
WP_CLI::add_command( Ec_Store_WP_CLI::$command, 'Ec_Store_WP_CLI' );