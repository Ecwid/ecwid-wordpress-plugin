
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
	.custom-row { display: flex; flex-wrap: wrap; }
	.custom-row .custom-row__column { flex-grow: 1; width: 50%; }
	.custom-row .custom-row__column:nth-child(2) { padding-left: 16px; }

	.sync-block {
		display: -webkit-box;
	    display: -ms-flexbox;
	    display: flex;
	    margin-bottom: -8px;
	}
	.sync-block .preloader-circle {
	    width: 32px;
	    height: 32px;
	    margin-top: 6px;
	}
	.sync-block svg {
	    color: #99a9b7;
	}
	.sync-block .titled-item {
	    padding-left: 8px;
	}
</style>
<?php
// временная штука для удобства переключения экранов
$steps = array(
	1 => 'no_scope',
	'default',
	'settings',
	'process',
	'results'
);
$step = $steps[1];

if( isset($_GET['step']) ) {
	$step = $steps[ $_GET['step'] ];
}
?>



<div class="settings-page">
	<div class="settings-page__header">
		<div class="settings-page__titles settings-page__titles--left">
			<h1 class="settings-page__title"><?php echo sprintf( __( 'Import your products from WooCommerce to %s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></h1>
			<div class="settings-page__subtitle"></div>
		</div>

		<?php if ( !Ecwid_Config::is_wl() ): ?>
		<div class="a-card a-card--compact a-card--warning">
			<div class="a-card__paddings">
				<div class="cta-block">
					<div class="cta-block__central">
						<div class="cta-block__title">Important note</div>
						<div class="cta-block__content"><?php echo sprintf( __( 'Import creates new products. Please mind the maximum number of products and categories you can add to your store. This import tool will automatically stop when you reach the limit. To learn the current store limit or increase it, please see the <nobr><a %s>"Billing & Plans"</a></nobr> page in your store control panel. ', 'ecwid-shopping-cart' ), 'href="admin.php?page=ec-store-admin-billing"' ); ?></div>
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<div class="named-area">
			<div class="named-area__header">
				<div class="named-area__titles">
					<div class="named-area__title">Update your Catalog</div>
					<div class="named-area__subtitle">Description</div>
				</div>
			</div>
			<div class="named-area__body">

				<div class="a-card-stack">


					<!-- Results and errors -->
					<?php if( isset($_GET['results']) ) {?>
					<div class="a-card a-card--normal a-card--success">
						<div class="a-card__paddings">
							<div class="cta-block">
								<div class="cta-block__central">
									<div class="cta-block__title"><?php _e( 'Import complete', 'ecwid-shopping-cart' ); ?></div>
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
								</div>
							</div>
						</div>
					</div>

					<div class="a-card a-card--normal a-card--error">
						<div class="a-card__paddings">
							<div class="collapsible">
								<div class="collapsible__header"><?php _e( 'Some of the items could not be imported.', 'ecwid-shopping-cart' ); ?></div>
								<div class="collapsible__body">

									<div class="errors">
									    <a class="btn-details"><?php _e( 'Details...', 'ecwid-shopping-cart' ); ?></a>
									    <div class="details" id="fancy-errors"></div>
									    <pre class="details"></pre>
									</div>

								</div>
							</div>
						</div>
					</div>
					<?php } ?>
					
					<div class="a-card a-card--normal">
						<div class="a-card__paddings">
							<div class="feature-element has-picture">
								<div class="feature-element__core">
									<div class="feature-element__data">


										<?php if( $step == 'no_scope' ){?>
											<div class="feature-element__title"><?php echo sprintf( __( 'Import your products from WooCommerce to %s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></div>
											<div class="feature-element__content">
												<div class="feature-element__text">
													<p><?php echo sprintf( __( 'This import will copy your WooCommerce products and categories to your %s store.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></p>
													<p>У вас нехватает прав, сделайте реконнект</p>
												</div>
												<div class="feature-element__action">
													<a class="btn btn-primary btn-medium" id="reconnect-button" href="admin.php?page=<?php echo self::PAGE_SLUG_WOO; ?>&action=reconnect"><?php _e( 'Connect', 'ecwid-shopping-cart' ); ?></a>
												</div>
											</div>
										<?php }?>

										<?php if( $step == 'default' ){?>
											<div class="feature-element__title"><?php echo sprintf( __( 'Import your products from WooCommerce to %s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></div>
											<div class="feature-element__content">
												<div class="feature-element__text">
													<p><?php echo sprintf( __( 'This import will copy your WooCommerce products and categories to your %s store.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></p>
												</div>
												<div class="feature-element__action">
													<button type="button" class="btn btn-primary btn-medium" id="ecwid-importer-woo-go">
														<span><?php _e( 'Get started', 'ecwid-shopping-cart' ); ?></span>
													</button>
												</div>
											</div>
										<?php }?>


										<?php if( $step == 'settings' ){?>
											<div class="feature-element__title"><?php _e( 'Import settings', 'ecwid-shopping-cart' ); ?></div>
											<div class="feature-element__content">
												<div class="feature-element__text">
													<p><?php echo sprintf( __( 'This import will copy your WooCommerce products and categories to your %s store.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></p>
													
													<?php //if ( count( Ecwid_Importer::get_ecwid_demo_products() ) > 0 && Ecwid_Importer::count_ecwid_demo_products() < Ecwid_Importer::count_ecwid_products() ): ?>
													<div class="custom-checkbox">
														<label>
															<input 
																class="custom-checkbox__input"
																type="checkbox" 
																value="on" 
																name="<?php echo Ecwid_Importer::SETTING_DELETE_DEMO; ?>"
															/>
															<span class="custom-checkbox__label"></span>
															<span><?php _e( 'Remove demo products', 'ecwid-shopping-cart' ); ?></span>
														</label>
													</div>
													<?php //endif; ?>

													<?php //if ( Ecwid_Importer::count_ecwid_demo_products() < Ecwid_Importer::count_ecwid_products() ): ?>
													<div class="custom-checkbox">
														<label>
															<input 
																class="custom-checkbox__input"
																type="checkbox" 
																value="on" 
																name="<?php echo Ecwid_Importer::SETTING_UPDATE_BY_SKU; ?>"
															/>
															<span class="custom-checkbox__label"></span>
															<span><?php _e( 'Overwrite existing products with matching SKU', 'ecwid-shopping-cart' ); ?></span>
														</label>
													</div>
													<?php //endif; ?>
													
												</div>

												<div class="feature-element__action">
													<button type="button" class="btn btn-primary btn-medium" id="ecwid-importer-woo-go">
														<span><?php _e( 'Start import', 'ecwid-shopping-cart' ); ?></span>
													</button>
												</div>
											</div>
										<?php }?>

										<?php if( $step == 'process' ){?>
											<div class="feature-element__title"><?php _e( 'Import is in Progress', 'ecwid-shopping-cart' ); ?></div>
											<div class="feature-element__content">
												<div class="feature-element__text">
													<p>
														<p><?php echo sprintf( __( 'This import will copy your WooCommerce products and categories to your %s store.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></p>
														
													</p>
													<?php
													/*
														<?php _e( 'Copying products and categories', 'ecwid-shopping-cart' ); ?>
														<?php echo sprintf( 
												            __( 'Importing %s of %s items', 'ecwid-shopping-cart' ), 
												            '<span id="import-progress-current">0</span>', 
												            '<span id="import-progress-total">' . (Ecwid_Importer::count_woo_products() + Ecwid_Importer::count_woo_categories()) . '</span>' ); 
												    */?>
												</div>

												<div class="feature-element__action">
													<div class="sync-block">
														<div class="preloader-circle">
															<svg xml:space="http://www.w3.org/2000/svg" viewBox="0 0 30 30"><path fill="none" stroke="currentColor" stroke-miterlimit="10" d="M15 3c6.63 0 12 5.37 12 12s-5.37 12-12 12S3 21.63 3 15 8.37 3 15 3c2.19 0 4.24.58 6 1.61"></path></svg>
														</div>
														<div class="titled-item titled-item--small">
															<div class="titled-item__title"><?php _e( 'Copying products and categories', 'ecwid-shopping-cart' ); ?></div>
															<div class="titled-item__content"><?php _e( "Don't close this page until the import is complete." )?></div>
														</div>
													</div>
												</div>

											</div>
										<?php }?>


									</div>
									<div class="feature-element__picture">
										<?php require __DIR__ . '/import-picture-feature.svg'; ?>
									</div>
								</div>
							</div>
						</div>
					</div>

					<?php if( $step == 'process' ){?>
					<div class="a-card a-card--normal">
						<div class="a-card__paddings">
							<div class="smart-row">
								<div class="smart-row__column">
									<div class="titled-value titled-value--small">
										<div class="titled-value__title"><?php _e('Import Progress', 'ecwid-shopping-cart');?></div>
										<div class="titled-value__value">77%</div>
									</div>
								</div>
								<div class="smart-row__column">
									<div class="titled-value titled-value--small">
										<div class="titled-value__title"><?php _e('Uploaded Products', 'ecwid-shopping-cart');?></div>
										<div class="titled-value__value">25</div>
									</div>
								</div>
								<div class="smart-row__column">
									<div class="titled-value titled-value--small">
										<div class="titled-value__title"><?php _e('Uploaded Categories', 'ecwid-shopping-cart');?></div>
										<div class="titled-value__value">8</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php }?>


					<?php if( $step != 'process' && $step != 'no_scope' ) {?>
					<div class="a-card a-card--normal">
						<div class="a-card__paddings">
							<div class="smart-row">
								<div class="smart-row__column">
									<div class="titled-value titled-value--small">
										<div class="titled-value__title"><?php _e('Woo Categories', 'ecwid-shopping-cart');?></div>
										<div class="titled-value__value"><?php echo Ecwid_Importer::count_woo_categories();?></div>
									</div>
								</div>
								<div class="smart-row__column">
									<div class="titled-value titled-value--small">
										<div class="titled-value__title"><?php _e('Woo Products', 'ecwid-shopping-cart');?></div>
										<div class="titled-value__value"><?php echo Ecwid_Importer::count_woo_products();?></div>
									</div>
								</div>
								<div class="smart-row__column">
									<div class="titled-value titled-value--small">
										<div class="titled-value__title">
											<?php
											echo sprintf(
												__( '%s Categories', 'ecwid-shopping-cart' ),
												Ecwid_Config::get_brand()
											);?>
										</div>
										<div class="titled-value__value"><?php echo Ecwid_Importer::count_ecwid_categories();?></div>
									</div>
								</div>
								<div class="smart-row__column">
									<div class="titled-value titled-value--small">
										<div class="titled-value__title">
											<?php
											echo sprintf(
												__( '%s Categories', 'ecwid-shopping-cart' ),
												Ecwid_Config::get_brand()
											);?>
										</div>
										<div class="titled-value__value"><?php echo Ecwid_Importer::count_ecwid_products();?></div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php }?>


				</div>
			</div>
		</div>


	</div>
</div>
