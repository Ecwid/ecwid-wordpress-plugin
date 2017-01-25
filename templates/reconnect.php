<div class="wrap ecwid-admin ecwid-connect ecwid-reconnect">
	<div class="box">
		<div class="head"><?php ecwid_embed_svg('ecwid_logo_symbol_RGB');?>
			<h3>
				<?php printf( __( '%s Shopping Cart', 'ecwid-shopping-cart' ), Ecwid_WL::get_brand() ); ?>
			</h3>
		</div>
		<div class="greeting-image">
			<img src="<?php echo(esc_attr(ECWID_PLUGIN_URL)); ?>/images/store_inprogress.png" width="142" />
		</div>

		<div class="greeting-message mobile-br">
			<?php _e( 'Connect your store<br /> to this WordPress site', 'ecwid-shopping-cart' ); ?>
		</div>

		<?php if ($ecwid_oauth->get_reconnect_message()): ?>
			<div class="note reconnect-message">
				<?php echo $ecwid_oauth->get_reconnect_message(); ?>
			</div>
		<?php endif; ?>

		<div class="connect-button">
			<a href="admin-post.php?action=ecwid_connect&reconnect"><?php _e( 'Connect', 'ecwid-shopping-cart' ); ?></a>
		</div>

		<?php if ($connection_error && $ecwid_oauth->get_error() == 'cancelled'): ?>


			<div class="note auth-error">
			<span>
				<?php _e( 'Connection error - after clicking button you need to login and accept permissions to use our plugin. Please, try again.', 'ecwid-shopping-cart' ); ?>
			</span>
			</div>

		<?php elseif ($connection_error && $ecwid_oauth->get_error()  == 'other'): ?>

			<div class="note auth-error">
				<span>
					<?php printf( __( 'Looks like your site does not support remote POST requests that are required for %s API to work. Please, contact your hosting provider to enable cURL.', 'ecwid-shopping-cart' ), Ecwid_WL::get_brand() ); ?>
				</span>
			</div>

		<?php else: ?>

			<div class="note">
				<?php printf( __( 'To sell using %1$s, you must allow WordPress to access the %1$s plugin. The connect button will direct you to your %1$s account where you can provide permission.', 'ecwid-shopping-cart' ), Ecwid_WL::get_brand() ); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php require_once ECWID_PLUGIN_DIR . 'templates/admin-footer.php'; ?>
</div>
