<div class="ecwid-admin-footer">
	<div class="ecwid-admin-footer-block ecwid-app-badges-block">
		<h4 class="ecwid-admin-footer-title"><?php _e('Manage Store on iPhone, iPad or Android', 'ecwid-shopping-cart'); ?></h4>
		<div class="ecwid-admin-footer-text">
			<a target="_blank" id="ecwid-get-mobile-app" href="admin.php?page=ecwid-admin-mobile">
				<?php _e( 'Get Ecwid mobile app', 'ecwid-shopping-cart' ); ?>
			</a>
		</div>
	</div>
	<div class="ecwid-admin-footer-block">
		<h4 class="ecwid-admin-footer-title"><?php _e('Questions?', 'ecwid-shopping-cart'); ?></h4>
		<div class="ecwid-admin-footer-text">
			<?php _e('<a href="admin.php?page=ecwid-help">Read FAQ or contact support</a>', 'ecwid-shopping-cart'); ?>
		</div>
	</div>
<?php if (@$show_reconnect): ?>
	<div class="ecwid-admin-footer-block">
		<h4 class="ecwid-admin-footer-title"><?php _e('Want to connect another Ecwid store?', 'ecwid-shopping-cart'); ?></h4>
		<div class="ecwid-admin-footer-text">
			<?php echo sprintf(__('<a %s>Reconnect</a>', 'ecwid-shopping-cart'), 'href="admin.php?page=ecwid&reconnect"'); ?>
		</div>
	</div>
<?php endif; ?>