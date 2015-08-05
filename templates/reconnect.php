<div class="wrap ecwid-admin ecwid-connect">
	<div class="box">
		<h3>
			<?php ecwid_embed_svg('ecwid_logo_symbol_RGB');?>
			<?php _e( 'Ecwid Shopping Cart', 'ecwid-shopping-cart' ); ?>
			<span class="close"></span>
		</h3>
		<div class="greeting-image">
			<img src="<?php echo(esc_attr(ECWID_PLUGIN_URL)); ?>/images/store_inprogress.png" width="142" />
		</div>

		<div class="greeting-message mobile-br">
			<?php _e( 'Reconnect your store<br /> to this WordPress site', 'ecwid-shopping-cart' ); ?>
		</div>

		<div class="connect-button">
			<a href="<?php echo esc_attr($ecwid_oauth->get_auth_dialog_url()); ?>"><?php _e( 'Reconnect Ecwid store', 'ecwid-shopping-cart' ); ?></a>
		</div>

		<div class="note initial">
			<?php _e( 'New features available, reconnect to be in touch with our updates', 'ecwid-shopping-cart' ); ?>
		</div>

		<div class="create-account-link">
			<a href="">
				<?php _e( "Don't have Ecwid account? Create it here", 'ecwid-shopping-cart' ); ?>
			</a>
		</div>
	</div>
	<p><?php _e('Questions? Visit <a href="http://help.ecwid.com/?source=wporg">Ecwid support center</a>', 'ecwid-shopping-cart'); ?></p>
</div>
