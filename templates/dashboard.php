<?php
/*
$post_result = wp_remote_post('https://my.ecwid.com/api/oauth/token');
if (is_object($post_result) && @$post_result->get_error_message()) {
	$message = $post_result->get_error_message();
}
if ($message) {
	Ecwid_Message_Manager::show_message('no_oauth', array('message' => Ecwid_Message_Manager::get_oauth_message($message)));
} else {
}
*/
 ?>
<div class="wrap ecwid-admin ecwid-dashboard">
	<div class="box">
		<div class="head">
			<?php ecwid_embed_svg('ecwid_logo_symbol_RGB');?>
			<h3>
				<?php _e( 'Ecwid Shopping Cart', 'ecwid-shopping-cart' ); ?>
			</h3>
			<div class="store-id drop-down">
					<span>
						<?php _e( 'Store ID', 'ecwid-shopping-cart' ); ?> : <?php echo get_ecwid_store_id(); ?>
					</span>
				<ul>
					<li>
						<a href="admin-post.php?action=ecwid_disconnect"><?php _e( 'Disconnect store', 'ecwid-shopping-cart' ); ?></a>
					</li>
				</ul>
			</div>
		</div>
		<div class="body">
			<div class="greeting-image">
				<img src="<?php echo(esc_attr(ECWID_PLUGIN_URL)); ?>/images/store_ready.png" width="142" />
			</div>

			<div class="greeting">
			<?php if (@$_GET['settings-updated']): ?>
				<div class="greeting-title">
					<?php _e('Congratulations!', 'ecwid-shopping-cart'); ?>
				</div>
				<div class="greeting-message mobile-br">
					<?php _e( 'Your Ecwid store is now connected<br /> to your WordPress website', 'ecwid-shopping-cart' ); ?>
				</div>
			<?php else: ?>

				<div class="greeting-title">
					<?php _e('Greetings!', 'ecwid-shopping-cart'); ?>
				</div>
				<div class="greeting-message mobile-br">
					<?php _e( 'Your Ecwid store is connected<br /> to your WordPress website', 'ecwid-shopping-cart' ); ?>
				</div>
			<?php endif; ?>

			<ul class="greeting-links">
				<li>
					<a target="_blank" href="<?php echo Ecwid_Store_Page::get_store_url(); ?>"><?php _e('Visit storefront', 'ecwid-shopping-cart'); ?></a>
				</li>
				<li>
					<a target="_blank" href="//my.ecwid.com/cp?source=wporg"><?php _e('Open control panel', 'ecwid-shopping-cart'); ?></a>
				</li>
			</ul>



			</div>
		</div>
	</div>

	<?php require ECWID_PLUGIN_DIR . 'templates/admin-footer.php'; ?>
</div>