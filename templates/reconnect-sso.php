<div class="wrap ecwid-admin ecwid-connect ecwid-reconnect-allow-sso">
	<div class="box">
		<div class="head"><?php ecwid_embed_svg('ecwid_logo_symbol_RGB');?>
			<h3>
				<?php printf( __( '%s Shopping Cart', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?>
			</h3>
		</div>

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