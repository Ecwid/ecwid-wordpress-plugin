<div class="ec-page ec-page--welcome calypso-page">
	<div class="ec-page__body">
		<div class="ec-content">
			<div class="ec-logo">
				<?php
				if( !Ecwid_Config::is_wl() ) {
					ecwid_embed_svg( 'ec-logo' );
				}
				?>
			</div>
			<h2>
				<?php _e( 'Add online store to your website', 'ecwid-shopping-cart' ); ?>
			</h2>
			<div class="ec-subheading">
				<p>
					<?php echo sprintf(
						__( 'Create a new store or connect your existing store, if you have %s account. We will help you how to add products, to set up shipping and to show your store on this site.', 'ecwid-shopping-cart' ),
						Ecwid_Config::get_brand()
					); ?>
				</p>
			</div>

			<?php
			if( $state == 'create' ) {
				require_once ECWID_ADMIN_TEMPLATES_DIR . '/welcome-create.php';
			}

			if( $state == 'connect' ) {
				require_once ECWID_ADMIN_TEMPLATES_DIR . '/welcome-connect.php';
			}

			if( $state == 'no_oauth' ) {
				require_once ECWID_ADMIN_TEMPLATES_DIR . '/welcome-no_oauth.php';
			}
			?>

		</div>

		<?php if( !Ecwid_Config::is_wl() ) { ?>
		<div class="ec-poweredby">
			<?php echo sprintf(
				__( 'Service provided by <a %s>%s</a>', 'ecwid-shopping-cart' ),
				'href="https://www.ecwid.com?source=wporg" target="_blank"',
				'ecwid.com'
			); ?>
		</div>
		<?php } ?>
	</div>
</div>