<div class="wrap"><h1><?php _e( 'Import products to your Ecwid store', 'ecwid-shopping-cart' ); ?></h1>

<p>
<?php _e( 'Here, we will help you uploading your product catalog to Ecwid from another shopping cart or other sources.', 'ecwid-shopping-cart' ); ?>
</p>

<?php if ( $this->_need_to_show_woo() ): ?>
<div class="card">
    <h2><?php _e( 'Import product catalog from WooCommerce', 'ecwid-shopping-cart' ); ?></h2>
    
    <p>    
        <?php printf(__( 
                'We found you have a WooCommerce installed. Your WooCommerce store has %1$s&nbsp;products and %2$s&nbsp;categories. Would you like to import them to Ecwid?', 
                'ecwid-shopping-cart'
            ),
            Ecwid_Importer::count_woo_products(), Ecwid_Importer::count_woo_categories()
        ); ?>
    </p>
    <a href="admin.php?page=<?php echo self::PAGE_SLUG_WOO; ?>">
        <?php _e( 'Import your WooCommerce products to Ecwid', 'ecwid-shopping-cart' ); ?>
    </a>
    
    <p><?php _e('(You will be able to confirm the changes before the actual import)', 'ecwid-shopping-cart' ); ?></p>
</div>
<?php endif; ?>    

<div class="card">
    <h2><?php _e( 'Import product catalog from other sources', 'ecwid-shopping-cart' ); ?></h2>

    <p><?php _e( 'Ecwid allows you to upload your products in a form of CSV file. Learn more about this tool in the Ecwid Help Center', 'ecwid-shopping-cart'); ?></p>
    <p>
        <a href="<?php _e( 'https://support.ecwid.com/hc/en-us/articles/208079105-Importing-products', 'ecwid-shopping-cart' ); ?>"><?php _e( 'Learn more', 'ecwid-shopping-cart' ); ?></a>
    </p>
</div>

</div>