<div class="progress-indicator">
    <div class="icon">
	<?php ecwid_embed_svg('update'); ?>
    </div>
    <div class="inline-note">
        <?php _e( 'copying products and categories', 'ecwid-shopping-cart' ); ?>
    </div>
</div>
<div class="progress-message">
    <?php echo sprintf( 
            __( 'Importing %s of %s items', 'ecwid-shopping-cart' ), 
            '<span id="import-progress-current">0</span>', 
            '<span id="import-progress-total">' . (Ecwid_Importer::count_woo_products() + Ecwid_Importer::count_woo_categories()) . '</span>' ); 
    ?>
</div>