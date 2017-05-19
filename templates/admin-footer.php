<div class="ecwid-admin-footer">
	<?php if ( !Ecwid_Config::is_wl() ): ?>
	<div class="ecwid-admin-footer-block ecwid-app-badges-block">
		<h4 class="ecwid-admin-footer-title"><?php _e('Manage Store on iPhone, iPad or Android', 'ecwid-shopping-cart'); ?></h4>
		<div class="ecwid-admin-footer-text">
			<a target="_blank" id="ecwid-get-mobile-app" href="admin.php?page=ecwid-admin-mobile">
				<?php printf( __( 'Get %s mobile app', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?>

			</a>
		</div>
	</div>
	<div class="ecwid-admin-footer-block">
		<h4 class="ecwid-admin-footer-title"><?php _e('Questions?', 'ecwid-shopping-cart'); ?></h4>
		<div class="ecwid-admin-footer-text">
			<?php _e('<a href="admin.php?page=' . Ecwid_Admin::ADMIN_SLUG . '-help">Read FAQ or contact support</a>', 'ecwid-shopping-cart'); ?>
		</div>
	</div>
<?php endif; ?>
<?php if (@$show_reconnect): ?>
	<div class="ecwid-admin-footer-block">
		<h4 class="ecwid-admin-footer-title"><?php printf( __('Want to connect another %s store?', 'ecwid-shopping-cart'), Ecwid_Config::get_brand() ); ?></h4>
		<div class="ecwid-admin-footer-text">
			<?php echo sprintf(__('<a %s>Reconnect</a>', 'ecwid-shopping-cart'), 'href="' . Ecwid_Admin::get_dashboard_url() . '&reconnect"'); ?>
		</div>
	</div>
<?php endif; ?>