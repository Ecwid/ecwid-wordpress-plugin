<div class="ec-page ec-page--welcome calypso-page">
	<div class="ec-page__body">
		<div class="ec-content">
			<div class="ec-logo">
				<?php
				if ( ! Ecwid_Config::is_wl() ) {
					ecwid_embed_svg( 'ec-logo' );
				}
				?>
			</div>
			<h2>
				<?php esc_html_e( 'Add an Online Store to Your Website', 'ecwid-shopping-cart' ); ?>
			</h2>

			<?php if ( $state == 'create' || $state == 'connect' ) { ?>

				<div class="ec-subheading">
					<p>
						<?php
						echo esc_html(
							sprintf(
								__( 'Create a new store or connect an existing one, if you already have an %s account. The plugin will guide you through store setup and help publish it on your website.', 'ecwid-shopping-cart' ),
								Ecwid_Config::get_brand()
							)
						);
						?>
					</p>
				</div>

			<?php } ?>

			<?php if ( $state == 'no_oauth' ) { ?>
				<div class="ec-subheading">
					<p>
						<?php
						if ( $this->_is_registration_blocked_locale() ) {
							echo esc_html(
								sprintf(
									__( 'To add your store to your website, put your %1$s Store ID in the field below.', 'ecwid-shopping-cart' ),
									Ecwid_Config::get_brand()
								)
							);
						} else {
							echo wp_kses_post(
								sprintf(
									__( 'To add your store to your website, put your %1$s Store ID in the field below. If you don\'t have an %1$s account yet, create one on the <a %2$s>%1$s website</a>.', 'ecwid-shopping-cart' ),
									Ecwid_Config::get_brand(),
									'href="' . esc_attr( ecwid_get_register_link() ) . '" target="_blank"'
								)
							);
						}
						?>
					</p>
				</div>
				<?php
			}//end if
			?>


			<?php if ( $this->_is_registration_blocked_locale() ) { ?>
				<div class="ec-subheading">
					<p>
						<?php echo wp_kses_post( $this->get_welcome_page_note( __( 'Unfortunately, creating a new account is currently unavailable for your country. You can still connect an existing account.', 'ecwid-shopping-cart' ), 'ec-connection-error' ) ); ?>
					</p>
				</div>
			<?php } ?>


			<?php
			if ( $state == 'create' ) {
				require_once ECWID_ADMIN_TEMPLATES_DIR . '/welcome-create.php';
			}

			if ( $state == 'connect' ) {
				require_once ECWID_ADMIN_TEMPLATES_DIR . '/welcome-connect.php';
			}

			if ( $state == 'no_oauth' ) {
				require_once ECWID_ADMIN_TEMPLATES_DIR . '/welcome-no_oauth.php';
			}
			?>

		</div>

		<?php if ( ! Ecwid_Config::is_wl() ) { ?>
		<div class="ec-poweredby">
			<?php
			echo wp_kses_post(
				sprintf(
					__( 'Provided by <a %1$s>%2$s</a>', 'ecwid-shopping-cart' ),
					'href="https://www.ecwid.com?partner=wporg" target="_blank"',
					'ecwid.com'
				)
			);
			?>
		</div>
		<?php } ?>
	</div>
</div>
