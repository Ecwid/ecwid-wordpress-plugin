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

}
 
WP_CLI::add_command( Ec_Store_WP_CLI::$command, 'Ec_Store_WP_CLI' );