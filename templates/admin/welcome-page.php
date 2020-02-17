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
				<?php _e( 'Add an Online Store to Your Website', 'ecwid-shopping-cart' ); ?>
			</h2>

			<?php if( $state == 'create' || $state == 'connect' ) { ?>

			<div class="ec-subheading">
				<p>
					<?php echo sprintf(
						__( 'Create a new store or connect an existing one, if you already have an %s account. The plugin will guide you through store setup and help publish it on your website.', 'ecwid-shopping-cart' ),
						Ecwid_Config::get_brand()
					); ?>
				</p>
			</div>

			<?php } ?>

			<?php if( $state == 'no_oauth' ) { ?>

			<div class="ec-subheading">
				<p>
					<?php echo sprintf(
						__( 'To add your store to your website, put your %1$s Store ID in the field below. If you don\'t have an %1$s account yet, create one for free on the <a %2$s>%1$s website</a>.', 'ecwid-shopping-cart' ),
						Ecwid_Config::get_brand(),
						'href="' . esc_attr(ecwid_get_register_link()) . '" target="_blank"'
					); ?>
				</p>
			</div>

			<?php } ?>

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
				__( 'Provided by <a %s>%s</a>', 'ecwid-shopping-cart' ),
				'href="https://www.ecwid.com?partner=wporg" target="_blank"',
				'ecwid.com'
			); ?>
		</div>
		<?php } ?>
	</div>
</div>