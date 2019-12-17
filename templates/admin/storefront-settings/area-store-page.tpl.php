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
							<div class="feature-element__status">
								<?php
								$status_class = 'success';
								if( $page_status == 'draft' ) {
									$status_class = 'error';
								}
								?>
								<span class="feature-element__status-title <?php echo $status_class;?>"><?php _e('Status', 'ecwid-shopping-cart'); ?>:</span>
								<div class="feature-element__status-dropdown-container">
									<div class="btn-group linklike-dropdown">
										<button type="button" class="btn btn-default btn-medium" aria-hidden="true" style="display: none;"></button>
										<div class="btn btn-default btn-dropdown list-dropdown-no-general-text">
											<span class="btn-dropdown-container"><span class=""><?php echo ucfirst($page_status); ?></span></span><span class="icon-arr-down"></span>
										</div>
										<div class="list-dropdown list-dropdown-medium list-dropdown--left">
											<ul>
												<?php if( $page_status == 'publish' ) {?>
													<li><a href="<?php echo $page_link;?>" target="_blank"><?php _e('View page on the site', 'ecwid-shopping-cart'); ?></a></li>
												<?php }?>

												<?php if( $page_status == 'draft' ) {?>
													<li><a href="<?php echo $page_link;?>" target="_blank"><?php _e('Preview page on the site', 'ecwid-shopping-cart'); ?></a></li>
												<?php }?>
												
												<li><a href="<?php echo $page_edit_link;?>" target="_blank"><?php _e('Open page in the editor', 'ecwid-shopping-cart'); ?></a></li>
												
												<?php if( $page_status == 'publish' ) {?>
													<li class="list-dropdown__separator"></li>
													<li><a data-storefront-status="0"><?php _e('Switch to draft and hide from the site', 'ecwid-shopping-cart'); ?></a></li>
												<?php }?>

												<?php if( $page_status == 'draft' ) {?>
													<li><a data-storefront-status="1"><?php _e('Publish', 'ecwid-shopping-cart'); ?></a></li>
												<?php }?>
											</ul>
										</div>
									</div>
								</div>
							</div>

							<div class="feature-element__content">
								
								<!-- TO-DO сделать подключение микрошаблонов по шаблону <include tpl-$page-status.php>  -->
								<?php if( $page_status == 'publish' ) {?>
									<div class="feature-element__text">
										<p>
											<?php
											_e('Your storefront page is published and displayed on your site at ', 'ecwid-shopping-cart');
												
											echo sprintf('<a href="%s" target="_blank">%s</a>', $page_link, $page_link);
											?>
										</p>
									</div>
									<div class="feature-element__action">
										<a href="<?php echo $page_link;?>" class="feature-element__button btn btn-default btn-medium" target="_blank"><?php _e('Open store page', 'ecwid-shopping-cart'); ?></a>
									</div>
								<?php }?>

								<?php if( $page_status == 'draft' ) {?>
									<div class="feature-element__text">
										<p><?php _e("Your storefront page is in draft. Publish it when you're ready so your customers will see your storefront", 'ecwid-shopping-cart'); ?></p>
									</div>
									<div class="feature-element__action">
										<a class="feature-element__button btn btn-primary btn-medium" data-storefront-status="1"><?php _e('Publish store page', 'ecwid-shopping-cart'); ?></a>
									</div>
								<?php }?>

							</div>
						</div>
						<div class="feature-element__picture">
							<?php
							$feature_picture_path = esc_attr( ECWID_PLUGIN_URL ) . '/images/admin-storefront/';

							if( $page_status == 'draft' ) {
								$feature_picture_path .= 'store-draft.png';								
							} else {
								$feature_picture_path .= 'store-default.png';
							}

							?>
							<img src="<?php echo $feature_picture_path; ?>" width="142" />
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>