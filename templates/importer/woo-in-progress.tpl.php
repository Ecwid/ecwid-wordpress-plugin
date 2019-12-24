<div class="progress-indicator">
    <div class="icon">
	<?php ecwid_embed_svg('update'); ?>
    </div>
    <div class="inline-note">
        <?php _e( 'copying products and categories', 'ecwid-shopping-cart' ); ?>
    </div>
</div>
<div class="progress-message">
    <?php

    if ( ecwid_is_paid_account() ) {
        $total = Ecwid_Importer::count_woo_products() + Ecwid_Importer::count_woo_categories();
    } else {
        $total = Ecwid_Importer::count_woo_products();
    }

    echo sprintf( 
            __( 'Importing %s of %s items', 'ecwid-shopping-cart' ), 
            '<span id="import-progress-current">0</span>', 
            '<span id="import-progress-total">' . $total . '</span>' ); 
    ?>
</div>