<div class="form-block">
	<div class="form-block__input">
		<input type="input" id="ecwid-store-id" class="form-block__element" placeholder="<?php _e( 'Enter your Store ID', 'ecwid-shopping-cart' ); ?>" value="" size="32" />
	</div>
	<div class="form-block__group-append">
		<button id="ecwid-connect-no-oauth" data-href="admin-post.php?action=ec_connect" class="btn btn--orange btn--medium btn--no-animate form-block__btn form-block__element" type="button"><?php _e( 'Connect', 'ecwid-shopping-cart' ); ?></button>
	</div>
</div>
<div class="ec-note">
	<?php echo sprintf(
		__( 'Store ID is a unique identifier of your %1$s account. You can find it in your %1$s control panel on the <a %2$s>Dashboard page</a>.', 'ecwid-shopping-cart' ),
		Ecwid_Config::get_brand(),
		'href="https://' . Ecwid_Config::get_cpanel_domain() . '/cp/CP.html?partner=wporg#dashboard" target="_blank"'
	); ?>
</div>