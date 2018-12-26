<?php
    $no_oauth = @$_GET['oauth'] == 'no';
    $connection_error = isset( $_GET['connection_error'] );
?>

<div class="wrap ecwid-admin ecwid-connect<?php if ($no_oauth): ?> no-oauth<?php else: ?> with-oauth<?php endif; ?>">
	<div class="ec-store-box">
        <?php require ECWID_PLUGIN_DIR . 'templates/admin-head.php'; ?>

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
			<a href="admin-post.php?action=ec_connect" class="with-oauth"><?php _e( 'Connect', 'ecwid-shopping-cart' ); ?></a>
			<a id="ecwid-connect-no-oauth" href="admin-post.php?action=ec_connect" class="no-oauth" style="white-space: nowrap; width:auto"><?php _e( 'Save and connect', 'ecwid-shopping-cart' ); ?></a>
		</div>

		<?php if (!$connection_error): ?>

		<div class="note initial with-oauth">
			<?php printf( __( 'To display your store on this site, you need to allow WordPress to access your %1$s products. Please press connect to provide permission.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?>
		</div>

        <?php else: ?>
            
        <div class="note auth-error">
            <span>
				<?php _e( 'Connection error - after clicking button you need to login and accept permissions to use our plugin. Please, try again.', 'ecwid-shopping-cart' ); ?>
			</span>
        </div>
		<?php endif; ?>

        <?php if ($no_oauth): ?>        
		<h4 class="no-oauth where-to-find-store-id" style="text-align: center"><?php _e('Where to find your Store ID:', 'ecwid-shopping-cart'); ?></h4>
		<div class="note no-oauth">
			<?php printf( __( 'Store ID is a unique identifier of your %1$s account. You can find it in your %1$s control panel: open the <a %2$s>Dashboard page</a> and find the "<b>Store ID: NNNNNNN</b>" text, where <b>NNNNNNN</b> is your Store&nbsp;ID.', 'ecwid-shopping-cart'), Ecwid_Config::get_brand(), 'href="https://' . Ecwid_Config::get_cpanel_domain() . '/cp/CP.html?source=wporg#dashboard" target="_blank"' ); ?>
		</div>
        <?php endif; ?>
        
        <?php if ( !Ecwid_Config::is_no_reg_wl() ): ?>
		<div class="create-account-link">
			<a target="_blank" href="<?php echo esc_attr(ecwid_get_register_link()); ?>">
				<?php printf( __( "Don't have an %s account? Create one now.", 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?>
			</a>
		</div>
        <?php endif; ?>
	</div>
	<?php require ECWID_PLUGIN_DIR . 'templates/admin-footer.php'; ?>
</div>
