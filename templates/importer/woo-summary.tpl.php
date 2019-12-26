<div class="a-card a-card--normal" data-ec-importer-state="default">
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