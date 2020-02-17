<div class="named-area" data-ec-state="demo no-pages">
	<div class="named-area__header">
		<div class="named-area__titles"><div class="named-area__title"><?php _e( "What's next?" , 'ecwid-shopping-cart'); ?></div></div>
		<div class="named-area__description">
			<?php 
			echo sprintf(
				__( 'Add your %s store to the website and start selling in minutes.', 'ecwid-shopping-cart'),
				Ecwid_Config::get_brand()
			);
			?>
		</div>
	</div>
	<div class="named-area__body">

		<div class="a-card a-card--compact">
			<div class="a-card__paddings">
				<div class="promo-row">
					<div class="promo-row__content">
						<ul class="titled-items-list titled-items-list--ordered">
							<li class="titled-items-list__item titled-item">
								<div class="titled-item__title"><?php _e( "Customize store appearance" , 'ecwid-shopping-cart'); ?></div>
								<div class="titled-item__content"><?php _e( "Customize your store’s appearance to fit your business needs. Give your store the exact look and feel that reflects your brand." , 'ecwid-shopping-cart'); ?></div>
							</li>
							<li class="titled-items-list__item titled-item">
								<div class="titled-item__title"><?php _e( "Change store content" , 'ecwid-shopping-cart'); ?></div>
								<div class="titled-item__content"><?php _e( "Stay connected with your customers. Update the content of your store anytime to tell customers about ongoing promotions and what's new in your store." , 'ecwid-shopping-cart'); ?></div>
							</li>
							<li class="titled-items-list__item titled-item">
								<div class="titled-item__title"><?php _e( "Promote your store" , 'ecwid-shopping-cart'); ?></div>
								<div class="titled-item__content"><?php _e( "Help customers find your store on the site. Add the store link to the site menu, create additional store pages, and highlight store products on other site pages and in sidebars." , 'ecwid-shopping-cart'); ?></div>
							</li>
						</ul>
					</div>
					<div class="promo-row__image"><img src="<?php echo esc_attr( ECWID_PLUGIN_URL ); ?>/images/admin-storefront/customize-promo.png"></div>
				</div>
			</div>
		</div>

	</div>
</div>