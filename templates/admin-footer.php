<div class="ecwid-admin-footer">

	
	<?php if ( @$show_reconnect && Ecwid_Config::should_show_reconnect_in_footer() ) { ?>
		<div class="ecwid-admin-footer-block">
			<h4 class="ecwid-admin-footer-title"><?php echo __( 'Store ID', 'ecwid-shopping-cart' ); ?> <?php echo esc_html( get_ecwid_store_id() ); ?></h4>
			<div class="ecwid-admin-footer-text">
				<?php echo esc_html( sprintf( __( 'Want to connect another %s store?', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ) ); ?>
				<?php echo wp_kses_post( sprintf( __( '<a %s>Reconnect</a>', 'ecwid-shopping-cart' ), 'href="' . Ecwid_Admin::get_dashboard_url() . '&reconnect"' ) ); ?>
			</div>
		</div>
	<?php } ?>

	<?php if ( ! Ecwid_Config::is_wl() ) { ?>

	<div class="ecwid-admin-footer-block">
		<h4 class="ecwid-admin-footer-title"><?php esc_html_e( 'Questions?', 'ecwid-shopping-cart' ); ?></h4>
		<div class="ecwid-admin-footer-text">
			<?php echo wp_kses_post( __( '<a href="admin.php?page=' . Ecwid_Admin::ADMIN_SLUG . '-help">Read FAQ or contact support</a>', 'ecwid-shopping-cart' ) ); ?>
		</div>
	</div>

	<?php } ?>
</div>
