<div class="named-area" data-ec-state="publish draft">
	<div class="named-area__header">
		<div class="named-area__titles"><div class="named-area__title"><?php _e( 'Additional store pages', 'ecwid-shopping-cart'); ?></div></div>
		<div class="named-area__description"><?php _e( 'Highlight products, categories, and other store pages by adding them to separate website pages.', 'ecwid-shopping-cart'); ?></div>
	</div>
	<div class="named-area__body">

		<div class="a-card a-card--compact">
			<div class="a-card__paddings">
				<div class="iconable-block iconable-block--hide-in-mobile">
					<div class="iconable-block__infographics">
						<span class="iconable-block__icon">
							<?php
							ecwid_embed_svg( 'admin-storefront/icons/cart-checkout' );
							?>
						</span>
					</div>
					<div class="iconable-block__content">
						<div class="cta-block">
							<div class="cta-block__central">
								<div class="cta-block__title"><?php _e( 'Add cart and checkout page', 'ecwid-shopping-cart'); ?></div>
								<div class="cta-block__content">
									<?php _e( 'Customers go to the cart page to review products they’ve added to the cart and proceed to the checkout.', 'ecwid-shopping-cart'); ?>
								</div>
							</div>
							<div class="cta-block__cta">
								<a href="#" class="btn btn-default btn-medium" data-storefront-create-page="cart"><?php _e( 'Create Cart Page', 'ecwid-shopping-cart'); ?></a>
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
							ecwid_embed_svg( 'admin-storefront/icons/search-filters' );
							?>
						</span>
					</div>
					<div class="iconable-block__content">
						<div class="cta-block">
							<div class="cta-block__central">
								<div class="cta-block__title"><?php _e( 'Add a search and filters page', 'ecwid-shopping-cart'); ?></div>
								<div class="cta-block__content">
									<?php _e( 'Show customers that they can use a search and filters to find desired products faster.', 'ecwid-shopping-cart'); ?>
								</div>
							</div>
							<div class="cta-block__cta">
								<a href="#" class="btn btn-default btn-medium" data-storefront-create-page="search"><?php _e( 'Create Search Page', 'ecwid-shopping-cart'); ?></a>
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
							ecwid_embed_svg( 'admin-storefront/icons/category' );
							?>
						</span>
					</div>
					<div class="iconable-block__content">
						<div class="cta-block">
							<div class="cta-block__central">
								<div class="cta-block__title"><?php _e( 'Add a category page', 'ecwid-shopping-cart'); ?></div>
								<div class="cta-block__content">
									<?php
									if( $categories && count($categories) > 0 && count($categories) <= 100 ) {
										_e( 'Feature a specific store category on a separate page of your site.', 'ecwid-shopping-cart');
									} else {
										_e( 'Feature a specific store category on a separate page of your site.', 'ecwid-shopping-cart');
									}
									?>
								</div>
							</div>
							<div class="cta-block__cta">

								<?php if( $categories && count($categories) > 0 && count($categories) <= 100 ) { ?>
									<div class="btn-group dropdown-toggle drop-right">
										<button data-storefront-create-page="category" type="button" class="btn btn-default btn-medium" aria-hidden="true" style="display: none;"></button>
										<div class="btn btn-default btn-dropdown btn-medium list-dropdown-no-general-text">
											<span class="btn-dropdown-container">
												<span class="actions"><?php _e( 'Pick Category', 'ecwid-shopping-cart'); ?></span>
											</span>
											<span class="icon-arr-down"></span>
										</div>
										<div class="list-dropdown list-dropdown-medium list-dropdown-scroll">
											<ul>
											<?php foreach( $categories as $category ) {?>
												<li 
													data-storefront-create-page="category"
													data-storefront-item-id="<?php echo $category->id?>"
												><a><?php echo $category->name?></a></li>
											<?php } ?>
											</ul>
										</div>
									</div>
								<?php } else {?>
									<a href="#" class="btn btn-default btn-medium" data-storefront-create-page="category"><?php _e( 'Create Category Page', 'ecwid-shopping-cart'); ?></a>
								<?php } ?>

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
							ecwid_embed_svg( 'admin-storefront/icons/product' );
							?>
						</span>
					</div>
					<div class="iconable-block__content">
						<div class="cta-block">
							<div class="cta-block__central">
								<div class="cta-block__title"><?php _e( 'Add a product page', 'ecwid-shopping-cart'); ?></div>
								<div class="cta-block__content">
									<?php
									if( $products_total > 0 && $products_total <= 100 ) {
										_e( 'Create a landing page featuring one of your products.', 'ecwid-shopping-cart');
									} else {
										_e( 'Create a landing page featuring one of your products.', 'ecwid-shopping-cart');
									}
									?>
								</div>
							</div>
							<div class="cta-block__cta">

								<?php if( $products_total > 0 && $products_total <= 100 ) { ?>
									<div class="btn-group dropdown-toggle drop-right">
										<button data-storefront-create-page="category" type="button" class="btn btn-default btn-medium" aria-hidden="true" style="display: none;"></button>
										<div class="btn btn-default btn-dropdown btn-medium list-dropdown-no-general-text">
											<span class="btn-dropdown-container">
												<span class="actions"><?php _e( 'Pick Product', 'ecwid-shopping-cart'); ?></span>
											</span>
											<span class="icon-arr-down"></span>
										</div>
										<div class="list-dropdown list-dropdown-medium list-dropdown-scroll">
											<ul>
											<?php foreach( $products as $product ) {?>
												<li 
													data-storefront-create-page="product"
													data-storefront-item-id="<?php echo $product->id?>"
												><a><?php echo $product->name?></a></li>
											<?php } ?>
											</ul>
										</div>
									</div>
								<?php } else {?>
									<a href="#" class="btn btn-default btn-medium" data-storefront-create-page="product"><?php _e( 'Create Product Page', 'ecwid-shopping-cart'); ?></a>
								<?php } ?>

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
							ecwid_embed_svg( 'admin-storefront/icons/add-another-page' );
							?>
						</span>
					</div>
					<div class="iconable-block__content">
						<div class="cta-block">
							<div class="cta-block__central">
								<div class="cta-block__title"><?php _e( 'Add your store to other site pages', 'ecwid-shopping-cart'); ?></div>
								<div class="cta-block__content">
									<?php
									echo sprintf( 
										__( 'You can add the entire storefront, categories, products, or "Buy now" buttons to other pages on your site. To do that, open a page in the editor and add an appropriate %s block.', 'ecwid-shopping-cart'),
										Ecwid_Config::get_brand()
									);
									?>
								</div>
							</div>
							<div class="cta-block__cta">
								<a href="<?php echo admin_url( 'edit.php?post_type=page' ); ?>" target="_blank" class="btn btn-default btn-medium"><?php _e( 'Go to Pages', 'ecwid-shopping-cart'); ?></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<?php if( $store_pages ) { ?>
		<div class="a-card a-card--compact">
			<div class="a-card__paddings">
				<div class="iconable-block iconable-block--hide-in-mobile">
					<div class="iconable-block__infographics">
						<span class="iconable-block__icon">
							<?php
							ecwid_embed_svg( 'admin-storefront/icons/choose-main-page' );
							?>
						</span>
					</div>
					<div class="iconable-block__content">
						<div class="cta-block">
							<div class="cta-block__central">
								<div class="cta-block__title"><?php _e( 'Choose the main store page', 'ecwid-shopping-cart'); ?></div>
								<div class="cta-block__content">
									<?php _e( 'Your store is added to several pages on the site. Choose the main page. Customers are directed to the main store page when they click on the cart icon, store links in the site menu, and products in the sidebar.', 'ecwid-shopping-cart'); ?>
								</div>
								<div class="cta-block__content">
									
									<div class="fieldset fieldset--select fieldset--no-label">
										<div class="field field--medium field--filled">
											<select class="field__select" data-storefront-save-main-page="1">
												<?php foreach ( $store_pages as $id ): ?>
						                            <option
						                                    value="<?php echo $id; ?>"
						                                <?php if ( $id == $page_id ): ?>
						                                    selected="selected"
						                                <?php endif; ?>
						                            ><?php esc_html_e( get_post( $id )->post_title ); ?></option>
						                        <?php endforeach; ?>
											</select>
											<span class="field__arrow"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 26 26" focusable="false"><path d="M7.85 10l5.02 4.9 5.27-4.9c.65-.66 1.71-.66 2.36 0 .65.67.65 1.74 0 2.4l-6.45 6.1c-.33.33-.76.5-1.18.5-.43 0-.86-.17-1.18-.5l-6.21-6.1c-.65-.66-.65-1.74 0-2.41.66-.65 1.72-.65 2.37.01z"></path></svg></span>
										</div>
									</div>

								</div>
							</div>
							<div class="cta-block__cta"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>

	</div>
</div>