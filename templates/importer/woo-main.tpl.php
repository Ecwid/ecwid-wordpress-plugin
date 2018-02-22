<div class="wrap ecwid-importer state-<?php echo $this->_is_token_ok() ? 'woo-initial' : 'no-token'; ?>">
    <h1><?php echo sprintf( __( 'Import your products from WooCommerce to %s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></h1>

    <p><?php echo sprintf( __( 'This import will copy your WooCommerce products and categories to your %s store.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></p>

    <p><?php echo sprintf( __( '<b>Important note:</b> import creates new products. Existing %s products in your store will remain unchanged, even if they have same SKUs.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></p>

    <h2><?php _e( 'Import summary.', 'ecwid-shopping-cart' ); ?></h2>
    <p>
		<?php
		echo sprintf(
			__( 'Your WooCommerce store has %s products and %s categories', 'ecwid-shopping-cart' ),
			$this->importer->count_woo_products(),
			$this->importer->count_woo_categories()
		);
		?>
    </p>
    <p>
		<?php
		echo sprintf(
			__( 'Your %s store has %s products and %s categories', 'ecwid-shopping-cart' ),
			Ecwid_Config::get_brand(),
			$this->importer->count_ecwid_products(),
			$this->importer->count_ecwid_categories()
		);
		?>
    </p>
    <p>
		<?php
		echo sprintf(
			__( 'After import, your %s store will have %s products and %s categories', 'ecwid-shopping-cart' ),
			Ecwid_Config::get_brand(),
			$this->importer->count_ecwid_products() + $this->importer->count_woo_products(),
			$this->importer->count_ecwid_categories() + $this->importer->count_woo_categories()
		);
		?>
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