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
				Добавьте магазин на сайт
			</h2>
			<div class="ec-subheading">
				<p>
					Создайте новый магазин или подключите существующий, если у вас уже есть аккаунт в Ecwid. Мы подскажем, как добавить товары, настроить оплату и опубликовать магазин.
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
			Сервис предоставлен <a href="https://www.ecwid.ru" target="_blank">ecwid.ru</a>
		</div>
		<?php } ?>
	</div>
</div>