
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

/* TO-DO remove this after updating css framework*/
.canonical-status {
    display: inline-block;
    position: relative;
    text-align: left;
    white-space: nowrap;
    display: block;
    margin-top: 8px;
    margin-bottom: 8px;
    color: #262a2e;
    font-weight: 600;
}

.canonical-status .canonical-status__text {
    display: inline-block;
    white-space: normal
}

.canonical-status .canonical-status__icon {
    display: inline-block;
    box-sizing: content-box;
    height: 1em;
    margin: -2px 0 0 0;
    padding: 0 0 0 .35em;
    font-size: 1.2em;
    line-height: 1em;
    vertical-align: middle
}

.canonical-status .canonical-status__icon svg {
    width: auto;
    height: 100%;
    fill: currentColor
}

.canonical-status--prepend-icon {
    padding-left: 1.8em
}

.canonical-status--prepend-icon .canonical-status__icon {
    position: absolute;
    top: 0;
    left: 0;
    width: 1em;
    margin: 0;
    padding: .125em 0
}

.canonical-status--prepend-icon .canonical-status__icon svg {
    width: 100%
}

.canonical-status--prepend-icon .canonical-status__icon--emoji {
    padding: 0;
    line-height: 1.5
}

.canonical-status--large-icon .canonical-status__icon {
    padding-top: 0;
    padding-bottom: 0;
    font-size: 1.5em
}

.canonical-status--large-icon.canonical-status--prepend-icon {
    padding-left: 2.1em
}

.canonical-status--success {
    color: #37ba32
}

.canonical-status--warning {
    color: #de9d1c
}

.canonical-status--error {
    color: #fd3826
}

.canonical-status .canonical-status__icon,.canonical-status .canonical-status__action {
    display: none
}

.canonical-status--has-icon .canonical-status__icon {
    display: inline-block
}

.canonical-status--has-action .canonical-status__action {
    display: inline-block
}

.canonical-status--has-action .canonical-status__action .dropdown-menu {
    margin: 0
}

.canonical-status--has-action .canonical-status__text {
    margin-right: 8px
}

.canonical-status--loading .canonical-status__icon svg {
    width: 1em;
    height: 1em;
    margin: 0
}

.canonical-status--loading .canonical-status__icon svg path {
    transform-origin: center center;
    animation: spinright .6s infinite linear
}

</style>

