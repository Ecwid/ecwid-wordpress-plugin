
<?php
/*
<div class="wrap ecwid-importer state-<?php echo $this->_is_token_ok() ? 'woo-initial' : 'no-token'; ?>">
    <h1><?php echo sprintf( __( 'Import your products from WooCommerce to %s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></h1>

    <p><?php echo sprintf( __( 'This import will copy your WooCommerce products and categories to your %s store.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></p>

    <?php if ( !Ecwid_Config::is_wl() ): ?>
    <p><?php echo sprintf( __( '<b>Important note:</b> import creates new products.  please mind the maximum number of products and categories you can add to your store. This import tool will automatically stop when you reach the limit. To learn the current store limit or increase it, please see the "<a %s>Billing & Plans</a>" page in your store control panel. ', 'ecwid-shopping-cart' ), 'href="admin.php?page=ec-store-admin-billing"' ); ?></p>
    <?php endif; ?>
    
    <h2><?php _e( 'Import summary.', 'ecwid-shopping-cart' ); ?></h2>
    <p>
		<?php
			_e( 'Your WooCommerce store has ', 'ecwid-shopping-cart' );
            echo $this->_get_products_categories_message(
                Ecwid_Importer::count_woo_products(),
                Ecwid_Importer::count_woo_categories()
            );
		?>
    </p>
    <p>
		<?php
		printf(
			__( 'Your %s store has ', 'ecwid-shopping-cart' ),
			Ecwid_Config::get_brand()
		);
		echo $this->_get_products_categories_message(
			Ecwid_Importer::count_ecwid_products(),
			Ecwid_Importer::count_ecwid_categories()
        );		
		?>
    </p>
    <p>
		<?php
		echo sprintf(
			__( 'After import, your %s store will have ', 'ecwid-shopping-cart' ),
			Ecwid_Config::get_brand()
		);
		echo $this->_get_products_categories_message(
			Ecwid_Importer::count_ecwid_products() + Ecwid_Importer::count_woo_products(),
			Ecwid_Importer::count_ecwid_categories() + Ecwid_Importer::count_woo_categories()
		);
		?>
    </p>

	<?php if ( count( Ecwid_Importer::get_ecwid_demo_products() ) > 0 && Ecwid_Importer::count_ecwid_demo_products() < Ecwid_Importer::count_ecwid_products() ): ?>
    <h2><?php _e( 'Import settings.', 'ecwid-shopping-cart' ); ?></h2>
    <p>
		<label><input type="checkbox" class="import-settings" name="<?php echo Ecwid_Importer::SETTING_DELETE_DEMO; ?>"><?php _e( 'Remove demo products', 'ecwid-shopping-cart' ); ?></label>
    </p>
    <?php endif; ?>

	<?php if ( Ecwid_Importer::count_ecwid_demo_products() < Ecwid_Importer::count_ecwid_products() ): ?>
    <p>
        <label><input type="checkbox" class="import-settings" name="<?php echo Ecwid_Importer::SETTING_UPDATE_BY_SKU; ?>"><?php _e( 'Overwrite existing products with matching SKU', 'ecwid-shopping-cart' ); ?></label>
    </p>
	<?php endif; ?>

    <div class="importer-state importer-state-woo-initial">
		<?php require __DIR__ . '/woo-initial.tpl.php'; ?>
	</div>

    <div class="importer-state importer-state-no-token">
		<?php require __DIR__ . '/import-no-token.tpl.php'; ?>
    </div>

    <div class="importer-state importer-state-woo-in-progress">
		<?php require __DIR__ . '/woo-in-progress.tpl.php'; ?>
	</div>

	<div class="importer-state importer-state-woo-complete">
		<?php require __DIR__ . '/woo-complete.tpl.php'; ?>
	</div>
</div>
*/?>

<script type='text/javascript'>//<![CDATA[
    jQuery(document.body).addClass('ecwid-no-padding');
    jQuery(document.body).css({
    	'font-size': '13px'
    });
    jQuery('#wpbody').css({
    	'background-color': 'rgb(240, 242, 244)'
    });
//]]>
</script>
<style type="text/css">
	.settings-page input[type=checkbox]:disabled:before { content: ''; }
</style>



