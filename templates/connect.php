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
			<a href="admin-post.php?action=ecwid_connect" class="with-oauth"><?php _e( 'Connect', 'ecwid-shopping-cart' ); ?></a>
			<a id="ecwid-connect-no-oauth" href="admin-post.php?action=ecwid_connect" class="no-oauth" style="white-space: nowrap; width:auto"><?php _e( 'Save and connect', 'ecwid-shopping-cart' ); ?></a>
		</div>

		<?php if (!$connection_error): ?>

		<div class="note initial with-oauth">
			<?php _e( 'To sell using Ecwid, you must allow WordPress to access the Ecwid plugin. The connect button will direct you to your Ecwid account where you can provide permission.', 'ecwid-shopping-cart' ); ?>
		</div>

		<?php endif; ?>

		<h4 class="no-oauth where-to-find-store-id" style="text-align: center"><?php _e('Where to find your Store ID:', 'ecwid-shopping-cart'); ?></h4>
		<div class="note no-oauth">
			<?php echo sprintf( __('Store ID is a unique identifier of your Ecwid account. You can find it in your Ecwid control panel: open the <a %s>Dashboard page</a> and find the "<b>Store ID: NNNNNNN</b>" text, where <b>NNNNNNN</b> is your Store&nbsp;ID.', 'ecwid-shopping-cart'), 'href="https://my.ecwid.com/cp/CP.html?source=wporg#dashboard" target="_blank"'); ?>
		</div>

		<div class="create-account-link">
			<a target="_blank" href="<?php echo esc_attr(ecwid_get_register_link()); ?>">
				<?php _e( "Don't have an Ecwid account? Create one now.", 'ecwid-shopping-cart' ); ?>
			</a>
		</div>
	</div>
	<?php require_once ECWID_PLUGIN_DIR . 'templates/admin-footer.php'; ?>
</div>
