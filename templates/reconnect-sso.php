<div class="wrap ecwid-admin ecwid-connect ecwid-reconnect-allow-sso">
	<div class="ec-store-box">
        <?php require ECWID_PLUGIN_DIR . 'templates/admin-head.php'; ?>

		<div class="main-wrap">
			<div class="column">
				<h4><?php _e('Your store Control Panel. Right here in WordPress.', 'ecwid-shopping-cart'); ?></h4>
				<p class="note"><?php _e('Manage products, track sales, adjust settings - <nobr>All without</nobr> leaving this page.', 'ecwid-shopping-cart'); ?></p>
				<div class="connect-button">
					<a href="admin-post.php?action=ec_connect&reconnect"><?php _e( 'Re-connect to Enable Control Panel', 'ecwid-shopping-cart' ); ?></a>
				</div>
			</div>

			<div class="column">
				<img src="<?php echo(esc_attr(ECWID_PLUGIN_URL)); ?>/images/new-feature.png" />
			</div>
		</div>

	</div>
	<?php require_once ECWID_PLUGIN_DIR . 'templates/admin-footer.php'; ?>
</div>