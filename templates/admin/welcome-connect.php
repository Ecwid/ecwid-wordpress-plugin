<div class="ec-form">
	<div class="ec-button">
		<form action="<?php echo $connect_url; ?>" method="post">
			<button type="submit" class="btn btn--large btn--orange"><?php _e( 'Connect Your Store', 'ecwid-shopping-cart' ); ?></button>
		</form>
	</div>
	<?php if ( !Ecwid_Config::is_no_reg_wl() ) { ?>
	<a target="_blank" href="<?php echo esc_attr(ecwid_get_register_link()); ?>"><?php _e( 'Create store', 'ecwid-shopping-cart' ); ?>&nbsp;&rsaquo;</a>
	<?php } ?>
</div>

<?php
if( !$connection_error ) {

	$note = sprintf(
		__( 'To display your store on this site, you need to allow WordPress to access your %1$s products. Please press connect to provide permission.', 'ecwid-shopping-cart' ),
		Ecwid_Config::get_brand()
	);

	echo $this->get_welcome_page_note( $note );

} else {

	$error_note = __( 'Connection error - after clicking button you need to login and accept permissions to use our plugin. Please, try again.', 'ecwid-shopping-cart' );
	
	$oauth_error = $ecwid_oauth->get_error();

	if( !empty($oauth_error) ) {
		if ($ecwid_oauth->get_error()  == 'other') {

			$error_note = sprintf( __( 'Looks like your site does not support remote POST requests that are required for %s API to work. Please, contact your hosting provider to enable cURL.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() );
		} else {

			$error_note = sprintf( __( 'To sell using %1$s, you must allow WordPress to access the %1$s plugin. The connect button will direct you to your %1$s account where you can provide permission.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() );
		}
	}

	echo $this->get_welcome_page_note( $error_note, 'ec-connection-error' );
}

if( $ecwid_oauth->get_reconnect_message() ) {
	echo $this->get_welcome_page_note( $ecwid_oauth->get_reconnect_message() );
}

?>