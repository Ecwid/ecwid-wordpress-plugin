<div class="named-area">
	<div class="named-area__header">
		<div class="named-area__titles"><div class="named-area__title"><?php _e( 'Additional store pages', 'ecwid-shopping-cart'); ?></div></div>
		<div class="named-area__description"><?php _e( 'Your customers can search products, add them to the cart and go to checkout right from your store page. But you may consider adding separate pages and linking to them from the site menu to highlight cart and checkout, particular category or search/filters.', 'ecwid-shopping-cart'); ?></div>
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
									<?php _e( 'Cart page displays customer cart and invite them to check out', 'ecwid-shopping-cart'); ?>
								</div>
							</div>
							<div class="cta-block__cta">
								<a href="#" target="_blank" class="btn btn-default btn-medium"><?php _e( 'Create cart page', 'ecwid-shopping-cart'); ?></a>
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
								<div class="cta-block__title"><?php _e( 'Add search and filters page', 'ecwid-shopping-cart'); ?></div>
								<div class="cta-block__content">
									<?php _e( 'Search/filters page allows your customers find the products they want quicker', 'ecwid-shopping-cart'); ?>
								</div>
							</div>
							<div class="cta-block__cta">
								<a href="#" target="_blank" class="btn btn-default btn-medium"><?php _e( 'Create search page', 'ecwid-shopping-cart'); ?></a>
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
									<?php _e( 'You can feature a specific store category on a separate page of your site.', 'ecwid-shopping-cart'); ?>
								</div>
							</div>
							<div class="cta-block__cta">
								<a href="#" target="_blank" class="btn btn-default btn-medium"><?php _e( 'Pick a category', 'ecwid-shopping-cart'); ?></a>
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
									<?php _e( 'Create a landing page featuring one of your products', 'ecwid-shopping-cart'); ?>
								</div>
							</div>
							<div class="cta-block__cta">
								<a href="#" target="_blank" class="btn btn-default btn-medium"><?php _e( 'Pick a product', 'ecwid-shopping-cart'); ?></a>
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
								<div class="cta-block__title"><?php _e( 'Add store content to existing pages', 'ecwid-shopping-cart'); ?></div>
								<div class="cta-block__content">
									<?php
									echo sprintf( 
										__( 'You can add storefront, single categories, products or buy now buttons to other pages on your site. Open a page in the editor and use one of the %s blocks to add store content to the page', 'ecwid-shopping-cart'),
										Ecwid_Config::get_brand()
									);
									?>
								</div>
							</div>
							<div class="cta-block__cta">
								<a href="<?php echo admin_url( 'edit.php?post_type=page' ); ?>" target="_blank" class="btn btn-default btn-medium"><?php _e( 'Go to pages', 'ecwid-shopping-cart'); ?></a>
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
							ecwid_embed_svg( 'admin-storefront/icons/choose-main-page' );
							?>
						</span>
					</div>
					<div class="iconable-block__content">
						<div class="cta-block">
							<div class="cta-block__central">
								<div class="cta-block__title"><?php _e( 'Choose main store page', 'ecwid-shopping-cart'); ?></div>
								<div class="cta-block__content">
									<?php _e( 'You have your storefront added to several pages on your site. You can choose the main storefront page here &mdash; the store navigation menus and sidebar widgets will open it.', 'ecwid-shopping-cart'); ?>
								</div>
								<div class="cta-block__content">
									
									<div class="btn-group dropdown-toggle drop-left">
										<button type="button" class="btn btn-default btn-medium" aria-hidden="true" style="display: none;"></button>
										<div class="btn btn-default btn-dropdown btn-medium list-dropdown-no-general-text">
											<span class="btn-dropdown-container">
												<span class="actions">Actions</span>
											</span>
											<span class="icon-arr-down"></span>
										</div>
										<div class="list-dropdown list-dropdown-medium">
											<ul>
												<li><a>Edit</a></li>
												<li><a>Sort</a></li>
												<li><a>Remove</a></li>
											</ul>
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
		

	</div>
</div>