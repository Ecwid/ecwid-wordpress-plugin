<div class="named-area" data-ec-state="publish draft">
	<div class="named-area__header">
		<div class="named-area__titles"><div class="named-area__title"><?php esc_html_e( 'Navigation', 'ecwid-shopping-cart' ); ?></div></div>
		<div class="named-area__description"><?php esc_html_e( 'Help customers find your store on the website.', 'ecwid-shopping-cart' ); ?></div>
	</div>
	<div class="named-area__body">

		<div class="a-card a-card--compact" data-ec-storefront-disabled-card="draft">
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
						<div class="cta-block">
							<div class="cta-block__central">
								<div class="cta-block__title"><?php esc_html_e( 'Show your store on the home page', 'ecwid-shopping-cart' ); ?></div>
								<div class="cta-block__content">
									<?php
									require self::$templates_dir . '/draft-message.php';
									?>

									<div>
									<?php
									echo wp_kses_post(
										sprintf(
											__( 'Add your storefront to the website home page to make it more prominent. You can also tweak the site home page settings in <a href="%s" target="_blank">WordPress Settings > Reading</a>', 'ecwid-shopping-cart' ),
											admin_url( 'options-reading.php' )
										)
									);
									?>
									</div>
								</div>
							</div>
							<div class="cta-block__cta">
								<label class="checkbox big">
									<input name="" type="checkbox" 
									<?php
									if ( $store_on_front ) {
										?>
										checked=""<?php } ?> data-storefront-checkbox="store_on_front">
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

		<?php if ( Ecwid_Seo_Links::is_feature_available() ) { ?>

			<div class="a-card a-card--normal" data-storefront-card="change-link-form" style="display: none;">
				<div class="a-card__paddings">
					<div class="form-area">
						<div class="form-area__title"><?php esc_html_e( 'Customize store page address', 'ecwid-shopping-cart' ); ?></div>
						<div class="form-area__content">
							<div class="fieldsets-batch">
							<?php
								esc_html_e( 'A slug is the last part of a URL. You can create a custom slug for your store page. It’s better to keep it short since customers and search engines prefer short URLs. For example, use "/shop" or "/products".', 'ecwid-shopping-cart' );
							?>
							</div>
							<div class="fieldsets-batch">
								<div class="fieldset fieldset--no-label fieldset--with-prefix">
									<div class="fieldset__field-wrapper">
										<div class="field field--medium">
											<label class="field__label"></label>
											<input type="text" class="field__input" maxlength="64" name="post_name" value="<?php echo esc_attr( $page_slug ); ?>" data-ec-store-slug-input="1">
											<div class="field__placeholder"><?php esc_html_e( 'URL Slug', 'ecwid-shopping-cart' ); ?></div>
										</div>
										<div class="fieldset__field-prefix"><?php echo esc_url( get_site_url() ); ?>/</div>
									</div>
									<div class="field__error" aria-hidden="true" style="display: none;"></div>
								</div>
							</div>
						</div>
						<div class="form-area__action">
							<button type="button" class="btn btn-primary btn-medium" data-storefront-save-slug><?php esc_html_e( 'Save', 'ecwid-shopping-cart' ); ?></button>
							<button type="button" class="btn btn-link btn-medium" data-storefront-show-card="change-link"><?php esc_html_e( 'Cancel', 'ecwid-shopping-cart' ); ?></button>
						</div>
					</div>
				</div>
			</div>

			<div class="a-card a-card--compact" data-storefront-card="change-link" data-ec-storefront-disabled-card="draft">
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
							<div class="cta-block">
								<div class="cta-block__central">
									<div class="cta-block__title"><?php esc_html_e( 'Customize store page address', 'ecwid-shopping-cart' ); ?></div>

									<div class="cta-block__content" data-ec-state="publish">
										<b>
										<?php
										echo wp_kses_post(
											sprintf(
												__( 'Current URL slug: /<span data-ec-store-slug>%s</span>', 'ecwid-shopping-cart' ),
												$page_slug
											)
										);
										?>
										</b>
									</div>

									<div class="cta-block__content">
										<?php
										require self::$templates_dir . '/draft-message.php';
										?>
										<?php
											esc_html_e( 'A slug is the last part of a URL. You can create a custom slug for your store page. It’s better to keep it short since customers and search engines prefer short URLs. For example, use "/shop" or "/products".', 'ecwid-shopping-cart' );
										?>
									</div>
								</div>
								<div class="cta-block__cta">
									<a href="<?php echo esc_url( $page_edit_link ); ?>" target="_blank" class="btn btn-default btn-medium" data-storefront-show-card="change-link-form"><?php esc_html_e( 'Edit URL Slug', 'ecwid-shopping-cart' ); ?></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}//end if
		?>


		<div class="a-card a-card--compact" data-ec-storefront-disabled-card="draft">
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
						<div class="cta-block">
							<div class="cta-block__central">
								<div class="cta-block__title"><?php esc_html_e( 'Add store page to the site menu', 'ecwid-shopping-cart' ); ?></div>
								<div class="cta-block__content">
									<?php
									require self::$templates_dir . '/draft-message.php';
									?>
									
									<div><?php esc_html_e( 'Make your store accessible from the site menu so your customers can easily find it.', 'ecwid-shopping-cart' ); ?></div>
								</div>
							</div>
							<div class="cta-block__cta">
								<a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" target="_blank" class="btn btn-default btn-medium"><?php esc_html_e( 'Add Store Page to Menu', 'ecwid-shopping-cart' ); ?></a>
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
						<div class="cta-block">
							<div class="cta-block__central">
								<div class="cta-block__title"><?php esc_html_e( 'Feature your products in the sidebar', 'ecwid-shopping-cart' ); ?></div>
								<div class="cta-block__content">
									<?php esc_html_e( 'Highlight your best sellers or new products in the website sidebar.', 'ecwid-shopping-cart' ); ?>
								</div>
							</div>
							<div class="cta-block__cta">
								<a href="<?php echo esc_url( admin_url( 'widgets.php?highlight-ec-widgets=1' ) ); ?>" target="_blank" class="btn btn-default btn-medium"><?php esc_html_e( 'Manage Sidebar', 'ecwid-shopping-cart' ); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php if ( class_exists( 'Ecwid_Floating_Minicart' ) ) { ?>
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
						<div class="cta-block">
							<div class="cta-block__central">
								<div class="cta-block__title"><?php esc_html_e( 'Display the shopping cart icon on site pages', 'ecwid-shopping-cart' ); ?></div>
								<div class="cta-block__content">
									<?php
									echo wp_kses_post(
										sprintf(
											__( 'The shopping cart icon shows the number of items in the cart and helps customers proceed to the checkout. Additionally, you can <a href="%s" target="_blank">adjust the cart icon appearance</a>.', 'ecwid-shopping-cart' ),
											$customizer_minicart_link
										)
									);
									?>
								</div>
							</div>
							<div class="cta-block__cta">
								<label class="checkbox big">
									<input name="" type="checkbox" 
										<?php if ( ! $minicart_hide ) { ?>
											checked=""
										<?php } ?>
										data-storefront-checkbox="display_cart_icon">
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
			<?php
		}//end if
		?>

		<div class="a-card a-card--compact" id="ec-store-slugs-without-ids">
			<div class="a-card__paddings">
				<div class="iconable-block iconable-block--hide-in-mobile">
					<div class="iconable-block__infographics">
						<span class="iconable-block__icon">
							<?php
							ecwid_embed_svg( 'admin-storefront/icons/slugs-wihtout-ids' );
							?>
						</span>
					</div>
					<div class="iconable-block__content">
						<div class="cta-block">
							<div class="cta-block__central">
								<div class="cta-block__title">
                                    <?php 
                                    if( Ecwid_Seo_Links::is_slugs_editor_available() ) {
                                        esc_html_e( 'Customize URL slugs for products and categories', 'ecwid-shopping-cart' );
                                    } else {
                                        esc_html_e( 'Set URL slugs without IDs for products and categories', 'ecwid-shopping-cart' );
                                    }
                                    ?>
                                </div>
								<div class="cta-block__content">
                                    <?php 
                                    if( Ecwid_Seo_Links::is_slugs_editor_available() ) {
                                        esc_html_e( 'Remove IDs from URL slugs in products and categories to boost SEO and create a more user-friendly customer experience. If you customize slugs in your store’s control panel, this setting will display them. Once enabled, old slugs will automatically redirect to the new ones.', 'ecwid-shopping-cart' );
                                    } else {
                                        esc_html_e( 'Remove IDs from URL slugs in products and categories to boost SEO and create a more user-friendly customer experience. Once enabled, previous slugs will automatically redirect to the new ones.   ', 'ecwid-shopping-cart' );
                                    }
                                    ?>
								</div>
							</div>
							<div class="cta-block__cta">
								<label class="checkbox big">
									<input name="" type="checkbox" 
										<?php if ( $slugs_without_ids ) { ?>
											checked=""
										<?php } ?>
										data-storefront-checkbox="slugs_without_ids">
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

	</div>
</div>
