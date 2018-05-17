<div class="wrap ecwid-importer state-<?php echo $this->_is_token_ok() ? 'woo-initial' : 'no-token'; ?>">
    <h1><?php echo sprintf( __( 'Import your products from WooCommerce to %s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></h1>

    <p><?php echo sprintf( __( 'This import will copy your WooCommerce products and categories to your %s store.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></p>

    <?php if ( !Ecwid_Config::is_wl() ): ?>
    <p><?php echo sprintf( __( '<b>Important note:</b> import creates new products.  please mind the maximum number of products and categories you can add to your store. This import tool will automatically stop when you reach the limit. To learn the current store limit or increase it, please see the "<a %s>Billing & Plans</a>" page in your store control panel. ', 'ecwid-shopping-cart' ), 'href="admin.php?page=ec-store-admin-billing"' ); ?></p>
    <?php endif; ?>
    
    <h2><?php _e( 'Import summary.', 'ecwid-shopping-cart' ); ?></h2>
    <p>
		<?php
		echo sprintf(
			__( 'Your WooCommerce store has %s products and %s categories', 'ecwid-shopping-cart' ),
			Ecwid_Importer::count_woo_products(),
			Ecwid_Importer::count_woo_categories()
		);
		?>
    </p>
    <p>
		<?php
		echo sprintf(
			__( 'Your %s store has %s products and %s categories', 'ecwid-shopping-cart' ),
			Ecwid_Config::get_brand(),
			Ecwid_Importer::count_ecwid_products(),
			Ecwid_Importer::count_ecwid_categories()
		);
		?>
    </p>
    <p>
		<?php
		echo sprintf(
			__( 'After import, your %s store will have %s products and %s categories', 'ecwid-shopping-cart' ),
			Ecwid_Config::get_brand(),
			Ecwid_Importer::count_ecwid_products() + Ecwid_Importer::count_woo_products(),
			Ecwid_Importer::count_ecwid_categories() + Ecwid_Importer::count_woo_categories()
		);
		?>
    </p>

    <h2><?php _e( 'Import settings.', 'ecwid-shopping-cart' ); ?></h2>
    <?php if ( Ecwid_Import::allow_delete_demo_products() && Ecwid_Importer::count_ecwid_demo_products() < Ecwid_Importer::count_ecwid_products() ): ?>
    <p>
		<label><input type="checkbox" class="import-settings" name="<?php echo Ecwid_Importer::SETTING_DELETE_DEMO; ?>"><?php _e( 'Remove demo products', 'ecwid-shopping-cart' ); ?></label>
    </p>
    <?php endif; ?>
    <p>
        <label><input type="checkbox" class="import-settings" name="<?php echo Ecwid_Importer::SETTING_UPDATE_BY_SKU; ?>"><?php _e( 'Overwrite existing products with matching SKU', 'ecwid-shopping-cart' ); ?></label>
    </p>
    
    <div class="importer-state importer-state-woo-initial">
		<?php require __DIR__ . '/woo-initial.tpl.php'; ?>
	</div>

    <div class="importer-state importer-state-no-token">
		<?php require __DIR__ . '/import-no-token.php'; ?>
    </div>

    <div class="importer-state importer-state-woo-in-progress">
		<?php require __DIR__ . '/woo-in-progress.tpl.php'; ?>
	</div>

	<div class="importer-state importer-state-woo-complete">
		<?php require __DIR__ . '/woo-complete.tpl.php'; ?>
	</div>
</div>