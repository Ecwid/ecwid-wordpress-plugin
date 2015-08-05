<div class="wrap ecwid-admin ecwid-connect<?php if ($no_oauth): ?> no-oauth<?php else: ?> with-oauth<?php endif; ?>">
	<div class="box">
		<div class="head"><?php ecwid_embed_svg('ecwid_logo_symbol_RGB');?>
			<h3>
				<?php _e( 'Ecwid Shopping Cart', 'ecwid-shopping-cart' ); ?>
			</h3>
		</div>
		<div class="greeting-image">
			<img src="<?php echo(esc_attr(ECWID_PLUGIN_URL)); ?>/images/store_inprogress.png" width="142" />
		</div>

		<div class="greeting-message mobile-br">
			<?php _e( 'Connect your store<br /> to this WordPress site', 'ecwid-shopping-cart' ); ?>
		</div>

		<div class="connect-store-id no-oauth">
			<input type="text" id="ecwid-store-id" placeholder="<?php _e('Enter your Store ID', 'ecwid-shopping-cart'); ?>" />
		</div>
		<div class="connect-button">
			<a href="admin-post.php?action=ecwid_connect" class="with-oauth"><?php _e( 'Connect Ecwid store', 'ecwid-shopping-cart' ); ?></a>
			<a id="ecwid-connect-no-oauth" href="admin-post.php?action=ecwid_connect" class="no-oauth"><?php _e( 'Save and connect', 'ecwid-shopping-cart' ); ?></a>
		</div>

		<?php if (!$connection_error): ?>

		<div class="note initial with-oauth">
			<?php _e( 'After clicking button you need to login and accept permissions to use our plugin', 'ecwid-shopping-cart' ); ?>
		</div>

		<?php else: ?>

		<div class="note auth-error">
			<span>
				<?php _e( 'Connection error - after clicking button you need to login and accept permissions to use our plugin. Please, try again.', 'ecwid-shopping-cart' ); ?>
			</span>
		</div>

		<?php endif; ?>

		<h4 class="no-oauth where-to-find-store-id" style="text-align: center"><?php _e('Where to find your Store ID:', 'ecwid-shopping-cart'); ?></h4>
		<div class="note no-oauth">
			<?php echo sprintf( __('Store ID is a unique identifier of your Ecwid account. You can find it in your Ecwid control panel: open the <a %s>Dashboard page</a> and find the "<b>Store ID: NNNNNNN</b>" text, where <b>NNNNNNN</b> is your Store&nbsp;ID.', 'ecwid-shopping-cart'), 'href="https://my.ecwid.com/cp/CP.html?source=wporg#dashboard" target="_blank"'); ?>
		</div>

		<div class="create-account-link">
			<a target="_blank" href="<?php echo esc_attr(ecwid_get_register_link()); ?>">
				<?php _e( "Don't have Ecwid account? Create it here", 'ecwid-shopping-cart' ); ?>
			</a>
		</div>
	</div>
	<p><?php echo sprintf(__('Questions? Visit <a %s>Ecwid support center</a>', 'ecwid-shopping-cart'), 'target="_blank" href="http://help.ecwid.com/?source=wporg"'); ?></p>
</div>
