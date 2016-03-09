<div class="ecwid-admin-footer">
	<div class="ecwid-admin-footer-block">
		<h4 class="ecwid-admin-footer-title"><?php _e('Manage Store on iPhone, iPad or Android', 'ecwid-shopping-cart'); ?></h4>
		<div class="ecwid-app-badges">
			<a href="https://itunes.apple.com/en/app/ecwid/id626731456?mt=8"target="_blank" rel="nofollow">
				<?php ecwid_embed_svg('black-app-store'); ?>
			</a>
			<a href="https://play.google.com/store/apps/details?id=com.ecwid.android">
				<?php ecwid_embed_svg('black-google'); ?>
			</a>
		</div>
	</div>
	<div class="ecwid-admin-footer-block">
		<h4 class="ecwid-admin-footer-title"><?php _e('Questions?', 'ecwid-shopping-cart'); ?></h4>
		<div class="ecwid-admin-footer-text">
			<?php echo sprintf(__('<a href="https://help.ecwid.com/customer/ru/portal/articles/1085017-wordpress-org">Read FAQ</a> or contact support at <a %s>wordpress@ecwid.com</a>', 'ecwid-shopping-cart'), 'href="mailto:wordpress@ecwid.com"'); ?>
		</div>
	</div>
<?php if (@$show_reconnect): ?>
	<div class="ecwid-admin-footer-block">
		<h4 class="ecwid-admin-footer-title"><?php _e('Want to connect another Ecwid store?', 'ecwid-shopping-cart'); ?></h4>
		<div class="ecwid-admin-footer-text">
			<?php echo sprintf(__('You can reconnect on <a %s>this page</a>', 'ecwid-shopping-cart'), 'href="admin.php?page=ecwid&reconnect"'); ?>
		</div>
	</div>
<?php endif; ?>