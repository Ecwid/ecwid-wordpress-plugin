
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
.ec-importer-alert-warning, .ec-importer-alert-warning { display: none; }
[data-ec-importer-state] { display: none; }
[data-ec-importer-state="default"] { display: block; }

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



<div class="settings-page">
	<div class="settings-page__header">
		<div class="settings-page__titles settings-page__titles--left">
			<h1 class="settings-page__title"><?php echo sprintf( __( 'Import your products from WooCommerce to %s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></h1>
			<div class="settings-page__subtitle"></div>
		</div>

		<?php
		if( $this->_is_token_ok() ) {
			require __DIR__ . '/woo-complete-alert.tpl.php';
		}
		?>


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

										<div class="feature-element__title" data-ec-importer-state="default"><?php echo sprintf( __( 'Import your products from WooCommerce to %s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></div>

										<div class="feature-element__title" data-ec-importer-state="process"><?php _e( 'Import is in Progress', 'ecwid-shopping-cart' ); ?></div>

										<div class="feature-element__title" data-ec-importer-state="complete"><?php echo sprintf( __( 'Import your products from WooCommerce to %s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></div>

										<?php
										if( !$this->_is_token_ok() ) {
											require __DIR__ . '/import-no-token.tpl.php';
										}
										?>

										<?php if( $this->_is_token_ok() ){?>

											<div class="feature-element__status" data-ec-importer-state="complete">
												<span class="feature-element__status-title success">
													<?php 
													echo sprintf(
														__( 'Import completed. <a href="%s">Run again.</a>', 'ecwid-shopping-cart' ),
														admin_url( 'admin.php?page=' . Ecwid_Import_Page::PAGE_SLUG_WOO )
													);
													?>
												</span>
											</div>

											<div class="feature-element__status" data-ec-importer-state="process">
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
													<p><?php echo sprintf( __( 'Import creates new products. Please mind the maximum number of products and categories you can add to your store. This import tool will automatically stop when you reach the limit. To learn the current store limit or increase it, please see the <nobr><a %s target="_blank">"Billing & Plans"</a></nobr> page in your store control panel. ', 'ecwid-shopping-cart' ), 'href="admin.php?page=ec-store-admin-billing"' ); ?></p>
													<?php endif;?>
												</div>

												<div class="feature-element__action" data-ec-importer-state="default">
													<button type="button" class="btn btn-primary btn-medium" id="ecwid-importer-woo-go">
														<span><?php _e( 'Start import', 'ecwid-shopping-cart' ); ?></span>
													</button>
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

					<?php
					if( $this->_is_token_ok() ) {
						require __DIR__ . '/woo-summary.tpl.php';
					}
					?>


				</div>
			</div>
		</div>


	</div>
</div>