<div class="settings-page">
	<div class="settings-page__header">
		<div class="settings-page__titles settings-page__titles--left">
			<h1 class="settings-page__title"><?php echo sprintf( __( 'Import your products from WooCommerce to %s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></h1>
			<div class="settings-page__subtitle"></div>
		</div>


		<div class="named-area">
			<div class="named-area__header">
				<div class="named-area__titles">
					<div class="named-area__title">Update your Catalog</div>
					<div class="named-area__subtitle">Description</div>
				</div>
			</div>
			<div class="named-area__body">

				<div class="a-card-stack">
					
					<div class="a-card a-card--normal">
						<div class="a-card__paddings">
							<div class="feature-element has-picture">
								<div class="feature-element__core">
									<div class="feature-element__data">
										<div class="feature-element__title"><?php echo sprintf( __( 'Import your products from WooCommerce to %s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></div>
										<div class="feature-element__content">
											<div class="feature-element__text">
												<p><?php echo sprintf( __( 'This import will copy your WooCommerce products and categories to your %s store.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></p>

												<?php if ( !Ecwid_Config::is_wl() ): ?>
											    <p><?php echo sprintf( __( '<b>Important note:</b> import creates new products. Please mind the maximum number of products and categories you can add to your store. This import tool will automatically stop when you reach the limit. To learn the current store limit or increase it, please see the <nobr><a %s>"Billing & Plans"</a></nobr> page in your store control panel. ', 'ecwid-shopping-cart' ), 'href="admin.php?page=ec-store-admin-billing"' ); ?></p>
											    <?php endif; ?>
											</div>

											<!-- No scope -->
											<div class="feature-element__action">
												<a class="btn btn-primary btn-medium" id="reconnect-button" href="admin.php?page=<?php echo self::PAGE_SLUG_WOO; ?>&action=reconnect"><?php _e( 'Connect', 'ecwid-shopping-cart' ); ?></a>
											</div>

											<!-- Import in default -->
											<div class="feature-element__action">
												<button type="button" class="btn btn-primary btn-medium" id="ecwid-importer-woo-go">
													<span><?php _e( 'Start import', 'ecwid-shopping-cart' ); ?></span>
												</button>
											</div>

											<!-- Import in progress -->
											<div class="feature-element__action">
												<button class="btn btn-default btn-medium btn-loading">
													<span><?php _e( 'Start import', 'ecwid-shopping-cart' ); ?></span>
												</button>
											</div>
										</div>
									</div>
									<div class="feature-element__picture">
										<?php require __DIR__ . '/import-picture-feature.svg'; ?>
									</div>
								</div>
							</div>
						</div>
					</div>

					<?php //if ( count( Ecwid_Importer::get_ecwid_demo_products() ) > 0 && Ecwid_Importer::count_ecwid_demo_products() < Ecwid_Importer::count_ecwid_products() ): ?>
					<div class="a-card a-card--compact">
						<div class="a-card__paddings">
							<div class="iconable-block iconable-block--hide-in-mobile">
								<div class="iconable-block__infographics">
									<span class="iconable-block__icon">
										<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve"><g><path d="M740.4,401.4c-46-25.4-92.5-49.9-139.1-74.2c-36.3-18.9-73.3-19.2-110-0.9c-22,11-38.8,27.5-50.4,49.9c116.5,62,232.2,123.6,348.7,185.6c2.2-5.1,4.4-9.3,6-13.7C816.7,491.3,794.5,431.2,740.4,401.4z"/><path d="M875.6,35.1c-9.2-12.8-21.9-20.4-37.2-23.8c-16.7-3.7-26.1,0.2-35,14.3c-53,84.6-106,169.2-159,253.8c-1.7,2.7-3.2,5.4-4.8,8.1c1,1.1,1.5,2,2.3,2.4c36.2,19.3,72.4,38.6,109.1,58.2c1.3-2.1,2-3.1,2.6-4.2c41.6-93.9,83.1-187.8,124.8-281.6C882.7,52.3,881.7,43.5,875.6,35.1z"/><path d="M754.3,597.7c7.1-18.1-1.8-38.5-19.9-45.6c-18-7.2-38.5,1.7-45.6,19.8c-3.7,9.4-80.7,208.2-18.2,347.6c-29.6-0.3-76.3-7.1-141.9-31.8c-12.2-38-20.9-97.3,5.7-166.9c0,0-63.7,72.8-74.3,137.5c-13.7-6.6-28-13.8-43-21.8c-15-8.1-28.9-16-42-23.8c48.1-44.6,73.5-137.9,73.5-137.9c-43.3,60.6-97.5,86.2-135.9,97c-57.1-41.1-88.6-76.3-105.1-100.6c150.8-25,273.9-199.1,279.7-207.4c11.1-15.9,7.2-37.9-8.7-48.9c-15.9-11.2-37.8-7.2-49,8.7c-1.4,1.9-137.8,193.7-271.7,179.5c-10.4-1.1-21,2.5-28.6,10c-7.5,7.5-11.3,17.9-10.2,28.5c1.3,12.4,19.5,125.3,264.8,256.9C522.1,972.6,615.5,990,672.6,990c44.3,0,66.9-10.4,71.7-13c9.4-4.9,16-13.8,18.1-24.3c2.1-10.4-0.7-21.2-7.5-29.4C668.7,819.5,753.5,599.9,754.3,597.7z"/></g></svg>
									</span>
								</div>
								<div class="iconable-block__content">
									<div class="status-block">
										<div class="status-block__central">
											<div class="status-block__header">
												<span class="status-block__title"><?php _e( 'Remove demo products', 'ecwid-shopping-cart' ); ?></span>
											</div>
											<div class="status-block__content">
												<p>Some description</p>
											</div>
										</div>
										<div class="status-block__primary-action">
											<label class="checkbox big">
												<input 
													type="checkbox" 
													value="on" 
													name="<?php echo Ecwid_Importer::SETTING_DELETE_DEMO; ?>"
												/>
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
					<?php //endif; ?>

					<?php //if ( Ecwid_Importer::count_ecwid_demo_products() < Ecwid_Importer::count_ecwid_products() ): ?>
					<div class="a-card a-card--compact">
						<div class="a-card__paddings">
							<div class="iconable-block iconable-block--hide-in-mobile">
								<div class="iconable-block__infographics">
									<span class="iconable-block__icon">
										<svg height="512pt" viewBox="0 0 512.00012 512" width="512pt" xmlns="http://www.w3.org/2000/svg"><path d="m92 106v-69c0-11.046875 8.953125-20 20-20s20 8.953125 20 20v49.710938c15.671875-21.585938 35.359375-40.105469 58.125-54.460938 33.460938-21.097656 72.144531-32.25 111.875-32.25 115.792969 0 210 94.207031 210 210 0 11.046875-8.953125 20-20 20s-20-8.953125-20-20c0-93.738281-76.261719-170-170-170-57.789062 0-110.382812 28.667969-141.679688 76h50.679688c11.046875 0 20 8.953125 20 20s-8.953125 20-20 20h-69c-27.570312 0-50-22.429688-50-50zm370 158h-69c-11.046875 0-20 8.953125-20 20s8.953125 20 20 20h50.679688c-31.296876 47.332031-83.890626 76-141.679688 76-93.738281 0-170-76.261719-170-170 0-11.046875-8.953125-20-20-20s-20 8.953125-20 20c0 50.945312 18.238281 97.703125 48.523438 134.105469l-134.617188 133.703125c-7.835938 7.785156-7.878906 20.449218-.097656 28.285156 3.910156 3.9375 9.050781 5.90625 14.191406 5.90625 5.09375 0 10.191406-1.9375 14.09375-5.808594l134.800781-133.886718c36.257813 29.789062 82.628907 47.695312 133.105469 47.695312 39.730469 0 78.414062-11.152344 111.875-32.25 22.765625-14.355469 42.453125-32.875 58.125-54.460938v49.710938c0 11.046875 8.953125 20 20 20s20-8.953125 20-20v-69c0-27.570312-22.429688-50-50-50zm0 0"/></svg>
									</span>
								</div>
								<div class="iconable-block__content">
									<div class="status-block">
										<div class="status-block__central">
											<div class="status-block__header">
												<span class="status-block__title"><?php _e( 'Overwrite existing products with matching SKU', 'ecwid-shopping-cart' ); ?></span>
											</div>
											<div class="status-block__content">
												<p>Some description</p>
											</div>
										</div>
										<div class="status-block__primary-action">
											<label class="checkbox big">
												<input 
													type="checkbox" 
													value="on" 
													name="<?php echo Ecwid_Importer::SETTING_UPDATE_BY_SKU; ?>"
												/>
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
					<?php //endif; ?>


				</div>
			</div>
		</div>

		<!-- Import result -->
		<div class="named-area">
			<div class="named-area__header">
				<div class="named-area__titles">
					<div class="named-area__title"><?php _e( 'Current status', 'ecwid-shopping-cart' ); ?></div>
					<div class="named-area__subtitle"></div>
				</div>
			</div>
			<div class="named-area__body">
				<div class="a-card-stack">

					<div class="a-card a-card--normal">
						<div class="a-card__paddings">
							<ul class="titled-items-list">
								<li class="titled-items-list__item titled-item">
									<div class="titled-item__title"><?php _e( 'Copying products and categories', 'ecwid-shopping-cart' ); ?></div>
									<div class="titled-item__content">
										<?php echo sprintf( 
									            __( 'Importing %s of %s items', 'ecwid-shopping-cart' ), 
									            '<span id="import-progress-current">0</span>', 
									            '<span id="import-progress-total">' . (Ecwid_Importer::count_woo_products() + Ecwid_Importer::count_woo_categories()) . '</span>' ); 
									    ?>
									</div>
								</li>
							</ul>
						</div>
					</div>

					<div class="a-card a-card--compact a-card--error">
						<div class="a-card__paddings">
							<div class="iconable-block iconable-block--hide-in-mobile iconable-block--error">
								<div class="iconable-block__infographics">
									<span class="iconable-block__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70" focusable="false"><path d="M34.5 67C16.58 67 2 52.42 2 34.5S16.58 2 34.5 2 67 16.58 67 34.5 52.42 67 34.5 67zm0-62C18.23 5 5 18.23 5 34.5S18.23 64 34.5 64 64 50.77 64 34.5 50.77 5 34.5 5z"></path><path d="M34.5 49c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5zM35.5 38.57h-2l-1-14c0-1.17.89-2.07 2-2.07s2 .9 2 2l-1 14.07z"></path></svg></span>
								</div>
								<div class="iconable-block__content">
									<div class="cta-block">
										<div class="cta-block__central">
											<div class="cta-block__title">Reached the product count limit </div>
											<div class="cta-block__content"><?php echo sprintf( __ ( 'Not all products have been copied to %1$s because you reached the product count limit on your pricing plan in %1$s. If you want to import more products, please consider <a %2$s>upgrading your %1$s plan</a>.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand(), 'href="' . $this->_get_billing_page_url() .'"' ); ?></div>
										</div>
									</div>
									<span class="alert-close-mark">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M15.6 15.5c-.53.53-1.38.53-1.91 0L8.05 9.87 2.31 15.6c-.53.53-1.38.53-1.91 0s-.53-1.38 0-1.9l5.65-5.64L.4 2.4C-.13 1.87-.13 1.02.4.49s1.38-.53 1.91 0l5.64 5.63L13.69.39c.53-.53 1.38-.53 1.91 0s.53 1.38 0 1.91L9.94 7.94l5.66 5.65c.52.53.52 1.38 0 1.91z"></path></svg>
									</span>
								</div>
							</div>
						</div>
					</div>

					<div class="a-card a-card--compact a-card--success">
						<div class="a-card__paddings">
							<div class="iconable-block iconable-block--hide-in-mobile iconable-block--error">
								<div class="iconable-block__infographics"></div>
								<div class="iconable-block__content">
									<div class="cta-block">
										<div class="cta-block__central">
											<div class="cta-block__title"><?php _e( 'Import complete.', 'ecwid-shopping-cart' ); ?></div>
											<div class="cta-block__content">
												<ul>
												    <li>
												        <?php echo sprintf( __( 'Imported products: %s', 'ecwid-shopping-cart' ), '<span id="import-results-products">0</span>' ); ?>
												    </li>
												<?php if ( ecwid_is_paid_account() ): ?>     
												    <li>
														<?php echo sprintf( __( 'Imported categories: %s', 'ecwid-shopping-cart' ), '<span id="import-results-categories">0</span>' ); ?>
												    </li>
												</ul>
												<?php endif; ?>
											</div>
										</div>
										<div class="cta-block__cta">
											<a class="btn btn-primary btn-medium" href="admin.php?page=<?php echo Ecwid_Admin::ADMIN_SLUG; ?>-admin-products">
        										<?php echo sprintf( __('Go to your %s Products', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?>
											</a>
										</div>
									</div>
									<span class="alert-close-mark">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M15.6 15.5c-.53.53-1.38.53-1.91 0L8.05 9.87 2.31 15.6c-.53.53-1.38.53-1.91 0s-.53-1.38 0-1.9l5.65-5.64L.4 2.4C-.13 1.87-.13 1.02.4.49s1.38-.53 1.91 0l5.64 5.63L13.69.39c.53-.53 1.38-.53 1.91 0s.53 1.38 0 1.91L9.94 7.94l5.66 5.65c.52.53.52 1.38 0 1.91z"></path></svg>
									</span>
								</div>
							</div>
						</div>
					</div>


				</div>
			</div>
		</div>


		<!-- Statistics -->
		<div class="named-area">
			<div class="named-area__header">
				<div class="named-area__titles">
					<div class="named-area__title"><?php _e( 'Import summary', 'ecwid-shopping-cart' ); ?></div>
					<div class="named-area__subtitle"></div>
				</div>
			</div>
			<div class="named-area__body">
				<div class="a-card a-card--normal">
					<div class="a-card__paddings">
						<ul class="titled-items-list">
							<li class="titled-items-list__item titled-item">
								<div class="titled-item__title"></div>
								<div class="titled-item__content">
									<?php
									_e( 'Your WooCommerce store has ', 'ecwid-shopping-cart' );
						            echo $this->_get_products_categories_message(
						                Ecwid_Importer::count_woo_products(),
						                Ecwid_Importer::count_woo_categories()
						            );
									?>
								</div>
							</li>
							<li class="titled-items-list__item titled-item">
								<div class="titled-item__title"></div>
								<div class="titled-item__content">
									<?php
									printf(
										__( 'Your %s store has ', 'ecwid-shopping-cart' ),
										Ecwid_Config::get_brand()
									);
									echo $this->_get_products_categories_message(
										Ecwid_Importer::count_ecwid_products(),
										Ecwid_Importer::count_ecwid_categories()
							        );		
									?>
								</div>
							</li>
							<li class="titled-items-list__item titled-item">
								<div class="titled-item__title"></div>
								<div class="titled-item__content">
									<?php
									echo sprintf(
										__( 'After import, your %s store will have ', 'ecwid-shopping-cart' ),
										Ecwid_Config::get_brand()
									);
									echo $this->_get_products_categories_message(
										Ecwid_Importer::count_ecwid_products() + Ecwid_Importer::count_woo_products(),
										Ecwid_Importer::count_ecwid_categories() + Ecwid_Importer::count_woo_categories()
									);
									?>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>

<?php
/*
<div class="a-card a-card--normal">
	<div class="a-card__paddings">
		<div class="cta-block">
			<div class="cta-block__central">
				<div class="cta-block__title"><?php _e( 'Import summary', 'ecwid-shopping-cart' ); ?></div>
				<div class="cta-block__content">

				    <div>
				    	<?php
						_e( 'Your WooCommerce store has ', 'ecwid-shopping-cart' );
			            echo $this->_get_products_categories_message(
			                Ecwid_Importer::count_woo_products(),
			                Ecwid_Importer::count_woo_categories()
			            );
						?>
					</div>
				
					<div>
						<?php
						printf(
							__( 'Your %s store has ', 'ecwid-shopping-cart' ),
							Ecwid_Config::get_brand()
						);
						echo $this->_get_products_categories_message(
							Ecwid_Importer::count_ecwid_products(),
							Ecwid_Importer::count_ecwid_categories()
				        );		
						?>
				    </div>

					<div>
						<?php
						echo sprintf(
							__( 'After import, your %s store will have ', 'ecwid-shopping-cart' ),
							Ecwid_Config::get_brand()
						);
						echo $this->_get_products_categories_message(
							Ecwid_Importer::count_ecwid_products() + Ecwid_Importer::count_woo_products(),
							Ecwid_Importer::count_ecwid_categories() + Ecwid_Importer::count_woo_categories()
						);
						?>
					</div>

				</div>
			</div>
		</div>
	</div>
</div>
*/
?>