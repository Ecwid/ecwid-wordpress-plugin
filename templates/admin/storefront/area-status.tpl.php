<div class="named-area">
	<div class="named-area__header">
		<div class="named-area__titles">
			<div class="named-area__title"><?php _e('Store page', 'ecwid-shopping-cart'); ?></div>
			<div class="named-area__subtitle"><?php _e('Here is an explanation: how cool to use this platform to launch a site', 'ecwid-shopping-cart'); ?></div>
		</div>
	</div>
	<div class="named-area__body">
		<div class="a-card a-card--normal">
			<div class="a-card__paddings">
				<div class="feature-element has-picture">
					<div class="feature-element__core">
						<div class="feature-element__data">

							<div class="feature-element__title"><?php _e('Your store page', 'ecwid-shopping-cart'); ?></div>
							
							<!-- <div class="feature-element__status">
								<a class="iconable-link iconable-link--append">
									<span class="iconable-link__text"><?php _e( 'View demo store page', 'ecwid-shopping-cart' ); ?></span>
									<span class="iconable-link__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28"><path d="M25.5 15.39c-.83 0-1.5.67-1.5 1.5v4.41c0 1.49-1.21 2.71-2.71 2.71H6.71A2.72 2.72 0 0 1 4 21.29V6.71C4 5.21 5.21 4 6.71 4h4.45c.83 0 1.5-.67 1.5-1.5S11.99 1 11.16 1H6.71C3.56 1 1 3.56 1 6.71v14.58C1 24.44 3.56 27 6.71 27h14.58c3.15 0 5.71-2.56 5.71-5.71v-4.41c0-.82-.67-1.49-1.5-1.49z"></path><path d="M25.05 1h-7.37c-.83 0-1.5.67-1.5 1.5s.67 1.5 1.5 1.5h4.2l-9.94 9.94a1.49 1.49 0 0 0 0 2.12c.29.29.68.44 1.06.44s.77-.15 1.06-.44L24 6.12v4.2c0 .83.67 1.5 1.5 1.5s1.5-.67 1.5-1.5V2.95C27 1.87 26.13 1 25.05 1z"></path></svg></span>
								</a>
							</div> -->


							<div class="feature-element__status">

								<span class="feature-element__status-title success" data-ec-storefront="publish">
									<?php _e('Status', 'ecwid-shopping-cart'); ?>:
								</span>
								<span class="feature-element__status-title error" data-ec-storefront="draft">
									<?php _e('Status', 'ecwid-shopping-cart'); ?>:
								</span>

								<div class="feature-element__status-dropdown-container">

									<div class="dropdown-menu text-default">

										<div class="dropdown-menu__link">
											<a class="iconable-link">
												<div class="iconable-link__text" data-ec-storefront="publish"><?php _e( 'Published', 'ecwid-shopping-cart' ); ?></div>
												<div class="iconable-link__text" data-ec-storefront="draft"><?php _e( 'Draft', 'ecwid-shopping-cart' ); ?></div>
												&zwj;
												<span class="iconable-link__icon">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 28" focusable="false"><path d="M3.3 9.5l5.6 5.1 6-5.1c.8-.7 1.9-.7 2.6 0 .8.7.8 1.8 0 2.5l-7.2 6.4c-.5.4-1 .6-1.4.6s-1-.2-1.3-.5L.7 12.1c-.8-.7-.8-1.8 0-2.5.6-.8 1.9-.8 2.6-.1z"></path></svg>
												</span>
											</a>
										</div>
										
										<div class="list-dropdown list-dropdown-medium" style="display: none;" aria-hidden="true">
											<ul data-ec-storefront="publish">
												<?php self::render_dropdown_list_items( self::get_dropdown_items('publish') ); ?>
											</ul>

											<ul data-ec-storefront="draft">
												<?php self::render_dropdown_list_items( self::get_dropdown_items('draft') ); ?>
											</ul>
										</div>
									</div>

									<a class="iconable-link text-default simple-svg-loader" style="display: none;" aria-hidden="true">
										<div class="iconable-link__text"><?php echo ucfirst($page_status); ?></div>
										&zwj;
										<span class="iconable-link__icon">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 28 28" width="28" height="28" focusable="false"><path d="M14,27C6.83,27,1,21.17,1,14c0-1.56,0.27-3.08,0.81-4.52C2.1,8.7,2.96,8.31,3.74,8.59c0.78,0.29,1.17,1.15,0.88,1.93 C4.21,11.63,4,12.8,4,14c0,5.51,4.49,10,10,10c5.51,0,10-4.49,10-10c0-5.51-4.49-10-10-10c-0.83,0-1.5-0.67-1.5-1.5S13.17,1,14,1 c7.17,0,13,5.83,13,13C27,21.17,21.17,27,14,27z"></path></svg>
										</span>
									</a>
								</div>
							</div>

							<div class="feature-element__content" data-ec-storefront="publish">
								<div class="feature-element__text">
									<?php
									_e('Your storefront page is published and displayed on your site at ', 'ecwid-shopping-cart');
										
									echo sprintf( '<a href="%s" target="_blank" data-ec-store-link="1">%s</a>', $page_link, urldecode($page_link) );
									?>
								</div>
								<div class="feature-element__action">
									<a href="<?php echo $page_link;?>" class="feature-element__button btn btn-default btn-medium" target="_blank"><?php _e('Open store page', 'ecwid-shopping-cart'); ?></a>
								</div>
							</div>

							<div class="feature-element__content" data-ec-storefront="draft">
								<div class="feature-element__text">
									<p><?php _e("Your storefront page is in draft. Publish it when you're ready so your customers will see your storefront", 'ecwid-shopping-cart'); ?></p>
								</div>
								<div class="feature-element__action">
									<a class="feature-element__button btn btn-primary btn-medium" data-storefront-status="1"><?php _e('Publish store page', 'ecwid-shopping-cart'); ?></a>
								</div>
							</div>

							<div class="feature-element__content" data-ec-storefront="demo">
								<div class="feature-element__text">
									<p><?php _e('The demo store page. It will be filled with your products and published when you set up your store', 'ecwid-shopping-cart'); ?></p>
								</div>
								<div class="feature-element__action">
									<a class="feature-element__button btn btn-primary btn-medium"><?php _e('Set up your store', 'ecwid-shopping-cart'); ?></a>
								</div>
							</div>
						</div>
						<div class="feature-element__picture">
							<img src="<?php echo esc_attr( ECWID_PLUGIN_URL ); ?>/images/admin-storefront/store-default.png" data-ec-storefront="publish"/>
							<img src="<?php echo esc_attr( ECWID_PLUGIN_URL ); ?>/images/admin-storefront/store-draft.png" data-ec-storefront="draft"/>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>