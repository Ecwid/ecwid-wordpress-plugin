<div class="ec-form">
	<div class="ec-button">

		<button type="button" class="ec-create-store-button btn btn--medium btn--orange"><?php _e( 'Create Store', 'ecwid-shopping-cart' ); ?></button>
		
		<button type="button" class="ec-create-store-loading btn btn--medium btn--orange btn--loading">&nbsp;</button>
		
		<button type="button" class="ec-create-store-success btn btn--medium btn--orange"><?php _e('Store is created', 'ecwid-shopping-cart'); ?></button>
	</div>
	<a href="<?php echo $connect_url; ?>">Подключить существующий магазин&nbsp;&rsaquo;</a>
</div>

<div class="ec-note ec-create-store-note">
	Текст до создания магазина
</div>

<div class="ec-note ec-create-store-loading-note">
	<?php _e('Creating store', 'ecwid-shopping-cart'); ?>
</div>

<div class="ec-note ec-create-store-success-note">
	<?php _e('Preparing your store dashboard', 'ecwid-shopping-cart'); ?>
</div>