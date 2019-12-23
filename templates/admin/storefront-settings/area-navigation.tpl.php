<div class="named-area">
	<div class="named-area__header">
		<div class="named-area__titles"><div class="named-area__title"><?php _e( 'Navigation', 'ecwid-shopping-cart'); ?></div></div>
		<div class="named-area__description"><?php _e( 'Site menu, extra widgets, store page link and other navigation tools.', 'ecwid-shopping-cart'); ?></div>
	</div>
	<div class="named-area__body">

		<div <?php Ecwid_Admin_Storefront_Page::show_draft_attribute( $page_status );?> class="a-card a-card--compact">
			<div class="a-card__paddings">
				<div class="iconable-block iconable-block--hide-in-mobile">
					<div class="iconable-block__infographics">
						<span class="iconable-block__icon">
							<?php
							ecwid_embed_svg( 'admin-storefront/icons/store-on-home-page' );
							?>
						</span>
					</div>
					<div class="iconable-block__content">
						<div class="status-block">
							<div class="status-block__central">
								<div class="status-block__header">
									<span class="status-block__title"><?php _e( 'Show your store on the home page', 'ecwid-shopping-cart'); ?></span>
									<span class="status-block__edit">Edit</span>
								</div>
								<div class="status-block__content">

									<?php
									if( $page_status == 'draft' ) {
										require __DIR__ . '/draft-message.tpl.php';
									}
									?>

									<p>
									<?php
									echo sprintf(
										__( 'Feature your store page on the website home page to maket it more prominent. You can also tweak the site home page settings on the <a href="%s" target="_blank">WP Settings -> Reading</a>', 'ecwid-shopping-cart'),
										admin_url( 'options-reading.php' )
									);
									?>
									</p>
								</div>
							</div>
							<div class="status-block__actions-dropdown"></div>
							<div class="status-block__primary-action">
								<label class="checkbox big">
									<input name="" type="checkbox" <?php if($store_on_front){?>checked=""<?php }?> data-storefront-checkbox="store_on_front">
									<div data-on="enabled" data-off="disabled">
										<div></div>
									</div>
									<span class="checkbox__on-text-placeholder">enabled</span>
									<span class="checkbox__off-text-placeholder">disabled</span>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php if( Ecwid_Seo_Links::is_feature_available() ) {?>

			<div class="a-card a-card--normal" data-storefront-card="change-link-form" style="display: none;">
				<div class="a-card__paddings">
					<div class="form-area">
						<div class="form-area__title"><?php _e( 'Customize store page link', 'ecwid-shopping-cart'); ?></div>
						<div class="form-area__content">
							<p><?php _e( 'Set the slug of the page. This address is displayed on customer-facing invoices and emails.', 'ecwid-shopping-cart' ); ?></p>
							<div class="fieldsets-batch">
								<div class="fieldset">
									<div class="field field--medium">
										<label class="field__label"><?php _e( 'URL Slug', 'ecwid-shopping-cart' ); ?></label>
										<input type="text" class="field__input" maxlength="64" name="post_name" value="<?php echo $page_slug; ?>">
										<div class="field__placeholder"><?php _e( 'URL Slug', 'ecwid-shopping-cart' ); ?></div>
									</div>
									<div class="field__error"></div>
								</div>
							</div>
						</div>
						<div class="form-area__action">
							<button type="button" class="btn btn-primary btn-medium" data-storefront-save-slug><?php _e( 'Save', 'ecwid-shopping-cart' ); ?></button>
							<button type="button" class="btn btn-link btn-medium" data-storefront-show-card="change-link"><?php _e( 'Cancel', 'ecwid-shopping-cart' ); ?></button>
						</div>
					</div>
				</div>
			</div>

			<div <?php Ecwid_Admin_Storefront_Page::show_draft_attribute( $page_status );?> class="a-card a-card--compact" data-storefront-card="change-link">
				<div class="a-card__paddings">
					<div class="iconable-block iconable-block--hide-in-mobile">
						<div class="iconable-block__infographics">
							<span class="iconable-block__icon">
								<?php
								ecwid_embed_svg( 'admin-storefront/icons/customize-page-link' );
								?>
							</span>
						</div>
						<div class="iconable-block__content">
							<div class="status-block">
								<div class="status-block__central">
									<div class="status-block__header">
										<span class="status-block__title"><?php _e( 'Customize store page link', 'ecwid-shopping-cart'); ?></span>
										<span class="status-block__edit">Edit</span>
									</div>
									<div class="status-block__content">

										<?php
										if( $page_status == 'draft' ) {
											require __DIR__ . '/draft-message.tpl.php';
										} else {
										?>

										<p><b>
											<?php echo sprintf(
												__( 'Current store link: %s', 'ecwid-shopping-cart' ),
												$page_link
											);
											?>
										</b></p>
										<?php } ?>

										<p>
										<?php
											_e( 'Make your store URL short and readable so your customers and search engines can remember it. For example: "/shop" or "/products". You can adjust it in the page editor in the Permalink section.', 'ecwid-shopping-cart');
										?>
										</p>
									</div>
								</div>
								<div class="status-block__actions-dropdown"></div>
								<div class="status-block__primary-action">
									<a href="<?php echo $page_edit_link; ?>" target="_blank" class="btn btn-default btn-medium" data-storefront-show-card="change-link-form"><?php _e( 'Change link', 'ecwid-shopping-cart'); ?></a>
								</div>

							</div>
						</div>
					</div>
				</div>
			</div>
		<?php } ?>


		<div <?php Ecwid_Admin_Storefront_Page::show_draft_attribute( $page_status );?> class="a-card a-card--compact">
			<div class="a-card__paddings">
				<div class="iconable-block iconable-block--hide-in-mobile">
					<div class="iconable-block__infographics">
						<span class="iconable-block__icon">
							<?php
							ecwid_embed_svg( 'admin-storefront/icons/add-page-to-menu' );
							?>
						</span>
					</div>
					<div class="iconable-block__content">
						<div class="status-block">
							<div class="status-block__central">
								<div class="status-block__header">
									<span class="status-block__title"><?php _e( 'Add store page to the site menu', 'ecwid-shopping-cart'); ?></span>
									<span class="status-block__edit">Edit</span>
								</div>
								<div class="status-block__content">
									<?php
									if( $page_status == 'draft' ) {
										require __DIR__ . '/draft-message.tpl.php';
									}
									?>
									
									<p><?php _e( 'Make sure your store is accessible from the site menu so your customers can easily find it.', 'ecwid-shopping-cart'); ?></p>
								</div>
							</div>
							<div class="status-block__actions-dropdown"></div>
							<div class="status-block__primary-action">
								<a href="<?php echo admin_url('nav-menus.php'); ?>" target="_blank" class="btn btn-default btn-medium"><?php _e( 'Add store page to the menu', 'ecwid-shopping-cart'); ?></a>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>


		<div class="a-card a-card--compact">
			<div class="a-card__paddings">
				<div class="iconable-block iconable-block--hide-in-mobile">
					<div class="iconable-block__infographics">
						<span class="iconable-block__icon">
							<?php
							ecwid_embed_svg( 'admin-storefront/icons/feature-sidebar' );
							?>
						</span>
					</div>
					<div class="iconable-block__content">
						<div class="status-block">
							<div class="status-block__central">
								<div class="status-block__header">
									<span class="status-block__title"><?php _e( 'Feature your products in sidebar', 'ecwid-shopping-cart'); ?></span>
									<span class="status-block__edit">Edit</span>
								</div>
								<div class="status-block__content">
									<p><?php _e( 'Highlight best sellers or new products in the sidebar', 'ecwid-shopping-cart'); ?></p>
								</div>
							</div>
							<div class="status-block__actions-dropdown"></div>
							<div class="status-block__primary-action">
								<a href="<?php echo admin_url( 'widgets.php' );?>" target="_blank" class="btn btn-default btn-medium"><?php _e( 'Manage sidebar widgets', 'ecwid-shopping-cart'); ?></a>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>

		<?php if( class_exists( 'Ecwid_Floating_Minicart' ) ) {?>
		<div class="a-card a-card--compact">
			<div class="a-card__paddings">
				<div class="iconable-block iconable-block--hide-in-mobile">
					<div class="iconable-block__infographics">
						<span class="iconable-block__icon">
							<?php
							ecwid_embed_svg( 'admin-storefront/icons/show-shopping-cart-icon' );
							?>
						</span>
					</div>
					<div class="iconable-block__content">
						<div class="status-block">
							<div class="status-block__central">
								<div class="status-block__header">
									<span class="status-block__title"><?php _e( 'Display shopping cart icon on the site pages', 'ecwid-shopping-cart'); ?></span>
									<span class="status-block__edit">Edit</span>
								</div>
								<div class="status-block__content">
									<p><?php 
									echo sprintf(
										__( 'The shopping cart icon helps your customers find their shopping cart and proceed to checkout. Additionaly, you can <a href="%s" target="_blank">adjust the icon appearance</a>.', 'ecwid-shopping-cart'),
										$customizer_minicart_link
									);
									?></p>
								</div>
							</div>
							<div class="status-block__actions-dropdown"></div>
							<div class="status-block__primary-action">
								<label class="checkbox big">
									<input name="" type="checkbox" <?php if(!$minicart_hide) {?>checked=""<?php } ?> data-storefront-checkbox="display_cart_icon">
									<div data-on="enabled" data-off="disabled">
										<div></div>
									</div>
									<span class="checkbox__on-text-placeholder">enabled</span>
									<span class="checkbox__off-text-placeholder">disabled</span>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>

	</div>
</div>