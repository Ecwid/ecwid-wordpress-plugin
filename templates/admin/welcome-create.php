<div class="ec-form">
<?php if ( ! $this->_is_registration_blocked_locale() ) { ?>
	<div class="ec-button">
		<button type="button" class="ec-create-store-button btn btn--large btn--orange"><?php esc_html_e( 'Create Store', 'ecwid-shopping-cart' ); ?></button>
	</div>
	<a href="<?php echo esc_url( $connect_url ); ?>" class="ec-connect-store"><?php esc_html_e( 'Connect your store', 'ecwid-shopping-cart' ); ?>&nbsp;&rsaquo;</a>
<?php } else { ?>
	<div class="ec-button">
		<form action="<?php echo esc_url( $connect_url ); ?>" method="post">
			<button type="submit" class="btn btn--large btn--orange"><?php esc_html_e( 'Connect your store', 'ecwid-shopping-cart' ); ?></button>
		</form>
	</div>
<?php } ?>
</div>

<div class="ec-note ec-create-store-success-note">
	<?php esc_html_e( 'Your store has been created. Preparing your store dashboard ...', 'ecwid-shopping-cart' ); ?>
</div>

<?php
require_once ECWID_ADMIN_TEMPLATES_DIR . '/welcome-connection-message.php';
?>

<?php
require_once ECWID_ADMIN_TEMPLATES_DIR . '/welcome-terms-privacy.php';
?>
