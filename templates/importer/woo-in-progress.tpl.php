<div class="progress-indicator">
    <div class="icon">
	<?php ecwid_embed_svg('update'); ?>
    </div>
    <div class="inline-note">Some text about progress
    </div>
</div>
<div class="progress-message">
    <?php echo sprintf( 
            __( 'Importing %s of %s items', 'ecwid-shopping-cart' ), 
            '<span id="import-progress-current">1</span>', 
            '<span id="import-progress-total">' . ($this->importer->count_woo_products() + $this->importer->count_woo_categories()) . '</span>' ); 
    ?>
</div>