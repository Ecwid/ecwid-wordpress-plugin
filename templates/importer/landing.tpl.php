<div class="wrap"><h1><?php printf( __( 'Import products to your %s store', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?></h1>

<p>
<?php printf( __( 'Here, we will help you uploading your product catalog to %s from another shopping cart or other sources.', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?>
</p>

<?php if ( $this->_need_to_show_woo() ): ?>
<div class="card">
    <h2><?php _e( 'Import product catalog from WooCommerce', 'ecwid-shopping-cart' ); ?></h2>
    
    <p>    
        <?php printf(__( 
                'We found you have a WooCommerce installed. Your WooCommerce store has %1$s&nbsp;products and %2$s&nbsp;categories. Would you like to import it to %3$s?', 
                'ecwid-shopping-cart'
            ),
            Ecwid_Importer::count_woo_products(), Ecwid_Importer::count_woo_categories(), Ecwid_Config::get_brand()
        ); ?>
    </p>
    <a href="admin.php?page=<?php echo self::PAGE_SLUG_WOO; ?>">
        <?php printf( __( 'Import your WooCommerce catalog to %s', 'ecwid-shopping-cart' ), Ecwid_Config::get_brand() ); ?>
    </a>
    
    <p><?php _e('(You will be able to confirm the changes before the actual import)', 'ecwid-shopping-cart' ); ?></p>
</div>
<?php endif; ?>    
    
<?php if ( !Ecwid_Config::is_wl() ): ?>    
<div class="card">
    <h2><?php _e( 'Import product catalog from other sources', 'ecwid-shopping-cart' ); ?></h2>

    <p><?php _e( 'Ecwid allows you to upload your products in a form of CSV file. Learn more about this tool in the Ecwid Help Center', 'ecwid-shopping-cart'); ?></p>
    <p>
        <a href="<?php _e( 'https://support.ecwid.com/hc/en-us/articles/208079105-Importing-products', 'ecwid-shopping-cart' ); ?>"><?php _e( 'Learn more', 'ecwid-shopping-cart' ); ?></a>
    </p>
</div>
<?php endif; ?>

</div>