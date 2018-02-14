<div class="wrap"><h1>Import products to your Ecwid store</h1>

<p>
Here, we will help you uploading your product catalog to Ecwid from another shopping cart or other sources.
</p>

<div class="card">
    <h2>Import product catalog from WooCommerce</h2>
    
    <p>    
    We found you have a WooCommerce installed. Your WooCommerce store has <?php echo $this->importer->count_woo_products(); ?>&nbsp;products and <?php echo $this->importer->count_woo_categories(); ?>&nbsp;categories. Would you like to import them to Ecwid?
    </p>
    <a href="admin.php?page=<?php echo self::PAGE_SLUG_WOO; ?>">Import your Woo products to Ecwid</a>
    
    <p>(You will be able to confirm the changes before the actual import)</p>
</div>

<div class="card">
    <h2>Import product catalog from other sources</h2>

    <p>Ecwid allows you to upload your products in a form of CSV file. Learn more about this tool in the Ecwid Help Center</p>
    <p>
        <a href="https://support.ecwid.com/hc/en-us/articles/208079105-Importing-products">Learn more</a>
    </p>
</div>

</div>