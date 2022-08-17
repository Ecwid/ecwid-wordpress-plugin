<div class="ecwid-admin-footer">
	<?php if ( ! Ecwid_Config::is_wl() ) : ?>
		
		<?php if ( Ecwid_Admin_Main_Page::uses_integrated_admin() ) : ?>    
	<div class="ecwid-admin-footer-block ecwid-app-badges-block">
		<h4 class="ecwid-admin-footer-title"><?php esc_html_e( 'Manage Store on iPhone, iPad or Android', 'ecwid-shopping-cart' ); ?></h4>
		<div class="ecwid-admin-footer-text">
			<a target="_blank" id="ecwid-get-mobile-app" href="admin.php?page=ecwid-admin-mobile">
				<?php echo esc_html( sprintf( __( 'Get %s mobile app', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ) ); ?>

			</a>
		</div>
	</div>
	<?php endif; ?>
		
	<div class="ecwid-admin-footer-block">
		<h4 class="ecwid-admin-footer-title"><?php esc_html_e( 'Questions?', 'ecwid-shopping-cart' ); ?></h4>
		<div class="ecwid-admin-footer-text">
			<?php echo wp_kses_post( __( '<a href="admin.php?page=' . Ecwid_Admin::ADMIN_SLUG . '-help">Read FAQ or contact support</a>', 'ecwid-shopping-cart' ) ); ?>
		</div>
	</div>
		
	<?php endif; ?>
	
	<?php if ( @$show_reconnect && Ecwid_Config::should_show_reconnect_in_footer() ) : ?>
		<div class="ecwid-admin-footer-block">
			<h4 class="ecwid-admin-footer-title"><?php echo esc_html( sprintf( __( 'Want to connect another %s store?', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ) ); ?></h4>
			<div class="ecwid-admin-footer-text">
				<?php echo wp_kses_post( sprintf( __( '<a %s>Reconnect</a>', 'ecwid-shopping-cart' ), 'href="' . Ecwid_Admin::get_dashboard_url() . '&reconnect"' ) ); ?>
			</div>
		</div>
	<?php endif; ?>
</div>
