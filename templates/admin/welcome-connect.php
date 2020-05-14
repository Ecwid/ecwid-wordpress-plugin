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
require_once ECWID_ADMIN_TEMPLATES_DIR . '/welcome-connection-message.php';
?>

<?php
require_once ECWID_ADMIN_TEMPLATES_DIR . '/welcome-terms-privacy.php';
?>
