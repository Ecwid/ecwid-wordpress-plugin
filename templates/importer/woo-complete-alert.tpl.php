<div class="a-card a-card--compact" id="ec-importer-alert" style="display: none;">
	<div class="a-card__paddings">

		<div class="iconable-block iconable-block--hide-in-mobile">
			<div class="iconable-block__infographics">

				<span class="iconable-block__icon" data-ec-importer-alert="warning"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70" focusable="false"><path d="M34.5 67C16.58 67 2 52.42 2 34.5S16.58 2 34.5 2 67 16.58 67 34.5 52.42 67 34.5 67zm0-62C18.23 5 5 18.23 5 34.5S18.23 64 34.5 64 64 50.77 64 34.5 50.77 5 34.5 5z"></path><path d="M34.5 49c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5zM35.5 38.57h-2l-1-14c0-1.17.89-2.07 2-2.07s2 .9 2 2l-1 14.07z"></path></svg></span> 

				<span class="iconable-block__icon" data-ec-importer-alert="success"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"><path d="M34.5 67h-.13c-8.68-.03-16.83-3.45-22.94-9.61C5.32 51.23 1.97 43.06 2 34.38 2.07 16.52 16.65 2 34.5 2h.13c8.68.03 16.83 3.45 22.94 9.61 6.12 6.16 9.46 14.34 9.43 23.02C66.93 52.48 52.35 67 34.5 67zm0-62C18.3 5 5.06 18.18 5 34.39c-.03 7.88 3.01 15.3 8.56 20.89 5.55 5.59 12.95 8.69 20.83 8.72h.12c16.2 0 29.44-13.18 29.5-29.39.03-7.88-3.01-15.3-8.56-20.89C49.89 8.13 42.49 5.03 34.61 5h-.11z"></path><path d="M32.17 46.67l-10.7-10.08c-.6-.57-.63-1.52-.06-2.12.57-.6 1.52-.63 2.12-.06l8.41 7.92 14.42-16.81c.54-.63 1.49-.7 2.12-.16.63.54.7 1.49.16 2.12L32.17 46.67z"></path></svg></span>

			</div>
			<div class="iconable-block__content">
				<div class="cta-block">
					<div class="cta-block__central">
						<div class="cta-block__title"><?php _e( 'Import completed', 'ecwid-shopping-cart' ); ?></div>
						<div class="cta-block__content">
							<?php 
							echo sprintf( __( 'Imported <b>%s</b> products', 'ecwid-shopping-cart' ), '<span id="import-results-products">0</span>' );
							if ( ecwid_is_paid_account() ) {
								echo ", "; 
								echo sprintf( __( '<b>%s</b> categories', 'ecwid-shopping-cart' ), '<span id="import-results-categories">0</span>' );
							}
							?>
						</div>

						<div class="cta-block__content" data-ec-importer-alert="warning">
							<?php _e( 'Some of the items could not be imported.', 'ecwid-shopping-cart' ); ?>

							<span data-ec-importer-alert="limit">
							<?php
								echo sprintf( 
									__( 'Part of the products have not been copied to %1$s, because you reached the products limit on your pricing plan in %1$s. If you want to import more products, please consider <nobr><a %2$s>upgrading your %1$s plan.</a></nobr>', 'ecwid-shopping-cart' ),
									Ecwid_Config::get_brand(), 
									'href="' . $this->_get_billing_page_url() .'"'
								);
							?>
							</span>
						</div>

						<div class="cta-block__content" data-ec-importer-alert="warning">
							<?php 
							echo sprintf(
								__( 'Download <a href="%s">import log</a>', 'ecwid-shopping-cart' ),
								'admin-post.php?action=' . Ecwid_Import_Page::ACTION_GET_WOO_IMPORT_LOG
							);
							?>
						</div>

					</div>
					<div class="cta-block__cta">
						<a class="btn btn-primary btn-medium" href="admin.php?page=<?php echo Ecwid_Admin::ADMIN_SLUG; ?>-admin-products">
							<?php echo sprintf( __('Go to Your %s Products', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>