<?php
// временная штука для удобства переключения экранов
$steps = array(
	1 => 'no_scope',
	'default',
	'process',
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


		<!-- Results and errors -->
		<?php if( isset($_GET['results']) ) {?>

			<?php if( isset($_GET['warning']) ) { ?>
			<div class="a-card a-card--compact a-card--warning">
			<?php } else { ?>
			<div class="a-card a-card--compact a-card--success">
			<?php } ?>

				<div class="a-card__paddings">
					
					<?php if( isset($_GET['warning']) ) { ?>
					<div class="iconable-block iconable-block--hide-in-mobile iconable-block--warning">
					<?php } else { ?>
					<div class="iconable-block iconable-block--hide-in-mobile iconable-block--success">
					<?php } ?>

						<div class="iconable-block__infographics">
							<?php if( isset($_GET['warning']) ) { ?>
								<span class="iconable-block__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70" focusable="false"><path d="M34.5 67C16.58 67 2 52.42 2 34.5S16.58 2 34.5 2 67 16.58 67 34.5 52.42 67 34.5 67zm0-62C18.23 5 5 18.23 5 34.5S18.23 64 34.5 64 64 50.77 64 34.5 50.77 5 34.5 5z"></path><path d="M34.5 49c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5zM35.5 38.57h-2l-1-14c0-1.17.89-2.07 2-2.07s2 .9 2 2l-1 14.07z"></path></svg></span> 
							<?php } else { ?>
								<span class="iconable-block__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70"><path d="M34.5 67h-.13c-8.68-.03-16.83-3.45-22.94-9.61C5.32 51.23 1.97 43.06 2 34.38 2.07 16.52 16.65 2 34.5 2h.13c8.68.03 16.83 3.45 22.94 9.61 6.12 6.16 9.46 14.34 9.43 23.02C66.93 52.48 52.35 67 34.5 67zm0-62C18.3 5 5.06 18.18 5 34.39c-.03 7.88 3.01 15.3 8.56 20.89 5.55 5.59 12.95 8.69 20.83 8.72h.12c16.2 0 29.44-13.18 29.5-29.39.03-7.88-3.01-15.3-8.56-20.89C49.89 8.13 42.49 5.03 34.61 5h-.11z"></path><path d="M32.17 46.67l-10.7-10.08c-.6-.57-.63-1.52-.06-2.12.57-.6 1.52-.63 2.12-.06l8.41 7.92 14.42-16.81c.54-.63 1.49-.7 2.12-.16.63.54.7 1.49.16 2.12L32.17 46.67z"></path></svg></span>
							<?php } ?>
						</div>
						<div class="iconable-block__content">
							<div class="cta-block">
								<div class="cta-block__central">
									<div class="cta-block__title"><?php _e( 'Import complete', 'ecwid-shopping-cart' ); ?></div>
									<div class="cta-block__content">
										<div>
										<?php 
										echo sprintf( __( 'Imported <b>%s</b> products', 'ecwid-shopping-cart' ), '<span id="import-results-products">0</span>' );
										if ( ecwid_is_paid_account() ) {
											echo ", "; 
											echo sprintf( __( '<b>%s</b> categories', 'ecwid-shopping-cart' ), '<span id="import-results-categories">0</span>' );
										}
										?>
										</div>

										<?php if( isset($_GET['warning']) ) { ?>
											<div><?php _e( 'Some of the items could not be imported, <a href="%s">download the import log</a>.', 'ecwid-shopping-cart' ); ?></div>
										<?php } ?>
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
				</div>
			</div>

			<?php if( isset($_GET['warning']) ) { ?>
			<div class="a-card a-card--compact a-card--warning">
				<div class="a-card__paddings">
					<div class="iconable-block iconable-block--hide-in-mobile iconable-block--warning">
						<div class="iconable-block__infographics">
							<span class="iconable-block__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 70 70" focusable="false"><path d="M34.5 67C16.58 67 2 52.42 2 34.5S16.58 2 34.5 2 67 16.58 67 34.5 52.42 67 34.5 67zm0-62C18.23 5 5 18.23 5 34.5S18.23 64 34.5 64 64 50.77 64 34.5 50.77 5 34.5 5z"></path><path d="M34.5 49c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5zM35.5 38.57h-2l-1-14c0-1.17.89-2.07 2-2.07s2 .9 2 2l-1 14.07z"></path></svg></span>
						</div>
						<div class="iconable-block__content">
							<div class="cta-block">
								<div class="cta-block__central">
									<div class="cta-block__title"><?php _e( 'Reached the product count limit', 'ecwid-shopping-cart' ); ?></div>
									<div class="cta-block__content">
										<?php echo sprintf( __ ( 'Not all products have been copied to %1$s because you reached the product count limit on your pricing plan in %1$s. If you want to import more products, please consider <a %2$s>upgrading your %1$s plan</a>.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand(), 'href="' . $this->_get_billing_page_url() .'"' ); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php } ?>
		<?php } ?>

		<div class="named-area">
			<div class="named-area__header">
				<div class="named-area__titles">
					<div class="named-area__title"><?php _e( 'Update your Catalog', 'ecwid-shopping-cart' ); ?></div>
					<div class="named-area__subtitle"><?php echo sprintf( __( 'This import will copy your WooCommerce products and categories to your %s store.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></div>
				</div>
			</div>
			<div class="named-area__body">

				<div class="a-card-stack">
					
					<div class="a-card a-card--normal">
						<div class="a-card__paddings">
							<div class="feature-element has-picture">
								<div class="feature-element__core">
									<div class="feature-element__data">


										<?php if( $step == 'no_scope' ){?>
											<div class="feature-element__title"><?php echo sprintf( __( 'Import your products from WooCommerce to %s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></div>
											<div class="feature-element__content">
												<div class="feature-element__text">
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
													<?php if ( !Ecwid_Config::is_wl() ): ?>
													<p><?php echo sprintf( __( 'Import creates new products. Please mind the maximum number of products and categories you can add to your store. This import tool will automatically stop when you reach the limit. To learn the current store limit or increase it, please see the <nobr><a %s>"Billing & Plans"</a></nobr> page in your store control panel. ', 'ecwid-shopping-cart' ), 'href="admin.php?page=ec-store-admin-billing"' ); ?></p>
													<?php endif;?>
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

											<div class="feature-element__status">
												<div class="canonical-status canonical-status--has-icon canonical-status--loading canonical-status--prepend-icon canonical-status--warning">
													<div class="canonical-status__text">
														<?php _e( 'Copying products and categories.', 'ecwid-shopping-cart' ); ?>
														<?php echo sprintf( 
											            __( 'Importing %s of %s items', 'ecwid-shopping-cart' ), 
											            '<span id="import-progress-current">0</span>', 
											            '<span id="import-progress-total">' . (Ecwid_Importer::count_woo_products() + Ecwid_Importer::count_woo_categories()) . '</span>' ); 
														?>
													</div>
													<div class="canonical-status__icon">
														<span>
															<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28" width="28" height="28"
															 focusable="false">
															<path d="M14,27C6.83,27,1,21.17,1,14c0-1.56,0.27-3.08,0.81-4.52C2.1,8.7,2.96,8.31,3.74,8.59c0.78,0.29,1.17,1.15,0.88,1.93 C4.21,11.63,4,12.8,4,14c0,5.51,4.49,10,10,10c5.51,0,10-4.49,10-10c0-5.51-4.49-10-10-10c-0.83,0-1.5-0.67-1.5-1.5S13.17,1,14,1 c7.17,0,13,5.83,13,13C27,21.17,21.17,27,14,27z"></path>
															</svg>
														</span>
													</div>
												</div>
											</div>

											<div class="feature-element__content">
												<div class="feature-element__text">
													<?php if ( !Ecwid_Config::is_wl() ): ?>
													<p><?php echo sprintf( __( 'Import creates new products. Please mind the maximum number of products and categories you can add to your store. This import tool will automatically stop when you reach the limit. To learn the current store limit or increase it, please see the <nobr><a %s>"Billing & Plans"</a></nobr> page in your store control panel. ', 'ecwid-shopping-cart' ), 'href="admin.php?page=ec-store-admin-billing"' ); ?></p>
													<?php endif;?>
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


					<?php if( $step != 'no_scope' ) {?>
					<div class="a-card a-card--normal">
						<div class="a-card__paddings">
							
							<ul class="titled-items-list">
								<li class="titled-items-list__item titled-item">
									<div class="titled-item__title"><?php _e( 'Import summary', 'ecwid-shopping-cart' ); ?></div>
									<div class="titled-item__content">
										&mdash; 
										<?php
											_e( 'Your WooCommerce store has ', 'ecwid-shopping-cart' );
								            echo $this->_get_products_categories_message(
								                Ecwid_Importer::count_woo_products(),
								                Ecwid_Importer::count_woo_categories()
								            );
										?>
									</div>
									<div class="titled-item__content">
										&mdash; 
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
									<div class="titled-item__content">
										&mdash;
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
					<?php }?>


				</div>
			</div>
		</div>


	</div>
</div>
